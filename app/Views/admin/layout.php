<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo ?? 'Administración') ?> · JoJo Fandom Hub</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f1f5f9; color: #1e293b; }
        nav { background: #7f1d2d; padding: 0 24px; display: flex; flex-wrap: wrap; align-items: center; gap: 4px; }
        nav .marca { color: #fff; font-weight: bold; margin-right: 18px; padding: 14px 0; }
        nav a { color: #fde2e4; text-decoration: none; padding: 14px 10px; font-size: 14px; }
        nav a:hover { background: #9b2c3d; color: #fff; }
        nav .derecha { margin-left: auto; color: #fde2e4; font-size: 13px; }
        main { max-width: 1050px; margin: 28px auto; padding: 0 20px; }
        .tarjeta { background: #fff; border-radius: 12px; padding: 26px; box-shadow: 0 2px 10px #0000000f; margin-bottom: 22px; }
        h1 { margin: 0 0 18px; font-size: 24px; }
        h2 { margin: 0 0 14px; font-size: 18px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 14px; vertical-align: middle; }
        th { background: #f8fafc; color: #475569; }
        label { display: block; margin: 14px 0 6px; font-weight: bold; font-size: 14px; }
        input[type=text], input[type=number], input[type=file], select, textarea {
            width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; background: #fff;
        }
        textarea { min-height: 90px; resize: vertical; }
        .btn { display: inline-block; border: 0; border-radius: 8px; padding: 9px 16px; background: #7f1d2d; color: #fff;
               font-size: 14px; cursor: pointer; text-decoration: none; }
        .btn:hover { background: #9b2c3d; }
        .btn.gris { background: #64748b; }
        .btn.rojo { background: #dc2626; padding: 6px 12px; }
        .btn.chico { padding: 6px 12px; }
        .acciones { display: flex; gap: 8px; align-items: center; }
        .acciones form { margin: 0; }
        .barra { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
        .ok { background: #dcfce7; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; }
        .err { background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 18px; }
        .err ul { margin: 0; padding-left: 18px; }
        .fila { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; }
        .checks { display: flex; flex-wrap: wrap; gap: 8px 18px; }
        .checks label { display: flex; align-items: center; gap: 6px; margin: 0; font-weight: normal; }
        .checks input { width: auto; }
        .miniatura { width: 56px; height: 56px; object-fit: cover; border-radius: 8px; background: #e2e8f0; }
        .chip { display: inline-block; background: #fde2e4; color: #7f1d2d; border-radius: 99px; padding: 2px 10px; font-size: 12px; }
        .chip.verde { background: #dcfce7; color: #166534; }
        .chip.gris { background: #e2e8f0; color: #475569; }
        .ayuda { color: #64748b; font-size: 13px; margin: 4px 0 0; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 14px; }
        .stat { background: #f8fafc; border-radius: 10px; padding: 16px; text-align: center; }
        .stat strong { display: block; font-size: 28px; color: #7f1d2d; }
    </style>
</head>
<body>
    <nav>
        <span class="marca">JoJo Fandom Hub · Admin</span>
        <a href="<?= base_url('admin') ?>">Inicio</a>
        <a href="<?= base_url('admin/partes') ?>">Partes</a>
        <a href="<?= base_url('admin/personajes') ?>">Personajes</a>
        <a href="<?= base_url('admin/generos') ?>">Géneros</a>
        <a href="<?= base_url('admin/animes') ?>">Anime</a>
        <a href="<?= base_url('admin/preguntas') ?>">Test</a>
        <a href="<?= base_url('personas') ?>">CRUD Personas</a>
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
