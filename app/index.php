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
require_once './controllers/ProductoPedidoController.php';
require_once './controllers/LoginController.php';
require_once './controllers/EncuestaController.php';
require_once './middlewares/autenticadorMW.php';
require_once './middlewares/loggerMV.php';



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
    $group->get('[/]', \UsuarioController::class . ':ObtenerUsuarios');
    $group->post('[/]', \UsuarioController::class . ':AltaUsuario');
    $group->post('/baja', \UsuarioController::class . ':BajaUsuario');
    $group->post('/modificar', \UsuarioController::class . ':ModificarUsuario');
})->add(new LoggerMW())
->add(new AuthenticatorMW('socio'));

$app->group('/mesas', function (RouteCollectorProxy $group) {
    $group->get('[/]', \MesaController::class . ':ObtenerMesas')->add(new AuthenticatorMW('socio'));
    $group->post('[/]', \MesaController::class . ':AltaMesa');
    $group->post('/baja', \MesaController::class . ':BajaMesa');
    $group->post('/modificar', \MesaController::class . ':ModificarMesa')->add(new AuthenticatorMW('mesero'));
    $group->get('/popular', \MesaController::class . ':TraerMasUsada')->add(new AuthenticatorMW('socio'));
    $group->post('/cerrar', \MesaController::class . ':CerrarMesa')->add(new AuthenticatorMW('socio'));
});

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':ObtenerProductos');
    $group->post('[/]', \ProductoController::class . ':AltaProducto');
    $group->post('/baja', \ProductoController::class . ':BajaProducto');
    $group->post('/modificar', \ProductoController::class . ':ModificarProducto');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':ObtenerPedidos')->add(new AuthenticatorMW('socio'));
    $group->post('[/]', \PedidoController::class . ':AltaPedido')->add(new AuthenticatorMW('mesero'));
    $group->post('/baja', \PedidoController::class . ':BajaPedido');
    $group->post('/modificar', \PedidoController::class . ':ModificarPedido');
    $group->get('/tiempo', \PedidoController::class . ':ObtenerTiempo');
    $group->get('/listos', \PedidoController::class . ':ObtenerListosParaServir')->add(new AuthenticatorMW('mesero'));
    $group->post('/cobrar', \PedidoController::class . ':MozoPedidoCliente')->add(new AuthenticatorMW('mesero'));
});

$app->group('/productopedido', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \ProductoPedidoController::class . ':ObtenerProductoPedidos');
    $group->get('/sector', \ProductoPedidoController::class . ':TraerSectorProducto')->add(new LoggerMW());
    $group->post('[/]', \ProductoPedidoController::class . ':AltaProductoPedido');
    $group->post('/realizar', \ProductoPedidoController::class . ':EmpleadoTomaProducto')->add(new LoggerMW());
    
});

$app->group('/encuestas', function (RouteCollectorProxy $group) 
{
    $group->get('/comentarios', \EncuestaController::class . ':TraerMejoresComentarios')->add(new AuthenticatorMW('socio'));
    $group->get('[/]', \EncuestaController::class . ':ObtenerEncuestas');
    $group->get('/{id}', \EncuestaController::class . ':ObtenerUnaEncuesta');
    $group->post('[/]', \EncuestaController::class . ':AltaEncuesta');
});

$app->group('/csv', function (RouteCollectorProxy $group) {
    $group->get('/guardar', \UsuarioController::class . ':GuardarUsuarios');
    $group->get('/cargar', \UsuarioController::class . ':CargarUsuarios');
});


$app->run();

