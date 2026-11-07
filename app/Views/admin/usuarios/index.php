<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <h1 style="margin:0">Usuarios del sistema</h1>
        <a class="btn" href="<?= base_url('admin/usuarios/nuevo') ?>">+ Nuevo usuario</a>
    </div>

    <table>
        <thead><tr><th>ID</th><th>Usuario</th><th>Rol</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php if (empty($usuarios)): ?>
            <tr><td colspan="4">No hay usuarios registrados.</td></tr>
        <?php endif; ?>
        <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= (int) $u['id'] ?></td>
                <td>
                    <strong><?= esc($u['usuario']) ?></strong>
                    <?php if ($u['usuario'] === $actual): ?><span class="chip gris">Tú</span><?php endif; ?>
                </td>
                <td>
                    <?php if ($u['rol'] === 'ADMIN'): ?>
                        <span class="chip verde">Administrador</span>
                    <?php else: ?>
                        <span class="chip">Usuario</span>
                    <?php endif; ?>
                </td>
                <td class="acciones">
                    <a class="btn chico gris" href="<?= base_url('admin/usuarios/' . $u['id'] . '/editar') ?>">Editar</a>
                    <?php if ($u['usuario'] !== $actual): ?>
                        <form method="post" action="<?= base_url('admin/usuarios/' . $u['id'] . '/eliminar') ?>"
                              onsubmit="return confirm('¿Eliminar este usuario?')">
                            <?= csrf_field() ?>
                            <button class="btn rojo" type="submit">Eliminar</button>
                        </form>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
