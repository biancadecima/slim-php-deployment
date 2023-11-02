<?php
class Pedido{
    public $id;
    public $idMesa;
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
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (idMesa, tiempoEstimado, productos) VALUES (:idMesa, :tiempoEstimado, :productos)");

        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
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
    
}
?>