<?php
// Error Handling
error_reporting(-1);
ini_set('display_errors', 1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/PedidoController.php';
require_once './db/dataAccess.php';
require_once './middlewares/autenticadorMW.php';
require_once 'C:\xampp\htdocs\slim-php-deployment\app\middlewares\loggerMV.php';
require_once 'C:\xampp\htdocs\slim-php-deployment\app\controllers\LoginController.php';

require __DIR__ . '/../vendor/autoload.php';

// Instantiate App
$app = AppFactory::create();

// Add error middleware
$app->addErrorMiddleware(true, true, true);

// Add parse body
$app->addBodyParsingMiddleware();

$app->group('/auth', function (RouteCollectorProxy $group) {
    $group->post('/login', \LoginController::class . ':logIn');
});

$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':ObtenerUsuarios')
        ->add(new LoggerMW())
        ->add(new AuthenticatorMW('socio'));
    $group->post('[/]', \UsuarioController::class . ':AltaUsuario')/*->add(new AuthenticatorMW('socio'))*/;
    $group->delete('[/]', \UsuarioController::class . ':BajaUsuario')/*->add(new AuthenticatorMW('socio'))*/;
    //$group->update('[/]', \UsuarioController::class . ':ModificarUsuario')->add(new AuthenticatorMW());
});

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':ObtenerMesas');
    $group->post('[/]', \MesaController::class . ':AltaMesa');
});

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':ObtenerProductos');
    $group->post('[/]', \ProductoController::class . ':AltaProducto');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':ObtenerPedidos');
    $group->post('/estado', \PedidoController::class . ':ModificarEstado');
    $group->post('[/]', \PedidoController::class . ':AltaPedido');
});



$app->run();

