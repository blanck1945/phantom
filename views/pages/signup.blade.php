@extends('template.metada')

<body class="w-full bg-slate-500">

    <div>

    </div>
    <form class="rounded bg-white flex flex-col gap-4" method="POST" action="/signup">
    <h2>Crea tu tienda gratis</h2>

    <div>
        <label for="mise_name">Nombre de tu comercio</label>
        <input type="text" name="mise_name" id="mise_name">
    </div>

    <div>
        <label for="mise_slug">URL de tu tienda</label>
        <input type="text" name="mise_slug" id="mise_slug">
    </div>

    <div>
        <label for="mise_price">Price</label>
        <input type="text" name="mise_price" id="mise_price">
    </div>

    <div class="flex flex-col">
        <label class="font-bold" for="mise_category">Categoria de tu negocio</label>
        <select class="border rounded" name="mise_category" id="mise_category">
            @foreach($page_data["categories"] as $category)
                <option value="{{ $category->id }}">{{ $category->value }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="mise_phone">Numero donde se comunicaran contigo</label>
        <input type="text" name="mise_phone" id="mise_phone">
    </div>

    <div class="bg-slate-500">
        <label class="" for="mise_country">Pais de tu negocio</label>
        <select name="mise_country" id="mise_country">
            <option value="ARG">Argentina</option>
            <option value="CHL">Chile</option>
        </select>
    </div>

    <div>
        <label for="mise_email">Tu correo electronico</label>
        <input type="text" name="mise_email" id="mise_email">
    </div>

    <div>
        <label for="mise_password">Contraseña</label>
        <input type="password" name="mise_password" id="mise_password">
    </div>

    <div>
        <label for="mise_terms">Acepto las condiciones del servicio</label>
        <input type="checkbox" name="mise_terms" id="mise_terms">
    </div>

    <button>Crear tu cuenta</button>
</form>
    
</body>
</html>