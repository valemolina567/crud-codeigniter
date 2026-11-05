<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <h1 style="margin:0">Géneros</h1>
        <a class="btn" href="<?= base_url('admin/generos/nuevo') ?>">+ Nuevo género</a>
    </div>

    <table>
        <thead><tr><th>Nombre</th><th>Descripción</th><th>Anime</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (empty($generos)): ?>
            <tr><td colspan="4">No hay géneros registrados.</td></tr>
        <?php endif; ?>
        <?php foreach ($generos as $g): ?>
            <tr>
                <td><strong><?= esc($g['name']) ?></strong></td>
                <td><?= esc($g['description']) ?></td>
                <td><span class="chip gris"><?= (int) $g['total_anime'] ?></span></td>
                <td class="acciones">
                    <a class="btn chico gris" href="<?= base_url('admin/generos/' . $g['id'] . '/editar') ?>">Editar</a>
                    <form method="post" action="<?= base_url('admin/generos/' . $g['id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Eliminar este género?')">
                        <?= csrf_field() ?>
                        <button class="btn rojo" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
