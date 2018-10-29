<?php

class Consultas extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function consultasIniciales()
	{
		$sqlUsuarios = "SELECT id FROM tb_usuarios";
		$queryUsuarios = $this->db->query($sqlUsuarios);
		$usuarios=$queryUsuarios->num_rows();

		$sqlArticulos = "SELECT id FROM tb_inventario";
		$queryArticulos = $this->db->query($sqlArticulos);
		$articulos=$queryArticulos->num_rows();

		$hoy=date('Y-m-d');
		$sqlRecaudado = "SELECT sum(Total) as suma FROM tb_ventas WHERE Fecha >= '$hoy'";
		$queryRecaudado = $this->db->query($sqlRecaudado);
		$recaudado=$queryRecaudado->row();
		$recaudado=$recaudado->suma;
		$valores=array(
			'articulos'=>$articulos,
			'recaudado'=>$recaudado,
			'usuarios'=>$usuarios,
			'creditos'=>0
		);
		return $valores;
	}

	function isUser($user, $pass)
	{
		$sql = "SELECT * FROM tb_usuarios WHERE username = ? ";
		$query = $this->db->query( $sql, array($user) );
		if ($query->num_rows() > 0) {
			$sql = "SELECT * FROM tb_usuarios WHERE username = ? and pass = ? ";
			$query = $this->db->query($sql, array($user,$pass) );
			if ($query->num_rows() > 0) {
				return '1';
			} else {
				return 'Contraseña incorrecta';
			}
		} else {
			return 'Usuario no encontrado';
		}
	}

	function findIdUser($user, $pass)
	{
		$sql = "SELECT * FROM tb_usuarios WHERE username = ? AND pass= ? ";
		$query = $this->db->query($sql,array($user,$pass));
		return $query->row();
	}

	function existeUsername($user)
	{
		$sql = "SELECT id FROM tb_usuarios WHERE username = ? ";
		$query = $this->db->query($sql,array($user));
		if ($query->num_rows() == 0) {
			return false;
		}
		return true;
	}



	function getConfigs()
	{
		$sql = "SELECT * FROM tb_config limit 1";
		$query = $this->db->query($sql);
		return $r=$query->row();
	}

	function configTema()
	{
		$sql = "SELECT tema FROM tb_config limit 1";
		$query = $this->db->query($sql);
		$r=$query->row();
		return $r->tema;
	}

	function configImpuesto()
	{
		$sql = "SELECT tema FROM tb_config limit 1";
		$query = $this->db->query($sql);
		$r=$query->row();
		return $r->tema;
	}

	function configNombreEmpresa()
	{
		$sql = "SELECT nombreEmpresa FROM tb_config limit 1";
		$query = $this->db->query($sql);
		$r=$query->row();
		return $r->nombreEmpresa;
	}
	function configLogo()
	{
		$sql = "SELECT logo FROM tb_config limit 1";
		$query = $this->db->query($sql);
		$r=$query->row();
		return $r->logo;
	}

	function getInventario($codigo="")
	{
		if($codigo!="")
		{
			$sql = "SELECT  inv.id,inv.codigo,inv.descripcion,inv.costo,inv.precio,inv.precioMayoreo,inv.cantidadMayoreo,inv.idDepartamento,inv.cantidad,inv.idProveedor,dep.departamento,inv.idTipo,tv.nombreTipo, tp.nombre as proveedor
			FROM tb_inventario inv
			inner join tb_departamentos dep on inv.idDepartamento = dep.id
			inner join tb_tipos tv on inv.idTipo = tv.id
			inner join tb_proveedores tp on inv.idProveedor = tp.id
			WHERE codigo = ?
			ORDER BY inv.descripcion ASC";
			$query = $this->db->query( $sql,array($codigo) );
			return $query->row_array();
		}
		else{
			$sql = "SELECT  inv.id,inv.codigo,inv.descripcion,inv.costo,inv.precio,inv.precioMayoreo,inv.cantidadMayoreo,inv.idDepartamento,inv.cantidad,inv.idProveedor,dep.departamento,inv.idTipo,tv.nombreTipo, tp.nombre as proveedor
			FROM tb_inventario inv
			inner join tb_departamentos dep on inv.idDepartamento = dep.id
			inner join tb_tipos tv on inv.idTipo = tv.id
			inner join tb_proveedores tp on inv.idProveedor = tp.id
			ORDER BY inv.descripcion ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}

	function getInventariobyId($id)
	{
		$sql = "SELECT  *
		FROM tb_inventario
		WHERE id = ?";
		$query = $this->db->query( $sql, array($id) );
		return $query->row_array();
	}

	function getVentas()
	{
		$sql = "SELECT  vts.id,vts.idUsuario,vts.Total,vts.Fecha,u.nombre as nombreUsuario
		FROM tb_ventas vts
		inner join tb_usuarios u on vts.idUsuario = u.id
		WHERE vts.Total>0";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getItemsDeVentas($idVenta)
	{
		$sql = "SELECT mv.*, inv.descripcion
		FROM  tb_movimientosventas	mv
		inner join tb_inventario inv on inv.id = mv.idInventario
		WHERE mv.idVenta = ? ";
		$query = $this->db->query( $sql,array($idVenta) );
		return $query->result_array();
	}



	function getDepartamentos($idDepartamento=0)
	{
		if($idDepartamento>0){
			$sql = "SELECT * FROM tb_departamentos WHERE id= ? ";
			$query = $this->db->query($sql , array($idDepartamento) );
			return $query->row_array();
		}
		else {
			$sql = "SELECT * FROM tb_departamentos ORDER BY id ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}


	function getTipoVenta()
	{
		$sql = "SELECT * FROM tb_tipos ORDER BY id ASC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function comprobarCodigo($codigo="")
	{
		$sql = "SELECT * FROM tb_inventario WHERE codigo = ? ";
		$query = $this->db->query($sql , array($codigo));
		if ($query->num_rows() == 0) {
			return false;
		}
		return true;
	}

	public function getUsers($id=0)
	{
		if($id!=0)
		{
			$sql = "SELECT u.*,r.rol
			FROM tb_usuarios u
			inner join tb_roles r on u.idRol = r.id
			Where u.id= ?
			ORDER BY u.nombre ASC";
			$query = $this->db->query( $sql, array($id) );
			return $query->row_array();
		}
		$sql = "SELECT u.*,r.rol
		FROM tb_usuarios u
		inner join tb_roles r on u.idRol = r.id
		ORDER BY u.nombre ASC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getClientes($id=0)
	{
		if($id!=0)
		{
			$sql = "SELECT * FROM tb_clientes Where id= ?	ORDER BY nombre ASC";
			$query = $this->db->query($sql, array($id) );
			return $query->row_array();
		}
		$sql = "SELECT * FROM tb_clientes ORDER BY nombre ASC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getProveedores($idProv=0){
		if($idProv>0){
			$sql = "SELECT * FROM tb_proveedores WHERE id= ?	ORDER BY nombre ASC";
			$query = $this->db->query( $sql, array($idProv) );
			return $query->row_array();
		}
		else {
			$sql = "SELECT * FROM tb_proveedores	ORDER BY nombre ASC";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
	}



	public function getRoles()
	{
		$sql = "SELECT * FROM tb_roles";
		$query = $this->db->query($sql);
		return $query->result_array();
	}



	public function comprobarItemEnVenta($idVenta,$idItem)
	{
		$sql = "SELECT * FROM tb_movimientosventas WHERE idVenta = ? AND idInventario = ? ";
		$query = $this->db->query($sql, array($idVenta,$idItem) );
		if ($query->num_rows() == 0) {
			return false;
		}
		return true;
	}

	public function getMovimientoVenta($idVenta,$idItem)
	{
		$sql = "SELECT * FROM tb_movimientosventas WHERE idVenta = ? AND idInventario= ? ";
		$query = $this->db->query($sql, array($idVenta,$idItem) );
		return $query->row_array();
	}

	public function getMovimientosData($codigo="")
	{
		if($codigo==""){
			$sql = "SELECT mvs.id,mvs.idVenta,mvs.cantidad, vts.Fecha,inv.codigo,inv.descripcion,us.nombre as usuario,mvs.tipo,mvs.fechaEntrada
			FROM tb_movimientosventas mvs
			left outer join tb_ventas vts on mvs.idVenta=vts.id
			left outer join tb_inventario inv on mvs.idInventario=inv.id
			left outer join tb_usuarios us on vts.idUsuario=us.id";
			$query = $this->db->query($sql);
			return $query->result_array();
		}
		else{
			$sql = "SELECT mvs.id,mvs.idVenta,mvs.cantidad, vts.Fecha,inv.codigo,inv.descripcion,us.nombre as usuario,,mvs.fechaEntrada
			FROM tb_movimientosventas mvs
			left outer join tb_ventas vts on mvs.idVenta=vts.id
			left outer join tb_inventario inv on mvs.idInventario=inv.id
			left outer join tb_usuarios us on vts.idUsuario=us.id
			WHERE inv.codigo=?";
			$query = $this->db->query($sql, array($codigo) );
			return $query->result_array();
		}
	}

	public function getVentaById($idVenta)
	{
		$sql = "SELECT * FROM tb_ventas WHERE id = ?";
		$query = $this->db->query($sql, array($idVenta) );
		return $query->row_array();
	}

	function getMaxIdVentasByUser($idUsuario)
	{
		$sql = "SELECT * FROM tb_ventas WHERE idUsuario = ? ORDER BY id DESC limit 1";
		$query = $this->db->query($sql,array($idUsuario) );
		if ($query->num_rows() == 0) {
			return 0;
		}
		else{
			return $query->row_array();
		}
	}



	public function recaudacionByUserHoy($idUser=0)
	{
		if($idUser!=0){
			$hoy=date('Y-m-d');
			$sqlRecaudado = "SELECT sum(Total) as suma FROM tb_ventas WHERE Fecha >= ? and idUsuario = ? ";
			$queryRecaudado = $this->db->query($sqlRecaudado,array($hoy,$idUser) );
			$recaudado=$queryRecaudado->row();
			$recaudado=$recaudado->suma;
			if($recaudado<=0)$recaudado=0;
			return $recaudado;
		}
	}




	function getVentasPeriodo($fecha1="",$fecha2="")
	{
		$sql = "SELECT  vts.id,vts.idUsuario,vts.Total,vts.Fecha,u.nombre as nombreUsuario
		FROM tb_ventas vts
		inner join tb_usuarios u on vts.idUsuario = u.id
		WHERE vts.Total>0
		and  vts.Fecha > ?
		and vts.Fecha <= ?";
		$query = $this->db->query($sql,array($fecha1." 00:00:00",$fecha2." 23:59:59") );
		return $query->result_array();
	}



	public function recaudacionByUser($fecha1,$fecha2,$idUser=0)
	{
		if($idUser!=0){
			$hoy=date('Y-m-d');
			$sqlRecaudado = "SELECT sum(Total) as suma
			FROM tb_ventas
			WHERE idUsuario = ?
			and  Fecha > ?
			and Fecha<= ? ";
			$queryRecaudado = $this->db->query($sqlRecaudado,array($idUser,$fecha1." 00:00:00",$fecha2." 23:59:59") );
			$recaudado=$queryRecaudado->row();
			$recaudado=$recaudado->suma;
			if($recaudado<=0)$recaudado=0;
			return $recaudado;
		}
	}


	public function getCantidadByProducto($idProducto=0)
	{
		$sql = "SELECT cantidad FROM tb_inventario
		WHERE id = ? ";
		$query = $this->db->query($sql,array($idProducto));
		$r=$query->row();
		return $r->cantidad;
	}

	public function getMonedaString()
	{
		$sql = "SELECT monedaString FROM tb_config";
		$query = $this->db->query($sql);
		$r=$query->row();
		return $r->monedaString;
	}

	public function getTiketera()
	{
		$sql = "SELECT tiketera FROM tb_config";
		$query = $this->db->query($sql);
		$r=$query->row();
		return $r->tiketera;
	}

}
?>
