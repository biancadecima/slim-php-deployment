<?php
require_once './models/pedido.php';
class PedidoController{

    public static function AltaPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
    
        $idMesa = $parametros['idMesa'];
        $tiempoEstimado = $parametros['tiempoEstimado'];
        $productos = $parametros['productos'];

        $pedido = new Pedido();
        $pedido->idMesa = $idMesa;
        $pedido->tiempoEstimado = $tiempoEstimado;
        $pedido->productos = $productos;
    
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
}

?>