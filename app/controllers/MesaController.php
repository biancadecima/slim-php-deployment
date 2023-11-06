<?php
require_once './models/mesa.php';
class MesaController{

    public function AltaMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $estado = $parametros['estado'];

        $mesa = new Mesa();
        $mesa->estado = $estado;

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
}
?>