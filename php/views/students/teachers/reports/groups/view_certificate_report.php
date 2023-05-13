<?php
$listStudent = array();
$listIncidents = array();
if (isset($_GET['id_group'])) {
    $listStudent = $attendance->getListStudentByGroup($id_group);
    $listIncidents = $attendance->getListIncidents();
    $id_period = $_GET['id_period'];
    $SubjectsInfo = $groupsReports->getSubjects($id_group, $id_academic_area);
    $num_subjects = count($SubjectsInfo);
    $num_students = count($listStudent);
    $general_prom = 0;
    $general_sum = 0;
    $str_aca = "";
    $id_area_Aca = $_GET['id_academic'];
    if ($id_area_Aca == 1) {
        $str_aca = "esp";
    } else {
        $str_aca = "heb";
    }
}
?>


<?php if (!empty($listStudent)) : ?>

    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-md-12 container-list-students">
                    <h3 class="ml-2">CONSULTA Y DESCARGA DE BOLETAS</h3>
                    <h4 id="group_period" class="ml-2"></h4>
                    <h5 class="heading mb-0 ml-2">Alumnos: <?= $num_students ?></h5>
                    <script type="text/javascript" src="https://raw.github.com/Stuk/jszip/master/jszip.js"></script>
                    <script>
                        var txt_group = $('#id_group option:selected').text();
                        var txt_period = $('#id_period option:selected').text();
                        $('#group_period').text('');
                        $('#group_period').text(txt_group + ' | Periodo:' + txt_period);
                    </script>
                    <br>
                    <a href="?submodule=export_zip&id_academic=<?= $str_aca ?>&id_group=<?= $_GET['id_group'] ?>&id_period=<?= $id_period ?>" target="_blank"><button class="btn btn-icon btn-primary" type="button" id="export_zip" data-toggle="tooltip" data-placement="top" title="Archivo *.zip">
                            <span class="btn-inner--icon"><i class="fas fa-file-archive"></i></span>
                            <span class="btn-inner--text">Generar archivo ZIP</span>
                        </button></a>

                    <br><br>
                    <div class="table-responsive">
                        <table class="table align-items-center table-flush" id="tCertificate">
                            <thead class="thead-light">
                                <tr>
                                    <th style="padding-left: 1px !important; padding-right:1px !important;" class="text-center font-weight-bold ">CÓD. ALUMNO</th>
                                    <th style="padding-left: 1px !important; padding-right:1px !important;" class="text-center font-weight-bold ">NOMBRE</th>
                                    <th style="padding-left: 1px !important; padding-right:1px !important;" class="text-center font-weight-bold ">PDF BOLETA</th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php
                                if (isset($_GET['id_ciclo']) && !empty($_GET['id_ciclo'])) {
                                    $path = $groups->get_cicle_path($_GET['id_ciclo'])->path;
                                    $path = str_replace('\\', '/', $path);
                                    var_dump($path);
                                }
                                ?>
                                <?php foreach ($listStudent as $student) :
                                    $sum_per = 0;
                                    $prom_per = 0;
                                ?>
                                    <tr>
                                        <td style="padding-left: 1px !important; padding-right:1px !important;" class="text-center"><?= strtoupper($student->student_code) ?></td>
                                        <td style="padding-left: 1px !important; padding-right:1px !important;" class="text-center"><?= strtoupper($student->name_student) ?></td>
                                        <?php
                                        $link = "/periodo_" . $id_period . "/" . $str_aca . "/" . "cuantitativa/" . strtoupper($student->student_code) . ".pdf";
                                        if (isset($path)) {
                                            $archivo_pdf = $path . $link;
                                            $href = '/boletas/' . substr($path, 11) . $link;
                                        } else {
                                            $archivo_pdf = dirname(__DIR__, 10) . "/boletas" . $link;
                                            $href = "/boletas/" . $link;
                                        }
                                        //echo '<pre>', var_dump($archivo_pdf), '</pre>';
                                        if (file_exists($archivo_pdf)) {
                                        ?>
                                            <td style="text-align: center !important;">
                                                <a href="<?= $href ?>" target="_blank" class="avatar rounded-circle mr-3">
                                                    <img src="images/download-pdf.png">
                                                </a>
                                            </td>
                                        <?php
                                        } else { ?>
                                            <td style="text-align: center !important;">
                                                <a class="avatar rounded-circle mr-3">
                                                    <img src="images/pdf.png">
                                                </a>
                                            </td>
                                        <?php
                                        }
                                        ?>

                                    </tr>
                                <?php endforeach; ?>
                                <?php $general_prom = $general_sum / $num_students; ?>
                                <thead class="thead-light">
                                </thead>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        console.log('kdv ewrlvn3oirtv3');
        var txt_group = $('#id_group option:selected').text();
        var txt_period = $('#id_period option:selected').text();
    </script>
<?php endif; ?>