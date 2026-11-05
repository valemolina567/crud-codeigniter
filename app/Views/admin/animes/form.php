<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<?php
$accion = $anime ? 'admin/animes/' . $anime['id'] : 'admin/animes';
// Tras un error de validación se conservan los géneros marcados; si no, los guardados en BD
$marcados = array_map('intval', (array) (old('genres', null, false) ?? $seleccion));
?>

<div class="tarjeta">
    <h1><?= esc($titulo) ?></h1>

    <form method="post" action="<?= base_url($accion) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <label for="title">Título</label>
        <input type="text" id="title" name="title" maxlength="150" value="<?= esc(old('title', $anime['title'] ?? '', false)) ?>" required>

        <label for="synopsis">Sinopsis</label>
        <textarea id="synopsis" name="synopsis" maxlength="3000"><?= esc(old('synopsis', $anime['synopsis'] ?? '', false)) ?></textarea>

        <div class="fila">
            <div>
                <label for="episodes">Episodios</label>
                <input type="number" id="episodes" name="episodes" min="1" value="<?= esc(old('episodes', $anime['episodes'] ?? '', false)) ?>" required>
            </div>
            <div>
                <label for="release_year">Año de estreno</label>
                <input type="number" id="release_year" name="release_year" min="1950" value="<?= esc(old('release_year', $anime['release_year'] ?? '', false)) ?>" required>
            </div>
            <div>
                <label for="rating">Calificación (0 a 10)</label>
                <input type="number" id="rating" name="rating" min="0" max="10" step="0.01" value="<?= esc(old('rating', $anime['rating'] ?? '', false)) ?>" required>
            </div>
        </div>

        <label>Géneros (relación anime ↔ género)</label>
        <?php if (empty($generos)): ?>
            <p class="ayuda">Primero crea géneros en la sección <a href="<?= base_url('admin/generos') ?>">Géneros</a>.</p>
        <?php else: ?>
            <div class="checks">
                <?php foreach ($generos as $g): ?>
                    <label>
                        <input type="checkbox" name="genres[]" value="<?= (int) $g['id'] ?>" <?= in_array((int) $g['id'], $marcados, true) ? 'checked' : '' ?>>
                        <?= esc($g['name']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <p class="ayuda">Selecciona al menos un género: sin géneros el anime nunca podría recomendarse.</p>
        <?php endif; ?>

        <label for="cover">Portada (JPG, PNG o WEBP, máx. 2 MB)</label>
        <?php if (! empty($anime['cover_url'])): ?>
            <p><img class="miniatura" style="width:100px;height:100px" src="<?= base_url($anime['cover_url']) ?>" alt="Portada actual"></p>
            <p class="ayuda">Sube otra imagen solo si quieres reemplazar la actual.</p>
        <?php endif; ?>
        <input type="file" id="cover" name="cover" accept="image/png,image/jpeg,image/webp">

        <p style="margin-top:20px" class="acciones">
            <button class="btn" type="submit">Guardar</button>
            <a class="btn gris" href="<?= base_url('admin/animes') ?>">Cancelar</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>
