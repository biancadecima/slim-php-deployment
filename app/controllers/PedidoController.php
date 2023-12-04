<?php
require_once 'C:\xampp\htdocs\slim-php-deployment\app\models\pedido.php';
require_once 'C:\xampp\htdocs\slim-php-deployment\app\models\mesa.php';
class PedidoController{

    public static function AltaPedido($request, $response, $args){
        $parametros = $request->getParsedBody();
        if(isset($parametros['nombreCliente']) && isset($parametros['idMozo']) && isset($parametros['idMesa'])){
            $pedido = new Pedido();
            $pedido->nombreCliente = $parametros['nombreCliente'];
            $pedido->precio = 0;
            $pedido->estado = "Pendiente";
            $pedido->tiempoEstimado = 0;
            $pedido->idMesa = $parametros['idMesa'];
            $pedido->idMozo = $parametros['idMozo'];
            if(Mesa::TraerMesaPorID($pedido->idMesa) && Usuario::VerificarMesero($pedido->idMozo)){
                if(isset($_FILES['imagenMesa']) && $_FILES['imagenMesa'] != null){
                    $imagenMesa = Pedido::GuardarImagenPedido("./images/", $_FILES['imagenMesa'], $pedido->idMesa, $pedido->nombreCliente);
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

    public function ObtenerTiempo($request, $response, $args){
        $parametros = $request->getQueryParams();
        $id = $parametros['id'];
        $pedido = Pedido::TraerPedidoPorID($id);
        if($pedido){
            $payload = json_encode(array("mensaje" => "El tiempo estimado del pedido es de $pedido->tiempoEstimado minutos"));
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