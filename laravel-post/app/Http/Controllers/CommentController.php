<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Post $post)
    {
        return view(
            'comments.index',
            [
                'post' => $post,
            ]
        );
    }

    public function store(Post $post, Request $request)
    {
        $post->comments()->create(
            [
                'user_id' => $request->user()->id,
                'post_id' => $post->id,
                'comment' => $request->comment,
            ]
        );
        return back();
    }
}
