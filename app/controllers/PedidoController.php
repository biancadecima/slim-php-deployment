<?php
require_once 'C:\xampp\htdocs\slim-php-deployment\app\models\pedido.php';
class PedidoController{

    public static function AltaPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
    
        $idMozo = $parametros['idMozo'];
        $idMesa = $parametros['idMesa'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $stringProductos = $parametros['productos'];
        $arrayProductos = explode(",", $stringProductos);
        $productos = array();
        foreach($arrayProductos as $id){
            array_push($productos, Producto::TraerProducto_Id($id));
        }

        $pedido = new Pedido();
        $pedido->idMozo = $idMozo;
        $pedido->idMesa = $idMesa;
        $pedido->tiempoEstimado = $tiempoEstimado;
        $pedido->productos = json_encode($productos);
    
        $pedido->CrearPedido();
    
        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));
    
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerPedidos($request, $response, $args){
        $pedidos = Pedido::TraerPedidos();
        $payload = json_encode(array("lista" => $pedidos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public static function ModificarEstado($request, $response, $args){
        $parametros = $request->getParsedBody();
        // me traigo el pedido con el id ingresado por body
        $pedido = new Pedido();
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
                    $pedido->tiempoEstimado = "00:00:30";
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
            
        }else{
            $payload = json_encode(array("mensaje" => "Numero de pedido no encontrado."));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

?>