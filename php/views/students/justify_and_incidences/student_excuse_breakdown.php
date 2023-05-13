<?php

$id_student = $_GET['id_student'];
$id_absences_excuse = $_GET['id_absences_excuse'];
if (isset($_GET['id_student']) && isset($_GET['id_absences_excuse'])) {
    $id_student = $_GET['id_student'];
    $id_absences_excuse = $_GET['id_absences_excuse'];
    $getRegisteredExcuses = $attendance->breakdownJustifyDetail($id_student, $id_absences_excuse);
}


?>
<div class="card mb-4">
    <div class="card-body">

        <?php if (!empty($getRegisteredExcuses)) :
            $student_code = $getRegisteredExcuses[0]->student_code;
            $student_name = $getRegisteredExcuses[0]->student_name;
        ?>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h2><?= $student_code ?> | <?= $student_name ?></h2>
                    <h3 class="ml-2">DESGLOSE DE AUSENCIAS ANTICIPADAS</h3>
                    <h2><?= mb_strtoupper($getRegisteredExcuses[0]->excuse_description) ?></h2>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tStudents">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th class="font-weight-bold col-sm-2">Descripción de Justificación</th> -->
                                    <th class="font-weight-bold col-sm-2">Día de aplicación</th>
                                    <th class="font-weight-bold col-sm-2">¿Justificable?</th>
                                    <th class="font-weight-bold col-sd-2">Anular Día</th>
                                    <th class="font-weight-bold col-sm-2">Aplicar en Asistencia</th>
                                    <!-- <th class="font-weight-bold col-sd-2">Comentario de ausencia</th> -->
                                    <!-- <th class="font-weight-bold col-sd-2">Editar</th> -->
                                </tr>
                            </thead>
                            <tbody class="list">

                                <?php foreach ($getRegisteredExcuses as $excuse) :

                                    $check_apply = '';
                                    if ($excuse->apply_excuse == 1) {
                                        $check_apply = 'checked';
                                    }
                                    $check_active = '';
                                    if ($excuse->active_excuse == 1) {
                                        $check_active = 'checked';
                                    }

                                ?>
                                    <tr>
                                        <!-- <td class="font-weight-bold"><?= $excuse->excuse_description ?></td> -->
                                        <td class="font-weight-bold"><?= $excuse->absence_day ?></td>
                                        <td class="font-weight-bold">
                                            <label class="custom-toggle">
                                                <input class="checkAusenciaAplica" id="apply<?= $excuse->id_absences_excuse_breakdown ?>" data-id-breakdown="<?= $excuse->id_absences_excuse_breakdown ?>" type="checkbox" <?= $check_apply ?>>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </td>
                                        <td class="font-weight-bold">
                                            <label class="custom-toggle">
                                                <input class="checkAusenciaActiva" data-id-breakdown="<?= $excuse->id_absences_excuse_breakdown ?>" type="checkbox" <?= $check_active ?>>
                                                <span class="custom-toggle-slider rounded-circle"></span>
                                            </label>
                                        </td>
                                        <td class="font-weight-bold text-center">

                                            <button class="btn btn-icon btn-primary btn-sm updateAttendanceRecords"  data-id-breakdown="<?= $excuse->id_absences_excuse_breakdown ?>" type="button">
                                                <span class="btn-inner--icon"><i class="fa fa-arrows-rotate"></i></span>
                                            </button>
                                        </td>
                                       <!--  <td class="font-weight-bold" id="comment<?= $excuse->id_absences_excuse_breakdown ?>"><?= $excuse->day_absence_comment ?></td> -->
                                        <!-- <td class="font-weight-bold">
                                            <button class="btn btn-icon btn-info btn-sm editExcuseBreakdown" data-toggle="modal" data-target="#editBreakDown" type="button" id="<?= $excuse->id_absences_excuse_breakdown ?>"><span class="btn-inner--icon"><i class="fa fa-pen"></i></span></button>
                                        </td> -->
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php else : ?>
            <div class="card">
                <div class="card-body">
                    <h1>NO SE ENCONTRARON REGISTROS EN LOS PARÁMETROS ESTABLECIDOS</h1>
                </div>

            </div>
        <?php endif; ?>

    </div>
</div>

<?php include_once 'modals/editBreakDown.php'; ?>

<script src="js/functions/students/teachers/export_table.js"></script>
<script src="js/functions/students/teachers/incidents_reports.js"></script>
<script src="js\functions\students\justify_and_incidences\justify_and_incidences.js"></script>