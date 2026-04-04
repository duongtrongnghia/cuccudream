<?php
namespace App\Livewire;
use App\Models\Answer;
use App\Models\Question;
use App\Services\XpService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class QaPage extends Component {
    use WithPagination;

    #[Url] public string $filter = 'all';
    public bool $showAsk = false;
    #[Rule('required|min:5|max:200')] public string $title = '';
    #[Rule('nullable|max:50000')] public string $body = '';
    public bool $isAnonymous = false;
    public ?int $openQuestionId = null;
    public string $answerBody = '';

    public function setFilter(string $f) { $this->filter = $f; $this->resetPage(); }

    public function toggleQuestion(int $id): void {
        $this->openQuestionId = $this->openQuestionId === $id ? null : $id;
        $this->answerBody = '';
    }

    public function submitAnswer(): void {
        if (!Auth::check() || !$this->openQuestionId || blank($this->answerBody)) return;
        $this->validate(['answerBody' => 'required|min:3|max:5000']);
        $q = Question::findOrFail($this->openQuestionId);

        // Anti-spam: max 1 answer per question per user
        $existing = Answer::where('question_id', $q->id)->where('user_id', Auth::id())->exists();
        if ($existing) {
            $this->addError('answerBody', 'Bạn đã trả lời câu hỏi này rồi.');
            return;
        }

        // Anti-spam: max 10 answers per hour
        $recentCount = Answer::where('user_id', Auth::id())
            ->where('created_at', '>=', now()->subHour())->count();
        if ($recentCount >= 10) {
            $this->addError('answerBody', 'Bạn đã trả lời quá nhiều. Vui lòng đợi 1 giờ.');
            return;
        }

        Answer::create(['question_id' => $q->id, 'user_id' => Auth::id(), 'body' => $this->answerBody]);
        if ($q->status === 'open') { $q->update(['status' => 'answered']); }
        app(XpService::class)->award(Auth::user(), 'comment', 1.0, 'Trả lời câu hỏi', $q);
        $this->answerBody = '';
    }

    public function submitQuestion(): void {
        $this->validate();
        $q = Question::create([
            'user_id' => Auth::id(), 'title' => $this->title, 'body' => $this->body,
            'is_anonymous' => $this->isAnonymous,
        ]);
        app(XpService::class)->award(Auth::user(), 'post', 0.33, 'Đặt câu hỏi', $q);
        $this->reset(['title','body','isAnonymous','showAsk']);
    }

    public function render() {
        $query = Question::with(['user','answers'])->withCount('answers');
        match($this->filter) {
            'unanswered' => $query->where('status','open'),
            'answered'   => $query->where('status','answered'),
            'mine'       => $query->where('user_id', Auth::id()),
            default      => null,
        };
        return view('livewire.qa-page', ['questions' => $query->latest()->paginate(10)])
            ->layout('layouts.app', ['title' => 'Hỏi đáp — Cúc Cu Dream™']);
    }
}
