@extends('layout.navbar')


@section('content')
    <img class="rounded-lg" src="images/phantom.jpeg" alt="boogiepop">

    <div class="my-4">

        <h1 class="font-bold text-center text-3xl">{{ $page_data['framework']}} Framework</h1>
        <p class="font-semibold text-2xl text-center">Una Arquitectura Modular Inspirada en NestJS</p>
    </div>
    <p class='text-center'>{{ $page_data['framework']}} es un framework de PHP diseñado para el desarrollo de aplicaciones escalables, estructuradas y altamente
        mantenibles. Inspirado en la arquitectura de NestJS, Phantom adopta un enfoque modular que organiza el código en
        componentes bien definidos como módulos, controladores, servicios, guardias, middlewares y pipes. Esta estructura
        permite una clara separación de responsabilidades y facilita el crecimiento de las aplicaciones sin comprometer la
        organización del código.</p>
@endsection
