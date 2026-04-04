---
name: SePay Webhook API Research
description: Technical implementation guide for SePay bank transfer webhook integration in Laravel
type: research
date: 2026-04-02
---

# SePay Webhook API — Bank Transfer Verification

## 1. Webhook Endpoint Setup

**URL Configuration:**
- Set webhook URL in SePay Dashboard → WebHooks menu
- Your endpoint must accept POST requests
- Response must be `{"success": true}` with HTTP 200-201 status

**Event Triggers:**
- Select "money in" (incoming transfers) or "money out" or both
- Filter by specific bank accounts
- Optionally filter by virtual accounts (VA)
- Option to skip notifications if payment code (`code` field) is missing

---

## 2. JSON Payload Format

SePay POSTs the following JSON structure:

```json
{
  "id": 92704,
  "gateway": "Vietcombank",
  "transactionDate": "2023-03-25 14:02:37",
  "accountNumber": "0123499999",
  "code": null,
  "content": "transfer to buy iphone",
  "transferType": "in",
  "transferAmount": 2277000,
  "accumulated": 19077000,
  "subAccount": null,
  "referenceCode": "MBVCB.3278907687",
  "description": ""
}
```

**Key Fields:**
- `id`: Unique transaction ID (use for deduplication)
- `gateway`: Bank name (Vietcombank, MBBank, etc.)
- `transactionDate`: ISO datetime of transfer
- `content`: Transfer description (nội dung chuyển khoản) — **this is where payment codes go**
- `code`: Extracted payment code (can be null if not matched)
- `transferType`: "in" or "out"
- `transferAmount`: Amount in VND
- `referenceCode`: Bank's reference code (combine with `id` + `transferAmount` for deduplication)
- `accumulated`: Accumulated account balance

---

## 3. Authentication & Verification

**API Key Authentication:**
```
Authorization: Apikey YOUR_API_KEY
```

**Webhook Signature Verification:**
- Extract signature from `sepay-signature` header
- Verify using your webhook secret via `verifyWebhookSignature(requestBody, header, secret)`
- Reject webhook if signature invalid
- SePay Laravel package provides this via built-in methods

**Configuration:**
- `SEPAY_WEBHOOK_TOKEN`: Your API key
- `SEPAY_MATCH_PATTERN`: Pattern for payment code extraction (default: "SE")

---

## 4. Webhook Dashboard Configuration

1. Go to my.sepay.vn → WebHooks
2. Create new webhook
3. Enter target URL (your endpoint)
4. Select authentication type:
   - OAuth 2.0
   - API Key (recommended)
   - None
5. Choose trigger events (in/out/both)
6. Set filters (accounts, virtual accounts)
7. Save

---

## 5. Transfer Content (nội dung chuyển khoản) Matching

**How it works:**
- User enters transfer content when sending money (e.g., "SE123456" or "SE ABC-XYZ-789")
- SePay automatically extracts a `code` field based on matching rules
- If payment code found, `code` field is populated; otherwise `null`
- Optional: Configure to skip webhooks where `code` is null (require payment code)

**Implementation:**
- Match incoming `content` field against expected patterns (order/invoice codes)
- Example: `content.includes("SE" + orderId)`
- SePay also extracts to `code` field automatically for simpler matching
- Fallback to content string parsing if auto-extraction doesn't match

---

## 6. Retry & Deduplication

**Retries:**
- SePay retries failed requests automatically (Fibonacci intervals)
- Max 7 attempts over ~5 hours
- Responds to HTTP codes 200-299 as success

**Deduplication:**
Combine these fields to prevent duplicate processing:
- `id` (transaction ID)
- `referenceCode` (bank reference)
- `transferAmount` + `transferType`
- `transactionDate`

Store processed webhooks in DB with composite key to idempotently handle retries.

---

## 7. Laravel Implementation Pattern

```php
// Webhook endpoint (e.g., routes/web.php)
Route::post('/webhook/sepay', SePayWebhookController::class);

// Controller
class SePayWebhookController {
    public function __invoke(Request $request) {
        // 1. Verify signature
        if (!$this->verifySePay($request)) {
            return response(['error' => 'Invalid signature'], 401);
        }
        
        // 2. Check for duplicates
        $webhook = SePay::firstOrCreate(
            ['webhook_id' => $request->id, 'reference_code' => $request->referenceCode],
            $request->all()
        );
        
        // 3. Process incoming transfer
        if ($request->transferType === 'in') {
            $order = Order::where('code', $request->code)
                     ->orWhere('id', $this->extractCode($request->content))
                     ->first();
            if ($order) {
                $order->markAsPaid($request->transferAmount);
            }
        }
        
        return ['success' => true];
    }
}
```

---

## 8. Critical Considerations

| Consideration | Details |
|---|---|
| **Idempotency** | Always check `id` + `referenceCode` before processing |
| **Amount Validation** | Match `transferAmount` against expected payment amount |
| **Timeout** | Set webhook endpoint timeout to ≥30s |
| **Logging** | Log all webhook requests for debugging failures |
| **HTTP Status** | Must return 200-201; other codes trigger retries |
| **Response Time** | SePay expects response within timeout; queue async processing |

---

## Sources

- [SePay WebHooks Integration Guide](https://docs.sepay.vn/tich-hop-webhooks.html)
- [SePay Developer Documentation](https://developer.sepay.vn/en/sepay-webhooks/)
- [Laravel SePay Package](https://github.com/sepayvn/laravel-sepay)
- [SePay OAuth2 API Documentation](https://developer.sepay.vn/en/sepay-oauth2/api-webhook)

---

## Unresolved Questions

1. **Exact signature algorithm**: SHA256 HMAC or HMAC-SHA1? (Documentation mentions `sepay-signature` header but doesn't specify algorithm)
2. **Payment code extraction rules**: Does SePay extract ANY code from content, or only specific patterns? (Docs mention default "SE" pattern but unclear if configurable per webhook)
3. **Webhook secret rotation**: How to rotate webhook secrets without downtime?
