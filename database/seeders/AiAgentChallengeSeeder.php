<?php

namespace Database\Seeders;

use App\Models\ChallengeTask;
use App\Models\Expedition;
use App\Models\ExpeditionMember;
use App\Models\User;
use Illuminate\Database\Seeder;

class AiAgentChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('is_admin', true)->first();
        if (!$admin) {
            $this->command->error('No admin user found. Create an admin first.');
            return;
        }

        $challenge = Expedition::updateOrCreate(
            ['title' => 'AI Agent Challenge 21 Day'],
            [
                'boss_name' => 'Xây dựng AI Agent đầu tiên của bạn',
                'description' => '21 ngày thực chiến để bạn hiểu và xây dựng AI Agent từ zero. Mỗi ngày 1 nhiệm vụ mới được mở khóa. Hoàn thành để nhận EXP và badge đặc biệt!',
                'difficulty' => 'normal',
                'required_days' => 21,
                'max_members' => 999,
                'created_by' => $admin->id,
                'status' => 'active',
                'deposit_aip' => 0,
                'price' => 52670000,
                'starts_at' => '2026-04-01 00:00:00',
                'ends_at' => '2026-04-22 00:00:00',
            ]
        );

        // Auto-join admin
        ExpeditionMember::firstOrCreate(
            ['expedition_id' => $challenge->id, 'user_id' => $admin->id],
            ['class_at_join' => $admin->class, 'joined_at' => now()]
        );

        $tasks = [
            ['day' => 1,  'title' => 'AI Agent là gì?', 'desc' => 'Tìm hiểu khái niệm AI Agent, sự khác biệt với chatbot thông thường. Viết 1 đoạn ngắn chia sẻ hiểu biết của bạn.'],
            ['day' => 2,  'title' => 'Cài đặt môi trường', 'desc' => 'Cài đặt Python, API key (OpenAI/Claude), và chạy thử lệnh gọi API đầu tiên.'],
            ['day' => 3,  'title' => 'Prompt Engineering cơ bản', 'desc' => 'Viết 3 system prompt khác nhau cho 3 use case: hỗ trợ khách hàng, viết content, phân tích dữ liệu.'],
            ['day' => 4,  'title' => 'Tạo chatbot đầu tiên', 'desc' => 'Xây dựng chatbot đơn giản có thể trò chuyện qua terminal/command line.'],
            ['day' => 5,  'title' => 'Memory & Context', 'desc' => 'Thêm tính năng nhớ lịch sử hội thoại cho chatbot. Hiểu về context window và token limit.'],
            ['day' => 6,  'title' => 'Tool Use / Function Calling', 'desc' => 'Cho AI Agent gọi được function: tính toán, tra cứu thời tiết, hoặc tìm kiếm web.'],
            ['day' => 7,  'title' => 'Review tuần 1', 'desc' => 'Tổng kết tuần 1. Chia sẻ những gì bạn đã học được và demo chatbot của bạn.'],
            ['day' => 8,  'title' => 'RAG - Retrieval Augmented Generation', 'desc' => 'Tìm hiểu RAG. Cho AI Agent đọc và trả lời từ tài liệu của bạn.'],
            ['day' => 9,  'title' => 'Vector Database', 'desc' => 'Cài đặt vector DB (ChromaDB/Pinecone). Lưu và truy vấn embeddings.'],
            ['day' => 10, 'title' => 'Agent với nhiều Tools', 'desc' => 'Xây dựng Agent có thể sử dụng 3+ tools: search, calculator, file reader.'],
            ['day' => 11, 'title' => 'Agent Workflow', 'desc' => 'Thiết kế workflow cho Agent: plan → execute → verify. Xử lý khi tool gọi thất bại.'],
            ['day' => 12, 'title' => 'Web Scraping Agent', 'desc' => 'Tạo Agent có thể crawl web, trích xuất thông tin và tóm tắt nội dung.'],
            ['day' => 13, 'title' => 'Email/Notification Agent', 'desc' => 'Tạo Agent có thể gửi email hoặc thông báo dựa trên điều kiện bạn đặt.'],
            ['day' => 14, 'title' => 'Review tuần 2', 'desc' => 'Tổng kết tuần 2. Demo Agent phức tạp nhất bạn đã xây. Chia sẻ code và kết quả.'],
            ['day' => 15, 'title' => 'Multi-Agent System', 'desc' => 'Xây dựng hệ thống 2+ Agent phối hợp với nhau: researcher + writer, hoặc planner + executor.'],
            ['day' => 16, 'title' => 'Agent cho Business', 'desc' => 'Thiết kế Agent giải quyết 1 bài toán thực tế trong business của bạn (marketing, CS, ops).'],
            ['day' => 17, 'title' => 'Error Handling & Guardrails', 'desc' => 'Thêm xử lý lỗi, rate limiting, và guardrails để Agent hoạt động an toàn.'],
            ['day' => 18, 'title' => 'Deploy Agent', 'desc' => 'Deploy Agent lên server hoặc cloud. Cho người khác sử dụng được.'],
            ['day' => 19, 'title' => 'Monitoring & Logging', 'desc' => 'Thêm logging và monitoring cho Agent. Track được usage, errors, và performance.'],
            ['day' => 20, 'title' => 'Optimize & Scale', 'desc' => 'Tối ưu chi phí API, caching responses, và xử lý concurrent requests.'],
            ['day' => 21, 'title' => 'Demo Day', 'desc' => 'Trình bày Agent hoàn chỉnh của bạn. Chia sẻ bài học, kết quả, và kế hoạch tiếp theo.'],
        ];

        foreach ($tasks as $task) {
            ChallengeTask::updateOrCreate(
                ['expedition_id' => $challenge->id, 'day_number' => $task['day']],
                ['title' => $task['title'], 'description' => $task['desc']]
            );
        }

        $this->command->info("Created 'AI Agent Challenge 21 Day' with 21 tasks.");
    }
}
