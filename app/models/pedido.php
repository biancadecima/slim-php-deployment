<?php
class Pedido{
    public $id;
    public $idMozo;
    public $idMesa;
    public $estado;
    public $tiempoEstimado;
    public $productos;
    public $imagen;
    public $activo;

    public function CrearPedido()
    {
        $productosJson =  json_encode($this->productos);

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (idMozo, idMesa, estado, tiempoEstimado, productos, imagen, activo) VALUES (:idMozo, :idMesa, :estado, :tiempoEstimado, :productos, :imagen, :activo)");

        $estadoInicial = 'En espera';
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estadoInicial, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':productos', $productosJson, PDO::PARAM_STR);
        $consulta->bindValue(':imagen', $this->imagen, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $this->activo, PDO::PARAM_INT);

        $consulta->execute();
    }

    public function DefinirDestinoImagen($ruta){
        //$destino = str_replace('\\', '/', $ruta).$this->idMesa.".png";
        //return $destino;

        $destino = $ruta."\\".$this->idMesa.".png";
        return $destino;
    }

    public static function TraerPedidos(){
        $pedido = null;
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("SELECT * FROM pedido where activo = 1");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
	}

    public static function TraerPedidoPorID($id)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from pedido where id = ? and activo = 1");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $pedido = $consulta->fetchObject('Pedido');
        return $pedido;
    }

    public function ActualizarEstadoPedido($estado){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE pedido SET estado = ?, tiempoEstimado = ? WHERE id = ? and activo = 1");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $this->tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(3, $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function EliminarPedido($id){ // debe ser una baja logica
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido SET activo = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarPedido($id, $idMozo, $idMesa, $estado, $tiempoEstimado, $productos){ //no se como plantearlo
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE pedido SET idMozo = ?, idMesa = ?, estado = ?, tiempoEstimado = ?, productos = ? WHERE id = ?");
        $consulta->bindValue(1, $idMozo, PDO::PARAM_INT);
        $consulta->bindValue(2, $idMesa, PDO::PARAM_INT);
        $consulta->bindValue(3, $estado, PDO::PARAM_STR);
        $consulta->bindValue(4, $tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(5, $productos, PDO::PARAM_STR);
        $consulta->bindValue(6, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }
    
}
?>