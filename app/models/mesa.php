<?php
//require_once("");
class Mesa{
    public $id;
    public $estado;
    public $activo;

   /* public function __construct($estado, $id = null)
    {
        $this->estado = $estado;
        if($id != null){
            $this->id = $id;
        }
    }*/

    public function CrearMesa(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (estado) VALUES (:estado)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function TraerMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function TraerMesaPorID($id)
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta = $objetoAccesoDato->prepararConsulta("SELECT * from mesa where id = ? AND activo = true");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        $consulta->execute();
        $mesa = $consulta->fetchObject();
        return $mesa; //no lo devuelvo como objeto tal cual
    }

    public function CambiarEstadoMesaPorPedido($id_pedido){
        $pedido = Pedido::TraerPedidoPorID($id_pedido);
        $mesa = Mesa::TraerMesaPorID($pedido->idMesa);
        switch ($pedido->estado) {
            case "En espera":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "En preparacion":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Finalizado":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Entregado":
                $estadoMesa = "con cliente comiendo";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
        }
    }

    public static function ActualizarEstadoMesa($id, $estado){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE mesa SET estado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }
}
?>