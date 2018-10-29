<form class='form' id='<?=$idForm?>' autocomplete='off'>
  <input type='hidden' name='idc' value='<?=$idCliente?>'/>
  <div class='form-group'>
    <label>Nombre(s) del cliente</label>
    <input type='text' class='form-control' placeholder='Nombre' name='nombre' maxlength='50' required='required' value='<?=$nombre?>'/>
  </div>


    <div class='form-group'>
      <label>Apellido Paterno</label>
      <input type='text' class='form-control' placeholder='Apellido Paterno' name='apellidop' maxlength='20' required='required' value='<?=$cliente['apellidoP']?>'/>
    </div>

    <div class='form-group'>
      <label>Apellido Materno</label>
      <input type='text' class='form-control' placeholder='Apellido Materno' name='apellidom' maxlength='20' required='required' value='<?=$cliente['apellidoM']?>'/>
    </div>


    <div class='form-group'>
      <label>Calle</label>
      <input type='text' class='form-control' placeholder='Calle' name='calle' maxlength='30' value='<?=$cliente['dirCalle']?>'/>
    </div>
    <div class='form-group'>
      <label>Colonia</label>
      <input type='text' class='form-control' placeholder='Colonia' name='colonia' maxlength='30'  value='<?=$cliente['dirColonia']?>'/>
    </div>
    <div class='form-group'>
      <label>Numero</label>
      <input type='text' class='form-control' placeholder='000' name='numero' maxlength='5'  value='<?=$cliente['dirNumero']?>'/>
    </div>
    <div class='form-group'>
      <label>Código Postal</label>
      <input type='text' class='form-control' placeholder='00000' name='cp' maxlength='5'  value='<?=$cliente['dirCP']?>'/>
    </div>
    <div class='form-group'>
      <label>Municipio</label>
      <input type='text' class='form-control' placeholder='Municipio' name='municipio' maxlength='30' value='<?=$cliente['dirMunicipio']?>'/>
    </div>
    <div class='form-group'>
      <label>Estado</label>
      <input type='text' class='form-control' placeholder='Estado' name='estado' maxlength='30' value='<?=$cliente['dirEstado']?>'/>
    </div>

    <div class='form-group'>
      <label>Telefono</label>
      <input type='text' class='form-control' placeholder='0000000000' name='telefono' maxlength='10' value='<?=$cliente['telefono']?>'/>
    </div>



  <br/><br/>
  <button type='submit' class='btn btn-success pull-right'><?=$textoBoton?></button>
  <div class='pull-right'>&nbsp;&nbsp;&nbsp;&nbsp;</div>
  <button type='button' class='btn btn-default pull-right' data-dismiss='modal'>Cancelar</button>
</form>
