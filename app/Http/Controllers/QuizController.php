<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();
        return view('quiz.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        $quiz->load('questions.choices');
        return view('quiz.show', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $quiz->load('questions.choices');
        $answers = $request->input('answers', []);
        $score = 0;
        $total = $quiz->questions->count();
        foreach ($quiz->questions as $question) {
            $correct = $question->choices->where('is_correct', 1)->first();
            if (isset($answers[$question->id]) && $correct && $answers[$question->id] == $correct->id) {
                $score++;
            }
        }

        $nilai = $total > 0 ? ($score / $total) * 100 : 0;

        return view('quiz.result', compact('quiz', 'score', 'total', 'nilai'));
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'questions' => 'required|array|min:1',
        ]);
        $quiz = Quiz::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);
        foreach ($request->questions as $qIdx => $qData) {
            $question = $quiz->questions()->create([
                'question' => $qData['question'],
            ]);
            foreach ($qData['choices'] as $cIdx => $cData) {
                $question->choices()->create([
                    'choice' => $cData['choice'],
                    'is_correct' => (isset($qData['correct']) && $qData['correct'] == $cIdx) ? 1 : 0,
                ]);
            }
        }
        return redirect('admin/dashboard')->with('success', 'Quiz berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $quiz = Quiz::findOrFail($id);
        if (Auth::id() != $quiz->user_id && !Auth::user()->is_admin) {
            return redirect('/')->with('error', 'Tidak diizinkan menghapus');
        }

        $quiz->delete();
        return redirect('admin/dashboard')->with('success', 'Quiz berhasil dihapus.');
    }
}
