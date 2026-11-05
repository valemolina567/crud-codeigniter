<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <h1 style="margin:0">Partes de la saga</h1>
        <a class="btn" href="<?= base_url('admin/partes/nuevo') ?>">+ Nueva parte</a>
    </div>

    <table>
        <thead><tr><th>N.º</th><th>Título</th><th>Protagonista</th><th>Año</th><th>Personajes</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (empty($partes)): ?>
            <tr><td colspan="6">No hay partes registradas.</td></tr>
        <?php endif; ?>
        <?php foreach ($partes as $p): ?>
            <tr>
                <td><?= (int) $p['number'] ?></td>
                <td><strong><?= esc($p['title']) ?></strong></td>
                <td><?= esc($p['protagonist']) ?></td>
                <td><?= esc($p['release_year']) ?></td>
                <td><span class="chip gris"><?= (int) $p['total_personajes'] ?></span></td>
                <td class="acciones">
                    <a class="btn chico gris" href="<?= base_url('admin/partes/' . $p['id'] . '/editar') ?>">Editar</a>
                    <form method="post" action="<?= base_url('admin/partes/' . $p['id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Eliminar esta parte?')">
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
