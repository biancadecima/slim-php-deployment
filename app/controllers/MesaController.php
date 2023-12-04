<?php
require_once './models/mesa.php';
class MesaController{

    public function AltaMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        //$estado = $parametros['estado'];

        $mesa = new Mesa();
        $mesa->estado = "cerrada";

        $mesa->CrearMesa();
        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function ObtenerMesas($request, $response, $args)
    {
        $mesas  = Mesa::TraerMesas();
        $payload = json_encode(array("listaMesas" => $mesas));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public static function CambiarEstadoMesaPorPedido($id_pedido){
        $pedido = Pedido::TraerPedidoPorID($id_pedido);
        $mesa = Mesa::TraerMesaPorID($pedido->idMesa);
        switch ($pedido->estado) {
            case "En espera":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "En preparacion":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Finalizado":
                $estadoMesa = "con cliente esperando pedido";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
            case "Entregado":
                $estadoMesa = "con cliente comiendo";
                Mesa::ActualizarEstadoMesa($mesa->id, $estadoMesa);
                break;
        }
    }

    public function BajaMesa($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $mesa = Mesa::TraerMesaPorID($id);
        if($mesa){
            if(Mesa::EliminarMesa($id)){
                $payload = json_encode(array("mensaje" => "Mesa eliminado con exito"));
            }
        }else{
            $payload = json_encode(array("mensaje" => "Error en eliminar Mesa"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ModificarMesa($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $mesa = Mesa::TraerMesaPorID($id);
        if($mesa){
            if (isset($parametros['estado'])){
                $mesa->estado = $parametros['estado']; 
                Mesa::ModificarMesa($id, $mesa->estado);
                $payload = json_encode(array("mensaje" => "Mesa modificado con exito"));
            }else{
                $payload = json_encode(array("mensaje" => "Parametros insuficientes"));
            }
        }else{
            $payload = json_encode(array("mensaje" => "Error en modificar Mesa"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public static function TraerMasUsada($request, $response, $args)
    {
        if (($mesa = Mesa::TraerMasUsada()) == false) {
            $payload = json_encode(array("mensaje" => "No se encontró la mesa"));
        }

        $payload = (json_encode(['mesa' => $mesa]));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args){
        $parametros = $request->getParsedBody();
        $id = $parametros['id'];
        $mesa = Mesa::TraerMesaPorID($id);
        if($mesa){
            $mesa->estado = "cerrada";
            Mesa::ModificarMesa($id, $mesa->estado);
            $payload = json_encode(array("mensaje" => "Mesa cerrada con exito"));
        }else{
            $payload = json_encode(array("mensaje" => "Error en cerrar Mesa"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

 
}
?>