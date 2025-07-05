<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Story::latest()->with('user');

            if ($search = $request->query('search')) {
                $query->where('title', 'like', "%$search%")->orWhere('content', 'like', "%$search%");
            }

            $stories = $query->paginate(9);

            return view('story.index', compact('stories'));
        } catch (\Exception $e) {
            Log::error('Story Index Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat daftar cerita.');
        }
    }

    public function show($id)
    {
        try {
            $story = Story::with(['user', 'comments.user'])->findOrFail($id);
            $canEdit = Auth::check() && (Auth::id() == $story->user_id || Auth::user()->is_admin);
            return view('story.show', compact('story', 'canEdit'));
        } catch (\Exception $e) {
            Log::error('Story Show Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat cerita.');
        }
    }

    public function create()
    {
        try {
            return view('story.create');
        } catch (\Exception $e) {
            Log::error('Story Create Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka form.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'image' => 'required|image|mimes:png,jpg,jpeg|max:5242',
                'pdf' => 'nullable|mimes:pdf|max:10240'
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
        } catch (\Exception $e) {
            Log::error('PDF Upload Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal upload file: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $story = Story::findOrFail($id);
            if (Auth::id() != $story->user_id && !Auth::user()->is_admin) {
                return redirect('/')->with('error', 'Tidak diizinkan.');
            }
            return view('story.edit', compact('story'));
        } catch (\Exception $e) {
            Log::error('Story Edit Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membuka form edit.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $story = Story::findOrFail($id);
            if (Auth::id() != $story->user_id && !Auth::user()->is_admin) {
                return redirect('/')->with('error', 'Tidak diizinkan.');
            }

            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'image' => 'image|mimes:png,jpg,jpeg|max:5242',
                'pdf' => 'nullable|mimes:pdf|max:10240',
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
        } catch (\Exception $e) {
            Log::error('Story Update Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui cerita: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $story =  Story::findOrFail($id);
            if (Auth::id() != $story->user_id && !Auth::user()->is_admin) {
                return redirect('/')->with('error', 'Tidak diizinkan menghapus');
            }

            if ($story->image) {
                Storage::disk('public')->delete($story->image);
            }
            if ($story->pdf) {
                Storage::disk('public')->delete($story->pdf);
            }

            $story->delete();
            return redirect('/')->with('success', 'Cerita berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Story Destroy Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus cerita: ' . $e->getMessage());
        }
    }
}
