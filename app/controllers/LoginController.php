<?php
require_once './models/usuario.php';
//require_once './middlewares/autenticadorMW.php';
require_once 'C:\xampp\htdocs\slim-php-deployment\utils\autenticadorJWT.php';
class LoginController{

    public function logIn($request, $response, $args){
        $parametros = $request->getParsedBody();

        $username = $parametros['username'];
        $contrasenia = $parametros['contrasenia'];
        $usuario = Usuario::TraerUsuarioPorLogin($username, $contrasenia);

        if($usuario){ 
            $datos = array('id' => $usuario->id, 'tipo'=> $usuario->tipo);
            $token = AutentificadorJWT::CrearToken($datos);
            $payload = json_encode(array('jwt' => $token));
        } else {
            $payload = json_encode(array('error' => 'Usuario o contraseña incorrectos'));
        }

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}
?>