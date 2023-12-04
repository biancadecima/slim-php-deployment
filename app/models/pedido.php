<?php
class Pedido{
    public $id;
    public $idMesa;
    public $idMozo;
    public $nombreCliente;
    public $precio;
    public $estado;
    public $tiempoEstimado;
    public $imagenMesa;

    public function CrearPedido(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedido (idMozo, idMesa, nombreCliente, estado, tiempoEstimado, precio, imagenMesa) VALUES (:idMozo, :idMesa, :nombreCliente, :estado, :tiempoEstimado, :precio, :imagenMesa)");

        //$estadoInicial = 'En espera';
        $consulta->bindValue(':idMesa', $this->idMesa, PDO::PARAM_INT);
        $consulta->bindValue(':idMozo', $this->idMozo, PDO::PARAM_INT);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->bindValue(':imagenMesa', $this->imagenMesa, PDO::PARAM_STR);

        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    /*public function DefinirDestinoImagen($ruta){
        $destino = str_replace('\\', '/', $ruta).$this->idMesa."-".$this->id.".png";
        return $destino;

        //$destino = $ruta."\\".$this->idMesa."-".$this->id.".png";
        //return $destino;
    }*/

    public static function GuardarImagenPedido($ruta, $urlImagen, $idMesa, $nombreCliente)
    {
        $destino = $ruta ."\\". $idMesa . "-" . $nombreCliente . ".jpg";  
        move_uploaded_file($urlImagen["tmp_name"], $destino);
        return $destino;
    }

    public static function TraerPedidos(){
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

    public static function ActualizarEstadoPedido($id, $estado){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("UPDATE pedido SET estado = ? WHERE id = ?");
        $consulta->bindValue(1, $estado, PDO::PARAM_STR);
        $consulta->bindValue(2, $id, PDO::PARAM_INT);
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
        $consulta->bindValue(4, $tiempoEstimado, PDO::PARAM_INT);
        $consulta->bindValue(5, $productos, PDO::PARAM_STR);
        $consulta->bindValue(6, $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function TraerPedidosListosParaServir(){
        $objetoAccesoDato = AccesoDatos::obtenerInstancia(); 
        $consulta =$objetoAccesoDato->prepararConsulta("SELECT * FROM pedido WHERE estado = 'Listo Para Servir'");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
	}
    public static function SumarPrecio($id, $precioprod)
    {
        $objAcessoDatos = AccesoDatos::ObtenerInstancia();

        $consulta = $objAcessoDatos->PrepararConsulta("UPDATE pedido SET precio = precio + :precioprod WHERE id = :id");
        
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->bindValue(':precioprod', $precioprod, PDO::PARAM_INT);

        $consulta->execute();

        return $consulta->rowCount();

    }
    public static function ActualizarEstadoYTiempo($id)
    {
        $objAcessoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAcessoDatos->prepararConsulta("UPDATE pedido SET estado = :estado, tiempoEstimado = :tiempoEstimado WHERE id = :id");

        $tiempoEstimado = Pedido::ObtenerTiempoEstimado($id);
        $estadoDelPedido = Pedido::ObtenerEstado($id);

        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estadoDelPedido, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT);
        //$consulta->bindValue(':fechaModificacion', date('Y-m-d'), PDO::PARAM_STR);

        $consulta->execute();
    }

    public static function ObtenerTiempoEstimado($id){
        $lista = ProductoPedido::TraerPorIdPedido($id);
        $tiempoPreparacion = array();
        $tiempoMasAlto = 0;
        foreach($lista as $value)
        {
            $tiempoPreparacion[] = $value->tiempoPreparacion;
        }
        $tiempoMasAlto = max($tiempoPreparacion);
        return $tiempoMasAlto;
    }
    public static function ObtenerEstado($id){
        $lista = ProductoPedido::TraerPorIdPedido($id);
        $estados = array();
        $estadoRetorno = null;
        foreach($lista as $value){
            if($value->estado == "Pendiente"){
                $estadoRetorno = "Pendiente";
                break;
            }

            if($value->estado == "En Preparacion" || $value->estado == "Listo Para Servir"){
                $estados[] = $value->estado;
                
            }
            
        }

        if($estadoRetorno == null)
        {
            if(in_array("En Preparacion", $estados))
            {
                $estadoRetorno = "En Preparacion";
            }
            else if(in_array("Listo Para Servir", $estados))
            {
                $estadoRetorno = "Listo Para Servir";
            }
        }

        return $estadoRetorno;
    }
    
}
?>