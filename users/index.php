<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Accept: application/json");
header("Content-Type: application/json");

include_once 'controller.php';

$method = $_SERVER["REQUEST_METHOD"];
$controller = new Controller();

switch ($method) {
    case 'GET':
        $user_id = isset($_GET['id']) ? $_GET['id'] : null;
        $controller->read($user_id);
        break;
    case 'OPTIONS':
        $origin = $_SERVER["HTTP_ORIGIN"];
        header('Access-Control-Allow-Origin: ' . $origin);
        break;
    case 'POST':
        $controller->create();
        break;
    case 'PUT':
        $controller->update();
        break;
    case 'DELETE':
        $user_id = isset($_GET['id']) ? $_GET['id'] : null;
        $controller->delete($user_id);
        break;
    default:
        // Invalid Request Method
        header("HTTP/1.0 405 Method Not Allowed");
        break;
}
