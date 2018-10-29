<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// $config['protocol'] = 'smtp';
// $config['smtp_host'] = 'vecopias.com.mx';
// $config['smtp_user'] = 'gastos@vecopias.com.mx';
// $config['smtp_pass'] = 'IjVaxslZcBvB2EG';
// $config['smtp_port'] = '26';
// $config['smtp_timeout'] = '5';
// $config['mailtype'] = 'text';

$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['mailtype'] = 'html';