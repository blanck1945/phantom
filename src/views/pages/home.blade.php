@extends('layout.navbar')


@section('content')
    <img src="images/th.jpeg" alt="boogiepop">

    <h1 class="text-red-700">Blade template</h1>
    <p>Blade is the simple, yet powerful templating engine provided with Laravel. Unlike other popular PHP templating
        engines, Blade does not restrict you from using plain PHP code in your views. All Blade views are compiled into
        plain PHP code and cached until they are modified, meaning Blade adds essentially zero overhead to your application.
        Blade view files use the .blade.php file extension and are typically stored in the resources/views directory.</p>
@endsection
