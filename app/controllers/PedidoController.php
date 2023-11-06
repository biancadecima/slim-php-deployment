<?php
require_once './models/pedido.php';
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
}

?>