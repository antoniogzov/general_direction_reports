<?php
include 'card_select_justify_and_incidences.php';

$listStudent = array();
$listIncidents = array();
$today = date('Y-m-d');
if (isset($_GET['type_report'])) {
    $today_date_time = date('Y-m-d 00:00:00');
}

if (isset($_GET['type_report'])) {
    if (($grants & 8)) {
        $listStudent = $attendance->getAllStudentsCoordinator();
        if (!empty($listStudent)) {
            $first_student = $listStudent[0]->id_student;
            $group_id = $listStudent[0]->group_id;

            $IncidentsClasif = $attendance->getIncidentsClasifications($group_id);
            $getSubjectsByGroup = $attendance->getSubjectsByGroup($group_id);
        }
    } else {
        $listStudent = $attendance->getAllStudentsTeacher();
        if (!empty($listStudent)) {
            $first_student = $listStudent[0]->id_student;
            $group_id = $listStudent[0]->group_id;

            $IncidentsClasif = $attendance->getIncidentsClasifications($group_id);
            $getSubjectsByGroup = $attendance->getSubjectsByGroup($group_id);
        }
    }
}
?>

<?php if (!empty($listStudent)) : ?>
    <script src="vendor/tablefilter/tablefilter.js"></script>
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

            <div class="row">
                <div class="col-md-12 container-list-students">
                    <?php if ($_GET['type_report'] == '1') : ?>
                        <h3 class="ml-2">REGISTRAR / JUSTIFICAR FALTAS</h3>
                        <?php include 'registrar_faltas.php' ?>
                    <?php else : ?>
                        <?php if ($_GET['type_report'] == '2') : ?>
                            <h3 class="ml-2">SUSPENSIÓN DE ALUMNOS</h3>
                            <?php include 'suspension_alumnos.php' ?>
                        <?php endif; ?>
                        <?php if ($_GET['type_report'] == '3') : ?>
                            <h3 class="ml-2">INCIDENCIAS DE ALUMNOS</h3>
                            <?php include 'registrar_incidencias.php' ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    
<script>
    var tf = new TableFilter(document.querySelector("#tStudents"), {
        base_path: "../general/js/vendor/tablefilter/tablefilter/",
        col_2: "select",
        col_3: "none",
        col_4: "none",
        col_5: "none",
        col_6: "none",
        paging: {
            results_per_page: ["Registros por página: ", [10, 25, 50, 100]],
        },
        rows_counter: true,
        btn_reset: true,
    });
    tf.init();

    $('#tStudents').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'colvis',
            'excel',
            'print'
        ]
    });
</script>
<?php else : ?>
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div id="student_list_container">

                </div>
            </div>
        </div>
    </div>
    </div>

<?php endif; ?>
<?php
include 'modals/seguimientoIncidencias.php';

include 'modals/modalSuspend.php';
include 'modals/modalBreakdown.php';
include 'modals/seguimientoInasistencia.php';
include 'modals/modalIncidentsBreakdown.php';
include 'modals/addAbsenceDocument.php';
include 'modals/modalJustify.php';
include 'modals/documentAbsenceList.php';
include dirname(__DIR__, 1) . '/teachers/incident_modal.php';

?>
<script src="js\functions\students\justify_and_incidences\justify_and_incidences.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="vendor/bootstrap-datepicker/dist/js/moment.min.js"></script>
<script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- DATE PICKER COORDINADOR -->

<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>

<script>
    Swal.close();
</script>