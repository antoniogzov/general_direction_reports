 <?php
if (isset($_GET['id_assignment'])) {
        $sj     = $_GET['id_assignment'];
        
        if(isset($_GET['id_period'])){
            $id_period=$_GET['id_period'];
        }else {
            $id_period="";
        }
        
        if(!empty($id_level_combination = $helpers->getIdsLevelCombination($sj))){
            $id_level_combination = $id_level_combination->id_level_combination;
            $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
        }
        
        /* $period = $_GET['id_assignment']; */
        ?>
                    <!-- HEADER MATERIAS ESPAÑOL -->
                    <div class="header bg-gradient-default pb-6">
                        <div class="container-fluid">
                            <div class="header-body">
                                <div class="row align-items-center py-4">
                                    <div class="col-lg-6 col-7">
                                        <input type="hidden" id="assignment" value="<?=$_GET['id_assignment'];?>">
                                       
                                        
                                        <h6 class="h2 text-white d-inline-block mb-0">PLAN DE EVALUACIÓN</h6>
                                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                                
                                            <h6 class="modal-title text-muted" id="modal-title-default" > <?=$queries->getGroupCode($sj);?> | <?=$queries->sbjByName($sj);?></h6>
                                            </ol>
                                        </nav>
                                    </div>
                                    <div class="col-lg-6 col-5 text-right" id="btn_agregar_criterio" style="display:none;" >
                                    <!-- <button  class="btn btn-sm btn-neutral" id="btn_new_plan" data-toggle="modal" data-target="#newPlan" >
                                    <i class="fas fa-book"></i>
                                            Calificaciones
                                        </button> -->
                                    <?=$queries->visibilityImportButton($sj,$id_period)?>   
                                    
                                    <button  class="btn btn-sm btn-neutral" id="btn_new_plan" data-toggle="modal" data-target="#newPlan" >
                                            <i class="fas fa-plus"></i>
                                            Añadir criterio de evaluación
                                        </button>
                                        
                                    </div>
                                </div>
                                <h5 class="text-white d-inline-block mb-0">* Elija un periodo:</h5>
                                        <select name="" class="form-control col-3" id="select_periodo">
                                            <option  value="">Periodo:</option>
                                            <?=$queries->periods($id_level_combination);?>
                                        </select>
                                        <br><br>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid mt--6">
                        <div class="row">
                            <div class="col-lg-6">
                                  <div  id="div_criterios" style="display: none">

                                   
                                    <div class="card">
                                    <!-- Card header -->
                                    <div class="card-header">
                                    <!-- Title -->
                                    <h5 class="h3 mb-0">Criterios de Evaluación</h5>
                                    </div>
                                    <div id="div_planes" style="height: 350px" class="card overflow-auto bg-gradient-secondary shadow">
                                    <div id="div_s_and_ass">
                                    <input type="hidden" id="id_period_selected"value="<?=$id_period?>">
                                    <input type="hidden" id="id_assignment" value="<?=$sj?>">
                                    </div>
                                    
                                    <?=$queries->getPlan($sj,$id_period)?>
                                    
                                    </div>
                                    </div>
                                    </div>
                                </div> 

                            <div class="col-lg-6">
                                <div class="card bg-gradient-default shadow" id="div_grafica" style="display:none;">
                                    <div class="card-header bg-transparent">
                                        Título
                                    </div>
                                    <div class="chart">
                                        <canvas id="chart-pie" class="chart-canvas"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
} else {?>
                        <!-- HEADER MATERIAS ESPAÑOL -->
                        <div class="header bg-gradient-default pb-6">
                            <div class="container-fluid">
                                <div class="header-body">
                                    <div class="row align-items-center py-4">
                                        <div class="col-lg-6 col-7">
                                            <h6 class="h2 text-white d-inline-block mb-0">Asignatura</h6>
                                            <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                                                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                                    <li class="breadcrumb-item active"> <i class="fas fa-language"></i></li>
                                                    <li class="breadcrumb-item active" aria-current="page">Español</li>
                                                </ol>
                                            </nav>
                                        </div>
                                        <div class="col-lg-6 col-5 text-right">
                                            <a href="#" class="btn btn-sm btn-neutral">Filtros</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- PAGINA PRINCIPAL -->
                        <div class="container-fluid mt--6">
                            <div class="row">
                                <div class="col">
                                    Diseño de página principal
                                </div>
                            </div>
                        <?php }
