<?php

require_once '../../resources/UserResource.php';
require_once '../../resources/ProductResource.php';

$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$segments = explode('/', trim($request, '/'));

$resource = $segments[2] ?? null;
$id = $segments[3] ?? null;

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
        $controller->update($id);
        break;
    case 'DELETE':
        $controller->destroy($id);
        break;
}
?>
