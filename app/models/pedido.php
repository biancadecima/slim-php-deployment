<?php
class Pedido{
    public $id;
    public $idMesa;
    public $nombreCliente;
    public $precio;
    public $estado;
    public $tiempoEstimado;

    public function CrearPedido(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (idMesa, nombreCliente, estado, tiempoEstimado, precio) VALUES (:idMesa, :nombreCliente, :estado, :tiempoEstimado, :precio)");

        //$estadoInicial = 'En espera';
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);

        $consulta->execute();
    }

    public function DefinirDestinoImagen($ruta){
        //$destino = str_replace('\\', '/', $ruta).$this->idMesa.".png";
        //return $destino;

        $destino = $ruta."\\".$this->idMesa."-".$this->id.".png";
        return $destino;
    }

    public static function TraerPedidos(){
        $pedido = null;
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("SELECT * FROM pedido");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
	}

    public static function TraerPedidoPorID($id)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from pedido where id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $pedido = $consulta->fetchObject('Pedido');
        return $pedido;
    }

    public function ActualizarEstadoPedido($estado){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE pedido SET estado = ?, tiempoEstimado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $this->tiempoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(3, $this->id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function EliminarPedido($id){ // debe ser una baja logica
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedido SET estado = terminado WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarPedido($id, $idMozo, $idMesa, $estado, $tiempoEstimado, $productos){
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