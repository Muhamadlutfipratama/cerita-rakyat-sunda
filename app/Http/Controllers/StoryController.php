<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Story::latest()->with('user');

        if ($search = $request->query('search')) {
            $query->where('title', 'like', "%$search%")->orWhere('content', 'like', "%$search%");
        }

        $stories = $query->paginate(9);

        return view('story.index', compact('stories'));
    }

    public function show($id)
    {
        $story = Story::with(['user', 'comments.user'])->findOrFail($id);
        $canEdit = Auth::check() && (Auth::id() == $story->user_id || Auth::user()->is_admin);
        return view('story.show', compact('story', 'canEdit'));
    }

    public function create()
    {
        return view('story.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg|max:5242',
            'pdf' => 'nullable|mimes:pdf|max:5242'
        ]);

        $story = new Story();
        $story->user_id = Auth::id();
        $story->title = $request->title;
        $story->content = $request->content;

        if ($request->hasFile('image')) {
            $story->image = $request->file('image')->store('stories', 'public');
        }

        if ($request->hasFile('pdf')) {
            $story->pdf = $request->file('pdf')->store('pdfs', 'public');
        }

        $story->save();
        return redirect('/')->with('success', 'Cerita berhasil ditambahkan');
    }

    public function edit($id)
    {
        $story = Story::findOrFail($id);
        if (Auth::id() != $story->user_id && !Auth::user()->is_admin) {
            return redirect('/')->with('error', 'Tidak diizinkan.');
        }

        return view('story.edit', compact('story'));
    }

    public function update(Request $request, $id)
    {
        $story = Story::findOrFail($id);
        if (Auth::id() != $story->user_id && !Auth::user()->is_admin) {
            return redirect('/')->with('error', 'Tidak diizinkan.');
        }

        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg|max:5242',
            'pdf' => 'nullable|mimes:pdf|max:5242',
        ]);

        $story->title = $request->title;
        $story->content = $request->content;

        if ($request->hasFile('image')) {
            if ($story->image) {
                Storage::disk('public')->delete($story->image);
            }
            $story->image = $request->file('image')->store('stories', 'public');
        }

        if ($request->hasFile('pdf')) {
            if ($story->pdf) {
                Storage::disk('public')->delete($story->pdf);
            }
            $story->pdf = $request->file('pdf')->store('pdfs', 'public');
        }

        $story->save();
        return redirect('/')->with('success', 'Cerita berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $story =  Story::findOrFail($id);
        if (Auth::id() != $story->user_id && !Auth::user()->is_admin) {
            return redirect('/')->with('error', 'Tidak diizinkan menghapus');
        }

        if ($story->image) {
            Storage::disk('public')->delete($story->image);
        }

        $story->delete();
        return redirect('/')->with('success', 'Cerita berhasil dihapus.');
    }
}
