<?php
require_once './db/dataAccess.php'; 
class Producto{
    public $id;
    public $descripcion;
    public $sector;
    public $precio;
    //public $tiempoEstimado;
    public $activo;

    public function __construct($descripcion, $sector, $precio, $activo, $id = null)
    {
        $this->descripcion = $descripcion;
        $this->sector = $sector;
        $this->precio = $precio;
        //$this->tiempoEstimado = $tiempoEstimado;
        $this->activo = $activo;
        if($id != null){
            $this->id = $id;
        }
    }

    public function CrearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO producto (descripcion, sector, precio, activo) VALUES (:descripcion, :sector, :precio, :activo)");
        $consulta->bindValue(':descripcion', $this->descripcion, PDO::PARAM_STR);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':activo', $this->activo, PDO::PARAM_INT);
        $consulta->execute();
    }


    public static function TraerProductos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM producto WHERE activo = true");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS);
    }

    public static function TraerProducto_Id($id) 
	{
        $producto = null;
        $objAccesoDatos = AccesoDatos::obtenerInstancia(); 
        $consulta =$objAccesoDatos->prepararConsulta("SELECT * from producto where id = ? and activo = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $productoBuscado = $consulta->fetchObject();
        if($productoBuscado != false){
            $producto = new Producto($productoBuscado->descripcion, $productoBuscado->sector, $productoBuscado->precio, /*$productoBuscado->tiempoEstimado,*/ $productoBuscado->activo, $productoBuscado->id);
        }else{
            return false;
        }

        return $producto;
	}

    public static function EliminarProducto($id){ // debe ser una baja logica
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE producto SET activo = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarProducto($id, $descripcion, $sector, $precio){ 
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE producto SET descripcion = ?, sector = ?, precio = ? WHERE id = ?");
        $consulta->bindValue(1, $descripcion, PDO::PARAM_STR);
        $consulta->bindValue(2, $sector, PDO::PARAM_STR);
        $consulta->bindValue(3, $precio, PDO::PARAM_INT);
        $consulta->bindValue(4, $id, PDO::PARAM_INT);
   
        return $consulta->execute();
    }

    
    
}