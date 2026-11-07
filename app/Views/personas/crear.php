<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="contenedor">

    <h1>Agregar Persona</h1>

    <form action="<?= base_url('personas/guardar') ?>" method="post" enctype="multipart/form-data">

        <?= csrf_field() ?>

        <div class="campo">
            <label for="nombre">Nombre:</label>

            <input
                type="text"
                id="nombre"
                name="nombre"
                required
            >
        </div>

        <div class="campo">
            <label for="apellido">Apellido:</label>

            <input
                type="text"
                id="apellido"
                name="apellido"
                required
            >
        </div>

        <div class="campo">
            <label for="fecha_nacimiento">Fecha de nacimiento:</label>

            <input
                type="date"
                id="fecha_nacimiento"
                name="fecha_nacimiento"
                required
            >
        </div>

        <div class="campo">
            <label for="imagen">Imagen:</label>
            <input
                type="file"
                id="imagen"
                name="imagen"
                accept="image/*"
                required
            >
        </div>

        <div class="botones">

            <button type="submit">
                Guardar Persona
            </button>

            <a href="<?= base_url('personas') ?>" class="volver">
                Volver
            </a>

        </div>

    </form>

</div>

<?= $this->endSection() ?>
