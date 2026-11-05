<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <div>
            <h1 style="margin:0 0 4px">Opciones de respuesta</h1>
            <span class="ayuda">Pregunta: <strong><?= esc($pregunta['text']) ?></strong>
                · <?= (int) $pregunta['is_active'] === 1 ? 'Activa' : 'Inactiva' ?></span>
        </div>
        <a class="btn gris" href="<?= base_url('admin/preguntas') ?>">← Volver a preguntas</a>
    </div>

    <table>
        <thead><tr><th>Opción</th><th>Pesos a personajes</th><th>Pesos a géneros</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (empty($opciones)): ?>
            <tr><td colspan="4">Esta pregunta aún no tiene opciones.</td></tr>
        <?php endif; ?>
        <?php foreach ($opciones as $o): ?>
            <tr>
                <td><?= esc($o['text']) ?></td>
                <td><span class="chip <?= (int) $o['pesos_personajes'] > 0 ? 'verde' : '' ?>"><?= (int) $o['pesos_personajes'] ?></span></td>
                <td><span class="chip gris"><?= (int) $o['pesos_generos'] ?></span></td>
                <td class="acciones">
                    <a class="btn chico" href="<?= base_url('admin/opciones/' . $o['id']) ?>">Editar pesos</a>
                    <form method="post" action="<?= base_url('admin/opciones/' . $o['id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Eliminar esta opción y sus pesos?')">
                        <?= csrf_field() ?>
                        <button class="btn rojo" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="tarjeta">
    <h2>Agregar opción</h2>
    <form method="post" action="<?= base_url('admin/preguntas/' . $pregunta['id'] . '/opciones') ?>">
        <?= csrf_field() ?>
        <label for="text">Texto de la opción</label>
        <input type="text" id="text" name="text" maxlength="255" value="<?= esc(old('text', null, false)) ?>" required>
        <p style="margin-top:16px"><button class="btn" type="submit">Agregar y asignar pesos</button></p>
    </form>
</div>

<?= $this->endSection() ?>
