<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="contenedor">

    <h1>Lista de Personas</h1>

    <a href="<?= base_url('personas/crear') ?>" class="boton">
        + Agregar Persona
    </a>

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de nacimiento</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php if (!empty($personas)): ?>

                <?php foreach ($personas as $persona): ?>

                    <tr>

                        <td>
                            <?= $persona['id'] ?>
                        </td>

                        <td>
                            <?php if (!empty($persona['imagen'])): ?>

                                <img
                                    src="<?= base_url('uploads/personas/' . $persona['imagen']) ?>"
                                    alt="Foto de <?= htmlspecialchars($persona['nombre']) ?>"
                                    class="imagen"
                                >

                            <?php else: ?>

                                Sin imagen

                            <?php endif; ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($persona['nombre']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($persona['apellido']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($persona['fecha_nacimiento']) ?>
                        </td>

                        <td>

                            <a
                                href="<?= base_url('personas/editar/' . $persona['id']) ?>"
                                class="editar"
                            >
                                Editar
                            </a>

                            <a
                                href="<?= base_url('personas/eliminar/' . $persona['id']) ?>"
                                class="eliminar"
                                onclick="return confirm('¿Seguro que deseas eliminar esta persona?')"
                            >
                                Eliminar
                            </a>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="6">
                        No hay personas registradas.
                    </td>
                </tr>

            <?php endif; ?>

        </tbody>

    </table>

</div>

<?= $this->endSection() ?>
