<?php
require_once './models/ProductoPedido.php';
class ProductoPedidoController{
    public function AltaProductoPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if(isset($parametros['idProducto']) && isset($parametros['idPedido']) && isset($parametros['idEmpleado']))
        {
            $productoPedido = new ProductoPedido();
            $productoPedido->idProducto = $parametros['idProducto'];
            $productoPedido->idPedido =  $parametros['idPedido'];
            $productoPedido->estado = "Pendiente";
            $productoPedido->tiempoPreparacion = 0;
            $productoPedido->idEmpleado = $parametros['idEmpleado'];
            $productoPedido->CrearProductoPedido();
            $payload = json_encode(array("mensaje" => "ProductoPedido creado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo crear el ProductoPedido."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function ObtenerUnProductoPedido($request, $response, $args)
    {
        $id = $args['id'];
        $productoPedido = ProductoPedido::TraerPorId($id);
        $payload = json_encode($productoPedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ObtenerProductoPedidos($request, $response, $args)
    {
        $lista = ProductoPedido::TraerProductoPedidos();

        $payload = json_encode($lista);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodosIdPedido($request, $response, $args)
    {
        $id = $args['id'];
        $productoPedidosIdPedidos = ProductoPedido::TraerPorIdPedido($id);

        $payload = json_encode($productoPedidosIdPedidos);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTipoProducto($request, $response, $args)
    {
        $sector = $args['sector'];
        $productoPedidosTipoProductos = ProductoPedido::TraerTipoProducto($sector);
        if($productoPedidosTipoProductos != false)
        {
            $payload = json_encode($productoPedidosTipoProductos);
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo encontrar productos con ese tipo."));
        }

    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarProductoPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id = $args['id'];
        $productoPedido = ProductoPedido::TraerPorId($id);
        if(isset($parametros['idProducto']) && isset($parametros['idPedido']) && isset($parametros['idEmpleado']) && isset($parametros['tiempo']) && isset($parametros['estado']))
        {
            $productoPedido->idProducto = $parametros['idProducto'];
            $productoPedido->idPedido = $parametros['idPedido'];
            $productoPedido->idEmpleado = $parametros['idEmpleado'];
            $productoPedido->tiempo = $parametros['tiempo'];
            $productoPedido->estado = $parametros['estado'];

            ProductoPedido::ModificarProductoPedido($productoPedido);
            $payload = json_encode(array("mensaje" => "Producto modificado con exito."));
        }
        else
        {
            $payload = json_encode(array("error" => "No se pudo modificar el producto."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}
?>