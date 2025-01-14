<!DOCTYPE html>
<html lang="en">

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

@yield('navbar')
