<center>
  <h2>Total: <?=$monedaString?><?=number_format($total,2)?></h2>
  <form class="form form-inline" action="" method="post" id="formrecibido" autocomplete="off">
    <input type="hidden" name="idVenta" value="<?=$idVenta?>">
    <input type="hidden" name="total" value="<?=$total?>">
    <input type="hidden" name="impuesto" value="<?=$impuesto?>">
    <?php
    if($impuesto)
    {
      ?>
      <input type="hidden" name="impuestoPorciento" value="<?=$impuestoPorciento?>">
      <input type="hidden" name="nombreImpuesto" value="<?=$nombreImpuesto?>">
      <?php
    }
    ?>
    <div class="form-group">
      <label for="recibido">Recibiendo:</label>
      <div class="input-group">
        <div class="input-group-addon"><b><?=$monedaString?></b></div>
        <input type="text" class="form-control" id="recibido" autofocus name="recibido" maxlength="7" required="required" placeholder="<?=$total?>">
      </div>
    </div>
    <br>
    <br>
    <button type="submit" class="btn btn-success btn-block">Continuar</button>
  </form>
</center>
