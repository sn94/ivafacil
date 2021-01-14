<div class="container-fluid"> 
    <table class="table table-hover table-striped">

                <thead>
                
                    <tr>
                    <th>Dígitos</th>
                    <?php foreach ($calendario as $calendar) : ?>
                        <th> <?= $calendar->ultimo_d_ruc?></th> 
                        <?php endforeach; ?>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                  

                    <tr  >
                    <td>Día vencimiento</td>
                    <?php foreach ($calendario as $calendar) : ?>
                    <td>
                    <input type="hidden" name="regnro[]" value="<?= $calendar->regnro?>">
                    <input maxlength="2" class="form form-control form-control-sm" oninput="solo_numero(event)" type="text" name="dia_vencimiento[]"  value="<?= $calendar->dia_vencimiento?>">
                    </td>

                    <?php endforeach; ?>
                    <td>
                    <button  class="btn btn-success btn-sm"   type="submit">Guardar</button>
                    </td>
                    </tr>
                  


                </tbody>
            </table> 
</div>