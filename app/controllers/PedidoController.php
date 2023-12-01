<?php
require_once 'C:\xampp\htdocs\slim-php-deployment\app\models\pedido.php';
require_once 'C:\xampp\htdocs\slim-php-deployment\app\models\mesa.php';
class PedidoController{

    public static function AltaPedido($request, $response, $args){
        $parametros = $request->getParsedBody();
        if(isset($parametros['nombreCliente']) && isset($parametros['precio']) && isset($parametros['idMesa'])){
            $pedido = new Pedido();
            $pedido->nombreCliente = $parametros['nombreCliente'];
            $pedido->precio = $parametros['precio'];
            $pedido->estado = "Pendiente";
            $pedido->tiempoEstimado = 0;
            $pedido->idMesa = $parametros['idMesa'];
            $pedido->idMozo = $parametros['idMozo'];
            if(Mesa::TraerMesaPorID($pedido->idMesa) && Usuario::VerificarMesero($pedido->idMozo)){
                if(isset($_FILES['imagenMesa']) != null){
                    $imagenMesa = Pedido::GuardarImagenPedido("images/", $_FILES['imagenMesa'], $$pedido->idMesa, $pedido->nombreCliente);
                }else{
                    $imagenMesa = "-";
                }
                $pedido->imagenMesa = $imagenMesa;
                Mesa::ModificarMesa($pedido->idMesa, "con cliente esperando pedido");
                $pedido->CrearPedido();
                $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
            }else{
                $payload = json_encode(array("error" => "No se pudo crear el pedido"));
            }
        }else{
            $payload = json_encode(array("error" => "No se pudo crear el pedido por parametros insufientes"));
        } 

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerPedidos($request, $response, $args){
        $pedidos = Pedido::TraerPedidos();
        $payload = json_encode(array("lista" => $pedidos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

  /*  public static function ModificarEstado($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $estado = $parametros['estado'];

        $pedidoSolicitado = Pedido::TraerPedidoPorID($id);
        $pedido = new Pedido();
        $pedido->id = $pedidoSolicitado->id;
        $pedido->estado = $pedidoSolicitado->tiempoEstimado;
        $pedido->estado = $pedidoSolicitado->estado;

        if($pedido != null){
            switch($estado){
                case 'En preparacion':
                    $pedido->ActualizarEstadoPedido($estado);
                    $payload = json_encode(array("mensaje" => "El estado del pedido era ".$pedido->estado." y se ha actualizado a ".$estado." exitosamente"));
                    break;
                case 'Finalizado':
                    $pedido->tiempoEstimado = "00:05:00";
                    $pedido->ActualizarEstadoPedido($estado);
                    $payload = json_encode(array("mensaje" => "El estado del pedido era ".$pedido->estado." y se ha actualizado a ".$estado." exitosamente"));
                    break;
                case 'Entregado':
                    $pedido->tiempoEstimado = "00:00:00";
                    $pedido->ActualizarEstadoPedido($estado);
                    $payload = json_encode(array("mensaje" => "El estado del pedido era ".$pedido->estado." y se ha actualizado a ".$estado." exitosamente"));
                    break;
                default:
                    $payload = json_encode(array("mensaje" => "Valor de estado no valido"));
            }
            MesaController::CambiarEstadoMesaPorPedido($id);
        }else{
            $payload = json_encode(array("mensaje" => "Numero de pedido no encontrado."));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }*/

    public function BajaPedido($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $pedido = Pedido::TraerPedidoPorID($id);
        if($pedido){
            if(Pedido::EliminarPedido($id)){
                $payload = json_encode(array("mensaje" => "Pedido eliminado con exito"));
            }
        }else{
            $payload = json_encode(array("mensaje" => "Error en eliminar Pedido"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarPedido($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $pedido = Pedido::TraerPedidoPorID($id);
        if($pedido){
            if (isset($parametros['idMozo'])){
                $pedido->idMozo = $parametros['idMozo'];
            }elseif(isset($parametros['idMesa'])){
                $pedido->idMesa = $parametros['idMesa'];
            }elseif(isset($parametros['estado'])){
                $pedido->estado = $parametros['estado'];
            }elseif(isset($parametros['tiempoEstimado'])){
                $pedido->tiempoEstimado = $parametros['tiempoEstimado'];
            }elseif(isset($parametros['productos'])){
                $pedido->productos = $parametros['productos'];
            }

            Pedido::ModificarPedido($id, $pedido->idMozo, $pedido->idMesa, $pedido->estado, $pedido->tiempoEstimado, $pedido->productos);
            $payload = json_encode(array("mensaje" => "Pedido modificado con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Error en modificar Pedido"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
/*
    public function RelacionarFoto($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $pedido = Pedido::TraerPedidoPorID($id);
        if(isset($_FILES['imagen'])){
            $rutaImagen ='C:\xampp\htdocs\slim-php-deployment\images';
            $imagen = $_FILES['imagen'];
            $destino = $pedido->DefinirDestinoImagen($rutaImagen);
            if(move_uploaded_file($imagen['tmp_name'], $destino)){
                $payload = json_encode(array("mensaje" => "Imagen del pedido relacionada con exito"));
            }else{
                $payload = json_encode(array("mensaje" => "Error en relacionar imagen con Pedido"));
            } 
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }*/

    public function ObtenerTiempo($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $pedido = Pedido::TraerPedidoPorID($id);
        if($pedido){
            $payload = json_encode(array("mensaje" => "El tiempo estimado del pedido es de $pedido->tiempoEstimado"));
        }else{
            $payload = json_encode(array("mensaje" => "No se encontró la mesa"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerListosParaServir($request, $response, $args){
        $pedidos = Pedido::TraerPedidosListosParaServir();
        $payload = json_encode(array("lista" => $pedidos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function MozoPedidoCliente($request, $response, $args)
    {
        $params = $request->getParsedBody();

        $idPedido = $params['idPedido'];
        $estadoMesa = $params['nuevoEstadoMesa'];

        $pedido = Pedido::TraerPedidoPorID($idPedido);

        Mesa::ModificarMesa($pedido->idMesa, $estadoMesa);

        if($estadoMesa == "Con cliente comiendo")
        {
            Pedido::ActualizarEstadoPedido($idPedido, "Entregado");   
        }
        else if($estadoMesa == "Con cliente pagando")
        {
            Pedido::ActualizarEstadoPedido($idPedido, "Finalizado");   
        }         

        $payload = json_encode(array("mensaje" => "Se ha entregado el pedido correctamente"));

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>