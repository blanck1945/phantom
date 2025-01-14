@extends('template.metadata')

@section('navbar')

    <body class="pt-0">
        <nav class="border-b flex justify-between h-16 text-2xl px-4">
            <img 
            src="https://cdn.vectorstock.com/i/1000x1000/70/63/isolated-object-coin-and-coins-logo-graphic-vector-27617063.webp" alt=""
            class="w-12 h-12">

            <div class="flex items-center gap-3 text-base">
                <a href="/dashboard">Overview</a>
                <a href="/transactions">Trasanctions</a>
                <a href="/categories">Categories</a>
                <span>|</span>
                <p class="flex items-center gap-1">
                    <img class="w-12 h-8" src="https://banner2.cleanpng.com/20180418/gre/avf82tlrb.webp" alt="user_avatar">
                </p>
            </div>
        </nav>

        <main class="mt-4 flex flex-col items-center w-1/2 m-auto">
            @yield('content')
        </main>
    </body>
    
@endsection
