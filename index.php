<?php

header("Content-Type: application/json");

require_once '../../resource/UserResource.php';
require_once '../../resource/ProductResource.php';

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Obtener segmentos reales de la URL
$segments = explode('/', trim($request, '/'));

/*
Ejemplo URL:
http://localhost/api-rest-php/api/v1/productos/1

Segments:
[0] => api-rest-php
[1] => api
[2] => v1
[3] => productos
[4] => 1
*/

// Buscar posición de "api"
$apiIndex = array_search('api', $segments);

if ($apiIndex === false || !isset($segments[$apiIndex + 2])) {
    http_response_code(404);
    echo json_encode(["message" => "Ruta inválida"]);
    exit;
}

$resource = $segments[$apiIndex + 2] ?? null;
$id = $segments[$apiIndex + 3] ?? null;

switch ($resource) {

    case 'users':
        $controller = new UserResource();
        break;

    case 'productos':
        $controller = new ProductResource();
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Recurso no encontrado"]);
        exit;
}

switch ($method) {

    case 'GET':
        $id ? $controller->show($id) : $controller->index();
        break;

    case 'POST':
        $controller->store();
        break;

    case 'PUT':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "ID requerido"]);
            exit;
        }
        $controller->update($id);
        break;

    case 'DELETE':
        if (!$id) {
            http_response_code(400);
            echo json_encode(["message" => "ID requerido"]);
            exit;
        }
        $controller->destroy($id);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
?>
