<?php
class Usuario{
    public $id;
    public $nombre;
    public $apellido;
    public $fechaRegistro;
    public $tipo;
    public $username;
    public $contrasenia;
    public $activo; //para la baja logica


    public function __construct(){}


    public function CrearUsuario(){
        $accesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $accesoDato->prepararConsulta("INSERT INTO usuario (nombre, apellido, fechaRegistro, tipo, username, contrasenia, activo) VALUES (:nombre, :apellido, :fechaRegistro, :tipo, :username, :contrasenia, :activo)");
    
        $activo = 1;
        // Asigna los valores a los marcadores de posición en la consulta
        $consulta->bindParam(':nombre', $this->nombre);
        $consulta->bindParam(':apellido', $this->apellido);
        $consulta->bindParam(':fechaRegistro', $this->fechaRegistro);
        $consulta->bindParam(':tipo', $this->tipo);
        $consulta->bindParam(':username', $this->username);
        $consulta->bindParam(':contrasenia', $this->contrasenia);
        $consulta->bindParam(':activo', $activo);
    
        // Ejecuta la consulta
        $consulta->execute();
    
        return $accesoDato->obtenerUltimoId();
    }

    public static function TraerUsuarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario WHERE activo = 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'usuario');
    }

    public static function TraerUsuarioPorID($id)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from usuario where id = ? AND activo = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $usuario = $consulta->fetchObject();
        return $usuario;
    }

    public static function VerificarMesero($id)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from usuario where id = ? AND activo = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $usuario = $consulta->fetchObject();
        if($usuario->tipo == 'mesero'){
            return true;
        }
        return false;
    }

    public static function EliminarUsuario($id){ // debe ser una baja logica
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE usuario SET activo = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarUsuario($id, $nombre, $apellido, $fechaRegistro, $tipo, $username, $contrasenia){ //no se como plantearlo
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE usuario SET nombre = ?, apellido = ?, fechaRegistro = ?, tipo = ?, username = ?, contrasenia = ?  WHERE id = ?");
        $consulta->bindValue(1, $nombre, PDO::PARAM_STR);
        $consulta->bindValue(2, $apellido, PDO::PARAM_STR);
        $consulta->bindValue(3, $fechaRegistro, PDO::PARAM_STR);
        $consulta->bindValue(4, $tipo, PDO::PARAM_STR);
        $consulta->bindValue(5, $username, PDO::PARAM_STR);
        $consulta->bindValue(6, $contrasenia, PDO::PARAM_STR);
        $consulta->bindValue(7, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function TraerUsuarioPorLogin($username, $contrasenia){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from usuario where username = ? AND contrasenia = ? AND activo = 1");
        $consulta->bindValue(1, $username, PDO::PARAM_STR);
        $consulta->bindValue(2, $contrasenia, PDO::PARAM_STR);
        $consulta->execute();
        $usuario = $consulta->fetchObject();
        return $usuario;
    }

}
?>