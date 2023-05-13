<?php
include_once dirname(__FILE__, 3) . '/controllers/models_calc.php';
$existingConf = verifyExistingModelsConfig($_GET['id_assignment'], $_GET['id_period']);
?>
<!-- -->
<!-- -->
<div class="row">
	<div class="col-md-12">
		<div class="card-wrapper" id="div_criterios">
			<div class="card">
				<!-- Card header -->
				<div class="card-header">
					<!-- Title -->
					<h5 class="h3 mb-0">Modelos de cálculo</h5>
				</div>
				<div class="card-body">
					<?php if(empty($existingConf)): ?>
						<?php $modelsCalc = getCalculationmodels(); ?>
						<?php if(!empty($modelsCalc)): ?>
							<h3 class="text-center">Elija el modelo de cálculo que desea agregar a la asignatura:</h3>
							<div class="row mt-5">
								<?php foreach($modelsCalc AS $mdl): ?>
									<div class="col-sm-6">
										<div id="cd-<?= $mdl->operation_model_id ?>" class="card card-slct">
											<div class="card-body text-center" role="button">
												<h5 class="card-title">
													<?= $mdl->name_operation_model ?>
												</h5>
												<img style="width: 100%; height: 90%;" src="images/models_calc/<?= $mdl->operation_model_img ?>" />
											</div>
											<button type="button" id="<?= $mdl->operation_model_id ?>" class="btn btn-success btn_apply" data-name-function="<?= $mdl->name_function_php ?>" data-id-assignment="<?= $_GET['id_assignment'] ?>" disabled style="display: none;">Aplicar modelo</button>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						<?php else: ?>
							<h1 class="text-center">ACTUALMENTE NO HAY MODELOS DE CÁLCULO PARA ELEGIR</h1>
						<?php endif; ?>	
					<?php else: ?>
						<h1 class="text-center">ACTUALMENTE YA TIENE UN MODELO CONFIGURADO</h1>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>