<?php
require_once 'C:\xampp\htdocs\slim-php-deployment\app\models\pedido.php';
class PedidoController{

    public static function AltaPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
    
        $idMozo = $parametros['idMozo'];
        $idMesa = $parametros['idMesa'];
        //$tiempoEstimado = $parametros['tiempoEstimado'];
        $stringProductos = $parametros['productos'];
        $array_idsProductos = explode(",", $stringProductos);
        $productos = array();
        foreach($array_idsProductos as $id){
            array_push($productos, Producto::TraerProducto_Id($id));
        }


        $tiemposEnSegundos = array_map(function($producto) {
            list($horas, $minutos, $segundos) = explode(':', $producto->tiempoEstimado);
            return $horas * 3600 + $minutos * 60 + $segundos;
        }, $productos);
        
        $indiceProductoMayor = array_search(max($tiemposEnSegundos), $tiemposEnSegundos);
        $producto_mayor = $productos[$indiceProductoMayor];

        $pedido = new Pedido();
        $pedido->idMozo = $idMozo;
        $pedido->idMesa = $idMesa;
        $pedido->tiempoEstimado = $producto_mayor->tiempoEstimado;
        $pedido->productos = json_encode($productos);
        $pedido->activo = 1;
    
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
            MesaController::CambiarEstadoMesaPorPedido($id);
        }else{
            $payload = json_encode(array("mensaje" => "Numero de pedido no encontrado."));
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

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
}

?>