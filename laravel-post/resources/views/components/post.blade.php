@props(['post' => $post])

<div>
    @if ($post->id != request()->get('id'))

        <div class="mb-4 bg-gray-50 p-5 rounded-lg relative ">
            {{-- <div class="absolute right-0 top-0"><i class="fas fa-ellipsis-h cursor-pointer mr-3"></i></div> --}}

            <a href="{{ route('users.posts', $post->user) }}" class="font-bold">{{ $post->user->name }}</a> <span
                class="text-gray-600 text-sm">
                {{ $post->created_at->diffForHumans() }}
            </span>
            <p class="mb-2 p-5">{{ $post->body }}</p>

            {{-- start delete post --}}
            @auth
                @if (auth()->user()->role === 1)

                    <form action="{{ route('posts.destroy', $post) }}" method="post" class="mr-1 float-right">
                        @csrf
                        @method('DELETE')
                        <button type="submit"><i class="fas fa-trash text-red-500 text-xl  cursor-pointer"></i></button>
                    </form>
                @else
                    @can('delete', $post)
                        <form action="{{ route('posts.destroy', $post) }}" method="post" class="mr-1 float-right">
                            @csrf
                            @method('DELETE')
                            <button type="submit"><i class="fas fa-trash text-red-500 text-xl  cursor-pointer"></i></button>
                        </form>
                    @endcan
                @endif
            @endauth
            {{-- end delete post --}}


            @auth
                @can('delete', $post)
                    <form action="{{ route('posts.edit', $post) }}" method="POST" class="mr-1 float-right mr-4">
                        @method('GET')
                        @csrf
                        <button type="submit"><i class="fas fa-edit text-gray-700 text-xl  cursor-pointer"></i></button>
                    </form>
                @endcan
            @endauth

            <div class="flex item-center">
                @auth

                    @if (!$post->likedBy(auth()->user()))
                        <form action="{{ route('posts.like', $post) }}" method="post" class="mr-1">
                            @csrf
                            <button type="submit"><i
                                    class="far fa-thumbs-up text-current text-2xl cursor-pointer"></i></button>
                        </form>
                    @else
                        <form action="{{ route('posts.like', $post) }}" method="post" class="mr-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit"><i
                                    class="fas fa-thumbs-up text-blue-500 text-2xl cursor-pointer"></i></button>
                        </form>
                    @endif


                @endauth
                <span>{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
                <form action="{{ route('posts.comment', $post) }}" method="post" class="mr-2 ml-10">
                    @csrf
                    @method('GET')
                    <button type="submit"><i class="far fa-comment text-gray-700 text-xl cursor-pointer"></i>
                        {{ $post->comments->count() }}</button>

                </form>
            </div>

            {{-- start Comment section --}}
            @auth
                <form action="{{ route('posts.comment', $post) }}" method="POST" class="my-4 ">
                    @csrf
                    <div class="mb-4">
                        <label for="comment" class="sr-only">Comment</label>
                        <textarea name="comment" id="comment" cols="30" rows="1"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('comment') border-red-500 @enderror"
                            placeholder="write a comment ..."></textarea>

                        @error('comment')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded font-medium">Reply</button>
                    </div>
                </form>
            @endauth

            {{-- end comment section --}}

        </div>
    @endif


    @can('update', $post)
        @if ($post->id == request()->get('id'))
            <form action="{{ route('posts.update', $post) }}" method="POST" class="mb-4">
                @method('PATCH')
                @csrf
                <div class="mb-4">
                    <label for="body" class="sr-only">Body</label>
                    <textarea name="body" id="body" cols="30" rows="4"
                        class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('body') border-red-500 @enderror"
                        placeholder="Post something!">{{ $post->body }}</textarea>

                    @error('body')
                        <div class="text-red-500 mt-2 text-sm">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded font-medium">Update</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded font-medium"><a href="{{ route('posts') }}"
                            class="p-3">Cancel</a></button>
                </div>
            </form>
        @endif
    @endcan

</div>
