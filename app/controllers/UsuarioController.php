<?php
require_once './models/usuario.php';
class UsuarioController{

    public function AltaUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $fechaRegistro = $parametros['fechaRegistro'];
        $tipo = $parametros['tipo'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->fechaRegistro = $fechaRegistro;
        $usr->tipo = $tipo;
        $usr->CrearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerUsuarios($request, $response, $args)
    {
        $usuarios = Usuario::TraerUsuarios();

        $payload = json_encode(array("listaUsuarios" => $usuarios));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
/*
    public function TraerUsuarioPorID($request, $response, $args)
    {
        $usuarios = Usuario::TraerUsuarios();

        $payload = json_encode(array("listaUsuarios" => $usuarios));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }*/
}
?>