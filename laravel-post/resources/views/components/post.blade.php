@props(['post' => $post])

<div>
    <div class="mb-4">
        <a href="{{route('users.posts',$post->user)}}" class="font-bold">{{$post->user->name}}</a> <span class="text-gray-600 text-sm">
            {{$post->created_at->diffForHumans()}}
        </span>
        <p class="mb-2">{{$post->body}}</p>

        @can('delete',$post)
            <form action="{{route('posts.destroy',$post)}}" method="post" class="mr-1">
                @csrf
                @method('DELETE')
                <button type="submit"><i class="fas fa-trash text-red-500 text-xl cursor-pointer"></i></button>
            </form>
        @endcan    
        <div class="flex items-center">
        @auth

            @if(!$post->likedBy(auth()->user()))
                <form action="{{route('posts.like',$post)}}" method="post" class="mr-1">
                    @csrf
                    <button type="submit"><i class="far fa-thumbs-up text-current text-2xl cursor-pointer"></i></button>
                </form>
            @else
                <form action="{{route('posts.like',$post)}}" method="post" class="mr-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"><i class="fas fa-thumbs-up text-blue-500 text-2xl cursor-pointer"></i></button>
                </form>
            @endif

           
        @endauth
        <span>{{$post->likes->count()}} {{Str::plural('like',$post->likes->count())}}</span>
        </div>
    </div> 
</div>