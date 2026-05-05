<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // LIST + SEARCH + PAGINATION
    public function index(Request $request)
    {
        $search = $request->search;

        $posts = Post::when($search, function ($query) use ($search) {
            $query->where('title', 'like', "%$search%");
        })
            ->orderBy('id', 'asc') // ASC order
            ->paginate(5);

        return view('posts.index', compact('posts', 'search'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('posts.create');
    }

    // STORE DATA
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Post::create($request->all());

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully');
    }

    // SHOW (View Single Post)
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    // EDIT PAGE
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required'
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully');
    }

    // DELETE
    public function destroy($id)
    {
        Post::findOrFail($id)->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully');
    }
}