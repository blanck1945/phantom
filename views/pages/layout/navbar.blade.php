@extends('template.metada')

@section('navbar')

    <body class="pt-0">
        <nav class="border-b flex items-center gap-5 h-16 text-2xl px-4">
            <a href="/">Home</a>
            <a href="/docs">Docs</a>
        </nav>

        <main class="mt-4 flex flex-col items-center w-1/2 m-auto">
            @yield('content')
        </main>
    </body>
    
@endsection
