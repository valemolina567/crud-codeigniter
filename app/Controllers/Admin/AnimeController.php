<?php

namespace App\Controllers\Admin;

use App\Models\AnimeModel;
use App\Models\GeneroModel;

class AnimeController extends AdminBaseController
{
    private const RUTA_PORTADAS = 'uploads/animes';

    private function reglas(): array
    {
        $maxAnio = (int) date('Y') + 1;

        return [
            'title'        => ['label' => 'Título', 'rules' => 'required|min_length[2]|max_length[150]'],
            'synopsis'     => ['label' => 'Sinopsis', 'rules' => 'permit_empty|max_length[3000]'],
            'episodes'     => ['label' => 'Episodios', 'rules' => 'required|is_natural_no_zero|less_than_equal_to[5000]'],
            'release_year' => [
                'label' => 'Año de estreno',
                'rules' => "required|integer|greater_than_equal_to[1950]|less_than_equal_to[{$maxAnio}]",
            ],
            // DATO SENSIBLE del Core: el rating desempata las recomendaciones de anime.
            'rating'       => [
                'label' => 'Calificación',
                'rules' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[10]',
            ],
            // Relación N:M: cada género enviado debe existir en la tabla genres.
            'genres'       => ['label' => 'Géneros', 'rules' => 'required'],
            'genres.*'     => ['label' => 'Género', 'rules' => 'is_natural_no_zero|is_not_unique[genres.id]'],
            'cover'        => [
                'label' => 'Portada',
                'rules' => 'is_image[cover]|mime_in[cover,image/jpg,image/jpeg,image/png,image/webp]|max_size[cover,2048]',
            ],
        ];
    }

    private function datos(): array
    {
        return [
            'title'        => trim((string) $this->request->getPost('title')),
            'synopsis'     => trim((string) $this->request->getPost('synopsis')),
            'episodes'     => (int) $this->request->getPost('episodes'),
            'release_year' => (int) $this->request->getPost('release_year'),
            'rating'       => round((float) $this->request->getPost('rating'), 2),
        ];
    }

    /** Sube la portada (si hay). Devuelve la ruta relativa o null. */
    private function subirPortada(): ?string
    {
        $archivo = $this->request->getFile('cover');

        if (! $archivo || ! $archivo->isValid() || $archivo->hasMoved()) {
            return null;
        }

        $nombre = $archivo->getRandomName();
        $archivo->move(ROOTPATH . 'public/' . self::RUTA_PORTADAS, $nombre);

        return self::RUTA_PORTADAS . '/' . $nombre;
    }

    private function borrarArchivo(?string $rutaRelativa): void
    {
        if ($rutaRelativa && is_file(ROOTPATH . 'public/' . $rutaRelativa)) {
            unlink(ROOTPATH . 'public/' . $rutaRelativa);
        }
    }

    /** Reemplaza las filas de anime_genres del anime por las seleccionadas. */
    private function sincronizarGeneros(int $animeId, array $generoIds): void
    {
        $tabla = db_connect()->table('anime_genres');
        $tabla->where('anime_id', $animeId)->delete();

        $filas = [];
        foreach (array_unique(array_map('intval', $generoIds)) as $generoId) {
            $filas[] = ['anime_id' => $animeId, 'genre_id' => $generoId];
        }

        if ($filas !== []) {
            db_connect()->table('anime_genres')->insertBatch($filas);
        }
    }

    public function index()
    {
        $db     = db_connect();
        $animes = $db->table('animes')->orderBy('title')->get()->getResultArray();

        // Géneros de cada anime (consulta simple, compatible con cualquier configuración de MySQL)
        $filas = $db->table('anime_genres ag')
            ->select('ag.anime_id, g.name')
            ->join('genres g', 'g.id = ag.genre_id')
            ->orderBy('g.name')
            ->get()->getResultArray();

        $generosPorAnime = [];
        foreach ($filas as $fila) {
            $generosPorAnime[$fila['anime_id']][] = $fila['name'];
        }

        foreach ($animes as &$anime) {
            $anime['generos'] = isset($generosPorAnime[$anime['id']])
                ? implode(', ', $generosPorAnime[$anime['id']])
                : null;
        }
        unset($anime);

        return view('admin/animes/index', ['titulo' => 'Catálogo de anime', 'animes' => $animes]);
    }

    public function nuevo()
    {
        return view('admin/animes/form', [
            'titulo'      => 'Nuevo anime',
            'anime'       => null,
            'generos'     => (new GeneroModel())->orderBy('name')->findAll(),
            'seleccion'   => [],
        ]);
    }

    public function guardar()
    {
        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $db = db_connect();
        $db->transStart(); // anime + géneros se guardan juntos o no se guarda nada

        $datos              = $this->datos();
        $datos['cover_url'] = $this->subirPortada();
        $id                 = (new AnimeModel())->insert($datos, true);
        $this->sincronizarGeneros((int) $id, (array) $this->request->getPost('genres'));

        $db->transComplete();

        return redirect()->to('/admin/animes')->with('exito', 'Anime creado correctamente.');
    }

    public function editar(int $id)
    {
        $anime     = $this->buscarOFallar(new AnimeModel(), $id);
        $seleccion = array_column(
            db_connect()->table('anime_genres')->where('anime_id', $id)->get()->getResultArray(),
            'genre_id'
        );

        return view('admin/animes/form', [
            'titulo'    => 'Editar anime',
            'anime'     => $anime,
            'generos'   => (new GeneroModel())->orderBy('name')->findAll(),
            'seleccion' => array_map('intval', $seleccion),
        ]);
    }

    public function actualizar(int $id)
    {
        $modelo = new AnimeModel();
        $anime  = $this->buscarOFallar($modelo, $id);

        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $db = db_connect();
        $db->transStart();

        $datos = $this->datos();
        $nueva = $this->subirPortada();
        if ($nueva) {
            $this->borrarArchivo($anime['cover_url']);
            $datos['cover_url'] = $nueva;
        }

        $modelo->update($id, $datos);
        $this->sincronizarGeneros($id, (array) $this->request->getPost('genres'));

        $db->transComplete();

        return redirect()->to('/admin/animes')->with('exito', 'Anime actualizado.');
    }

    public function eliminar(int $id)
    {
        $modelo = new AnimeModel();
        $anime  = $this->buscarOFallar($modelo, $id);

        $this->borrarArchivo($anime['cover_url']);
        $modelo->delete($id); // anime_genres se borra en cascada

        return redirect()->to('/admin/animes')->with('exito', 'Anime eliminado.');
    }
}
