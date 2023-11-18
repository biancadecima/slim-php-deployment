<?php
require_once './models/usuario.php';
class UsuarioController{

    public function AltaUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $tipo = $parametros['tipo'];
        $username = $parametros['username'];
        $contrasenia = $parametros['contrasenia'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->fechaRegistro = date('Y-m-d H:i:s');
        $usr->tipo = $tipo;
        $usr->username = $username;
        $usr->contrasenia = $contrasenia;
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

    public function BajaUsuario($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $usuario = Usuario::TraerUsuarioPorID($id);
        if($usuario){
            if(Usuario::EliminarUsuario($id)){
                $payload = json_encode(array("mensaje" => "Usuario eliminado con exito"));
            }
        }else{
            $payload = json_encode(array("mensaje" => "Error en eliminar usuario"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUsuario($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $usuario = Usuario::TraerUsuarioPorID($id);
        if($usuario){
            if (isset($parametros['nombre'])){
                $usuario->nombre = $parametros['nombre'];
            }elseif(isset($parametros['apellido'])){
                $usuario->apellido = $parametros['apellido'];
            }elseif(isset($parametros['fechaRegistro'])){
                $usuario->fechaRegistro = $parametros['fechaRegistro'];
            }elseif(isset($parametros['tipo'])){
                $usuario->tipo = $parametros['tipo'];
            }elseif(isset($parametros['username'])){
                $usuario->username = $parametros['username'];
            }elseif(isset($parametros['contrasenia'])){
                $usuario->contrasenia = $parametros['contrasenia'];
            }
            Usuario::ModificarUsuario($id, $usuario->nombre, $usuario->apellido, $usuario->fechaRegistro, $usuario->tipo, $usuario->username, $usuario->contrasenia);
            $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Error en modificar usuario"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function GuardarUsuarios($request, $response, $args){
        $path = "usuarios.csv";
        $param = $request->getQueryParams();
        $usuariosArray = array();
        $usuarios = Usuario::TraerUsuarios();

        foreach($usuarios as $i){
            $usuario = array($i->id, $i->nombre, $i->apellido, $i->fechaRegistro, $i->tipo, $i->username, $i->contrasenia, $i->activo);
            $usuariosArray[] = $usuario;
        }

        $archivo = fopen($path, "w");
        $encabezado = array("id", "nombre", "apellido", "fechaRegistro", "tipo", "username", "contrasenia", "activo");
        fputcsv($archivo, $encabezado);
        foreach($usuariosArray as $fila){
            fputcsv($archivo, $fila);
        }
        fclose($archivo);
        $retorno = json_encode(array("mensaje"=>"Usuarios guardados en CSV con exito"));
           
        $response->getBody()->write($retorno);
        return $response;
    }

    public static function CargarUsuarios($request, $response, $args){
        $path = "usuarios.csv";
        $archivo = fopen($path, "r");
        $encabezado = fgets($archivo);

        while(!feof($archivo)){
            $linea = fgets($archivo);
            $datos = str_getcsv($linea);
            var_dump($datos);

            //if(isset($datos[1])){
                $usuario = new Usuario();
                $usuario->id = $datos[0];
                $usuario->nombre = $datos[1];
                $usuario->apellido = $datos[2];
                $usuario->fechaRegistro = $datos[3];
                $usuario->tipo = $datos[4];
                $usuario->username = $datos[5];
                $usuario->contrasenia = $datos[6];
                $usuario->activo = $datos[7];
                $usuario->CrearUsuario();
            //}
        }
        fclose($archivo);
                
        $retorno = json_encode(array("mensaje"=>"Usuarios guardados en base de datos con exito"));
        $response->getBody()->write($retorno);
        return $response;
    }

}
?>