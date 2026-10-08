<?php

namespace App\Controllers;

use App\Models\PersonaModel;

class PersonaController extends BaseController
{
    // Mostrar todas las personas
    public function index()
    {
        $model = new PersonaModel();

        $data = [
            'personas' => $model->findAll()
        ];

        return view('personas/index', $data);
    }

    // Mostrar formulario para crear una persona
    public function crear()
    {
        return view('personas/crear');
    }

    // Guardar una nueva persona
    public function guardar()
    {
        $model = new PersonaModel();

        // Obtener la imagen enviada desde el formulario
        $imagen = $this->request->getFile('imagen');

        // Nombre que tendrá la imagen
        $nombreImagen = $imagen->getRandomName();

        // Guardar la imagen en public/uploads/personas
        $imagen->move(ROOTPATH . 'public/uploads/personas', $nombreImagen);

        // Guardar los datos en la base de datos
        $model->insert([
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento'),
            'imagen' => $nombreImagen
        ]);

        return redirect()->to('/personas');
    }

    // Mostrar formulario para editar una persona
    public function editar($id)
    {
        $model = new PersonaModel();

        $data = [
            'persona' => $model->find($id)
        ];

        return view('personas/editar', $data);
    }

    // Actualizar una persona
    public function actualizar($id)
    {
        $model = new PersonaModel();

        $persona = $model->find($id);

        $imagen = $this->request->getFile('imagen');

        $datos = [
            'nombre' => $this->request->getPost('nombre'),
            'apellido' => $this->request->getPost('apellido'),
            'fecha_nacimiento' => $this->request->getPost('fecha_nacimiento')
        ];

        // Si se seleccionó una nueva imagen
        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {

            // Eliminar la imagen anterior
            if (!empty($persona['imagen'])) {

                $rutaImagenAnterior = ROOTPATH . 'public/uploads/personas/' . $persona['imagen'];

                if (file_exists($rutaImagenAnterior)) {
                    unlink($rutaImagenAnterior);
                }
            }

            // Crear nombre nuevo para la imagen
            $nombreImagen = $imagen->getRandomName();

            // Guardar la nueva imagen
            $imagen->move(
                ROOTPATH . 'public/uploads/personas',
                $nombreImagen
            );

            $datos['imagen'] = $nombreImagen;
        }

        $model->update($id, $datos);

        return redirect()->to('/personas');
    }

    // Eliminar una persona
    public function eliminar($id)
    {
        $model = new PersonaModel();

        $persona = $model->find($id);

        // Eliminar la imagen de la carpeta
        if (!empty($persona['imagen'])) {

            $rutaImagen = ROOTPATH . 'public/uploads/personas/' . $persona['imagen'];

            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
        }

        // Eliminar la persona de la base de datos
        $model->delete($id);

        return redirect()->to('/personas');
    }
}