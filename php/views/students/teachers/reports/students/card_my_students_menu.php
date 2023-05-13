<?php
if (($grants & 8)) {
?>

    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">MÓDULO MIS ALUMNOS</h3>
            </div>
            <!-- Card body -->
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- -->
                            <a href="?submodule=student_academic" style="width: 100%">
                                <div class="card card-stats">
                                    <!-- Card body -->
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">CONTROL ESCOLAR</h3>
                                                <span class="h3 text-muted mb-0">Consulte información escolar de alumnos.</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/education.png" width="95" />
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
                            <!-- -->
                            <a href="?submodule=students_information" style="width: 100%">
                                <div class="card card-stats">
                                    <!-- Card body -->
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">INFORMACIÓN PERSONAL</h3>
                                                <span class="h3 text-muted mb-0">Consulte información personal de alumnos.</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/website.png" width="95" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <!-- -->
                        </div>
                    </div>
                </div>
            </div>
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
                <h3 class="mb-0">MÓDULO MIS ALUMNOS</h3>
            </div>
            <!-- Card body -->
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- -->
                            <a href="?submodule=student_academic" style="width: 100%">
                                <div class="card card-stats">
                                    <!-- Card body -->
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">INFORMACIÓN ACADÉMICA</h3>
                                                <span class="h3 text-muted mb-0">Consulte información académica referente a los alumnos.</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/academic.png" width="95" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <!-- -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
}
