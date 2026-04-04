<?php

namespace Database\Seeders;

use App\Models\CommunityChallenge;
use App\Models\DaKhongCuc;
use App\Models\Expedition;
use App\Models\ExpeditionMember;
use App\Models\Membership;
use App\Models\PillarStat;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Settings ────────────────────────────────────────────────
        $settings = [
            ['membership_price_current',     '1500000',  'Giá membership hiện tại (VNĐ)'],
            ['escalate_every_n_members',     '50',       'Tăng giá mỗi N member mới'],
            ['escalate_amount',              '100000',   'Số tiền tăng mỗi bậc (VNĐ)'],
            ['free_questions_per_month',     '3',        'Số câu hỏi miễn phí/tháng'],
            ['deposit_chaos_aip',            '300',      'AIP deposit cho Chaos challenge'],
            ['burning_zone_threshold_pct',   '15',       'Ngưỡng % để kích hoạt Burning Zone'],
            ['burning_zone_bonus_pct',       '50',       'Bonus XP khi Burning Zone (%)'],
            ['affiliate_default_rate',       '0.20',     'Hoa hồng mặc định affiliate'],
            ['affiliate_elite_rate',         '0.25',     'Hoa hồng All In Elite'],
            ['weekly_challenge_reward_xp',   '75',       'XP reward Community Challenge'],
        ];
        foreach ($settings as [$key, $value, $desc]) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value, 'description' => $desc]);
        }

        // ─── Admin user ───────────────────────────────────────────────
        $admin = User::updateOrCreate(['email' => 'admin@cuccu.dream'], [
            'name'       => 'Admin',
            'username'   => 'admin',
            'password'   => Hash::make('password'),
            'account_type' => 'parent',
            'level'      => 100,
            'xp'         => 5000000,
            'aip'        => 10000,
            'streak'     => 90,
            'is_admin'   => true,
        ]);
        Membership::updateOrCreate(['user_id' => $admin->id], [
            'status'     => 'active',
            'starts_at'  => now()->subYear(),
            'expires_at' => now()->addYear(),
        ]);
        DaKhongCuc::updateOrCreate(['user_id' => $admin->id], ['total_count' => 12]);

        // ─── Demo members ─────────────────────────────────────────────
        $demoMembers = [
            ['Nguyễn Minh Khoa', 'khoa',    45, 150000,  2000],
            ['Trần Thị Lan',     'lan',     32, 95000,   1500],
            ['Lê Văn Dũng',      'dung',    28, 80000,   800],
            ['Phạm Thu Hà',      'ha',      20, 45000,   500],
            ['Vũ Đức Thành',     'thanh',   15, 25000,   300],
            ['Đặng Hải Yến',     'yen',     38, 120000,  1200],
            ['Bùi Quang Nam',    'nam',     55, 280000,  3000],
            ['Hoàng Anh Tuấn',   'tuan',    12, 18000,   200],
            ['Ngô Thị Bích',     'bich',    7,  8000,    100],
            ['Lý Minh Phúc',     'phuc',    65, 450000,  5000],
        ];

        $users = [];
        foreach ($demoMembers as [$name, $username, $level, $xp, $aip]) {
            $user = User::updateOrCreate(['username' => $username], [
                'name'     => $name,
                'email'    => $username . '@demo.com',
                'password' => Hash::make('password'),
                'account_type' => 'parent',
                'level'    => $level,
                'xp'       => $xp,
                'aip'      => $aip,
                'streak'   => rand(0, 45),
            ]);
            Membership::updateOrCreate(['user_id' => $user->id], [
                'status'     => 'active',
                'starts_at'  => now()->subMonths(rand(1, 11)),
                'expires_at' => now()->addMonths(rand(1, 12)),
            ]);
            $users[] = $user;
        }

        // ─── Demo kid accounts ────────────────────────────────────────
        $demoKids = [
            ['Bé Khoa',  'be.khoa',  $users[0]->id, 8,  2000],
            ['Bé Lan',   'be.lan',   $users[1]->id, 5,  800],
            ['Bé An',    'be.an',    $users[0]->id, 3,  300],
        ];
        foreach ($demoKids as [$name, $username, $parentId, $level, $xp]) {
            User::updateOrCreate(['username' => $username], [
                'name'         => $name,
                'password'     => Hash::make('password'),
                'account_type' => 'kid',
                'parent_id'    => $parentId,
                'level'        => $level,
                'xp'           => $xp,
                'streak'       => rand(0, 10),
            ]);
        }

        // Give some users Da Khong Cuc
        DaKhongCuc::updateOrCreate(['user_id' => $users[0]->id], ['total_count' => 5]);
        DaKhongCuc::updateOrCreate(['user_id' => $users[6]->id], ['total_count' => 3]);
        DaKhongCuc::updateOrCreate(['user_id' => $users[9]->id], ['total_count' => 7]);

        // ─── Sample posts ─────────────────────────────────────────────
        $samplePosts = [
            [$users[0], true, false,
                "🎨 Hôm nay bé nhà mình vẽ được bức tranh cảnh biển đầu tiên! Rất tự hào vì bé đã kiên nhẫn ngồi vẽ hơn 1 tiếng.\n\nCác bạn có tips gì để giúp bé duy trì đam mê vẽ không?"],
            [$users[1], false, true,
                "📚 Chia sẻ phương pháp dạy tiếng Anh cho bé 4 tuổi qua flashcard + bài hát.\n\nMỗi ngày 15 phút, sau 3 tháng bé nhớ được ~200 từ vựng cơ bản. Quan trọng là phải vui, không ép!"],
            [$users[6], true, false,
                "✨ Bé nhà mình tự làm thiệp sinh nhật bằng giấy origami tặng bà. Bà xúc động lắm!\n\nCho bé tự do sáng tạo là cách tốt nhất để phát triển tư duy. Các mẹ thử cho bé làm thiệp nhé."],
            [$users[9], false, false,
                "💡 Tip: Cho bé xem thiên nhiên trước khi vẽ. Hôm qua đi công viên, về bé vẽ cây và hoa đẹp hơn hẳn!\n\nObservation → Expression là quy trình tốt nhất cho trẻ nhỏ."],
            [$users[3], false, false,
                "🌱 Mình vừa tìm được bộ màu sáp ong organic cho bé, an toàn khi cầm nắm. Bé nhà mình 3 tuổi rất thích!\n\nCác mẹ có recommend thêm đồ dùng vẽ an toàn cho bé không?"],
        ];

        foreach ($samplePosts as [$user, $isCot, $isSignal, $content]) {
            Post::create([
                'user_id'   => $user->id,
                'content'   => $content,
                'is_cot'    => $isCot,
                'cot_at'    => $isCot ? now()->subDays(rand(1, 30)) : null,
                'cot_by'    => $isCot ? $admin->id : null,
                'is_signal' => $isSignal,
                'created_at'=> now()->subHours(rand(1, 200)),
            ]);
        }

        // ─── Community Challenge ──────────────────────────────────────
        CommunityChallenge::updateOrCreate(['week_start' => now()->startOfWeek()->toDateString()], [
            'title'        => 'Cả cộng đồng viết 50 bài tuần này',
            'target_type'  => 'post_count',
            'target_value' => 50,
            'current_value'=> Post::where('created_at', '>=', now()->startOfWeek())->count(),
            'reward_xp'    => 75,
            'week_start'   => now()->startOfWeek()->toDateString(),
            'week_end'     => now()->endOfWeek()->toDateString(),
        ]);

        // ─── Sample Expeditions ───────────────────────────────────────
        $expedition = Expedition::firstOrCreate(['title' => 'Chinh phục 100 khách hàng đầu tiên'], [
            'boss_name'    => 'Đạt 100 khách mua sản phẩm trong 30 ngày',
            'description'  => 'Cùng nhau triển khai chiến lược thu hút và convert khách hàng từ đầu.',
            'difficulty'   => 'hard',
            'required_days'=> 30,
            'max_members'  => 8,
            'created_by'   => $users[0]->id,
            'status'       => 'open',
            'deposit_aip'  => 0,
        ]);

        foreach (array_slice($users, 0, 3) as $u) {
            ExpeditionMember::firstOrCreate(
                ['expedition_id' => $expedition->id, 'user_id' => $u->id],
                ['joined_at' => now()]
            );
        }

        $this->command->info('✅ Seeder hoàn thành! Admin: admin@cuccu.dream / password');
        $this->command->info('✅ Demo members: khoa@demo.com, lan@demo.com, ... / password');
    }
}
