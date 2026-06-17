<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $posts = Post::when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(5);

        return view('posts.index', compact('posts', 'search'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'post-trixFields.content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->except(['_token', 'featured_image']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')
                ->store('posts', 'public');
        }

        Post::create($data);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return view('posts.show', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'post-trixFields.content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $post = Post::findOrFail($id);

        $data = $request->except(['_token', '_method', 'featured_image']);

        if ($request->hasFile('featured_image')) {

            if ($post->featured_image &&
                Storage::disk('public')->exists($post->featured_image)) {

                Storage::disk('public')->delete($post->featured_image);
            }

            $data['featured_image'] = $request->file('featured_image')
                ->store('posts', 'public');
        }

        $post->update($data);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully');
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if (
            $post->featured_image &&
            Storage::disk('public')->exists($post->featured_image)
        ) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully');
    }
}
