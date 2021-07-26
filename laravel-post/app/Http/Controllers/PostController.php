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

        $this->authorize('delete', $post);
        $post->delete();
        return back();
    }

    public function update(Post $post)
    {

        $this->authorize('update', $post);
        dd($post['body']);
        return back();
    }
}
