<?php
$array_level_combinations = array();

if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assignment))) {
    $id_level_combination = $id_level_combination->id_level_combination;
    $periods = $helpers->getAllPeriodsByLevelCombination($id_level_combination);
}

$id_period_calendar = '';
if (isset($_GET['id_period_calendar'])) {
    $id_period_calendar = $_GET['id_period_calendar'];
    if (!empty($periods)) {
        $id_period_calendar = $_GET['id_period_calendar'];
        $infoPeriod = $helpers->getPeriodByID($id_period_calendar);
        if (!empty($infoPeriod)) {
            $infoPeriod = $infoPeriod[0];
            $allow_editing_grades = $infoPeriod->allow_editing_grades;
            $grade_closing_date = $infoPeriod->grade_closing_date;
            $arr_grade_closing_date = explode(" ", $grade_closing_date);
            $arr_date_grade_closing_date = explode("-", $arr_grade_closing_date[0]);
            $date_grade_closing_date = $arr_date_grade_closing_date[2] . "/" . $arr_date_grade_closing_date[1] . "/" . $arr_date_grade_closing_date[0];

            $today_date = date('Y-m-d H:i:s');
            $editable = 1;
            if ($allow_editing_grades == 0) {
                $editable = 0;
            }
            $td_class = '';
            $select_prop = 'disabled';
            if ($editable == 1) {
                $td_class = 'contenteditable="true"';
                $select_prop = '';
            }
        }
    }
}



?>
<style>
    .Proccesed {
        background-color: rgba(177, 255, 176, 0.4);
    }

    .Proccesing {
        background-color: rgba(238, 189, 255, 0.4);
    }
</style>
<?php
include 'card_select_periods.php';
include 'php/models/expected_learnings.php';


if (isset($_GET['id_period_calendar'])) {
    //--- --- ---//
    $expected_learnings = new ExpectedLearnings;
    //--- --- ---//
    $getExistCatalogAssignments = $expected_learnings->getPeriodCatalog($id_assignment, $id_period_calendar);
    if (!empty($getExistCatalogAssignments)) {
        foreach ($getExistCatalogAssignments as $catalog) {
            $expected_learnings->checkStructureCatalog($id_assignment, $id_period_calendar, $catalog->id_expected_learning_catalog);
        }
        $listStudents = $expected_learnings->getStudentsByGroupAssignment($id_assignment);
        if (!empty($listStudents)) {
            include_once 'show_expected_learnings_students.php';
            include_once 'modals/addDocumentCatalog.php';
            include_once 'modals/addComentaryAE.php';
            include_once 'modals/editComentaryAE.php';
        }
    } else { ?>
        <div class="card">
            <h2 class="text-center p-4">Aún no hay una configuración para este periodo</h2>
        </div>
<?php }
}
?>



<script src="js\functions\expected_learning\qualif_expected_learnings.js" async></script>



<?php
if (isset($_GET['id_period_calendar'])) {
    $grade_closing_date_format = date("d/m/Y", strtotime($grade_closing_date));
    if ($grade_closing_date < $today_date) : ?>
        <?php if ($non_qualified > 0) : ?>
            <?php $getCommentPeriodnotQualified = $expected_learnings->getCommentPeriodnotQualified($_GET['id_assignment'], $id_period_calendar); ?>
            <?php if (!empty($getCommentPeriodnotQualified)) : ?>
                <?php $comment_period_not_qualified = $getCommentPeriodnotQualified[0]->comment;
                $comment_period_not_qualified = str_replace("\n", "<br>", $comment_period_not_qualified);
                $id_comment_period_not_qualified = $getCommentPeriodnotQualified[0]->id_comment_period_not_qualified; ?>
                <script>
                    $(document).ready(function() {
                        var comment = "<?= $comment_period_not_qualified ?>";
                        comment = comment.replace("<br>", "\n");
                        var id_comment_period_not_qualified = "<?= $id_comment_period_not_qualified ?>";
                        console.log(comment);

                        $(".uploadComentaryAE").attr("data-id-comment", id_comment_period_not_qualified);
                        htmldiv = '<textarea class="form-control" id="edit_commentary" rows="3">' + comment + '</textarea>';

                        $("#div_commentary_ae").html(htmldiv);
                        Swal.fire({
                            icon: 'info',
                            title: 'Ya no puede editar esta evaluación',
                            text: 'La evaluación ya ha sido cerrada el día <?= $grade_closing_date_format ?>. \n\n Únicamente puede ediar los motivos por los que no se alcanzaron a cubrir los aprendizajes esperados del periodo.',
                        }).then((result) => {
                            $('#btnEditComentaryAE').show();
                        })
                    });
                </script>
            <?php else : ?>
                <script>
                    $(document).ready(function() {
                        $('.addCommentAE').attr("data-id-assignment", <?= $_GET['id_assignment'] ?>);
                        $('.addCommentAE').attr("data-id-period-calendar", <?= $id_period_calendar ?>);
                        Swal.fire({
                            icon: 'info',
                            title: 'Ya no puede editar esta evaluación',
                            text: 'La evaluación ya ha sido cerrada el día <?= $grade_closing_date_format ?>. \n\n Únicamente puede ingresar los motivos por los que no se alcanzaron a cubrir los aprendizajes esperados del periodo.',
                        }).then((result) => {
                            $('#btnAddComentaryAE').show();
                        });
                    });
                </script>
            <?php endif; ?>
        <?php else : ?>
            <script>
                $(document).ready(function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Ya no puede editar esta evaluación',
                        text: 'La evaluación ya ha sido cerrada el día <?= $grade_closing_date_format ?>',
                    })
                });
            </script>
        <?php endif; ?>
    <?php else : ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: "info",
                    title: "EVITE LA PERDIDA DE DATOS",
                    html: '<p>Antes de cerrar la ventana de calificaciones de AE, por favor asegurese que todas las celdas modificadas se encuentren en color verde.</p><br><img src="images/info_cfae.gif" width="300" height="160">',
                    timer: 12000,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCloseButton: true,
                    showConfirmButton: false,
                });
            });
        </script>
    <?php endif;
} else { ?>

<?php
}
?>