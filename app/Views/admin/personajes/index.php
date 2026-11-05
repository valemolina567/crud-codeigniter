<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <h1 style="margin:0">Personajes</h1>
        <a class="btn" href="<?= base_url('admin/personajes/nuevo') ?>">+ Nuevo personaje</a>
    </div>

    <table>
        <thead><tr><th>Imagen</th><th>Nombre</th><th>Parte</th><th>Stand</th><th>Rol</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (empty($personajes)): ?>
            <tr><td colspan="6">No hay personajes registrados.</td></tr>
        <?php endif; ?>
        <?php foreach ($personajes as $c): ?>
            <tr>
                <td>
                    <?php if (! empty($c['imagen'])): ?>
                        <img class="miniatura" src="<?= base_url($c['imagen']) ?>" alt="<?= esc($c['name']) ?>">
                    <?php else: ?>
                        <span class="chip gris">Sin imagen</span>
                    <?php endif; ?>
                </td>
                <td><strong><?= esc($c['name']) ?></strong></td>
                <td>Parte <?= (int) $c['parte_numero'] ?> · <?= esc($c['parte_titulo']) ?></td>
                <td><?= esc($c['stand_name'] ?? '—') ?></td>
                <td><span class="chip"><?= esc($c['role']) ?></span></td>
                <td class="acciones">
                    <a class="btn chico gris" href="<?= base_url('admin/personajes/' . $c['id'] . '/editar') ?>">Editar</a>
                    <form method="post" action="<?= base_url('admin/personajes/' . $c['id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Eliminar este personaje? También se borrarán sus pesos en el test.')">
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
