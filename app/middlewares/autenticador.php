<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
class AuthenticatorMW{

    public $tipo;
    public function __construct($tipo) {
        $this->tipo = $tipo;
    }

    public function __invoke(Request $request, RequestHandler $handler): Response{
        $parametros = $request->getQueryParams();
        $id = $parametros['id'];
        $usuario = Usuario::TraerUsuarioPorID($id);
        if($usuario != null){
            if($usuario->tipo == $this->tipo){
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'Acceso autorizado solo para '.$this->tipo));
                $response->getBody()->write($payload);
            }
        }else{
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ID de Usuario no encontrado.'));
            $response->getBody()->write($payload);
        }
        

        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>