?>

                        <!-- MODALS -->
                       
                        <div class="modal fade" id="newPlan" tabindex="-1" aria-labelledby="modal-default" aria-hidden="true">
                            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                                
                                <div class="modal-content">
                                
                                    <div class="modal-header">
                                    
                                    <h3 class="modal-title" id="modal-title-default">NUEVO CRITERIO DE EVALUACIÓN </h3>
                                    <h6 class="modal-title text-muted" id="modal-title-default" ><?=$queries->getGroupCode($sj);?><?=$queries->sbjByName($sj);?></h6>
                                        <button type="button" id="cerrar_mdl_criterio"class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                            
                                    </div>
                                    
                                    <div class="modal-body">
                                    
                                        <form>
                                        <div class="form-group">
                                        <label for="evaluation" class="form-label text-dark">Nombre</label>
                                            <div class="input-group">
                                            <select class="form-control" id="select_eval_name" name="evaluation"  required="required">
                                                <option value="">Seleccione un nombre de criterio</option>
                                                <?=$queries->getEvaluationName($conn)?>
                                            </select>
                                            </div>
                                        <label for="AditionalName" id="LabelAditionalName" style="display: none" class="form-label text-dark">Nuevo nombre</label>
                                            <div class="input-group">
                                            <input type="text" class="form-control" id="AditionalName" style="display: none"  name="AditionalName">
                                            </div>
                                        </div>
                                            
                                        <div class="form-group">  
                                            <label for="percentage" id="" class="form-label text-dark">Asignar porcentaje</label>
                                            <div class="input-group">
                                                    <input type="hidden" id="percentage_asigned" class="form-control">
                                                    <input type="number" name="percentage" id="percentage" class="form-control" placeholder="Ingrese un porcentaje...">
                                            </div>
                                            <h6 id="txt_percentage_asigned" style="color:#fb6340"></h6>
                                        </div>
                                        <div class="form-group">  
                                        <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                                            <div class="input-group">
                                            <input type="date" name="fechaFin" id="fechaFin" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group">  
                                             <label for="type" class="form-label text-dark">Método de captura de evaluación:</label>
                                                <div class="input-group">
                                                <select class="form-control" id="id_criterio">
                                                    <!-- <option value="1">Automático</option> -->
                                                    <option value="0" selected>Manual</option>
                                                </select>
                                                </div>
                                        </div>
                                        
                                        <div class="form-group">
                                        <label for="evaluation" class="form-label text-dark">Tipo de </label>
                                            <div class="input-group">
                                            <select class="form-control" id="select_eval_name" name="evaluation"  required="required">
                                                <option value="">Seleccione una opción</option>
                                                <option value="">1,2,3, ...</option>
                                                <option value="">A, B, C, ...</option>
                                            </select>
                                            </div>
                                        <label for="AditionalName" id="LabelAditionalName" style="display: none" class="form-label text-dark">Nuevo nombre</label>
                                            <div class="input-group">
                                            <input type="text" class="form-control" id="AditionalName" style="display: none"  name="AditionalName">
                                            </div>
                                        </div>

                                        <div class="form-group">  
                                        <label for="" id="" class="form-label text-dark">¿Tomar en cuenta para la calificación final?</label>    
                                            
                                            <ul class="list-group list-group-horizontal list-group-flush">
                                                    <li class="list-group-item">
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" id="check_afectar_calificacion" checked>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item"></li>
                                                    <li class="list-group-item"></li>
                                                    </ul>
                                        <div class="input-group">
                                        </div>
                                        </div>
                                        
                                        <div class="form-group">  
                                        <label for="subcriterios" id="lbl_check_subcriterios" class="form-label text-dark">¿Crear subcriterios?</label>     
                                            
                                        <ul class="list-group list-group-horizontal list-group-flush">
                                                    <li class="list-group-item">
                                                    <label class="custom-toggle">
                                                        <input type="checkbox" id="check_subcriterios">
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>
                                                        </label>
                                                    </li>
                                                    <li class="list-group-item"></li>
                                                    <li class="list-group-item"></li>
                                                    </ul>
                                        <div class="input-group">
                                        </div>
                                        </div>
                                        
                                        <div class="form-group">  
                                        <label for="subcriterios" id="lbl_subcriterios" class="form-label text-dark" style="display: none">Número de sub-criterios</label>
                                       
                                        <div class="input-group">
                                        <input type="number" class="form-control" id="subcriterios" name="subcriterios" onkeypress="return isNumberKey(event)" style="display: none" placeholder="Número de sub-criterios...">
                                            
                                            <input type="hidden" name="assignment" value="<?=$sj?>">
                                            <input type="hidden" name="period" value="<?=$id_period?>">
                                        </div>
                                        </div>
                                            
                                            <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-info" id="btn_guardar_criterio">Agregar</button>
                                            
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                <div class="modal fade" id="modal_elm" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                        <div class="modal-content bg-gradient-white">
                            <div class="modal-header">
                                <h6 class="modal-title text-dark text-lg-center" id="texto_confirmar"></h6>
                                
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form  method="POST" enctype="multipart/form-data">
                                    <input type="hidden" id="id_ev_eliminar" value="">
                                    <input type="hidden" name="subject" value="">
                                    
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" id="cerrar_m_eliminar">Cancelar</button>
                                        <button type="button" class="btn btn-success" id="cont_delete">Continuar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        <div class="modal fade" id="edit_subcriterios" tabindex="-1" aria-labelledby="modal-default" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="modal-title-default">Editar subcriterios de evaluación.</h6>
                    </div>
                    <div class="modal-body">
                        <form>
                        <label for="evaluation" class="form-label text-dark">Asignar nuevo nombre:</label>
                        <div id="div_subcriterios">

                        </div>


                        <button type="button" class="btn btn-primary" id="btn_actualizar_subcriterios">Guardar</button>
                        <button type="button" class="btn btn-secondary" id="cerrar_b_actualizar">Volver</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="import_plan" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-white">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark text-lg-center" id="texto_confirmar">Importar configuración de otro periodo</h6>
                
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <label for="evaluation" class="form-label text-dark">Periodo desde el que desa importar la configuración</label>
                        <select class="form-control" id="select_import_period"   required="required">
                        <option value="">Seleccione el periodo</option>
                        <?=$queries->periodsWithEvPlan($id_level_combination,$sj);?>
                        </select>
                        <input type="hidden" id="import_on_period" value="<?=$id_period?>">
                        <!-- <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                        <input type="date" name="fechaFin" id="fechaFin" class="form-control"> -->
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" id="btn_cancel_import_per_config">Volver</button>
                        <button type="button" class="btn btn-primary" id="btn_import_per_config">Importar</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="export_plan" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                <div class="modal-content bg-gradient-white">
                    <div class="modal-header">
                        <h6 class="modal-title text-dark text-lg-center" id="texto_confirmar">Exportar configuración a otro periodo</h6>
                
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                        <label for="evaluation" class="form-label text-dark">Seleccione los periodos a los se importarrá la configuración:</label>
                        <br>
                        
                        <ul class="list-group">
                            <?=$queries->periodsWithoutEvPlan($id_period,$sj);?>    
                        </ul>    
                        <input type="hidden" id="export_from_period" value="<?=$id_period?>">
                        <input type="hidden" id="id_assignment_export" value="<?=$sj?>">
                        <!-- <label for="fechaFin" class="form-label text-dark">Fecha de cumplimiento</label>
                        <input type="date" name="fechaFin" id="fechaFin" class="form-control"> -->
                        <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary" id="btn_cancel_export_per_config">Volver</button>
                        <button type="button" class="btn btn-primary" id="btn_export_per_config">Exportar</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>