<!-- <center>
<table class="table">
<thead>
<tr>

</tr>
</thead>
<tbody>
<tr>

</tr>
</tbody>
</table>
</center> -->

<h4>Venta: <u><?=$detalleVenta['id']?></u></h4>
<h4>Vendedor: <u><?=$vendedor['nombre']?></u></h4>
<h4>Fecha y Hora: <u><?=date('d-m-Y H:i:s', strtotime($detalleVenta['Fecha']))?></u></h4>
<table id="tbventa" class="table table-striped table-bordered dt-responsive nowrap table-hover table-condensed" cellspacing="0" width="100%" style="background: white!important">
  <thead>
    <tr>
      <th class="bg-default"><span>Codigo</span></th>
      <th class="bg-default"><span>Descripcion</span></th>
      <th class="bg-default"><span>Costo Unitario</span></th>
      <th class="bg-default text-center"><span>Cantidad</span></th>
      <th class="bg-default text-center"><span>Importe</span></th>
      <th class="hidden"><span>Descartar</span></th>
      <th class="hidden"><span>importex</span></th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($detalle as $fila){
      $item = $this->consultas->getInventariobyId($fila['idInventario']);
      ?>
      <tr>
        <td class="text-center"><?=$item['codigo']?></td>
        <td><?=$item['descripcion']?></td>
        <td class="text-right"><?=$monedaString?> <?=number_format($fila['costoUnitario'],2)?></td>
        <td class="text-center"><?=$fila['cantidad']?></td>
        <td class="text-right"><?=$monedaString?> <?=number_format($fila['costoUnitario']*$fila['cantidad'],2)?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>

  <div class="text-right">
    <h4>
      <?php
      if($detalleVenta['impuestoPorciento']>0){
        $subtotal=$detalleVenta['Total']/(1+($detalleVenta['impuestoPorciento']/100));
        ?>
        Subtotal: <?=$monedaString?> <?=number_format($subtotal,2)?></br>
        <?=$detalleVenta['nombreImpuesto']?> (<?=$detalleVenta['impuestoPorciento']?>%): <?=$monedaString?> <?=number_format($detalleVenta['Total']-$subtotal,2)?></br>
        <b>Total: <?=$monedaString?> <?=number_format($detalleVenta['Total'],2)?></b>
        <?php
      }
      else {
        ?>
        <b>Total: <?=$monedaString?> <?=number_format($detalleVenta['Total'],2)?></b>
        <?php
      }
      ?>
    </h4>
  </div>
