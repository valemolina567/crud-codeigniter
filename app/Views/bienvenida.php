<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta" style="text-align:center; padding:40px 26px;">
    <h1>¡Bienvenido, <?= esc($usuario) ?>!</h1>
    <p style="font-size:17px; margin:0 auto 14px; max-width:620px;">
        Te damos la bienvenida a <strong>JoJo Fandom Hub</strong>, la comunidad para los fans de
        JoJo's Bizarre Adventure.
    </p>
    <p class="ayuda" style="font-size:15px; max-width:620px; margin:0 auto;">
        Estamos preparando tu test de compatibilidad con personajes, las recomendaciones de anime y
        la búsqueda de fans con tus mismos intereses. ¡Vuelve pronto!
    </p>
</div>

<?= $this->endSection() ?>
