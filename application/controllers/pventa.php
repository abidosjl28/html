<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pventa extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
  }


  public function getItem()
  {
    $codigo = $this->input->post('codigo');
    $item = $this->consultas->getInventario($codigo);
    echo json_encode($item);
  }


  public function Importe()
  {
    $codigo = $this->input->post('codigo');
    $cantidad = $this->input->post('cantidad');

    $item = $this->consultas->getInventario($codigo);
    if($cantidad<$item['cantidadMayoreo'])
    {
      $total = $cantidad * $item['precio'];
    }
    else {
      $total = $cantidad * $item['precioMayoreo'];
    }
    echo $total;
  }

  public function NuevaVenta()
  {
    $idUser = $this->session->userdata('idUser');
    // // obtener el ultimo id de ventas del mismo usuario
    $maxIdVentas = $this->consultas->getMaxIdVentasByUser($idUser);
    $dataVenta = array(
      'idUsuario' => $idUser,
    );
    if($maxIdVentas!=0)
    {
      if($maxIdVentas['Total']<=0)
      {
        $this->delete->delMovimientosFallidos($maxIdVentas['id']);
      }
      if($maxIdVentas['Total']<=0)
      {
        echo $maxIdVentas['id'];
        die();
      }
      else{
        $idVenta = $this->insertar->newVenta($dataVenta);
        echo $idVenta;
        die();
      }
    }

    // asignar nueva venta a este usuario
    $idVenta = $this->insertar->newVenta($dataVenta);
    echo $idVenta;
  }

  public function delItemToVenta()
  {
    $idVenta=$this->input->post('idVenta');
    $codigoItem=$this->input->post('codigo');
    $item = $this->consultas->getInventario($codigoItem);
    $this->delete->delMovimiento($idVenta,$item['id']);
    echo "1";
  }

  public function v_recibido()
  {
    $total=$this->input->post('total');
    $idVenta=$this->input->post('idVenta');
    $impuesto=$this->input->post('impuesto');
    $data = array(
      'total'=>$total,
      'idVenta'=>$idVenta,
      'impuesto'=>$impuesto
    );
    if($impuesto){
      $configs=$this->consultas->getConfigs();
      $data['impuestoPorciento']=$configs->impuestoPorciento;
      $data['nombreImpuesto']=$configs->nombreImpuesto;
    }
    $data['monedaString']=$this->consultas->getMonedaString();
    $this->load->view('_cobrar',$data);
  }

  public function concretarVenta()
  {
    $total=$this->input->post('total');
    $idVenta=$this->input->post('idVenta');
    $recibido=$this->input->post('recibido');
    $impuesto=$this->input->post('impuesto');
    $impuestoPorciento=0;
    $nombreImpuesto="";
    $cambio =$recibido-$total;

    $datos=array(
      'total' => $total,
      'idVenta' => $idVenta,
      'recibido' => $recibido,
      'cambio' => $cambio,
    );

    if($impuesto){
      $impuestoPorciento=$this->input->post('impuestoPorciento');
      $nombreImpuesto=$this->input->post('nombreImpuesto');
    }

    if($cambio<0)
    {
      echo json_encode($datos);
      die();
    }
    $data = array(
      'Total'=>$total,
      'Fecha'=>date("Y-m-d h:i:s"),
      'impuestoPorciento'=>$impuestoPorciento,
      'nombreImpuesto'=>$nombreImpuesto
    );
    $where=$idVenta;
    $this->insertar->setVenta($data,$where);
    //descontar productos del inventario
    $listaItemsVenta = $this->consultas->getItemsDeVentas($idVenta);
    foreach ($listaItemsVenta as $itemVenta) {
      $myItem=$this->consultas->getInventariobyId($itemVenta['idInventario']);
      $nuevaCantidad = $myItem['cantidad']-$itemVenta['cantidad'];
      $dataSetItem=array(
        'cantidad'=>$nuevaCantidad
      );
      $whereSetItem=array(
        'id'=>$itemVenta['idInventario']
      );
      $this->insertar->setProducto($dataSetItem,$whereSetItem);
    }

    echo json_encode($datos);
  }





  public function verificarProducto()
  {
    $codigo = $this->input->post('codigo');
    echo $this->consultas->comprobarCodigo($codigo);
  }





  public function addItemToVenta()
  {
    $idVenta = $this->input->post('idVenta');
    $idItem = $this->input->post('idItem');
    $cantidad = $this->input->post('cantidad');

    $item=$this->consultas->getInventariobyId($idItem);
    if(!$item){
      $resultado = array(
        'validante'=>'n',
      );
      echo json_encode($resultado);
      die();
    }
    $costo=$item['precio'];
    if($cantidad>=$item['cantidadMayoreo']){
      $costo=$item['precioMayoreo'];
    }
    //comprobar si hay mas de este articulo para nadmas sumarlo
    $exysteItemEnVenta = $this->consultas->comprobarItemEnVenta($idVenta,$idItem);
    if($exysteItemEnVenta)
    {
      $movimiento = $this->consultas->getMovimientoVenta($idVenta,$idItem);
      $item = $this->consultas->getInventariobyId($idItem);
      $sumCantidad = $cantidad + $movimiento['cantidad'];
      $dataMovimiento = array(
        'cantidad'=>$sumCantidad,
        'costoUnitario'=>$costo
      );
      $whereMovimiento = array(
        'id'=>$movimiento['id']
      );
      $this->insertar->updateMovimientoVenta($dataMovimiento,$whereMovimiento);
      $resultado = array(
        'validante'=>'r',
        'ncantidad'=>$sumCantidad
      );
      echo json_encode($resultado);
    }
    else {


      $dataMovimiento = array(
        'idVenta' => $idVenta,
        'idInventario'=>$idItem,
        'cantidad'=>$cantidad,
        'costoUnitario'=>$costo
      );
      $this->insertar->newMovimientoVenta($dataMovimiento);
      $resultado = array(
        'validante'=>'x'
      );
      echo json_encode($resultado);
    }
  }



  // public function impVenta()
  // {
  //   $idVenta = $this->input->post('idVenta');
  //   $detalleItem = $this->consultas->getItemsDeVentas($idVenta);
  //   $detalleVenta = $this->consultas->getVentaById($idVenta);
  //   $nombreEmpresa = $this->consultas->getConfigs()->nombreEmpresa;
  //   $vendedor=$this->consultas->getUsers($detalleVenta['idUsuario']);
  //   $data=array(
  //     'detalle'=>$detalleItem,
  //     'detalleVenta'=>$detalleVenta,
  //     'vendedor'=>$vendedor,
  //     'nombreEmpresa'=>$nombreEmpresa
  //   );
  //   $data['monedaString']=$this->consultas->getMonedaString();
  //   $this->load->view('inventario/_ticket',$data);
  // }


  public function impVenta()
  {
    $idVenta = $this->input->post('idVenta');
    $recibido = $this->input->post('recibido');
    $detalleItem = $this->consultas->getItemsDeVentas($idVenta);
    $detalleVenta = $this->consultas->getVentaById($idVenta);
    $nombreEmpresa = $this->consultas->getConfigs();
    $nombreEmpresa = $nombreEmpresa->nombreEmpresa;
    $vendedor = $this->consultas->getUsers($detalleVenta['idUsuario']);
    $moneda=$this->consultas->getMonedaString();
    // $impresora = "C:\Users\jalonso\Desktop\miprint.txt";//$this->consultas->getPrinterCaja();
    $impresora=$this->consultas->getTiketera();
    try {
      $this->load->library('Ticket');
      $this->ticket->goTicket($detalleItem,$detalleVenta,$nombreEmpresa,$vendedor,$impresora,$recibido,$moneda);
    } catch (Exception $e) {
      log_message("error", "Error: Could not print. Message ".$e->getMessage());
      $this->ticket->close_after_exception();
    }


  }




  public function buscarpr()
  {
    $inventario = $this->consultas->getInventario();
    $data = array('inventario' => $inventario);
    $data['monedaString']=$this->consultas->getMonedaString();
    $this->load->view('_busqueda',$data);
  }












}
?>
