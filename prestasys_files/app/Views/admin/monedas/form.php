
                    <div class="container-fluid">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-center">Monedas</h4>
                            </div>
                            <div class="card-body card-block p-3">
                                <div class="row form-group">

                                    <div class="col-12">
                                        <div class="row form-group">

                                            <?php if (isset($OPERACION)  &&  $OPERACION == "M") : ?>
                                                <input type="hidden" name="regnro" value="<?= $moneda->regnro ?>">
                                            <?php endif; ?>

                                            <div class="col-12 col-md-4 pl-md-3 pl-0">
                                                <label for="nf-password" >Nombre Divisa:</label>
                                            </div>
                                            <div class="col-12 col-md-8 pl-0">
                                                <input value="<?= isset( $moneda->moneda)?  $moneda->moneda : '' ?>" type="text" maxlength="50" name="moneda" class=" form-control form-control-label form-control-sm ">

                                            </div>
                                            <div class="col-12 col-md-4 pl-md-3 pl-0">
                                                <label for="nf-password"  >Código:</label>
                                            </div>
                                            <div class="col-12 col-md-8 pl-0 ">
                                                <input  value="<?= isset($moneda->prefijo) ? $moneda->prefijo : '' ?>" maxlength="3" type="text" name="prefijo" class=" form-control form-control-sm ">
                                               
                                            </div>


                                            <div class="col-12 col-md-4 pl-md-3">
                                                <label for="nf-password"  >Cambio del día:</label>
                                            </div>
                                            <div class="col-12 col-md-8 pl-0">
                                                <input onfocus="if(this.value=='0') this.value='';"  onblur="if(this.value=='') this.value='0';"     oninput="solo_numero( event)" value="<?= isset($moneda->tcambio )? $moneda->tcambio  : '0' ?>" maxlength="10" type="text" name="tcambio" class=" form-control   form-control-sm ">
                                           
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                <!--end row form  -->


                            </div>
                            <div class="card-footer">
                                <button style="font-size: 12px;font-weight: 600;width:100%;" type="submit" class="btn btn-success btn-sm">
                                    <i class="fa fa-dot-circle-o"></i> GUARDAR
                                </button>
                            </div>

                        </div>
                    </div>