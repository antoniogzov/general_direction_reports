<?php
if (($grants & 8)) {
?>

    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="mb-0">Módulo de reportes AE</h3>
            </div>
            <div class="row">

                <div class="col-sm-6 col-md-4">
                    <div class="card-body">
                        <div class="row">
                            <a href="?submodule=reports_ae" style="width: 100%">
                                <div class="card card-stats">
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">DIAGRAMAS DE AE</h3>
                                                <span class="h3 text-muted mb-0">Consulte los aprendizajes esperados</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/organigrama.png" width="90" />
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
                            <a href="?submodule=criteria_reports_ae" style="width: 100%">
                                <div class="card card-stats">
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">APRENDIZAJES ESPERADOS / TABLAS</h3>
                                                <span class="h3 text-muted mb-0">Consulte los criterios de aprendizajes esperados</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/acceptance.png" width="90" />
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
                            <a href="?submodule=mutual_criteria_reports_ae" style="width: 100%">
                                <div class="card card-stats">
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">APRENDIZAJES ESPERADOS COMUNES</h3>
                                                <span class="h3 text-muted mb-0">Consulte los criterios comunes de materias de aprendizajes esperados</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/search_criteria.png" width="90" />
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
                            <a href="?submodule=programmatic_advance" style="width: 100%">
                                <div class="card card-stats">
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">AVANCE PROGRAMÁTICO</h3>
                                                <span class="h3 text-muted mb-0">Consulte el avance programático de aprendizajes esperados</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/avance.png" width="90" />
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

    <?php
} else if ($grants & 4) {
    //--- --- ---//
    ?>

        <div class="container-fluid mt--6">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="mb-0">Módulo de reportes AE</h3>
                </div>
                <div class="row">

                    <div class="col-sm-6 col-md-4">
                        <div class="card-body">
                            <div class="row">
                                <a href="?submodule=reports_ae" style="width: 100%">
                                    <div class="card card-stats">
                                        <div class="card-body box-head-cyan">
                                            <div class="row">
                                                <div class="col">
                                                    <h3 class="card-title text-uppercase font-weight-bold mb-2">DIAGRAMAS DE AE</h3>
                                                    <span class="h3 text-muted mb-0">Consulte los aprendizajes esperados</span>
                                                </div>
                                                <div class="col-auto">
                                                    <img alt="" src="images/organigrama.png" width="90" />
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
                                <a href="?submodule=criteria_reports_ae" style="width: 100%">
                                    <div class="card card-stats">
                                        <div class="card-body box-head-cyan">
                                            <div class="row">
                                                <div class="col">
                                                    <h3 class="card-title text-uppercase font-weight-bold mb-2">APRENDIZAJES ESPERADOS / TABLAS</h3>
                                                    <span class="h3 text-muted mb-0">Consulte los criterios de aprendizajes esperados</span>
                                                </div>
                                                <div class="col-auto">
                                                    <img alt="" src="images/acceptance.png" width="90" />
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
                                <a href="?submodule=mutual_criteria_reports_ae" style="width: 100%">
                                    <div class="card card-stats">
                                        <div class="card-body box-head-cyan">
                                            <div class="row">
                                                <div class="col">
                                                    <h3 class="card-title text-uppercase font-weight-bold mb-2">APRENDIZAJES ESPERADOS COMUNES</h3>
                                                    <span class="h3 text-muted mb-0">Consulte los criterios comunes de materias de aprendizajes esperados</span>
                                                </div>
                                                <div class="col-auto">
                                                    <img alt="" src="images/search_criteria.png" width="90" />
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
