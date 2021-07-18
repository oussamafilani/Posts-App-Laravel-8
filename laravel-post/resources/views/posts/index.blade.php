@extends('layouts.app')

@section('content')
<div class="flex justify-center">
    <div class="w-8/12 bg-white p-6 rounded-lg">
        <form action="{{route('posts')}}" method="POST" class="mb-4">
            @csrf
            <div class="mb-4">
                <label for="body" class="sr-only">Body</label>
                <textarea name="body" id="body" cols="30" rows="4" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('body') border-red-500 @enderror" placeholder="Post something!"></textarea>

                @error('body')
                    <div class="text-red-500 mt-2 text-sm">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded font-medium">Post</button>
            </div>
        </form>
        @if ($post->count())
        @foreach ($post as $p)
           <div class="mb-4">
                <a href="" class="font-bold">{{$p->user->name}}</a> <span class="text-gray-600 text-sm">
                    {{$p->created_at->diffForHumans()}}
                </span>
                <p class="mb-2">{{$p->body}}</p>
                @if($p->ownedBy(auth()->user()))
                    <div>
                        <form action="{{route('posts.destroy',$p)}}" method="post" class="mr-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"><i class="fas fa-trash text-red-500 text-xl cursor-pointer"></i></button>
                        </form>
                    </div>
                @endif
                <div class="flex items-center">
                @auth

                    @if(!$p->likedBy(auth()->user()))
                        <form action="{{route('posts.like',$p)}}" method="post" class="mr-1">
                            @csrf
                            <button type="submit"><i class="far fa-thumbs-up text-current text-2xl cursor-pointer"></i></button>
                        </form>
                    @else
                        <form action="{{route('posts.like',$p)}}" method="post" class="mr-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"><i class="fas fa-thumbs-up text-blue-500 text-2xl cursor-pointer"></i></button>
                        </form>
                    @endif

                   
                @endauth
                <span>{{$p->likes->count()}} {{Str::plural('like',$p->likes->count())}}</span>
                </div>
            </div> 
        @endforeach
        {{$post->links()}}
        @else
            <p>There are no posts</p>
        @endif
    </div>
</div>
@endsection