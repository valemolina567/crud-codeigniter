<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Personas</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 40px;
        }

        .contenedor {
            max-width: 1000px;
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

        .boton {
            display: inline-block;
            background-color: #333;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .boton:hover {
            background-color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #333;
            color: white;
        }

        .imagen {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
        }

        .editar {
            color: #0066cc;
            text-decoration: none;
            margin-right: 10px;
        }

        .eliminar {
            color: #cc0000;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="contenedor">

    <h1>Lista de Personas</h1>

    <a href="<?= base_url('personas/crear') ?>" class="boton">
        + Agregar Persona
    </a>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($personas)): ?>

                <?php foreach ($personas as $persona): ?>

                    <tr>

                        <td>
                            <?= $persona['id'] ?>
                        </td>

                        <td>
                            <?php if (!empty($persona['imagen'])): ?>

                                <img
                                    src="<?= base_url('uploads/personas/' . $persona['imagen']) ?>"
                                    alt="Foto de <?= htmlspecialchars($persona['nombre']) ?>"
                                    class="imagen"
                                >

                            <?php else: ?>

                                Sin imagen

                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($persona['nombre']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($persona['apellido']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($persona['fecha_nacimiento']) ?>
                        </td>

                        <td>

                            <a
                                href="<?= base_url('personas/editar/' . $persona['id']) ?>"
                                class="editar"
                            >
                                Editar
                            </a>

                            <a
                                href="<?= base_url('personas/eliminar/' . $persona['id']) ?>"
                                class="eliminar"
                                onclick="return confirm('¿Seguro que deseas eliminar esta persona?')"
                            >
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6">
                        No hay personas registradas.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<div style="display: flex; justify-content: space-between;
            align-items: center; margin-bottom: 20px;">

    <span>
        Sesión iniciada como:
        <strong><?= esc(session()->get('usuario')) ?></strong>
    </span>

    <a href="<?= base_url('logout') ?>"
       style="background: #dc2626; color: white; padding: 10px 15px;
              border-radius: 8px; text-decoration: none;">
        Cerrar sesión
    </a>

</div>

</body>
</html>