<?php
class Producto{
    public $id;
    public $descripcion;
    public $precio;

    /*public function __construct($descripcion, $precio, $id = null)
    {
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        if($id != null){
            $this->id = $id;
        }
    }*/

    public function CrearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto (descripcion, precio) VALUES (:descripcion, :precio)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function TraerProductos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM producto");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }
    
}