<?php

namespace App\Controllers\Admin;

use App\Models\ParteModel;
use App\Models\PersonajeModel;

class PersonajeController extends AdminBaseController
{
    private const RUTA_IMAGENES = 'uploads/personajes';
    private const ROLES         = ['Protagonista', 'Aliado', 'Antagonista', 'Secundario'];

    private function reglas(): array
    {
        return [
            // Clave foránea: se valida en back-end que la parte EXISTA (el dropdown no basta).
            'part_id'     => ['label' => 'Parte', 'rules' => 'required|is_natural_no_zero|is_not_unique[jojo_parts.id]'],
            'name'        => ['label' => 'Nombre', 'rules' => 'required|min_length[2]|max_length[100]'],
            'stand_name'  => ['label' => 'Nombre del Stand', 'rules' => 'permit_empty|max_length[100]'],
            'role'        => ['label' => 'Rol', 'rules' => 'required|in_list[' . implode(',', self::ROLES) . ']'],
            'description' => ['label' => 'Descripción', 'rules' => 'permit_empty|max_length[2000]'],
            // La imagen es opcional; si se envía debe ser una imagen real de máx. 2 MB.
            'imagen'      => [
                'label' => 'Imagen',
                'rules' => 'is_image[imagen]|mime_in[imagen,image/jpg,image/jpeg,image/png,image/webp]|max_size[imagen,2048]',
            ],
        ];
    }

    private function datos(): array
    {
        return [
            'part_id'     => (int) $this->request->getPost('part_id'),
            'name'        => trim((string) $this->request->getPost('name')),
            'stand_name'  => trim((string) $this->request->getPost('stand_name')) ?: null,
            'role'        => (string) $this->request->getPost('role'),
            'description' => trim((string) $this->request->getPost('description')),
        ];
    }

    /** Guarda la imagen subida (si hay) reemplazando la anterior. */
    private function guardarImagen(int $personajeId, string $nombre): void
    {
        $archivo = $this->request->getFile('imagen');

        if (! $archivo || ! $archivo->isValid() || $archivo->hasMoved()) {
            return;
        }

        $this->borrarImagenes($personajeId);

        $nuevoNombre = $archivo->getRandomName();
        $archivo->move(ROOTPATH . 'public/' . self::RUTA_IMAGENES, $nuevoNombre);

        db_connect()->table('character_images')->insert([
            'character_id' => $personajeId,
            'url'          => self::RUTA_IMAGENES . '/' . $nuevoNombre,
            'caption'      => $nombre,
        ]);
    }

    private function borrarImagenes(int $personajeId): void
    {
        $tabla    = db_connect()->table('character_images');
        $imagenes = $tabla->where('character_id', $personajeId)->get()->getResultArray();

        foreach ($imagenes as $img) {
            $ruta = ROOTPATH . 'public/' . $img['url'];
            if (is_file($ruta)) {
                unlink($ruta);
            }
        }

        db_connect()->table('character_images')->where('character_id', $personajeId)->delete();
    }

    public function index()
    {
        $personajes = db_connect()->table('characters c')
            ->select('c.*, p.number AS parte_numero, p.title AS parte_titulo, '
                . '(SELECT url FROM character_images ci WHERE ci.character_id = c.id ORDER BY ci.id LIMIT 1) AS imagen', false)
            ->join('jojo_parts p', 'p.id = c.part_id')
            ->orderBy('p.number')
            ->orderBy('c.name')
            ->get()->getResultArray();

        return view('admin/personajes/index', ['titulo' => 'Personajes', 'personajes' => $personajes]);
    }

    public function nuevo()
    {
        return view('admin/personajes/form', [
            'titulo'    => 'Nuevo personaje',
            'personaje' => null,
            'imagen'    => null,
            'partes'    => (new ParteModel())->orderBy('number')->findAll(),
            'roles'     => self::ROLES,
        ]);
    }

    public function guardar()
    {
        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $datos = $this->datos();
        $id    = (new PersonajeModel())->insert($datos, true);

        $this->guardarImagen((int) $id, $datos['name']);

        return redirect()->to('/admin/personajes')->with('exito', 'Personaje creado correctamente.');
    }

    public function editar(int $id)
    {
        $personaje = $this->buscarOFallar(new PersonajeModel(), $id);
        $imagen    = db_connect()->table('character_images')->where('character_id', $id)->orderBy('id')->get()->getRowArray();

        return view('admin/personajes/form', [
            'titulo'    => 'Editar personaje',
            'personaje' => $personaje,
            'imagen'    => $imagen['url'] ?? null,
            'partes'    => (new ParteModel())->orderBy('number')->findAll(),
            'roles'     => self::ROLES,
        ]);
    }

    public function actualizar(int $id)
    {
        $modelo = new PersonajeModel();
        $this->buscarOFallar($modelo, $id);

        if (! $this->validate($this->reglas())) {
            return $this->volverConErrores($this->validator->getErrors());
        }

        $datos = $this->datos();
        $modelo->update($id, $datos);
        $this->guardarImagen($id, $datos['name']);

        return redirect()->to('/admin/personajes')->with('exito', 'Personaje actualizado.');
    }

    public function eliminar(int $id)
    {
        $modelo = new PersonajeModel();
        $this->buscarOFallar($modelo, $id);

        $this->borrarImagenes($id);
        $modelo->delete($id); // los pesos del test se borran en cascada

        return redirect()->to('/admin/personajes')->with('exito', 'Personaje eliminado.');
    }

    /**
     * Endpoint JSON para el dropdown dependiente:
     * al elegir una parte, devuelve solo los personajes de esa parte.
     */
    public function porParte(int $parteId)
    {
        $personajes = db_connect()->table('characters')
            ->select('id, name')
            ->where('part_id', $parteId)
            ->orderBy('name')
            ->get()->getResultArray();

        return $this->response->setJSON($personajes);
    }
}
