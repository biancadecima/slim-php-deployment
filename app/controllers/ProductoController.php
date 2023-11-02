<?php
require_once './models/producto.php';
class ProductoController{

    public static function AltaProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $precio = $parametros['precio'];

        $producto = new Producto();
        $producto->descripcion = $descripcion;
        $producto->precio = $precio;

        $producto->CrearProducto();

        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }


    public static function ObtenerProductos($request, $response, $args) {
        $productos  = Producto::TraerProductos();
    
        $payload = json_encode(array("listaProductos" => $productos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

}
?>