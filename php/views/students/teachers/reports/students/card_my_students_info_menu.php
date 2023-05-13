<?php
if (($grants & 8)) {
?>

    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">MIS ALUMNOS / INFORMACIÓN PERSONAL DE ALUMNOS</h3>
            </div>
            <!-- Card body -->
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- -->
                            <a href="?submodule=students_directory" style="width: 100%">
                                <div class="card card-stats">
                                    <!-- Card body -->
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">DIRECTORIO GENERAL DE CONTACTO</h3>
                                                <span class="h3 text-muted mb-0">Consulte la información de contacto de sus alumnos.</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/contact-book.png" width="95" />
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

   <!--  <div class="container-fluid mt--6">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="mb-0">Módulo de alumnos coordinador</h3>
            </div> -->
            <!-- Card body -->
           <!--  <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="card-body">
                        <div class="row">
                           
                            <a href="?submodule=student_list" style="width: 100%">
                                <div class="card card-stats">
                                  
                                    <div class="card-body box-head-cyan">
                                        <div class="row">
                                            <div class="col">
                                                <h3 class="card-title text-uppercase font-weight-bold mb-2">LISTA DE ALUMNOS / GENERAL</h3>
                                                <span class="h3 text-muted mb-0">Consulte la lista de alumnos.</span>
                                            </div>
                                            <div class="col-auto">
                                                <img alt="" src="images/student.png" width="95" />
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
    </div> -->
 
<?php
}
