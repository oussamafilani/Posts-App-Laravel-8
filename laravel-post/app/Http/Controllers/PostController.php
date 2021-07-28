<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->with(['user', 'likes'])->paginate(20);
        return view(
            'posts.index',
            [
                'posts' => $posts
            ]
        );
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'body' => 'required',
        ]);

        $request->user()->posts()->create($request->only('body'));

        return back();
    }

    public function destroy(Post $post)
    {

        if (auth()->user()->role === 1) {

            $post->delete();
        } else {

            $this->authorize('delete', $post);
        }
        return back();
    }


    public function update(Request $request, Post $post)
    {
        $post->update($request->only('body'));
        return redirect('/posts');
    }
}
