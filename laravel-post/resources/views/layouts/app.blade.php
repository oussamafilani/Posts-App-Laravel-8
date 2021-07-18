<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>post app</title>
    
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body class="bg-gray-200">
    <nav class="p-6 bg-white flex justify-between mb-6">
        
    <ul class="flex items-center">         

        <li>
            <a href="/" class="p-3">Home</a>
        </li> 
        <li>
            <a href="{{route('dashboard')}}" class="p-3">Dashboard</a>
        </li> 
        <li>
            <a href="{{route('posts')}}" class="p-3">Post</a>
        </li> 

    </ul>
    <ul class="flex items-center">

        @auth
            
        <li>
            <a href="" class="p-3">{{auth()->User()->username}}</a>
        </li> 

        <li>
            <form action="{{route('logout')}}" method="POST" class="p-3 inline">
                @csrf
                <button class="p-3" type="submit">Logout</button>
            </form>
        </li> 

        @endauth

        @guest
        <li>
            <a href="{{route('login')}}" class="p-3">Login</a>
        </li> 
        <li>
            <a href="{{route('register')}}" class="p-3">Register</a>
        </li> 
        @endguest
        

    </ul>

    </nav>
    @yield('content')
</body>
</html>