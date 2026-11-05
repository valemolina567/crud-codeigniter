<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <h1 style="margin:0">Preguntas del test de compatibilidad</h1>
        <a class="btn" href="<?= base_url('admin/preguntas/nuevo') ?>">+ Nueva pregunta</a>
    </div>

    <table>
        <thead><tr><th>Pos.</th><th>Pregunta</th><th>Opciones</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (empty($preguntas)): ?>
            <tr><td colspan="5">No hay preguntas registradas.</td></tr>
        <?php endif; ?>
        <?php foreach ($preguntas as $q): ?>
            <tr>
                <td><?= (int) $q['position'] ?></td>
                <td><strong><?= esc($q['text']) ?></strong></td>
                <td><span class="chip gris"><?= (int) $q['total_opciones'] ?></span></td>
                <td>
                    <?php if ((int) $q['is_active'] === 1): ?>
                        <span class="chip verde">Activa</span>
                    <?php else: ?>
                        <span class="chip gris">Inactiva</span>
                    <?php endif; ?>
                </td>
                <td class="acciones">
                    <a class="btn chico" href="<?= base_url('admin/preguntas/' . $q['id'] . '/opciones') ?>">Opciones y pesos</a>
                    <a class="btn chico gris" href="<?= base_url('admin/preguntas/' . $q['id'] . '/editar') ?>">Editar</a>
                    <form method="post" action="<?= base_url('admin/preguntas/' . $q['id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Eliminar la pregunta con todas sus opciones y pesos?')">
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
