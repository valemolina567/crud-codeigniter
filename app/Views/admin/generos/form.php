<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<?php $accion = $genero ? 'admin/generos/' . $genero['id'] : 'admin/generos'; ?>

<div class="tarjeta">
    <h1><?= esc($titulo) ?></h1>

    <form method="post" action="<?= base_url($accion) ?>">
        <?= csrf_field() ?>

        <label for="name">Nombre</label>
        <input type="text" id="name" name="name" maxlength="30" value="<?= esc(old('name', $genero['name'] ?? '', false)) ?>" required>

        <label for="description">Descripción</label>
        <textarea id="description" name="description" maxlength="500"><?= esc(old('description', $genero['description'] ?? '', false)) ?></textarea>

        <p style="margin-top:20px" class="acciones">
            <button class="btn" type="submit">Guardar</button>
            <a class="btn gris" href="<?= base_url('admin/generos') ?>">Cancelar</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>
