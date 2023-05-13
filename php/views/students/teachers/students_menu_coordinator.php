<div class="container-fluid mt--6">
	<div class="card mb-4">
		<!-- Card header -->
		<div class="card-header">
			<h3 class="mb-0">Módulo Alumnos</h3>
		</div>
		<!-- Card body -->
		<div class="row">
			<div class="col-sm-6 col-md-4">
				<div class="card-body">
					<div class="row">
						<!-- -->
						<a href="?submodule=attendance" style="width: 100%">
							<div class="card card-stats">
								<!-- Card body -->
								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">PASE DE LISTA</h3>
											<span class="h3 text-muted mb-0">Consulte la lista de alumnos.</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/attendant_student.png" width="95" />
										</div>
									</div>
								</div>
							</div>
						</a>
						<!-- -->
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="card-body">
					<div class="row">

						<a href="?submodule=edit_week_attendance" style="width: 100%">
							<div class="card card-stats">
								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">EDITAR ASISTENCIA SEMANAL</h3>
											<span class="h3 text-muted mb-0">Editar la asistencia semanal de un grupo.</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/edit_calendar.png" width="95" />
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>

			<!-- <div class="col-sm-6 col-md-4">
				<div class="card-body">
					<div class="row">

						<a href="?submodule=incidents" style="width: 100%">
							<div class="card card-stats">
								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">INCIDENCIAS</h3>
											<span class="h3 text-muted mb-0">Registrar incidencias de alumnos.</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/clipboard.png" width="95" />
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

						<a href="?submodule=justify_and_incidences" style="width: 100%">
							<div class="card card-stats">
								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">SEGUIMIENTOS</h3>
											<span class="h3 text-muted mb-0">Faltas, suspensiones e incidencias</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/seguimiento.png" width="75" />
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
						<a href="?submodule=evaluations_mda" style="width: 100%">
							<div class="card card-stats">
								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">EVALUACIONES MDA</h3>
											<span class="h3 text-muted mb-0">Consulte las evaluaciones MDA</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/mda.png" width="90" />
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>

			<?php if ($grants == 31) : ?>
				<div class="col-sm-6 col-md-4">
					<div class="card-body">
						<div class="row">
							<a href="?submodule=student_info" style="width: 100%">
								<div class="card card-stats">
									<div class="card-body box-head-cyan">
										<div class="row">
											<div class="col">
												<h3 class="card-title text-uppercase font-weight-bold mb-2">FICHA DE ALUMNOS</h3>
												<span class="h3 text-muted mb-0">Consultar fichas de alumno</span>
											</div>
											<div class="col-auto">
												<img alt="" src="images/student_information.png" width="95" />
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-sm-6 col-md-4">
				<div class="card-body">
					<div class="row">

						<a href="?submodule=admisions_gest" style="width: 100%">
							<div class="card card-stats">
								<div class="card-body box-head-cyan">
									<div class="row">
										<div class="col">
											<h3 class="card-title text-uppercase font-weight-bold mb-2">GESTIÓN DE ADMISIONES</h3>
											<span class="h3 text-muted mb-0">Gestión de admisiones</span>
										</div>
										<div class="col-auto">
											<img alt="" src="images/admisionlist.png" width="95" />
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>