<?php

namespace Controllers;

use Models\ActiveRecord;
use Models\Servicio;
use MVC\Router;

class ServicioController {

    public static function index(Router $router) {
        isAuth();
        isAdmin();

        $nombre = $_SESSION['nombre'];
        $servicios = Servicio::all();

        $router->render('/servicios/index', [
            'nombre' => $nombre,
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router) {
        isAuth();
        isAdmin();

        $nombre = $_SESSION['nombre'];
        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/crear', [
            'nombre' => $nombre,
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function actualizar(Router $router) {
        isAuth();
        isAdmin();

        $nombre = $_SESSION['nombre'];
        $alertas = [];

        if(!is_numeric($_GET['id'])) return;

        $id = $_GET['id'];
        $servicio = Servicio::find($id);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        $router->render('/servicios/actualizar', [
            'nombre' => $nombre,
            'alertas' => $alertas,
            'servicio' => $servicio
        ]);

    }

    public static function eliminar() {
        isAuth();
        isAdmin();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            $servicio->eliminar();
            header('Location: /servicios');
        }
    }
}