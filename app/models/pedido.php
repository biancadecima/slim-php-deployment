<?php
class Pedido{
    public $id;
    public $idMozo;
    public $idMesa;
    public $estado;
    public $tiempoEstimado;
    public $productos;

    /*public function __construct($id = null,  $idMesa, $tiempoEstimado, $productos = null){
        if($id != null){
            $this->id = $id;
        }
        $this->idMesa = $idMesa;
        $this->tiempoEstimado = $tiempoEstimado;
        $this->productos = array();
        if($productos != null){
            $this->productos = $productos; 
        }
    }*/

    public function CrearPedido()
    {
        $productosJson =  json_encode($this->productos);
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (idMozo, idMesa, estado, tiempoEstimado, productos) VALUES (:idMozo, :idMesa, :estado, :tiempoEstimado, :productos)");

        $estadoInicial = 'En espera';
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $estadoInicial, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':productos', $productosJson, PDO::PARAM_STR);

        $consulta->execute();
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
        $consulta = $objetoAccesoDato->prepararConsulta("select * from pedido where id = ?");
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
    
}
?>