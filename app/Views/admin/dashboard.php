<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <h1>Panel de administración</h1>
    <p class="ayuda">Aquí se administran los datos que alimentan el Core «Perfil de afinidad JoJo».</p>
    <div class="stats">
        <?php foreach ($totales as $nombre => $total): ?>
            <div class="stat"><strong><?= (int) $total ?></strong><?= esc($nombre) ?></div>
        <?php endforeach; ?>
    </div>
</div>

<div class="tarjeta">
    <h2>Orden recomendado de carga</h2>
    <ol>
        <li><a href="<?= base_url('admin/partes') ?>">Partes</a> de la saga</li>
        <li><a href="<?= base_url('admin/personajes') ?>">Personajes</a> (se asignan a una parte)</li>
        <li><a href="<?= base_url('admin/generos') ?>">Géneros</a> y <a href="<?= base_url('admin/animes') ?>">Anime</a></li>
        <li><a href="<?= base_url('admin/preguntas') ?>">Preguntas del test</a>, sus opciones y los pesos hacia personajes y géneros</li>
    </ol>
</div>

<?= $this->endSection() ?>
