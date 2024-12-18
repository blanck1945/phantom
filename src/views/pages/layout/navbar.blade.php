@extends('template.metada')

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @foreach ($page_data['metadata']['css'] as $css_file)
        <link rel="stylesheet" href="/css/styles.css">
    @endforeach

    <meta property="og:title" content="Phantom App" />
    <meta property="og:description" content="Light PHP framework for productivity" />
    <meta property="og:image" content="https://omniglot.com/images/langsamples/udhr_japanese-vert.gif" />

    <script src="https://cdn.tailwindcss.com"></script>
    <title>
        Phantom App
    </title>
</head>

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
