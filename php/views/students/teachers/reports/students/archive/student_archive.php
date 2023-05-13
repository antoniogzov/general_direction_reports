<?php
$hperiods = array();
$eperiods = array();
if (isset($_GET['id_student'])) {
    $id_student = $_GET['id_student'];
    $StudentInfo = $archives->getStudentInfo($id_student);
    $StudentGroups = $archives->GetGroupsStudent($id_student);
}

?>
<script>
    function loading() {
        Swal.fire({
            text: "Cargando...",
            html: '<img src="images/loading_iteach.gif" width="300" height="300">',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showCloseButton: false,
            showCancelButton: false,
            showConfirmButton: false,
        });
    }
    loading();
</script>

<div class="card">
    <div class="card-body">
        <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 active" id="infoGeneral-tab" data-toggle="tab" href="#infoGeneral" role="tab" aria-controls="infoGeneral" aria-selected="true"><i class="fas fa-info-circle"></i> Info. General</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0" id="infoAcademica-tab" data-toggle="tab" href="#infoAcademica" role="tab" aria-controls="infoAcademica" aria-selected="false"><i class="fas fa-address-card"></i> Info. Académica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0" id="Calificaciones-tab" data-toggle="tab" href="#Calificaciones" role="tab" aria-controls="Calificaciones" aria-selected="false"><i class="fas fa-school"></i> Calificaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0" id="cardAsistencias-tab" data-toggle="tab" href="#cardAsistencias" role="tab" aria-controls="cardAsistencias" aria-selected="false"><i class="fas fa-calendar-check"></i> Asistencias</a>
                </li>
            </ul>
        </div>
        <div class="card shadow">
            <div class="card-body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="infoGeneral" role="tabpanel" aria-labelledby="infoGeneral-tab">
                        <?php include 'info_general.php'; ?>
                    </div>
                    <div class="tab-pane fade " id="infoAcademica" role="tabpanel" aria-labelledby="infoAcademica-tab">
                        <?php include 'infoAcademica.php'; ?>
                    </div>
                    <div class="tab-pane fade " id="Calificaciones" role="tabpanel" aria-labelledby="Calificaciones-tab">
                        <?php include 'calificaciones.php'; ?>
                    </div>
                    <div class="tab-pane fade " id="cardAsistencias" role="tabpanel" aria-labelledby="cardAsistencias-tab">
                        <?php include 'asistencias.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    Swal.close();

    function getAssistanceDetailsNew(arr_ids_attendance_index, id_student) {

        data = "";

        var day_list = arr_ids_attendance_index.split("-");
        var c = day_list.length;
        if (c > 1) {

            $.ajax({
                    url: "php/controllers/students.php",
                    method: "POST",
                    data: {
                        mod: "getAttendanceDetailsJSON",
                        ids_attendance_index: arr_ids_attendance_index,
                        id_student: id_student,
                    },
                })
                .done(function(info) {
                    info = $.parseJSON(info);
                    // console.log(info);
                    Swal.fire({
                        title: "<h2>DETALLE DE ASISTENCIAS / AUSENCIAS</h2>",
                        icon: "info",
                        html: info.data,
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText: "Aceptar",
                    });
                })
                .fail(function(message) {
                    VanillaToasts.create({
                        title: "Error",
                        text: "Ocurrió un error, intentelo nuevamente",
                        type: "error",
                        timeout: 1200,
                        positionClass: "topRight",
                    });
                });
        } else {
            Swal.fire({
                title: "<strong>DETALLE DE AUSENCIAS</strong>",
                icon: "info",
                html: "<h3>Este alumno no cuenta con inasistencias</h3>",
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: "Aceptar",
            });
        }
    }

    function getCriteriaDetails(id_grade_period, id_student, id_assignment, promedio) {

        if (id_grade_period != '-') {

            $.ajax({
                    url: "php/controllers/students.php",
                    method: "POST",
                    data: {
                        mod: "getCriteriaDetails",
                        id_grade_period: id_grade_period,
                        id_student: id_student,
                        id_assignment: id_assignment,
                        promedio: promedio,
                    },
                })
                .done(function(info) {
                    info = $.parseJSON(info);
                    // console.log(info);
                    Swal.fire({
                        title: "<h2>DESGLOSE DE CALIFICACIÓN</h2>",
                        icon: "info",
                        html: info.data,
                        showCloseButton: true,
                        focusConfirm: false,
                        confirmButtonText: "Aceptar",
                    });
                })
                .fail(function(message) {
                    VanillaToasts.create({
                        title: "Error",
                        text: "Ocurrió un error, intentelo nuevamente",
                        type: "error",
                        timeout: 1200,
                        positionClass: "topRight",
                    });
                });
        } else {
            Swal.fire({
                title: "<strong>DESGLOSE DE CALIFICACIÓN</strong>",
                icon: "info",
                html: "<h3>Aún no se asigna calificación para este periodo</h3>",
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: "Aceptar",
            });
        }
    }
</script>
<script src="js\functions\student_archive\student_archive.js"></script>