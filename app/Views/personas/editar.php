<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="contenedor">

    <h1>Editar Persona</h1>

    <form action="<?= base_url('personas/actualizar/' . $persona['id']) ?>" method="post" enctype="multipart/form-data">

        <?= csrf_field() ?>

        <div class="campo">
            <label for="nombre">Nombre:</label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                value="<?= htmlspecialchars($persona['nombre']) ?>"
                required
            >
        </div>

        <div class="campo">
            <label for="apellido">Apellido:</label>

            <input
                type="text"
                id="apellido"
                name="apellido"
                value="<?= htmlspecialchars($persona['apellido']) ?>"
                required
            >
        </div>

        <div class="campo">
            <label for="fecha_nacimiento">Fecha de nacimiento:</label>

            <input
                type="date"
                id="fecha_nacimiento"
                name="fecha_nacimiento"
                value="<?= htmlspecialchars($persona['fecha_nacimiento']) ?>"
                required
            >
        </div>

        <div class="campo">
            <label for="imagen">Imagen:</label>
            <?php if (!empty($persona['imagen'])): ?>
                <p>Imagen actual:</p>
                <img
                    src="<?= base_url('uploads/personas/' . $persona['imagen']) ?>"
                    alt="Imagen actual"
                    style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;"
                >
            <?php endif; ?>
            <br><br>
            <label for="imagen">Seleccionar nueva imagen:</label>
            <input
                type="file"
                id="imagen"
                name="imagen"
                accept="image/*"
            >
        </div>

        <div class="botones">

            <button type="submit">
                Actualizar Persona
            </button>

            <a href="<?= base_url('personas') ?>" class="volver">
                Cancelar
            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>
