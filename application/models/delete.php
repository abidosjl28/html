<?php

class Delete extends CI_Model
{

	function __construct()
    {
		parent::__construct();
    }


    function delUser($id)
    {
        return $this->db->delete('tb_usuarios', array('id' => $id));
    }

		function delMovimientosFallidos($idVenta)
    {
        return $this->db->delete('tb_movimientosventas', array('idVenta' => $idVenta));
    }

		function delMovimiento($idVenta,$idItem)
    {
        return $this->db->delete('tb_movimientosventas', array('idVenta' => $idVenta,'idInventario'=>$idItem));
    }


		function delDepartamento($id)
    {
        return $this->db->delete('tb_departamentos', array('id' => $id));
    }

		function delProveedor($id)
    {
        return $this->db->delete('tb_proveedores', array('id' => $id));
    }
		function delCliente($id)
    {
        return $this->db->delete('tb_clientes', array('id' => $id));
    }
}
?>
