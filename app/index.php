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
//require_once './db/dataAccess.php';
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
    $group->get('[/]', \MesaController::class . ':ObtenerMesas');
    $group->post('[/]', \MesaController::class . ':AltaMesa');
    $group->post('/baja', \MesaController::class . ':BajaMesa');
    $group->post('/modificar', \MesaController::class . ':ModificarMesa');
    $group->get('/popular', \MesaController::class . ':TraerMasUsada')
    ->add(new AuthenticatorMW('socio'));
    $group->post('/cerrar', \MesaController::class . ':CerrarMesa')
    ->add(new AuthenticatorMW('socio'));
});

$app->group('/productos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \ProductoController::class . ':ObtenerProductos');
    $group->post('[/]', \ProductoController::class . ':AltaProducto');
    $group->post('/baja', \ProductoController::class . ':BajaProducto');
    $group->post('/modificar', \ProductoController::class . ':ModificarProducto');
});

$app->group('/pedidos', function (RouteCollectorProxy $group) {
    $group->get('[/]', \PedidoController::class . ':ObtenerPedidos');
    $group->post('[/]', \PedidoController::class . ':AltaPedido');
    $group->post('/baja', \PedidoController::class . ':BajaPedido');
    $group->post('/modificar', \PedidoController::class . ':ModificarPedido');
    $group->get('/tiempo', \PedidoController::class . ':ObtenerTiempo');
    $group->get('/listos', \PedidoController::class . ':ObtenerListosParaServir');
    //$group->post('/estado', \PedidoController::class . ':ModificarEstado');

})->add(new AuthenticatorMW('mesero'));

$app->group('/productopedido', function (RouteCollectorProxy $group) 
{
    $group->get('[/]', \ProductoPedidoController::class . ':ObtenerProductoPedidos');
    $group->get('/{id}', \ProductoPedidoController::class . ':ObtenerUnProductoPedido');
    $group->get('/tipoProducto/{tipoProducto}', \ProductoPedidoController::class . ':TraerTipoProducto');
    $group->post('[/]', \ProductoPedidoController::class . ':AltaProductoPedido');
    $group->put('/{id}', \ProductoPedidoController::class . ':ModificarProductoPedido');
    //$group->delete('[/]', \ProductoPedidoController::class . ':Borrar');
    //Debe cambiar el estado a “en preparación” y agregarle el tiempo de preparación.
});

$app->group('/encuestas', function (RouteCollectorProxy $group) 
{
    $group->get('/comentarios', \EncuestaController::class . ':TraerMejoresComentarios');
    $group->get('[/]', \EncuestaController::class . ':ObtenerEncuestas');
    $group->get('/{id}', \EncuestaController::class . ':ObtenerUnaEncuesta');
    $group->post('[/]', \EncuestaController::class . ':AltaEncuesta');
   // $group->put('/{id}', \EncuestaController::class . ':Modificar');
   // $group->delete('[/]', \EncuestaController::class . ':Borrar');
    //12- Alguno de los socios pide los mejores comentarios
    
});

$app->group('/csv', function (RouteCollectorProxy $group) {
    $group->get('/guardar', \UsuarioController::class . ':GuardarUsuarios');
    $group->get('/cargar', \UsuarioController::class . ':CargarUsuarios');
});


$app->run();

