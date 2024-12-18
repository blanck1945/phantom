@extends('layout.navbar')


@section('content')
    <img src="images/phantom.jpeg" alt="boogiepop">

    <h1 class="text-bold text-2xl my-4">{{ $page_data['framework']}} Framework: Una Arquitectura Modular Inspirada en NestJS</h1>
    <p class='text-center'>{{ $page_data['framework']}} es un framework de PHP diseñado para el desarrollo de aplicaciones escalables, estructuradas y altamente
        mantenibles. Inspirado en la arquitectura de NestJS, Phantom adopta un enfoque modular que organiza el código en
        componentes bien definidos como módulos, controladores, servicios, guardias, middlewares y pipes. Esta estructura
        permite una clara separación de responsabilidades y facilita el crecimiento de las aplicaciones sin comprometer la
        organización del código.</p>
@endsection
