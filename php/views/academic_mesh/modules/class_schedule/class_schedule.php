<?php
if (($grants & 8)) {
    $ClassSchendule = new ClassSchendule;

    $days = $ClassSchendule->getDays();

    $blocks = $ClassSchendule->getBlocks();
}

include 'card_select.php';
?>

<?php if ($grants & 8) :
    if (isset($_GET['id_academic']) && isset($_GET['id_teacher_sbj'])) {
?>
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
        <link rel="stylesheet" type="text/css" herf="http.//fonts.googleapis.com/cssfamily=Tangerine">
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="vendor/tablefilter/tablefilter.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <div class="card mb-4">
            <div class="row">
                <div class="col">
                    <div class="card col-md-12">
                        <div class="card-header border-0">
                            <script>
                                $(document).ready(function() {
                                    var teacher_name_subject = $("#id_teacher_sbj option:selected").text();
                                    $("#title_horario").text("HORARIO PARA: " + teacher_name_subject);
                                })
                            </script>
                            <h3 id="title_horario"></h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tableSubjectsCoordinator" style="table-layout: fixed;">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold text-center"></th>
                                        <?php foreach ($days as $day) : ?>
                                            <th class="display-1" style="color: white !important; font-weight: bold; font-size: 15px; background-color:#4299e1 !important"><?= $day->long_name ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    <?php foreach ($blocks as $block) : ?>
                                        <tr>
                                            <td class="display-1" style="text-align: center; font-size: 15px; vertical-align: middle;">
                                                Bloque <br>
                                                <p style="font-size:30px !important; font-weight: bold;"><?= $block->no_block ?></p>
                                            </td>

                                            <?php foreach ($days as $day) : ?>
                                                <td class="display-1"">
                                            <div class=" card">
                                                    <button type="button" data-id-academic-level="<?= $_GET['id_academic_level'] ?>" data-id-period-calendar="<?= $_GET['id_period'] ?>" data-toggle="modal" data-target="#setSubject" data-name-day="<?= $day->long_name ?>" data-no-block="<?= $block->no_block ?>" data-id-day="<?= $day->id_days ?>" data-id-block="<?= $block->id_class_block ?>" class="btn btn-outline-secondary  btn-block btnSetSubject">
                                                        <?php $getDayBlockAssignment = $ClassSchendule->getDayBlockAssignment($day->id_days, $block->id_class_block, $_GET['id_period']); ?>
                                                        <?php if (empty($getDayBlockAssignment)) : ?>
                                                            <p style="font-weight: bold; font-size:13px;" id="ButtonAssignmentBlock<?= $block->id_class_block ?>Day<?= $day->id_days ?>">Asignar materia <i class="fas fa-plus-circle"></i></p>
                                                        <?php else :
                                                            $getDayBlockAssignment = $getDayBlockAssignment[0];
                                                            $color_hex = $getDayBlockAssignment->color_hex;
                                                            $txt_btn = replaceString($getDayBlockAssignment->name_subject);
                                                        ?>
                                                            <p style="font-weight: bold; font-size:13px; color:#<?=$color_hex ?> !important;" id="ButtonAssignmentBlock<?= $block->id_class_block ?>Day<?= $day->id_days ?>"><?= $getDayBlockAssignment->group_code ?> <br> <?= $txt_btn ?></p>
                                                        <?php endif; ?>
                                                    </button>
                        </div>
                        <div class=" card">
                            <button type="button" data-id-academic-level="<?= $_GET['id_academic_level'] ?>" data-id-period-calendar="<?= $_GET['id_period'] ?>" data-toggle="modal" data-target="#setClassroom" data-name-day="<?= $day->long_name ?>" data-no-block="<?= $block->no_block ?>" data-id-day="<?= $day->id_days ?>" data-id-block="<?= $block->id_class_block ?>" class="btn btn-outline-secondary  btn-block btnSetClassroom">

                                <?php $getDayBlockClassroom = $ClassSchendule->getDayBlockClassroom($day->id_days, $block->id_class_block, $_GET['id_period']); ?>
                                <?php if (empty($getDayBlockClassroom)) : ?>
                                    <p style="font-weight: bold; font-size:13px;" id="ButtonClassroomBlock<?= $block->id_class_block ?>Day<?= $day->id_days ?>">Asignar aula <i class="fas fa-plus-circle"></i></p>
                                <?php else :
                                                    $getDayBlockClassroom = $getDayBlockClassroom[0];
                                ?>
                                    <p style="font-weight: bold; font-size:13px;" id="ButtonClassroomBlock<?= $block->id_class_block ?>Day<?= $day->id_days ?>"><?= $getDayBlockClassroom->name_classroom ?></p>
                                <?php endif; ?>
                            </button>
                        </div>
                        </td>
                    <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>

                </tfoot>
                </table>
                    </div>
                </div>
            </div>
        </div>
        </div>

<?php
    }
endif;
function replaceString($string)
{
    $str_final = "";
    $count = 0;
    for ($i = 0; $i < strlen($string); $i++) {
        $count++;
        if ($count > 18) {

            $count = 0;
            if ($string[$i] == " ") {
                $str_final .= "<br>";
            } else {
                $str_final .= $string[$i] . "-" . "<br>";
            }
        } else {
            $str_final .= $string[$i];
        }
    }
    return $str_final;
}

include 'modals/setSubject.php';
include 'modals/getAulas.php';

?>
<script src="js\functions\class_schedules\class_schendule.js">
</script>