<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index() {
        $quizzes = Quiz::all();
        return view('quiz.index', compact('quizzes'));
    }

    public function show(Quiz $quiz){
        $quiz->load('questions.choices');
        return view('quiz.show', compact('quiz'));
    }
}
