<?php

use App\Livewire\AdminCotReview;
use App\Livewire\AdminDashboard;
use App\Livewire\AdminReports;
use App\Livewire\AdminUsers;
use App\Livewire\SearchResults;
use App\Livewire\AdminCourseBuilder;
use App\Livewire\AdminCourses;
use App\Livewire\AdminTopics;
use App\Livewire\Auth\LoginForm;
use App\Livewire\Auth\RegisterForm;
use App\Livewire\CotPage;
use App\Livewire\ChallengeDetail;
use App\Livewire\ChallengePage;
use App\Livewire\CreateKidAccount;
use App\Livewire\FamilyPage;
use App\Livewire\MembershipPricing;
use App\Livewire\MessagesPage;
use App\Livewire\Feed;
use App\Livewire\AcademyDetail;
use App\Livewire\AcademyPage;
use App\Livewire\AffiliatePage;
use App\Livewire\LeaderboardPage;
use App\Livewire\ProfilePage;
use App\Livewire\QaPage;
use App\Livewire\SignalsPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ─── SePay webhook (no auth, verified by API key) ──────────────────
Route::post('/webhook/sepay', \App\Http\Controllers\SepayWebhookController::class)
    ->name('webhook.sepay');

// ─── Redirect root → feed or login ──────────────────────────────────
Route::get('/', fn() => redirect()->route(Auth::check() ? 'feed' : 'login'));

// ─── Guest routes ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    LoginForm::class)->name('login');
    Route::get('/register', RegisterForm::class)->name('register');
});

// Referral link (store in session)
Route::get('/ref/{username}', function (string $username) {
    session(['referral' => $username]);
    return redirect()->route('register');
})->name('referral');

// ─── Authenticated routes ────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // Membership pages (no active membership required)
    Route::get('/membership/expired', function () {
        return view('pages.membership-expired');
    })->name('membership.expired');
    Route::get('/membership/pricing', MembershipPricing::class)->name('membership.pricing');

    // ─── Main platform (require active membership) ──────────────────
    Route::middleware('App\Http\Middleware\RequireActiveMembership')->group(function () {
        Route::get('/feed',              Feed::class)->name('feed');
        Route::get('/cot',               CotPage::class)->name('cot');
        Route::get('/tin-hieu',          SignalsPage::class)->name('signals');
        Route::get('/hoi-dap',           QaPage::class)->name('qa');
        Route::get('/challenge',           ChallengePage::class)->name('challenge');
        Route::get('/challenge/{id}',    ChallengeDetail::class)->name('challenge.show');
        Route::get('/leaderboard',       LeaderboardPage::class)->name('leaderboard');
        Route::get('/khoa-hoc',          AcademyPage::class)->name('academy');
        Route::get('/khoa-hoc/{id}',     AcademyDetail::class)->name('academy.show');
        Route::get('/marketplace',       \App\Livewire\MarketplacePage::class)->name('marketplace')
            ->middleware(\App\Http\Middleware\ParentOnly::class);
        Route::get('/affiliate',         AffiliatePage::class)->name('affiliate')
            ->middleware(\App\Http\Middleware\ParentOnly::class);
        Route::get('/messages/{conversation?}', MessagesPage::class)->name('messages')
            ->middleware(\App\Http\Middleware\ParentOnly::class);
        Route::get('/gia-dinh',          FamilyPage::class)->name('family')
            ->middleware(\App\Http\Middleware\ParentOnly::class);
        Route::get('/gia-dinh/them-be',  CreateKidAccount::class)->name('family.create-kid')
            ->middleware(\App\Http\Middleware\ParentOnly::class);
        Route::get('/search',            SearchResults::class)->name('search');

        // ─── Admin routes ────────────────────────────────────────────
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/', AdminDashboard::class)->name('dashboard')
                ->can('admin');
            Route::get('/topics', AdminTopics::class)->name('topics')
                ->can('admin');
            Route::get('/courses', AdminCourses::class)->name('courses')
                ->can('admin');
            Route::get('/products', \App\Livewire\AdminProducts::class)->name('products')
                ->can('admin');
            Route::get('/courses/{id}/build', AdminCourseBuilder::class)->name('courses.build')
                ->can('admin');
            Route::get('/cot-review', AdminCotReview::class)->name('cot')
                ->can('admin');
            Route::get('/reports', AdminReports::class)->name('reports')
                ->can('admin');
            Route::get('/users', AdminUsers::class)->name('users')
                ->can('admin');
        });
        Route::get('/@{username}',       ProfilePage::class)->name('profile');
        Route::get('/u/{id}',            function ($id) {
            $user = \App\Models\User::findOrFail($id);
            return redirect()->route('profile', $user->username ?? $user->id);
        })->name('profile.id');
    });
});
