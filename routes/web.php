<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StoryController;
use App\Models\Comment;
use App\Models\Quiz;
use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoryController::class, 'index']);
Route::get('/story/{id}', [StoryController::class, 'show']);
Route::view('/about', 'about')->name('about');
Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
Route::get('/pdf/{filename}', function ($filename) {
    $path = storage_path('app/public/pdfs/' . $filename);
    if (!file_exists($path)) abort(404);
    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="' . $filename . '"'
    ]);
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/create', [StoryController::class, 'create'])->name('story.create');
    Route::post('/story', [StoryController::class, 'store']);
    Route::get('/story/{id}/edit', [StoryController::class, 'edit'])->name('story.edit');
    Route::put('/story/{id}', [StoryController::class, 'update']);
    Route::delete('/story/{id}', [StoryController::class, 'destroy']);

    Route::post('/comment', [CommentController::class, 'store']);
    Route::delete('/comment/{id}', [CommentController::class, 'destroy']);

    Route::post('/upload-image', function (Request $request) {
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('ckeditor', 'public');
            return response()->json([
                'uploaded' => 1,
                'filename' => basename($path),
                'url' => asset('storage/' . $path)
            ]);
        }
        return response()->json(['uploaded' => 0]);
    });
});

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/quiz/create', [QuizController::class, 'create'])->name('quiz.create');
    Route::post('/admin/quiz', [QuizController::class, 'store'])->name('quiz.store');
    Route::delete('/admin/quiz/{id}', [QuizController::class, 'destroy'])->name('quiz.delete');

    Route::get('/admin/dashboard', function () {
        $stories = Story::with('user')->latest()->paginate(10);
        $comments = Comment::with('user', 'story')->latest()->paginate(10);
        $quizzes = Quiz::with('user')->latest()->paginate(10);
        return view('admin.dashboard', compact('stories', 'comments', 'quizzes'));
    });
});
