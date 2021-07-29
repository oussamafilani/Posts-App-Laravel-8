<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>post app</title>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

{{-- <body class="bg-gray-200"> --}}

<body style="background-color: #F3F2EF">
    <div class="header-2">

        <nav class="bg-white py-2 mb-6 md:py-4">
            <div class="container px-4 mx-auto md:flex md:items-center">

                <div class="flex justify-between items-center">
                    <a href="/" class="font-bold text-xl text-indigo-600">FORUM</a>
                    <button
                        class="border border-solid border-gray-600 px-3 py-1 rounded text-gray-600 opacity-50 hover:opacity-75 md:hidden"
                        id="navbar-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

                <div class="hidden md:flex flex-col md:flex-row md:ml-auto mt-3 md:mt-0" id="navbar-collapse">

                    @auth
                        <a href="#"
                            class="p-2 lg:px-4 md:mx-2 text-gray-600 rounded hover:bg-gray-200 hover:text-gray-700 transition-colors duration-300">{{ auth()->User()->username }}</a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="p-2 lg:px-4 md:mx-2 text-indigo-600 text-center border border-transparent rounded hover:bg-indigo-100 hover:text-indigo-700 transition-colors duration-300">Logout</button>
                        </form>

                    @endauth
                    @guest
                        <a href="{{ route('login') }}"
                            class="p-2 lg:px-4 md:mx-2 text-indigo-600 text-center border border-transparent rounded hover:bg-indigo-100 hover:text-indigo-700 transition-colors duration-300">Login</a>
                        <a href="{{ route('register') }}"
                            class="p-2 lg:px-4 md:mx-2 text-indigo-600 text-center border border-solid border-indigo-600 rounded hover:bg-indigo-600 hover:text-white transition-colors duration-300 mt-1 md:mt-0 md:ml-1">Signup</a>
                    @endguest
                </div>
            </div>
        </nav>

    </div>
    @yield('content')

    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
