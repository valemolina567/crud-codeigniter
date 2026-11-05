<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <h1 style="margin:0">Catálogo de anime</h1>
        <a class="btn" href="<?= base_url('admin/animes/nuevo') ?>">+ Nuevo anime</a>
    </div>

    <table>
        <thead><tr><th>Portada</th><th>Título</th><th>Géneros</th><th>Año</th><th>Eps.</th><th>Rating</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (empty($animes)): ?>
            <tr><td colspan="7">No hay anime registrado.</td></tr>
        <?php endif; ?>
        <?php foreach ($animes as $a): ?>
            <tr>
                <td>
                    <?php if (! empty($a['cover_url'])): ?>
                        <img class="miniatura" src="<?= base_url($a['cover_url']) ?>" alt="<?= esc($a['title']) ?>">
                    <?php else: ?>
                        <span class="chip gris">Sin portada</span>
                    <?php endif; ?>
                </td>
                <td><strong><?= esc($a['title']) ?></strong></td>
                <td><?= esc($a['generos'] ?? '—') ?></td>
                <td><?= esc($a['release_year']) ?></td>
                <td><?= (int) $a['episodes'] ?></td>
                <td><span class="chip verde"><?= esc(number_format((float) $a['rating'], 2)) ?></span></td>
                <td class="acciones">
                    <a class="btn chico gris" href="<?= base_url('admin/animes/' . $a['id'] . '/editar') ?>">Editar</a>
                    <form method="post" action="<?= base_url('admin/animes/' . $a['id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Eliminar este anime?')">
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
