<div class="container-fluid mt--6">
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Módulo Alumnos</h3>
        </div>
        <!-- Card body -->
        <div class="row">

            <?php if ($grants == 15 || $grants == 31) : ?>
                <div class="col-sm-6 col-md-4">
                    <div class="card-body">
                        <div class="row">
                            <a href="?submodule=student_without_group" style="width: 100%">
                                <div class="card card-stats">
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">ASIGNAR GRUPOS</h3>
                                                <span class="h3 text-muted mb-0">Asigne alumnos a un grupo</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/alumnos_sin_asignar.png" width="95" />
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

                            <a href="?submodule=admin_students" style="width: 100%">
                                <div class="card card-stats">
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">ADMINISTRAR ALUMNOS</h3>
                                                <span class="h3 text-muted mb-0">Administrar mis alumnos</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/admin_stud.png" width="95" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>