<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<?php $accion = $pregunta ? 'admin/preguntas/' . $pregunta['id'] : 'admin/preguntas'; ?>

<div class="tarjeta">
    <h1><?= esc($titulo) ?></h1>

    <form method="post" action="<?= base_url($accion) ?>">
        <?= csrf_field() ?>

        <label for="text">Texto de la pregunta</label>
        <input type="text" id="text" name="text" maxlength="255" value="<?= esc(old('text', $pregunta['text'] ?? '', false)) ?>" required>

        <label for="position">Posición en el test</label>
        <input type="number" id="position" name="position" min="1" max="100" value="<?= esc(old('position', $pregunta['position'] ?? 1, false)) ?>" required>

        <?php if ($pregunta): ?>
            <div class="checks" style="margin-top:16px">
                <label>
                    <input type="checkbox" name="is_active" value="1" <?= old('is_active', $pregunta['is_active'], false) ? 'checked' : '' ?>>
                    Pregunta activa (aparece en el test)
                </label>
            </div>
            <p class="ayuda">Solo puede activarse si tiene al menos 2 opciones y cada opción tiene pesos de personajes.</p>
        <?php else: ?>
            <p class="ayuda">La pregunta se crea inactiva; podrás activarla cuando tenga opciones y pesos.</p>
        <?php endif; ?>

        <p style="margin-top:20px" class="acciones">
            <button class="btn" type="submit">Guardar</button>
            <a class="btn gris" href="<?= base_url('admin/preguntas') ?>">Cancelar</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>
