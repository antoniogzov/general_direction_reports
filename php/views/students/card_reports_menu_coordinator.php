<?php
if (($grants & 8)) {
?>
    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Módulo de reportes coordinador</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <!-- -->
                    <div class="col-md-4">
                        <a href="?submodule=attendance_report">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body box-head-cyan">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="card-title text-uppercase font-weight-bold mb-2">ASISTENCIA / AUSENCIA POR PERIODO</h3>
                                            <span class="h3 text-muted mb-0">Consulte la asistencia registrada</span>
                                        </div>
                                        <div class="col-auto">
                                            <img alt="" src="images/attendance_report.png" width="95" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- -->
                </div>
            </div>
            <!-- <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <a href="?submodule=reports">
                        <div class="card card-stats">
                            <div class="card-body box-head-cyan">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="card-title text-uppercase font-weight-bold mb-2">ALUMNOS</h3>
                                        <span class="h3 text-muted mb-0">Consulte la información de los alumnos.</span>
                                    </div>
                                    <div class="col-auto">
                                        <img alt="" src="images/student.png" width="85" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div> -->
        </div>
    </div>

<?php
} else if ($grants & 4) {
    //--- --- ---//
?>

    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">Módulo de reportes</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <div class="row">
                    <!-- -->
                    <div class="col-md-4">
                        <a href="?submodule=attendance_report">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body box-head-cyan">
                                    <div class="row">
                                        <div class="col">
                                            <h3 class="card-title text-uppercase font-weight-bold mb-2">ASISTENCIA / AUSENCIA POR PERIODO</h3>
                                            <span class="h3 text-muted mb-0">Consulte la asistencia registrada</span>
                                        </div>
                                        <div class="col-auto">
                                            <img alt="" src="images/attendance_report.png" width="95" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- -->
                </div>
            </div>
            <!-- <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <a href="?submodule=reports">
                        <div class="card card-stats">
                            <div class="card-body box-head-cyan">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="card-title text-uppercase font-weight-bold mb-2">ALUMNOS</h3>
                                        <span class="h3 text-muted mb-0">Consulte la información de los alumnos.</span>
                                    </div>
                                    <div class="col-auto">
                                        <img alt="" src="images/student.png" width="85" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div> -->
        </div>
    </div>

<?php
}
