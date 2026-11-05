<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<?php $accion = $parte ? 'admin/partes/' . $parte['id'] : 'admin/partes'; ?>

<div class="tarjeta">
    <h1><?= esc($titulo) ?></h1>

    <form method="post" action="<?= base_url($accion) ?>">
        <?= csrf_field() ?>

        <div class="fila">
            <div>
                <label for="number">Número de parte</label>
                <input type="number" id="number" name="number" min="1" max="20" value="<?= esc(old('number', $parte['number'] ?? '', false)) ?>" required>
            </div>
            <div>
                <label for="release_year">Año de publicación</label>
                <input type="number" id="release_year" name="release_year" min="1980" value="<?= esc(old('release_year', $parte['release_year'] ?? '', false)) ?>" required>
            </div>
        </div>

        <label for="title">Título</label>
        <input type="text" id="title" name="title" maxlength="100" value="<?= esc(old('title', $parte['title'] ?? '', false)) ?>" required>

        <label for="protagonist">Protagonista</label>
        <input type="text" id="protagonist" name="protagonist" maxlength="100" value="<?= esc(old('protagonist', $parte['protagonist'] ?? '', false)) ?>" required>

        <p style="margin-top:20px" class="acciones">
            <button class="btn" type="submit">Guardar</button>
            <a class="btn gris" href="<?= base_url('admin/partes') ?>">Cancelar</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>
