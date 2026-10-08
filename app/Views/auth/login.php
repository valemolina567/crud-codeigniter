
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .login {
            width: 100%;
            max-width: 400px;
            padding: 35px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 30px #00000012;
        }

        h1 {
            margin-top: 0;
            color: #1e293b;
            text-align: center;
        }

        p {
            color: #64748b;
            text-align: center;
        }

        label {
            display: block;
            margin: 18px 0 7px;
            font-weight: bold;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        button {
            width: 100%;
            margin-top: 25px;
            padding: 13px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            padding: 12px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login">
        <h1>Bienvenido</h1>
        <p>Ingresa para administrar las personas.</p>

        <?php $mensajeError = session()->getFlashdata('error'); ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
            <?php endif; ?>

        <form action="<?= base_url('login') ?>" method="post">
            <?= csrf_field() ?>

            <label for="usuario">Usuario</label>
            <input
                type="text"
                id="usuario"
                name="usuario"
                value="<?= esc(old('usuario')) ?>"
                autocomplete="username"
                required
            >

            <label for="password">Contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>