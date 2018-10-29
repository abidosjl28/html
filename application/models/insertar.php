<?php

class Insertar extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}


		function newProveedor($data)
	{
		return $this->db->insert('tb_proveedores', $data);
	}
	function setProveedor($data,$where)
	{
		return $this->db->update('tb_proveedores', $data,$where);
	}

	function newCliente($data)
	{
		return $this->db->insert('tb_clientes', $data);
	}
	function setCliente($data,$where)
	{
		return $this->db->update('tb_clientes', $data,$where);
	}


	function newProducto($data)
	{
		return $this->db->insert('tb_inventario', $data);
	}
	function setProducto($data,$where)
	{
		return $this->db->update('tb_inventario', $data,$where);
	}

	function newUser($data)
	{
		return $this->db->insert('tb_usuarios', $data);
	}

	function newDepartamento($data)
	{
		$this->db->insert('tb_departamentos', $data);
		return $this->db->insert_id();
	}
	function setDepartamento($data,$where)
	{
		return $this->db->update('tb_departamentos', $data,$where);
	}

	function setUser($data,$where)
	{
		return $this->db->update('tb_usuarios', $data,$where);
	}
	function newVenta($data)
	{
		$this->db->insert('tb_ventas', $data);
		return $this->db->insert_id();
	}

	function setVenta($data,$where)
	{
		return $this->db->update('tb_ventas', $data,array('id'=>$where));
	}

	function newMovimientoVenta($data)
	{
		return $this->db->insert('tb_movimientosventas', $data);
	}
	function updateMovimientoVenta($data,$where)
	{
		return $this->db->update('tb_movimientosventas', $data,$where);
	}

	public function setConfig($data)
	{
		return $this->db->update('tb_config', $data);
	}


}
?>
