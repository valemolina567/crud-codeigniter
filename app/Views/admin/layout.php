<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo ?? 'JoJo Fandom Hub') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/jojo-theme.css') ?>">
</head>
<body>
    <?php $esAdmin = session()->get('rol') === 'ADMIN'; ?>
    <nav>
        <span class="marca">JoJo Fandom Hub<?= $esAdmin ? ' · Admin' : '' ?></span>
        <?php if ($esAdmin): ?>
            <a href="<?= base_url('admin') ?>">Inicio</a>
            <a href="<?= base_url('admin/partes') ?>">Partes</a>
            <a href="<?= base_url('admin/personajes') ?>">Personajes</a>
            <a href="<?= base_url('admin/generos') ?>">Géneros</a>
            <a href="<?= base_url('admin/animes') ?>">Anime</a>
            <a href="<?= base_url('admin/preguntas') ?>">Test</a>
            <a href="<?= base_url('admin/usuarios') ?>">Usuarios</a>
        <?php else: ?>
            <a href="<?= base_url('personas') ?>">Personas</a>
        <?php endif; ?>
        <span class="derecha">
            <?= esc(session()->get('usuario')) ?> ·
            <a href="<?= base_url('logout') ?>" style="padding:0">Cerrar sesión</a>
        </span>
    </nav>

    <main>
        <?php if ($mensaje = session()->getFlashdata('exito')): ?>
            <div class="ok"><?= esc($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($mensaje = session()->getFlashdata('error')): ?>
            <div class="err"><?= esc($mensaje) ?></div>
        <?php endif; ?>

        <?php if ($errores = session()->getFlashdata('errores')): ?>
            <div class="err">
                <strong>Revisa los siguientes puntos:</strong>
                <ul>
                    <?php foreach ((array) $errores as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('contenido') ?>
    </main>
</body>
</html>
