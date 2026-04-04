<?php
namespace App\Livewire;
use App\Models\Bookmark;
use App\Models\Post;
use App\Models\User;
use App\Models\XpTransaction;
use App\Services\XpService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePage extends Component {
    use WithFileUploads;

    public User $profileUser;
    #[Url] public string $tab = 'posts';
    public $avatarUpload;
    public bool $editingProfile = false;
    public string $editName = '';
    public string $editBio = '';
    public string $editEmail = '';

    public function startEditProfile(): void
    {
        if (Auth::id() !== $this->profileUser->id) return;
        $this->editName = $this->profileUser->name;
        $this->editBio = $this->profileUser->bio ?? '';
        $this->editEmail = $this->profileUser->email;
        $this->editingProfile = true;
    }

    public function saveProfile(): void
    {
        if (Auth::id() !== $this->profileUser->id) return;
        $this->validate([
            'editName' => 'required|min:2|max:50',
            'editBio' => 'nullable|max:200',
            'editEmail' => 'required|email|unique:users,email,' . $this->profileUser->id,
        ]);
        $newUsername = \Illuminate\Support\Str::slug($this->editName, '');
        // Ensure unique username
        $base = $newUsername;
        $i = 1;
        while (\App\Models\User::where('username', $newUsername)->where('id', '!=', $this->profileUser->id)->exists()) {
            $newUsername = $base . $i;
            $i++;
        }
        $this->profileUser->update([
            'name' => $this->editName,
            'username' => $newUsername,
            'bio' => $this->editBio ?: null,
            'email' => $this->editEmail,
        ]);
        $this->profileUser->refresh();
        $this->editingProfile = false;
    }

    public function cancelEditProfile(): void
    {
        $this->editingProfile = false;
    }

    public function mount(string $username): void {
        $query = User::where('username', $username);
        if (is_numeric($username)) {
            $query->orWhere('id', (int) $username);
        }
        $this->profileUser = $query->with(['daKhongCuc','powerSymbols','membership','children','parent'])->firstOrFail();
    }
    public function setTab(string $t) { $this->tab = $t; }

    public function updatedAvatarUpload(): void
    {
        $this->validate(['avatarUpload' => 'image|max:2048']);
        if (Auth::id() !== $this->profileUser->id) return;

        $path = $this->avatarUpload->store('avatars', 'public');
        if ($this->profileUser->avatar) {
            Storage::disk('public')->delete($this->profileUser->avatar);
        }
        $this->profileUser->update(['avatar' => $path]);
        $this->profileUser->refresh();
    }
    public function render() {
        $xpService = app(XpService::class);
        $posts = $this->tab === 'posts'
            ? $this->profileUser->posts()->with(['user'])->withCount(['likes','allComments'])->latest()->paginate(10)
            : null;
        $cotPosts = $this->tab === 'cot'
            ? $this->profileUser->posts()->where('is_cot', true)->with(['user'])->withCount(['likes','allComments'])->latest()->paginate(10)
            : null;
        $bookmarkedPosts = ($this->tab === 'bookmarks' && auth()->id() === $this->profileUser->id)
            ? Post::whereIn('id', Bookmark::where('user_id', $this->profileUser->id)->pluck('post_id'))
                ->with(['user'])->withCount(['likes','allComments'])->latest()->paginate(10)
            : null;
        $symbols = $this->profileUser->powerSymbols->keyBy('pillar');
        $badges = $this->profileUser->userBadges()->with('badge')->get();

        // Contribution heatmap — last 52 weeks
        $since = now()->subWeeks(52)->startOfWeek();
        $contributions = XpTransaction::where('user_id', $this->profileUser->id)
            ->where('created_at', '>=', $since)
            ->selectRaw("DATE(created_at) as date, SUM(amount) as total")
            ->groupBy(DB::raw('DATE(created_at)'))
            ->pluck('total', 'date')
            ->toArray();

        return view('livewire.profile-page', [
            'xpProgress' => $xpService->expProgressPct($this->profileUser),
            'toNext'     => $xpService->expToNextLevel($this->profileUser),
            'posts'      => $posts,
            'cotPosts'   => $cotPosts,
            'bookmarkedPosts' => $bookmarkedPosts,
            'symbols'    => $symbols,
            'badges'     => $badges,
            'contributions' => $contributions,
            'contributionStart' => $since,
            'familyChildren' => $this->profileUser->isParent() ? $this->profileUser->children : collect(),
            'familyParent'   => $this->profileUser->isKid() ? $this->profileUser->parent : null,
        ])->layout('layouts.app', ['title' => $this->profileUser->name . ' — Cúc Cu Dream™']);
    }
}
