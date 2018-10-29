<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Principal extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();

  }


  public function index()
  {
    $idUser = $this->session->userdata('idUser');
    $rol= $this->session->userdata('rol');
    $user=$this->consultas->getUsers($idUser);
    if(!$idUser)
    {
      $dataHeader['titulo']="Login";
      $this->load->view('header',$dataHeader);
      $data['error'] ="";
      $this->load->view('login',$data);
      $dataFooter=array(
        'scripts'=> ""
      );
      $this->load->view('footer',$dataFooter);
      return false;
    }
    else if($rol=="1" || $rol=="3") // Administrador
    {
      $data['msj'] ="";
      $dataHeader['titulo']="Admin";
      $dataSidebar = array();
      $dataSidebar['classInventario']="";
      $dataSidebar['classVentas']="";
      $dataSidebar['classMovimientos']="";
      $dataSidebar['classUsuarios']="";
      $dataSidebar['classCreditos']="";

      $dataSidebar['classInventarioGeneral']="active";
      $dataSidebar['classInventarioModificar']="";
      $dataSidebar['classInventarioAgregar']="";
      $dataSidebar['classInventarioNuevo']="";
      $dataSidebar['classInventarioCBarras']="";
      $dataSidebar['classConfiguraciones']="";
      $dataSidebar['classProveedores']="";
      $dataSidebar['classClientes']="";

      $tema = $this->consultas->configTema();
      $dataSidebar['tema']="$tema";
      $dataSidebar['usuario']=$user;
      $consultasWitget=$this->consultas->consultasIniciales();
      $data=array_merge($data,$consultasWitget);
      $data['listaUsuarios']=$this->consultas->getUsers();
      $data['monedaString']=$this->consultas->getMonedaString();
      $this->load->view('header',$dataHeader);
      $this->load->view('sidebar',$dataSidebar);
      $this->load->view('admin-inicio',$data);
      $this->load->view('main-footer');
      $dataFooter=array(
        'scripts'=> "<script src='".base_url()."js/admin.js'></script>"
      );
      $dataFooter['scripts'].="<script src='".base_url()."js/tema.js'></script>";
      $this->load->view('footer',$dataFooter);
    }
    else if($rol=="2") // Simple vendedor mortal
    {
      $data = array();
      $dataHeader['titulo']="Punto de Venta";
      $tema = $this->consultas->configTema();
      $dataSidebar['tema']="$tema";
      $dataSidebar['usuario']=$user;

      $data['configs']=$this->consultas->getConfigs();

      $data['impuesto_si']="hidden";
      if ($data['configs']->impuesto==1) {
        $data['impuesto_si']="";
      }
      $data['monedaString']=$this->consultas->getMonedaString();
      $this->load->view('header',$dataHeader);
      $this->load->view('sidebar',$dataSidebar);
      $this->load->view('main', $data);
      $this->load->view('main-footer');
      $dataFooter=array(
        'scripts'=> "<script src='".base_url()."js/pventa.js'></script>"
      );
      $this->load->view('footer',$dataFooter);
    }
  }


  public function logout()
  {
    $this->session->sess_destroy();
    redirect(base_url(), 'refresh');
  }

  public function tema()
  {
    $tema = $this->input->post('tema');
    $tema = array('tema' => $tema );
    $this->insertar->setConfig($tema);
  }
}
?>
