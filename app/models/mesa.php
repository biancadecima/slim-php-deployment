<?php
class Mesa{
    public $id;
    public $estado;

   /* public function __construct($estado, $id = null)
    {
        $this->estado = $estado;
        if($id != null){
            $this->id = $id;
        }
    }*/

    public function CrearMesa(){
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (estado) VALUES (:estado)");

        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function TraerMesas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesa");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }
}
?>