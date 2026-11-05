<?= $this->extend('admin/layout') ?>
<?= $this->section('contenido') ?>

<div class="tarjeta">
    <div class="barra">
        <div>
            <h1 style="margin:0 0 4px">Pesos de la opción</h1>
            <span class="ayuda">Pregunta: <strong><?= esc($pregunta['text']) ?></strong></span>
        </div>
        <a class="btn gris" href="<?= base_url('admin/preguntas/' . $pregunta['id'] . '/opciones') ?>">← Volver a opciones</a>
    </div>

    <form method="post" action="<?= base_url('admin/opciones/' . $opcion['id']) ?>">
        <?= csrf_field() ?>
        <label for="text">Texto de la opción</label>
        <input type="text" id="text" name="text" maxlength="255" value="<?= esc(old('text', $opcion['text'], false)) ?>" required>
        <p style="margin-top:12px"><button class="btn chico" type="submit">Guardar texto</button></p>
    </form>
</div>

<!-- ================= PESOS HACIA PERSONAJES (dropdown dependiente) ================= -->
<div class="tarjeta">
    <h2>Pesos hacia personajes</h2>
    <p class="ayuda">Peso de 0 a 3 (0 = sin relación, 3 = relación fuerte). Elige primero la parte: el segundo desplegable se llena solo con los personajes de esa parte.</p>

    <table>
        <thead><tr><th>Personaje</th><th>Parte</th><th>Peso</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($pesosPersonajes)): ?>
            <tr><td colspan="4">Aún no hay pesos asignados.</td></tr>
        <?php endif; ?>
        <?php foreach ($pesosPersonajes as $w): ?>
            <tr>
                <td><?= esc($w['name']) ?></td>
                <td>Parte <?= (int) $w['parte_numero'] ?></td>
                <td><span class="chip verde"><?= (int) $w['weight'] ?></span></td>
                <td>
                    <form method="post" action="<?= base_url('admin/opciones/' . $opcion['id'] . '/pesos-personaje/' . $w['character_id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Quitar este peso?')">
                        <?= csrf_field() ?>
                        <button class="btn rojo" type="submit">Quitar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post" action="<?= base_url('admin/opciones/' . $opcion['id'] . '/pesos-personaje') ?>" style="margin-top:18px">
        <?= csrf_field() ?>
        <div class="fila">
            <div>
                <label for="part_id">Parte</label>
                <select id="part_id" name="part_id" required>
                    <option value="">— Selecciona una parte —</option>
                    <?php foreach ($partes as $p): ?>
                        <option value="<?= (int) $p['id'] ?>">Parte <?= (int) $p['number'] ?> · <?= esc($p['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="character_id">Personaje</label>
                <select id="character_id" name="character_id" required disabled>
                    <option value="">— Primero elige una parte —</option>
                </select>
            </div>
            <div>
                <label for="weight">Peso</label>
                <select id="weight" name="weight" required>
                    <option value="0">0 · sin relación</option>
                    <option value="1">1 · baja</option>
                    <option value="2">2 · media</option>
                    <option value="3" selected>3 · fuerte</option>
                </select>
            </div>
        </div>
        <p style="margin-top:14px"><button class="btn" type="submit">Guardar peso de personaje</button></p>
    </form>
</div>

<!-- ================= PESOS HACIA GÉNEROS ================= -->
<div class="tarjeta">
    <h2>Pesos hacia géneros</h2>

    <table>
        <thead><tr><th>Género</th><th>Peso</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($pesosGeneros)): ?>
            <tr><td colspan="3">Aún no hay pesos asignados.</td></tr>
        <?php endif; ?>
        <?php foreach ($pesosGeneros as $w): ?>
            <tr>
                <td><?= esc($w['name']) ?></td>
                <td><span class="chip verde"><?= (int) $w['weight'] ?></span></td>
                <td>
                    <form method="post" action="<?= base_url('admin/opciones/' . $opcion['id'] . '/pesos-genero/' . $w['genre_id'] . '/eliminar') ?>"
                          onsubmit="return confirm('¿Quitar este peso?')">
                        <?= csrf_field() ?>
                        <button class="btn rojo" type="submit">Quitar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <form method="post" action="<?= base_url('admin/opciones/' . $opcion['id'] . '/pesos-genero') ?>" style="margin-top:18px">
        <?= csrf_field() ?>
        <div class="fila">
            <div>
                <label for="genre_id">Género</label>
                <select id="genre_id" name="genre_id" required>
                    <option value="">— Selecciona un género —</option>
                    <?php foreach ($generos as $g): ?>
                        <option value="<?= (int) $g['id'] ?>"><?= esc($g['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="weight_g">Peso</label>
                <select id="weight_g" name="weight" required>
                    <option value="0">0 · sin relación</option>
                    <option value="1">1 · baja</option>
                    <option value="2">2 · media</option>
                    <option value="3" selected>3 · fuerte</option>
                </select>
            </div>
        </div>
        <p style="margin-top:14px"><button class="btn" type="submit">Guardar peso de género</button></p>
    </form>
</div>

<script>
    // Dropdown dependiente: al elegir la parte se piden al servidor sus personajes (JSON).
    // OJO: esto es solo comodidad visual. El servidor vuelve a validar todo (PesoController).
    const selectParte = document.getElementById('part_id');
    const selectPersonaje = document.getElementById('character_id');
    const urlBase = <?= json_encode(base_url('admin/api/partes')) ?>;

    selectParte.addEventListener('change', async () => {
        selectPersonaje.innerHTML = '';
        selectPersonaje.disabled = true;

        const vacia = (texto) => {
            const o = document.createElement('option');
            o.value = '';
            o.textContent = texto;
            return o;
        };

        if (!selectParte.value) {
            selectPersonaje.appendChild(vacia('— Primero elige una parte —'));
            return;
        }

        selectPersonaje.appendChild(vacia('Cargando…'));

        try {
            const respuesta = await fetch(urlBase + '/' + encodeURIComponent(selectParte.value) + '/personajes', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (!respuesta.ok) throw new Error('HTTP ' + respuesta.status);
            const personajes = await respuesta.json();

            selectPersonaje.innerHTML = '';
            if (personajes.length === 0) {
                selectPersonaje.appendChild(vacia('Esta parte no tiene personajes'));
                return;
            }

            selectPersonaje.appendChild(vacia('— Selecciona un personaje —'));
            personajes.forEach((p) => {
                const o = document.createElement('option');
                o.value = p.id;
                o.textContent = p.name; // textContent evita inyectar HTML
                selectPersonaje.appendChild(o);
            });
            selectPersonaje.disabled = false;
        } catch (e) {
            selectPersonaje.innerHTML = '';
            selectPersonaje.appendChild(vacia('No se pudieron cargar los personajes'));
        }
    });
</script>

<?= $this->endSection() ?>
