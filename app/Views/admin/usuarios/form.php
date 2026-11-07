<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<?php
$editando = $usuario !== null;
$accion   = $editando ? 'admin/usuarios/' . $usuario['id'] : 'admin/usuarios';
$rolActual = old('rol', $usuario['rol'] ?? 'USER', false);
?>

<div class="tarjeta">
    <h1><?= esc($titulo) ?></h1>

    <form method="post" action="<?= base_url($accion) ?>" autocomplete="off">
        <?= csrf_field() ?>

        <label for="usuario">Usuario</label>
        <?php if ($editando): ?>
            <input type="text" id="usuario" value="<?= esc($usuario['usuario']) ?>" disabled>
            <p class="ayuda">El nombre de usuario no se puede cambiar.</p>
        <?php else: ?>
            <input type="text" id="usuario" name="usuario" maxlength="50" value="<?= esc(old('usuario', null, false)) ?>" required>
            <p class="ayuda">Letras, números, guion y guion bajo (mínimo 3 caracteres).</p>
        <?php endif; ?>

        <label for="rol">Rol</label>
        <select id="rol" name="rol" required>
            <option value="USER" <?= $rolActual === 'USER' ? 'selected' : '' ?>>Usuario (puede hacer el test y ver recomendaciones)</option>
            <option value="ADMIN" <?= $rolActual === 'ADMIN' ? 'selected' : '' ?>>Administrador (acceso al panel)</option>
        </select>

        <div class="fila">
            <div>
                <label for="password"><?= $editando ? 'Nueva contraseña (opcional)' : 'Contraseña' ?></label>
                <input type="password" id="password" name="password" maxlength="72" autocomplete="new-password" <?= $editando ? '' : 'required' ?>>
            </div>
            <div>
                <label for="password_confirm">Confirmar contraseña</label>
                <input type="password" id="password_confirm" name="password_confirm" maxlength="72" autocomplete="new-password" <?= $editando ? '' : 'required' ?>>
            </div>
        </div>
        <p class="ayuda">Mínimo 8 caracteres, con mayúscula, minúscula y número.<?= $editando ? ' Déjala vacía para no cambiarla.' : '' ?></p>

        <p style="margin-top:20px" class="acciones">
            <button class="btn" type="submit">Guardar</button>
            <a class="btn gris" href="<?= base_url('admin/usuarios') ?>">Cancelar</a>
        </p>
    </form>
</div>

<?= $this->endSection() ?>
