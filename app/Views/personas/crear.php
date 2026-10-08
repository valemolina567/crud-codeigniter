<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Agregar Persona</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 40px;
        }

        .contenedor {
            max-width: 600px;
            margin: auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 25px;
        }

        .campo {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .botones {
            margin-top: 25px;
        }

        button {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #555;
        }

        .volver {
            margin-left: 10px;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="contenedor">

    <h1>Agregar Persona</h1>

    <form action="<?= base_url('personas/guardar') ?>" method="post" enctype="multipart/form-data">

        <?= csrf_field() ?>

        <div class="campo">
            <label for="nombre">Nombre:</label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                required
            >
        </div>

        <div class="campo">
            <label for="apellido">Apellido:</label>

            <input
                type="text"
                id="apellido"
                name="apellido"
                required
            >
        </div>

        <div class="campo">
            <label for="fecha_nacimiento">Fecha de nacimiento:</label>

            <input
                type="date"
                id="fecha_nacimiento"
                name="fecha_nacimiento"
                required
            >
        </div>

        <div class="campo">
            <label for="imagen">Imagen:</label>
            <input
                type="file"
                id="imagen"
                name="imagen"
                accept="image/*"
                required
            >
        </div>

        <div class="botones">

            <button type="submit">
                Guardar Persona
            </button>

            <a href="<?= base_url('personas') ?>" class="volver">
                Volver
            </a>

        </div>

    </form>

</div>

</body>
</html>