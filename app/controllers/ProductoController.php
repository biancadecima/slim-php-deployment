<?php
require_once './models/producto.php';
class ProductoController{

    public static function AltaProducto($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $sector = $parametros['sector'];
        $precio = $parametros['precio'];
        $tiempoestimado = $parametros['tiempoEstimado'];

        $producto = new Producto($descripcion, $sector, $precio, $tiempoestimado, 1);
        //$producto->descripcion = $descripcion;
        //$producto->precio = $precio;

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

    public function BajaProducto($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $producto = Producto::TraerProducto_Id($id);
        if($producto){
            if(Producto::EliminarProducto($id)){
                $payload = json_encode(array("mensaje" => "Producto eliminado con exito"));
            }
        }else{
            $payload = json_encode(array("mensaje" => "Error en eliminar Producto"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarProducto($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $producto = Producto::TraerProducto_Id($id);
        if($producto){
            if (isset($parametros['descripcion'])){
                $producto->descripcion = $parametros['descripcion'];
            }elseif(isset($parametros['sector'])){
                $producto->sector = $parametros['sector'];
            }elseif(isset($parametros['precio'])){
                $producto->precio = $parametros['precio'];
            }

            Producto::ModificarProducto($id, $producto->descripcion, $producto->sector, $producto->precio);
            $payload = json_encode(array("mensaje" => "Producto modificado con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Error en modificar Producto"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

}
?>