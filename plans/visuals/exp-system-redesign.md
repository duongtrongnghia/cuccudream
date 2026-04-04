# EXP System Redesign — Engagement-Based

## Vấn đề hiện tại (XP)
```
Đăng bài = +15 XP  ← Spam bài = farm XP
Comment  = +3 XP   ← Spam comment = farm XP  
Tự làm = tự nhận   ← Không cần ai tương tác
```
**Hệ quả**: User spam bài/comment vô nghĩa để cày XP.

## Hệ thống EXP mới: Engagement-Driven

```
┌─────────────────────────────────────────────────────────────┐
│                    EXP = ĐÁNH GIÁ TỪ CỘNG ĐỒNG            │
│                                                             │
│  Bạn KHÔNG tự kiếm EXP. Người khác trao cho bạn.           │
│                                                             │
│  Bài viết hay  → Người khác like/comment → BẠN nhận EXP    │
│  Comment hay   → Người khác like         → BẠN nhận EXP    │
│  Câu trả lời  → Được chọn best answer   → BẠN nhận EXP    │
│  Bài CỐT      → Được duyệt             → BẠN nhận EXP     │
└─────────────────────────────────────────────────────────────┘
```

## Bảng EXP mới

### Người NHẬN EXP (Content Creator)
```
Hành động                          EXP     Ai nhận?
─────────────────────────────────────────────────────
Bài viết được LIKE                 +2      Tác giả bài viết
Bài viết được COMMENT              +3      Tác giả bài viết
Bài viết được BOOKMARK             +1      Tác giả bài viết
Comment được LIKE                  +1      Tác giả comment
Được chọn Best Answer              +25     Người trả lời
Bài được duyệt CỐT                +50     Tác giả bài viết
Expedition complete (team)         +100    Tất cả members
Expedition captain bonus           +200    Thuyền trưởng
Hoàn thành khóa học                +50     Học viên
Hoàn thành bài học                 +10     Học viên
```

### Người TƯƠNG TÁC (nhận ít hơn — khuyến khích tham gia)
```
Hành động                          EXP     Ai nhận?
─────────────────────────────────────────────────────
Đăng bài (base)                    +1      Tác giả (chỉ 1 EXP)
Viết comment (base)                +1      Người comment
Check-in expedition                +5      Người check-in
Đăng nhập hàng ngày                +1      User
```

### Streak Multiplier (giữ nguyên)
```
Streak 7 ngày   → x1.1
Streak 30 ngày  → x1.2
Streak 90 ngày  → x1.5
```

## So sánh Cũ vs Mới

```
                    CŨ (XP)              MỚI (EXP)
────────────────────────────────────────────────────
Đăng 1 bài          +15 XP              +1 EXP (base)
                                         +2 mỗi like nhận
                                         +3 mỗi comment nhận

Spam 10 bài rác     +150 XP (easy)      +10 EXP (vì ko ai like)
1 bài hay, 50 likes  +15 XP             +101 EXP (1 + 50*2)

→ BÀI HAY = NHIỀU EXP
→ SPAM = GẦN NHƯ KHÔNG CÓ EXP
```

## Flow Diagram

```
┌──────────┐     ┌──────────┐     ┌──────────────┐
│ User A   │     │ User B   │     │   EXP        │
│ đăng bài │────→│ like bài │────→│ A nhận +2    │
│          │     │          │     │ B nhận 0     │
└──────────┘     └──────────┘     └──────────────┘
                       │
                       │ comment
                       ▼
                 ┌──────────────┐
                 │ A nhận +3    │
                 │ B nhận +1    │
                 └──────────────┘
```

## Migration Plan

1. Rename `xp` → `exp` (hoặc giữ field name `xp` nhưng UI hiện "EXP")
2. Đổi XpService → ExpService với logic mới
3. Đổi tất cả UI label "XP" → "EXP"
4. Đổi dispatch points:
   - PostCard.toggleLike() → award EXP cho post owner (không phải liker)
   - PostCard.addComment() → award EXP cho post owner + ít cho commenter
   - Bỏ award khi tạo post (chỉ +1 base)

## Câu hỏi chưa rõ
- Có cần giữ lại dữ liệu XP cũ không? Hay reset về 0?
- AIP (currency) có đổi tên không?
- EXP có giới hạn ngày không? (VD: max 100 EXP/ngày để chống farming?)
