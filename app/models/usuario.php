<?php
class Usuario{
    public $id;
    public $nombre;
    public $apellido;
    public $fechaRegistro;
    public $tipo;
/*
    public function __construct($id = null, $nombre, $apellido, $fechaRegistro, $tipo)
    {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->fechaRegistro = $fechaRegistro;
        $this->tipo = $tipo;
        if($id != null){
            $this->id = $id;
        }*
    }*/

    public function CrearUsuario(){
        $accesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $accesoDato->prepararConsulta("INSERT INTO usuario (nombre, apellido, fechaRegistro, tipo) VALUES (:nombre, :apellido, :fechaRegistro, :tipo)");
    
        // Asigna los valores a los marcadores de posición en la consulta
        $consulta->bindParam(':nombre', $this->nombre);
        $consulta->bindParam(':apellido', $this->apellido);
        $consulta->bindParam(':fechaRegistro', $this->fechaRegistro);
        $consulta->bindParam(':tipo', $this->tipo);
    
        // Ejecuta la consulta
        $consulta->execute();
    
        return $accesoDato->obtenerUltimoId();
    }

    public static function TraerUsuarios()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuario");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'usuario');
    }

}
?>