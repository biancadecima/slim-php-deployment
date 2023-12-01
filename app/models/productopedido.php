<?php

class ProductoPedido{
    public $id;
    public $idProducto;
    public $idPedido;
    public $idEmpleado;
    public $estado;
    public $tiempoPreparacion;

    public function CrearProductoPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT into productopedido (idProducto,idPedido,idEmpleado,estado,tiempoPreparacion)values(:idProducto,:idPedido,:idEmpleado,:estado,:tiempoPreparacion)");
        $consulta->bindValue(':idProducto', $this->idProducto);
        $consulta->bindValue(':idPedido', $this->idPedido);
        $consulta->bindValue(':idEmpleado', $this->idEmpleado);
        $consulta->bindValue(':estado', $this->estado);
        $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion);
        $consulta->execute();
    }

    public static function TraerProductoPedidos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productopedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function TraerPorId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productopedido WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('ProductoPedido');
    }

    public static function TraerPorIdPedido($idPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM productopedido WHERE idPedido = :idPedido");
        $consulta->bindValue(':idPedido', $idPedido);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function TraerTipoProducto($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pp.* FROM productopedido pp JOIN productos p ON pp.idProducto = p.id WHERE p.sector = :sector");
        $consulta->bindValue(':sector', $sector);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'ProductoPedido');
    }

    public static function ModificarProductoPedido($productoPedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE productopedido SET idProducto = :idProducto, idPedido = :idPedido, idEmpleado = :idEmpleado, tiempo = :tiempo , estado = :estado WHERE id = :id");
        $consulta->bindValue(':idProducto', $productoPedido->idProducto);
        $consulta->bindValue(':idPedido', $productoPedido->idPedido);
        $consulta->bindValue(':idEmpleado', $productoPedido->idEmpleado);
        $consulta->bindValue(':tiempo', $productoPedido->tiempo);
        $consulta->bindValue(':estado', $productoPedido->estado);
        $consulta->bindValue(':id', $productoPedido->id);
        $consulta->execute();
    }
    
}
?>