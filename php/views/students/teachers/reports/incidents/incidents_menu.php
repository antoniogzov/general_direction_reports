<div class="container-fluid mt--6">
	<div class="card mb-4">
		<!-- Card header -->
		<!-- <div id="div_select_section">
			<label class="form-control-label" for="id_academic">* Elija una sección</label>
			<form>
				<select class="form-control" name="id_academic" id="id_academic">
					<option selected value="">Elija una opción</option>
				</select>
			</form>
		</div> -->
		<div class="card-header">
			<h3 class="mb-0">Reporte de incidencias</h3>
		</div>

		<!-- Card body -->
		<div class="row">

			<div class="col-sm-6 col-md-4">
				<div class="card-body">
					<div class="row">
						<a href="?submodule=group_incidents" style="width: 100%">
							<div class="card card-stats">

								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">INCIDENCIAS POR GRUPO</h3>
											<span class="h3 text-muted mb-0">Consulte las incidencias generadas por grupo</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/team.png" width="85" />
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="card-body">
					<div class="row">
						<a href="?submodule=general_historic_incidents" style="width: 100%">
							<div class="card card-stats">

								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">HISTÓRICO DE INCIDENCIAS</h3>
											<span class="h3 text-muted mb-0">Consulte todas las incidencias</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/history-paper.png" width="85" />
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="card-body">
					<div class="row">
						<a href="?submodule=week_historic_incidents" style="width: 100%">
							<div class="card card-stats">

								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">HISTÓRICO SEMANAL DE INCIDENCIAS</h3>
											<span class="h3 text-muted mb-0">Consulte todas las incidencias registradas en una semana</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/week-schedule.png" width="85" />
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
			<?php if (($grants & 8)) { ?>
				<div class="col-sm-6 col-md-4">
					<div class="card-body">
						<div class="row">
							<a href="?submodule=teacher_incidents" style="width: 100%">
								<div class="card card-stats">
									<div class="card-body box-head-cyan">
										<div class="row">
											<div class="col">
												<h3 class="card-title text-uppercase font-weight-bold mb-2">INCIDENCIAS POR PROFESOR</h3>
												<span class="h3 text-muted mb-0">Consulte las incidencias generadas por cada profesor</span>
											</div>
											<div class="col-auto">
												<img alt="" src="images/hisrtory_incidents.png" width="95" />
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			
				<!-- 
				<div class="col-sm-6 col-md-4">
					<div class="card-body">
						<div class="row">
						
							<a href="?submodule=historic_incidents" style="width: 100%">
								<div class="card card-stats">
						
									<div class="card-body box-head-cyan">
										<div class="row">
											<div class="col">
												<h3 class="card-title text-uppercase font-weight-bold mb-2">HISTÓRICO DE INCIDENCIAS</h3>
												<span class="h3 text-muted mb-0">Consulte el histórico completo de incidencias.</span>
											</div>
											<div class="col-auto">
												<img alt="" src="images/hisrtory_incidents.png" width="95" />
											</div>
										</div>
									</div>
								</div>
							</a>
						
						</div>
					</div>
				</div> -->

				<div class="col-sm-6 col-md-4">
					<div class="card-body">
						<div class="row">
							<a href="?submodule=excuse_registered" style="width: 100%">
								<div class="card card-stats">
									<div class="card-body box-head-cyan">
										<div class="row">
											<div class="col">
												<h3 class="card-title text-uppercase font-weight-bold mb-2">JUSTIFICACIONES DE INASISTENCIAS</h3>
												<span class="h3 text-muted mb-0">Consulte las justificaciones registradas</span>
											</div>
											<div class="col-auto">
												<img alt="" src="images/promise.png" width="95" />
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			<?php } ?>

		</div>
	</div>
</div>