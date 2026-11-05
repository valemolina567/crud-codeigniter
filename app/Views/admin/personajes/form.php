<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<?php $accion = $personaje ? 'admin/personajes/' . $personaje['id'] : 'admin/personajes'; ?>

<div class="tarjeta">
    <h1><?= esc($titulo) ?></h1>

    <form method="post" action="<?= base_url($accion) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <label for="part_id">Parte de la saga</label>
        <select id="part_id" name="part_id" required>
            <option value="">— Selecciona una parte —</option>
            <?php foreach ($partes as $p): ?>
                <option value="<?= (int) $p['id'] ?>" <?= (string) old('part_id', $personaje['part_id'] ?? '', false) === (string) $p['id'] ? 'selected' : '' ?>>
                    Parte <?= (int) $p['number'] ?> · <?= esc($p['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <div class="fila">
            <div>
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" maxlength="100" value="<?= esc(old('name', $personaje['name'] ?? '', false)) ?>" required>
            </div>
            <div>
                <label for="stand_name">Nombre del Stand (opcional)</label>
                <input type="text" id="stand_name" name="stand_name" maxlength="100" value="<?= esc(old('stand_name', $personaje['stand_name'] ?? '', false)) ?>">
            </div>
        </div>

        <label for="role">Rol</label>
        <select id="role" name="role" required>
            <option value="">— Selecciona un rol —</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= esc($rol) ?>" <?= old('role', $personaje['role'] ?? '', false) === $rol ? 'selected' : '' ?>><?= esc($rol) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="description">Descripción</label>
        <textarea id="description" name="description" maxlength="2000"><?= esc(old('description', $personaje['description'] ?? '', false)) ?></textarea>

        <label for="imagen">Imagen (JPG, PNG o WEBP, máx. 2 MB)</label>
        <?php if (! empty($imagen)): ?>
            <p><img class="miniatura" style="width:100px;height:100px" src="<?= base_url($imagen) ?>" alt="Imagen actual"></p>
            <p class="ayuda">Sube otra imagen solo si quieres reemplazar la actual.</p>
        <?php endif; ?>
        <input type="file" id="imagen" name="imagen" accept="image/png,image/jpeg,image/webp">

        <p style="margin-top:20px" class="acciones">
            <button class="btn" type="submit">Guardar</button>
            <a class="btn gris" href="<?= base_url('admin/personajes') ?>">Cancelar</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>
