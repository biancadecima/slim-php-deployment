<?php
//require_once("");
class Mesa{
    public $id;
    public $estado;
    public $activo;

    public function CrearMesa(){
        $activo = 1;
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesa (estado, activo) VALUES (:estado, :activo)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':activo', $activo, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function TraerMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa where activo = true");
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

    public static function EliminarMesa($id){ // debe ser una baja logica
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesa SET activo = 0 WHERE id = ?");
        $consulta->bindValue(1, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function ModificarMesa($id, $estado){ 
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE mesa SET estado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $id, PDO::PARAM_INT);
   
        return $consulta->execute();
    }

    public static function getMostPopular(): false|Mesa
    {
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta('SELECT * FROM mesa WHERE id = (SELECT idMesa FROM pedido GROUP BY idMesa ORDER BY COUNT(*) DESC LIMIT 1)');
        $consulta->execute();
        $mesa = $consulta->fetchObject();
        return $mesa; 
    }
    
}
?>