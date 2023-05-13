<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';
include '../models/evaluations.php';

session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();

function saveExpectedLearning()
{
    $learning_name = $_POST['learning_name'];
    $arr_learning_name = explode(" ", $learning_name);
    $short_desc_ln = "";

    if (count($arr_learning_name) > 1) {
        if (count($arr_learning_name) > 2) {
            for ($i = 0; $i <= 2; $i++) {
                if (strlen($arr_learning_name[$i]) > 1) {
                    $short_desc_ln .= (substr($arr_learning_name[$i], 0, 3)) . " ";
                } else {
                    $short_desc_ln .= $arr_learning_name[$i] . " ";
                }
            }
        } else {
            for ($i = 0; $i <= 1; $i++) {
                if (strlen($arr_learning_name[$i]) > 1) {

                    $short_desc_ln .= (substr($arr_learning_name[$i], 0, 3)) . " ";
                } else {
                    $short_desc_ln .= $arr_learning_name[$i] . " ";
                }
            }
        }
    } else {
        $short_desc_ln .= substr($learning_name, 0, 3) . " ";
    }

    $learning_description = $_POST['learning_description'];
    $id_period_calendar = $_POST['id_period_calendar'];
    $id_assignment = $_POST['id_assignment'];
    $id_academic_area = $_POST['id_academic_area'];
    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;
    $attendance = new Attendance;

    /* OBTENER INFORMACIÓN PREVIA PARA REGISTRO DE LA IFORMACIÓN */
    $stmt = "SELECT * FROM iteach_grades_quantitatives.period_calendar 
    WHERE id_period_calendar = $id_period_calendar";
    $period_calendar = $groups->getGroupFromTeachers($stmt);
    $no_period = $period_calendar[0]->no_period;
    if (!empty($period_calendar)) {
        $no_period = $period_calendar[0]->no_period;
    }

    $stmt = "SELECT sbj.* 
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    WHERE asg.id_assignment = $id_assignment";
    $getSubject = $groups->getGroupFromTeachers($stmt);

    $subject_name = "";
    if (!empty($getSubject)) {
        $subject_name = $getSubject[0]->name_subject;
        $id_subject = $getSubject[0]->id_subject;
    }

    $stmt = "SELECT gps.*, alg.degree AS level_grade_write,alg. id_level_grade
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
    WHERE asg.id_assignment = $id_assignment";
    $getGroup = $groups->getGroupFromTeachers($stmt);

    $group_code = "";
    $id_group = "";
    $id_level_grade = "";
    $level_grade_write = "";
    if (!empty($getGroup)) {
        $group_code = $getGroup[0]->group_code;
        $id_group = $getGroup[0]->id_group;
        $level_grade_write = $getGroup[0]->level_grade_write;
        $id_level_grade = $getGroup[0]->id_level_grade;
    }

    $stmt = "SELECT *, 
    CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name
    FROM colaboradores_ykt.colaboradores
    WHERE no_colaborador = '$_SESSION[colab]'";
    $getColaborador = $groups->getGroupFromTeachers($stmt);
    $teacher_name = "";

    if (!empty($getColaborador)) {
        $teacher_name = $getColaborador[0]->teacher_name;
    }
    $colaborador = "";
    $index_description = "A.E. " . $subject_name . " " . $level_grade_write . " | " . $group_code;
    $subindex_description = $index_description . " - PERIODO " . $no_period;

    $stmt = "SELECT elc.* 
    FROM expected_learning.relationship_expected_learning_assignments AS rela
    INNER JOIN expected_learning.expected_learning_index AS eli ON rela.id_expected_learning_index = eli.id_expected_learning_index
    INNER JOIN expected_learning.expected_learning_subindex AS els ON eli.id_expected_learning_index = els.id_expected_learning_index
    INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
    WHERE rela.id_assignment = '$id_assignment' AND els.id_period_calendar = '$id_period_calendar'";
    $getExpectedLearningExist = $groups->getGroupFromTeachers($stmt);

    if (!empty($getExpectedLearningExist)) {

        $id_subindex = $getExpectedLearningExist[0]->id_expected_learning_subindex;
        $stmt = "SELECT * 
        FROM expected_learning.expected_learning_catalog 
        WHERE id_expected_learning_subindex = '$id_subindex' ORDER BY no_position DESC LIMIT 1";
        $getExpectedLearningCatalogRegistered = $groups->getGroupFromTeachers($stmt);
        if (!empty($getExpectedLearningCatalogRegistered)) {
            $no_position = $getExpectedLearningCatalogRegistered[0]->no_position + 1;
        } else {
            $no_position = 1;
        }

        /* INSERTAR RELACION DE AE CON ASIGNATURAS */
        $stmt = "INSERT INTO expected_learning.expected_learning_catalog (
                    id_expected_learning_subindex,
                    short_description,
                    learning_description,
                    no_teacher_registered,
                    datelog,
                    no_position,
                    abbr_lena
                ) VALUES (
                    '$id_subindex',
                    '$learning_name',
                    '$learning_description',
                    '$_SESSION[colab]',
                    NOW(),
                    '$no_position',
                    '$short_desc_ln'
                )";
        if ($attendance->saveAttendance($stmt)) {
            $id_catalog = $attendance->getLastId();
            $stmt = "SELECT * 
                    FROM expected_learning.expected_learning_catalog 
                    WHERE id_expected_learning_subindex = '$id_subindex' ORDER BY no_position DESC LIMIT 1";
            $getExpectedLearningCatalogRegistered = $groups->getGroupFromTeachers($stmt);
            if (!empty($getExpectedLearningCatalogRegistered)) {
                $no_position = $getExpectedLearningCatalogRegistered[0]->no_position + 1;
            } else {
                $no_position = 1;
            }
            $data = array(
                'response' => true,
                'last_id' => $id_catalog,
                'no_position' => $no_position,
                'no_period' => $no_period,
                'message' => 'Se ha registrado correctamente el catalogo de aprendizaje'
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al registrar el catalogo de aprendizaje'
            );
        }
    } else {
        /* INSERTAR CABECERA DE AE */
        $stmt = "SELECT * FROM expected_learning.relationship_expected_learning_assignments WHERE id_assignment = '$id_assignment'";
        $getExpectedLearningExist = $groups->getGroupFromTeachers($stmt);
        if (empty($getExpectedLearningExist)) {
            $stmt = "INSERT INTO expected_learning.expected_learning_index (
                index_description,
                no_teacher_created,
                logdate
            ) VALUES (
                '$index_description',
                '$_SESSION[colab]',
                NOW()
            )";
            if ($attendance->saveAttendance($stmt)) {
                $id_index = $attendance->getLastId();

                $stmt = "INSERT INTO expected_learning.relationship_expected_learning_assignments (
                    id_assignment,
                    id_expected_learning_index
                        ) VALUES (
                            '$id_assignment',
                            '$id_index'
                        )";
                $attendance->saveAttendance($stmt);
            }
        } else {
            $id_index = $getExpectedLearningExist[0]->id_expected_learning_index;
        }

        /* INSERTAR RELACION DE AE CON ASIGNATURAS */
        /* INSERTAR SUBINDICE DE AE */
        $stmt = "SELECT * FROM expected_learning.expected_learning_subindex WHERE id_period_calendar = '$id_period_calendar' AND id_expected_learning_index = '$id_index'";
        $getExpectedLearningExist = $groups->getGroupFromTeachers($stmt);

        if (empty($getExpectedLearningExist)) {
            $stmt = "INSERT INTO expected_learning.expected_learning_subindex (
                subindex_title,
                id_level_grade,
                level_grade_write,
                id_period_calendar,
                id_subject,
                no_period,
                teacher_created,
                logdate,
                id_expected_learning_index
                  ) VALUES (
                '$subindex_description',
                '$id_level_grade',
                '$level_grade_write',
                '$id_period_calendar',
                '$id_subject',
                '$no_period',
                '$_SESSION[colab]',
                NOW(),
                '$id_index')";
            if ($attendance->saveAttendance($stmt)) {
                $id_subindex = $attendance->getLastId();
            }
        } else {
            $id_subindex = $getExpectedLearningExist[0]->id_expected_learning_subindex;
        }


        $stmt = "SELECT * 
                            FROM expected_learning.expected_learning_catalog 
                            WHERE id_expected_learning_subindex = '$id_subindex' ORDER BY no_position DESC LIMIT 1";
        $getExpectedLearningCatalogRegistered = $groups->getGroupFromTeachers($stmt);
        if (!empty($getExpectedLearningCatalogRegistered)) {
            $no_position = $getExpectedLearningCatalogRegistered[0]->no_position + 1;
        } else {
            $no_position = 1;
        }

        /* INSERTAR CATALOGO DE AE  */
        $today = date('Y-m-d H:i:s');
        $stmt = "INSERT INTO expected_learning.expected_learning_catalog ( id_expected_learning_subindex, short_description, learning_description, no_teacher_registered, datelog, no_position, abbr_lena) VALUES 
        ( ?, ?, ?, ?, ?, ?, ? )";

        $arr_value = array($id_subindex, $learning_name, $learning_description, $_SESSION['colab'], $today, $no_position, $short_desc_ln);
        if ($attendance->saveAE($stmt, $arr_value)) {
            $id_catalog = $attendance->getLastId();
            $stmt = "SELECT * 
                                FROM expected_learning.expected_learning_catalog 
                                WHERE id_expected_learning_subindex = '$id_subindex' ORDER BY no_position DESC LIMIT 1";
            $getExpectedLearningCatalogRegistered = $groups->getGroupFromTeachers($stmt);
            if (!empty($getExpectedLearningCatalogRegistered)) {
                $no_position = $getExpectedLearningCatalogRegistered[0]->no_position + 1;
            } else {
                $no_position = 1;
            }
            $data = array(
                'response' => true,
                'last_id' => $id_catalog,
                'no_position' => $no_position,
                'message' => 'Se ha registrado correctamente el catalogo de aprendizaje'
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al registrar el catalogo de aprendizaje'
            );
        }
    }
    echo json_encode($data);
}
function deleteExpectedLearning()
{
    $id_expected_learning_catalog = $_POST['id_expected_learning_catalog'];
    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT * FROM 
    expected_learning.expected_learning_deliverables
    WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'
    AND teacher_evidence_quailification IS NOT NULL";
    $getExpectedLearningDeliverables = $groups->getGroupFromTeachers($stmt);
    if (empty($getExpectedLearningDeliverables)) {

        $stmt_avg = "DELETE FROM expected_learning.catalog_learning_averages
        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
        $attendance->saveAttendance($stmt_avg);

        $stmt_arc = "DELETE FROM expected_learning.expected_learning_archives
        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
        $attendance->saveAttendance($stmt_arc);

        $stmt = "DELETE FROM expected_learning.expected_learning_deliverables 
        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";

        if ($attendance->saveAttendance($stmt)) {

            $stmt = "DELETE FROM expected_learning.expected_learning_catalog
            WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
            if ($attendance->saveAttendance($stmt)) {
                $data = array(
                    'response' => true,
                    'message' => 'Se ha eliminado correctamente el catalogo de aprendizaje'
                );
            } else {
                $data = array(
                    'response' => false,
                    'message' => 'Ocurrió un error al eliminar el catalogo de aprendizaje'
                );
            }
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al eliminar el catalogo de aprendizaje'
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se puede eliminar el catalogo de aprendizaje, ya que tiene registros'
        );
    }

    echo json_encode($data);
}
function deleteExpectedLearningStructure()
{
    $id_assignment = $_POST['id_assignment'];
    $groups = new Groups;
    $attendance = new Attendance;
    $stmt = "SELECT * FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.subjects AS sub ON sub.id_subject = asg.id_subject
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sub.id_subject AND els.id_level_grade = gps.id_level_grade
    INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
    inner join expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
    INNER JOIN expected_learning.expected_learning_deliverables AS eld ON elc.id_expected_learning_catalog = eld.id_expected_learning_catalog
    INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
    WHERE asg.id_assignment = '$id_assignment' AND rela.id_assignment = '$id_assignment' AND teacher_evidence_quailification IS NOT NULL";

    $getExpectedLearningDeliverables = $groups->getGroupFromTeachers($stmt);
    if (empty($getExpectedLearningDeliverables)) {

        $stmt = "SELECT * FROM expected_learning.relationship_expected_learning_assignments WHERE id_assignment = '$id_assignment'";
        $getExpectedLearningRelationship = $groups->getGroupFromTeachers($stmt);
        if (!empty($getExpectedLearningRelationship)) {
            $id_expected_learning_index = $getExpectedLearningRelationship[0]->id_expected_learning_index;



            $stmt = "SELECT * FROM expected_learning.expected_learning_subindex WHERE id_expected_learning_index = '$id_expected_learning_index'";
            $getExpectedSubindex = $groups->getGroupFromTeachers($stmt);
            if (!empty($getExpectedSubindex)) {
                foreach ($getExpectedSubindex as $subindex) {
                    $id_expected_learning_subindex = $subindex->id_expected_learning_subindex;
                    $stmt = "DELETE FROM expected_learning.expected_learning_catalog WHERE id_expected_learning_subindex = '$id_expected_learning_subindex'";
                    $attendance->saveAttendance($stmt);
                }
                $stmt = "DELETE FROM expected_learning.expected_learning_subindex WHERE id_expected_learning_index = '$id_expected_learning_index'";
                $attendance->saveAttendance($stmt);
            } else {
                $stmt = "DELETE FROM expected_learning.expected_learning_subindex WHERE id_expected_learning_index = '$id_expected_learning_index'";
                $attendance->saveAttendance($stmt);
            }
            $stmt = "DELETE FROM expected_learning.relationship_expected_learning_assignments WHERE id_expected_learning_index = '$id_expected_learning_index' AND id_assignment ='$id_assignment'";
            $attendance->saveAttendance($stmt);

            $stmt = "DELETE FROM expected_learning.expected_learning_index WHERE id_expected_learning_index = '$id_expected_learning_index'";
            if ($attendance->saveAttendance($stmt)) {

                if ($attendance->saveAttendance($stmt)) {
                    $data = array(
                        'response' => true,
                        'message' => 'Se ha eliminado correctamente la estructura de aprendizaje'
                    );
                } else {
                    $data = array(
                        'response' => false,
                        'message' => 'Ocurrió un error al eliminar la estructura de aprendizaje'
                    );
                }
            } else {
                $data = array(
                    'response' => false,
                    'message' => 'Ocurrió un error al eliminar la estructura de aprendizaje'
                );
            }
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se puede eliminar la estructura de A.E., ya que tiene registros'
        );
    }

    echo json_encode($data);
}

function getCatalogDetail()
{
    $id_expected_learning_catalog = $_POST['id_expected_learning_catalog'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM expected_learning.expected_learning_catalog 
            WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
    $catalog_item = $groups->getGroupFromTeachers($stmt);
    $descripcion = "";
    if (!empty($catalog_item)) {
        $titulo = $catalog_item[0]->short_description;
        $descripcion .= $catalog_item[0]->learning_description;
        $data = array(
            'response' => true,
            'message' => 'El criterio de aprendizaje se eliminó correctamente',
            'titulo' => $titulo,
            'descripcion' => $descripcion
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al eliminar el criterio de aprendizaje'
        );
    }

    echo json_encode($data);
}


function updateCatalogDetail()
{

    $id_expected_learning_catalog = $_POST['id_expected_learning_catalog'];
    $learning_name_edit = $_POST['learning_name_edit'];
    $learning_description = $_POST['learning_description'];

    $arr_learning_name = explode(" ", $learning_name_edit);
    $short_desc_ln = "";

    if (count($arr_learning_name) > 1) {
        for ($i = 0; $i <= 3; $i++) {
            if (strlen($arr_learning_name[$i]) > 1) {

                $short_desc_ln .= (substr($arr_learning_name[$i], 0, 3)) . " ";
            } else {
                $short_desc_ln .= $arr_learning_name[$i] . " ";
            }
        }
    } else {
        $short_desc_ln .= substr($learning_name_edit, 0, 3) . " ";
    }
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "UPDATE expected_learning.expected_learning_catalog
    SET short_description = '$learning_name_edit',
    learning_description = '$learning_description',
    abbr_lena = '$short_desc_ln'
            WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";


    if ($catalog_item = $attendance->saveAttendance($stmt)) {
        $data = array(
            'response' => true,
            'message' => 'El criterio de aprendizaje se actualizó correctamente',
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al actualizar el criterio de aprendizaje'
        );
    }

    echo json_encode($data);
}

function changePeriodCatalog()
{
    $id_expected_learning_catalog = $_POST['id_catalog'];
    $id_period_destiny = $_POST['id_period_destiny'];
    //    $id_period_origin = $_POST['id_period_origin'];

    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT els.* FROM expected_learning.expected_learning_catalog AS elc
            INNER JOIN expected_learning.expected_learning_subindex AS els ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
            WHERE elc.id_expected_learning_catalog = '$id_expected_learning_catalog'";

    $catalog_item = $groups->getGroupFromTeachers($stmt);


    if (!empty($catalog_item)) {

        $id_expected_learning_index = $catalog_item[0]->id_expected_learning_index;
        $stmt = "SELECT * FROM expected_learning.expected_learning_subindex 
                WHERE id_expected_learning_index = '$id_expected_learning_index' AND id_period_calendar = '$id_period_destiny'";
        $getExpectedLearningExist = $groups->getGroupFromTeachers($stmt);
        if (!empty($getExpectedLearningExist)) {
            $id_subindex = $getExpectedLearningExist[0]->id_expected_learning_subindex;
            $stmt = "SELECT * FROM expected_learning.expected_learning_catalog 
                    WHERE id_expected_learning_subindex = '$id_subindex' ORDER BY no_position DESC LIMIT 1";
            $getExpectedLearningCatalogRegistered = $groups->getGroupFromTeachers($stmt);
            if (!empty($getExpectedLearningCatalogRegistered)) {
                $no_position = $getExpectedLearningCatalogRegistered[0]->no_position + 1;
            } else {
                $no_position = 1;
            }
            $stmt = "UPDATE expected_learning.expected_learning_catalog 
                SET id_expected_learning_subindex = '$id_subindex',
                    no_position = '$no_position'
                WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
            if ($attendance->saveAttendance($stmt)) {
                $stmt = "SELECT elc.*, els.no_period FROM expected_learning.expected_learning_catalog AS elc 
                        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_subindex = els.id_expected_learning_subindex
                        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
                $getExpectedLearningCatalogRegistered = $groups->getGroupFromTeachers($stmt);
                if (!empty($getExpectedLearningCatalogRegistered)) {
                    $no_position = $getExpectedLearningCatalogRegistered[0]->no_position + 1;
                    $short_description = $getExpectedLearningCatalogRegistered[0]->short_description;
                    $no_period = $getExpectedLearningCatalogRegistered[0]->no_period;
                } else {
                    $no_position = 1;
                }
                $data = array(
                    'response' => true,
                    'message' => 'El criterio de aprendizaje se cambio de periodo correctamente',
                    'id_period_destiny' => $id_period_destiny,
                    'no_position' => $no_position,
                    'short_description' => $short_description,
                    'no_period' => $no_period
                );
            } else {
                $data = array(
                    'response' => false,
                    'message' => 'Ocurrió un error al cambiar el periodo del catalogo de aprendizaje'
                );
            }
        } else {
            $data = array(
                'response' => false,
                'message' => 'Debe crear una estrucura antes de migrar un aprendizaje'
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al migrar el criterio de aprendizaje'
        );
    }

    echo json_encode($data);
}

function updatePositions()
{
    $positions = $_POST['positions'];
    for ($i = 0; $i < count($positions); $i++) {
        $catalog_item = $positions[$i];
        //var_dump($catalog_item);
        $id_catalog = $catalog_item[0];
        $no_position = $catalog_item[1];
        $attendance = new Attendance();
        $stmt = "UPDATE expected_learning.expected_learning_catalog SET no_position = '$no_position' WHERE id_expected_learning_catalog = '$id_catalog';";
        $attendance->saveAttendance($stmt);
    }
}
function importExpectedLearning()
{
    $id_expected_learning = $_POST['id_expected_learning'];
    $id_assignment = $_POST['id_assignment'];

    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;
    $attendance = new Attendance;

    /* OBTENER INFORMACIÓN PREVIA PARA REGISTRO DE LA IFORMACIÓN */

    $stmt = "SELECT sbj.* 
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
    WHERE asg.id_assignment = $id_assignment";
    $getSubject = $groups->getGroupFromTeachers($stmt);

    $subject_name = "";
    if (!empty($getSubject)) {
        $subject_name = $getSubject[0]->name_subject;
        $id_subject = $getSubject[0]->id_subject;
    }

    $stmt = "SELECT gps.*, alg.degree AS level_grade_write,alg. id_level_grade
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN school_control_ykt.academic_levels_grade AS alg ON alg.id_level_grade = gps.id_level_grade
    WHERE asg.id_assignment = $id_assignment";
    $getGroup = $groups->getGroupFromTeachers($stmt);

    $group_code = "";
    $id_group = "";
    $id_level_grade = "";
    $level_grade_write = "";
    if (!empty($getGroup)) {
        $group_code = $getGroup[0]->group_code;
        $id_group = $getGroup[0]->id_group;
        $level_grade_write = $getGroup[0]->level_grade_write;
        $id_level_grade = $getGroup[0]->id_level_grade;
    }

    $stmt = "SELECT *, 
    CONCAT(nombres_colaborador, ' ', apellido_paterno_colaborador,  ' ', apellido_materno_colaborador) AS teacher_name
    FROM colaboradores_ykt.colaboradores
    WHERE no_colaborador = '$_SESSION[colab]'";
    $getColaborador = $groups->getGroupFromTeachers($stmt);
    $teacher_name = "";

    if (!empty($getColaborador)) {
        $teacher_name = $getColaborador[0]->teacher_name;
    }
    $colaborador = "";
    $index_description = "A.E. " . $subject_name . " " . $level_grade_write . " | " . $group_code;
    $check_structure = "SELECT * FROM expected_learning.expected_learning_index AS eli
                      INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON eli.id_expected_learning_index = rela.id_expected_learning_index
                      WHERE rela.id_assignment = '$id_assignment'";
    $check_structure_result = $groups->getGroupFromTeachers($check_structure);
    if (empty($check_structure_result)) {
        $stmt = "INSERT INTO expected_learning.expected_learning_index (
            index_description,
            no_teacher_created,
            logdate
        ) VALUES (
            '$index_description',
            '$_SESSION[colab]',
            NOW()
        )";
        if ($attendance->saveAttendance($stmt)) {
            $id_index = $attendance->getLastId();

            $stmt = "INSERT INTO expected_learning.relationship_expected_learning_assignments (
                id_assignment,
                id_expected_learning_index
                    ) VALUES (
                        '$id_assignment',
                        '$id_index'
                    )";
            if ($attendance->saveAttendance($stmt)) {
                $stmt = "SELECT id_expected_learning_subindex, subindex_title,
                         id_level_grade,
                         id_subject,
                         level_grade_write,
                         evaluation_type,
                         id_period_calendar,
                         no_period
                         FROM expected_learning.expected_learning_subindex WHERE id_expected_learning_index = '$id_expected_learning'";
                $getSubindex = $groups->getGroupFromTeachers($stmt);




                foreach ($getSubindex as  $subindex) {
                    $subindex_description = $index_description . " - PERIODO " . $subindex->no_period;

                    $stmt_lvl_comb = "SELECT lc.id_level_combination
                FROM school_control_ykt.level_combinations AS lc
                INNER JOIN school_control_ykt.groups AS groups ON groups.id_campus = lc.id_campus
                INNER JOIN school_control_ykt.assignments AS assignment ON groups.id_group = assignment.id_group
                INNER JOIN school_control_ykt.academic_levels_grade AS ac_le_gra ON groups.id_level_grade = ac_le_gra.id_level_grade
                INNER JOIN school_control_ykt.academic_levels AS ac_le ON ac_le_gra.id_academic_level = ac_le.id_academic_level
                INNER JOIN school_control_ykt.subjects AS subject ON assignment.id_subject = subject.id_subject
                WHERE (lc.id_section = groups.id_section OR lc.id_section = 3) AND lc.id_campus = groups.id_campus AND lc.id_academic_level = ac_le.id_academic_level AND lc.id_academic_area = subject.id_academic_area AND assignment.id_assignment = '$id_assignment' LIMIT 1";
                    $get_lvl_comb = $groups->getGroupFromTeachers($stmt_lvl_comb);
                    $id_level_combination = "";
                    if (!empty($get_lvl_comb)) {
                        $id_level_combination = $get_lvl_comb[0]->id_level_combination;
                        $sql_level_combinations = "SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_level_combination = '$id_level_combination' AND no_period = '$subindex->no_period'";
                        $getIdsLevelCombination = $groups->getGroupFromTeachers($sql_level_combinations);
                        $id_period_calendar = "";
                        if (!empty($getIdsLevelCombination)) {
                            $id_period_calendar = $getIdsLevelCombination[0]->id_period_calendar;
                        }
                    }

                    $stmt = "INSERT INTO expected_learning.expected_learning_subindex (
                        subindex_title,
                        id_level_grade,
                        id_subject,
                        level_grade_write,
                        evaluation_type,
                        id_period_calendar,
                        no_period,
                        id_expected_learning_index,
                        teacher_created,
                        logdate
                    ) VALUES (
                        '$subindex_description',
                        '$subindex->id_level_grade',
                        '$subindex->id_subject',
                        '$subindex->level_grade_write',
                        '$subindex->evaluation_type',
                        '$id_period_calendar',
                        '$subindex->no_period',
                        '$id_index',
                        '$_SESSION[colab]',
                        NOW()
                    )";

                    if ($attendance->saveAttendance($stmt)) {
                        $id_subindex = $attendance->getLastId();

                        $stmt = "SELECT short_description, learning_description, no_position, abbr_lena
                        FROM expected_learning.expected_learning_catalog 
                        WHERE id_expected_learning_subindex = '$subindex->id_expected_learning_subindex'";
                        $getCatalog = $groups->getGroupFromTeachers($stmt);
                        foreach ($getCatalog as  $catalog_item) {
                            $stmt = "INSERT INTO expected_learning.expected_learning_catalog (
                                short_description,
                                learning_description,
                                no_position,
                                id_expected_learning_subindex,
                                no_teacher_registered,
                                datelog,
                                abbr_lena
                            ) VALUES (
                                '$catalog_item->short_description',
                                '$catalog_item->learning_description',
                                '$catalog_item->no_position',
                                '$id_subindex',
                                '$_SESSION[colab]',
                                NOW(),
                                '$catalog_item->abbr_lena'
                            )";
                            if ($attendance->saveAttendance($stmt)) {
                                $data = array(
                                    'status' => 'success',
                                    'message' => 'Se ha importado correctamente el A.E.'
                                );
                            }
                        }
                    }
                }
                $data = array(
                    'response' => true,
                    'status' => 'success',
                    'message' => 'Se ha importado correctamente el A.E.'
                );
            } else {
                $data = array(
                    'response' => false,
                    'status' => 'error',
                    'message' => 'No se ha podido importar el A.E.'
                );
            }
        } else {
            $data = array(
                'response' => false,
                'status' => 'error',
                'message' => 'No se ha podido importar el A.E.'
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'El criterio de aprendizaje ya fue importado'
        );
    }



    echo json_encode($data);
}

function saveGradeCatalog()
{

    $id_grade_evaluation_catalog = $_POST['id_grade_evaluation_catalog'];
    $grade = $_POST['grade'];
    $is_averaged = $_POST['is_averaged'];
    $id_assignment = $_POST['id_assignment'];

    if ($grade == '') {
        $grade = 'NULL';
    } else {
        $grade = "'$grade'";
    }

    $attendance = new Attendance();
    $groups = new Groups();

    $stmt = "SELECT gps.* FROM school_control_ykt.assignments AS asg
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
            WHERE asg.id_assignment = '$id_assignment'";
    $getGroup = $groups->getGroupFromTeachers($stmt);
    $id_group = "0";
    if (!empty($getGroup)) {
        $id_group = $getGroup[0]->id_group;
    }

    $stmt = "UPDATE expected_learning.expected_learning_deliverables  SET teacher_evidence_quailification = $grade WHERE id_expected_learning_deliverables = '$id_grade_evaluation_catalog';";
    if ($attendance->saveAttendance($stmt)) {

        $info_grade = "SELECT *, elc.id_expected_learning_subindex FROM expected_learning.expected_learning_deliverables AS eld
        INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_catalog = eld.id_expected_learning_catalog
         WHERE id_expected_learning_deliverables = '$id_grade_evaluation_catalog'";
        $info_grade_result = $groups->getGroupFromTeachers($info_grade);

        if (!empty($info_grade_result)) {
            $id_student = $info_grade_result[0]->id_student;
            $id_period_calendar = $info_grade_result[0]->id_period_calendar;
            $id_expected_learning_catalog = $info_grade_result[0]->id_expected_learning_catalog;
            $id_expected_learning_subindex = $info_grade_result[0]->id_expected_learning_subindex;
        }

        $stmt_get_avg = "SELECT AVG(teacher_evidence_quailification) AS student_avg
        FROM expected_learning.expected_learning_deliverables AS eld
        INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_catalog = eld.id_expected_learning_catalog

        WHERE  id_student = '$id_student' AND teacher_evidence_quailification IS NOT NULL AND eld.id_period_calendar = '$id_period_calendar' AND id_expected_learning_subindex = '$id_expected_learning_subindex'";

        $getAvg = $groups->getGroupFromTeachers($stmt_get_avg);
        $student_avg = 'NULL';
        if (!empty($getAvg)) {
            $student_avg = $getAvg[0]->student_avg;
            if ($student_avg == '') {
                $student_avg = 'NULL';
            } else {
                $student_avg = number_format($student_avg, 1);
            }
        } else {
            $student_avg = 'NULL';
        }

        $stmt_check_avg_structure = "SELECT * FROM expected_learning.expected_learning_period_average
        WHERE id_student = '$id_student' AND id_period_calendar = '$id_period_calendar' AND id_assignment = '$id_assignment'";
        $check_avg_structure = $groups->getGroupFromTeachers($stmt_check_avg_structure);

        if (empty($check_avg_structure)) {

            $stmt_insert_avg = "INSERT INTO expected_learning.expected_learning_period_average (
                id_student,
                id_period_calendar,
                id_assignment,
                student_average,
                datelog
                 ) VALUES (
                '$id_student',
                '$id_period_calendar',
                '$id_assignment',
                '$student_avg',
                NOW())";

            $attendance->saveAttendance($stmt_insert_avg);
        } else {

            $stmt_get_st_avg = "UPDATE expected_learning.expected_learning_period_average
            SET student_average = $student_avg
            WHERE id_student = '$id_student' AND id_period_calendar = '$id_period_calendar' AND id_assignment = '$id_assignment'";

            $attendance->saveAttendance($stmt_get_st_avg);
        }

        $infoCriteriaPE = $attendance->checkCriteriaPE($id_student, $id_period_calendar, $id_assignment);
        if (!empty($infoCriteriaPE)) {

            $evaluation = new Evaluations;

            $grade = '';

            if ($student_avg == '' || $student_avg == 'NULL') {
                $grade = 'NULL';
            } else {
                $grade = $student_avg;
            }

            foreach ($infoCriteriaPE as $data) {
                $id_grades_evaluation_criteria = $data->id_grades_evaluation_criteria;
                $id_final_grade = $data->id_final_grade;
                $id_grade_period = $data->id_grade_period;

                $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria SET grade_evaluation_criteria_teacher = $grade WHERE id_grades_evaluation_criteria = $id_grades_evaluation_criteria";

                if ($evaluation->updateEvaluation($sql)) {
                    $evaluation->calculateAveragePerPeriod($id_final_grade, $id_period_calendar);
                    $grade_period = $evaluation->getGradePeriod($id_grade_period);

                    if (!empty($evaluation->checkDynamicCalculationByAssg($id_assignment))) {
                        $evaluation->calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period->grade_period, $id_period_calendar);
                        $grade_period = $evaluation->getGradePeriod($id_grade_period);
                    }


                    $someCriteriaOperational = $evaluation->checkAnyCriteriaOperational($id_final_grade, $id_grade_period);

                    foreach ($someCriteriaOperational as $info_operational) {
                        $grade_period = $evaluation->getGradePeriod($id_grade_period);
                        $evaluation->calculateAveragePeriodByCriteriaDynamic($id_grade_period, $info_operational->note_criteria, $info_operational->id_evaluation_plan, $grade_period->grade_period_calc);
                    }
                }
            }
        }

        if ($student_avg == 'NULL') {
            $student_avg = '-';
        }
        $stmt_get_avg_learn = "SELECT AVG(teacher_evidence_quailification) AS learning_avg 
        FROM expected_learning.expected_learning_deliverables 
        WHERE `id_expected_learning_catalog` = '$id_expected_learning_catalog' AND teacher_evidence_quailification 
        IN (SELECT teacher_evidence_quailification FROM expected_learning.expected_learning_deliverables 
        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog' AND teacher_evidence_quailification IS NOT NULL )";

        $get_avg_learn = $groups->getGroupFromTeachers($stmt_get_avg_learn);

        if (!empty($get_avg_learn)) {
            $learning_avg = $get_avg_learn[0]->learning_avg;
            if ($learning_avg == '') {
                $learning_avg = 'NULL';
            } else {
                $learning_avg = number_format($learning_avg, 1);
            }
        } else {
            $learning_avg = 'NULL';
        }

        $stmt_check_avg_learning = "SELECT * FROM expected_learning.catalog_learning_averages
        WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
        $check_avg_learning = $groups->getGroupFromTeachers($stmt_check_avg_learning);
        if (empty($check_avg_learning)) {
            $stmt_insert_avg_learning = "INSERT INTO expected_learning.catalog_learning_averages (
                id_expected_learning_catalog,
                learning_average
                 ) VALUES (
                '$id_expected_learning_catalog',
                '$learning_avg')";
            $attendance->saveAttendance($stmt_insert_avg_learning);
        } else {
            $stmt_get_avg_learning = "UPDATE expected_learning.catalog_learning_averages
            SET learning_average = '$learning_avg'
            WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
            $attendance->saveAttendance($stmt_get_avg_learning);
        }

        $stmt_get_avg_group = "SELECT AVG(student_average) AS group_average
        FROM expected_learning.expected_learning_period_average
        WHERE `id_assignment` = '$id_assignment' AND student_average
        IN (SELECT student_average FROM expected_learning.expected_learning_period_average
        WHERE id_assignment = '$id_assignment' AND student_average IS NOT NULL AND id_period_calendar = '$id_period_calendar')";

        $get_avg_group = $groups->getGroupFromTeachers($stmt_get_avg_group);
        if (!empty($get_avg_group)) {
            $group_average = $get_avg_group[0]->group_average;
            if ($group_average == '') {
                $group_average = 'NULL';
            } else {
                $group_average = number_format($group_average, 1);
            }
        } else {
            $group_average = 'NULL';
        }

        $stmt_check_avg_group = "SELECT * FROM expected_learning.expected_learning_average_group
        WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar'";
        $check_avg_group = $groups->getGroupFromTeachers($stmt_check_avg_group);
        if (empty($check_avg_group)) {
            $stmt_insert_avg_group = "INSERT INTO expected_learning.expected_learning_average_group (
                id_assignment,
                id_group,
                group_average,
                id_period_calendar
                 ) VALUES (
                '$id_assignment',
                '$id_group',
                '$group_average',
                '$id_period_calendar')";
            $attendance->saveAttendance($stmt_insert_avg_group);
        } else {
            $stmt_get_avg_learning = "UPDATE expected_learning.expected_learning_average_group
            SET group_average = '$group_average'
            WHERE id_assignment = '$id_assignment' AND id_period_calendar = '$id_period_calendar'";
            $attendance->saveAttendance($stmt_get_avg_learning);
        }

        $data = array(
            'response' => true,
            'status' => 'success',
            'message' => 'Se ha guardado correctamente la calificación',
            'student_avg' => $student_avg,
            'learning_avg' => $learning_avg,
            'id_expected_learning_catalog' => $id_expected_learning_catalog,
            'group_average' => $group_average
        );
    } else {
        $data = array(
            'response' => false,
            'status' => 'error',
            'message' => 'No se ha podido guardar la calificación'
        );
    }
    echo json_encode($data);
}
function getCatalogItemDetail()
{
    $id_expected_learning_catalog = $_POST['id_expected_learning_catalog'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM expected_learning.expected_learning_catalog 
            WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
    $catalog_item = $groups->getGroupFromTeachers($stmt);
    $descripcion = "<strong>Descripción de aprendizaje esperado:</strong> <br><br>";
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'catalog_item' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al eliminar el criterio de aprendizaje'
        );
    }

    echo json_encode($data);
}

function getCatalogue()
{
    $id_expected_learning_subindex = $_POST['id_expected_learning_subindex'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM expected_learning.expected_learning_catalog 
            WHERE id_expected_learning_subindex = '$id_expected_learning_subindex'";
    $catalog_item = $groups->getGroupFromTeachers($stmt);

    $html = '';
    $html .= '<table class="table table-bordered table-striped table-hover table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Descripción</th>
                        <th>Promedio</th>
                        <th>Ver evidencia</th>
                    </tr>
                </thead>
                <tbody>';
    if (!empty($catalog_item)) {
        foreach ($catalog_item as $key => $value) {
            $stmt_avg = "SELECT * FROM expected_learning.catalog_learning_averages
            WHERE id_expected_learning_catalog = '$value->id_expected_learning_catalog'";
            $cat_avg = $groups->getGroupFromTeachers($stmt_avg);
            if (!empty($cat_avg)) {
                $learning_average = $cat_avg[0]->learning_average;
            } else {
                $learning_average = '-';
            }

            $stmt_archive = "SELECT * FROM expected_learning.expected_learning_archives
            WHERE id_expected_learning_catalog = '$value->id_expected_learning_catalog'";
            $archive = $groups->getGroupFromTeachers($stmt_archive);
            if (!empty($archive)) {
                $link_type = $archive[0]->link_type;
                $url_archive = $archive[0]->url_archive;

                if ($link_type == '1') {
                    $url_archive =  $url_archive;
                } else {
                    $url_archive = '/wykt/interno/erp_realtime/iTeach/' . $url_archive;
                }
                $html .= '<tr>
                <td style="white-space: pre-wrap;">' . $value->short_description . '</td>
                <td>' . $learning_average . '</td>
                <td>
                    <a href="' . $url_archive . '" target="_blank" class="btn btn-sm btn-primary">
                    <i class="fa-solid fa-eye"></i>
                    </a>
                </td>
            </tr>';
            } else {
                $html .= '<tr>
                <td style="white-space: pre-wrap;">' . $value->short_description . '</td>
                <td>' . $learning_average . '</td>
                <td>
                    <i class="fa-solid fa-eye-slash"></i>
                </td>';
            }
        }
    } else {
        $html .= '<tr>
                    <td colspan="2">No hay registros</td>
                </tr>';
    }
    $html .= '</tbody>
            </table>';
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'catalog_item' => $html
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al eliminar el criterio de aprendizaje'
        );
    }

    echo json_encode($data);
}

function saveURLEvidence()
{
    $id_expected_learning_catalog = $_POST['id_expected_learning_catalog'];
    $url_evidence = $_POST['url'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt_get = "select * from expected_learning.expected_learning_archives 
            where id_expected_learning_catalog = '$id_expected_learning_catalog'";
    $catalog_item = $groups->getGroupFromTeachers($stmt_get);
    if (!empty($catalog_item)) {
        $stmt = "UPDATE expected_learning.expected_learning_archives 
            SET url_archive = '$url_evidence'
            WHERE id_expected_learning_catalog = '$id_expected_learning_catalog'";
    } else {
        $stmt = "INSERT INTO expected_learning.expected_learning_archives (
            id_expected_learning_catalog,
            url_archive,
            active_archive,
            no_teacher_upload,
            date_log,
            link_type
             ) VALUES (
            '$id_expected_learning_catalog',
            '$url_evidence',
            '1',
            '$_SESSION[colab]',
            NOW(),
            '1')";
    }



    if ($attendance->saveAttendance($stmt)) {

        $data = array(
            'response' => true,
            'message' => 'Se ha guardado correctamente la evidencia'
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'No se ha podido guardar la evidencia'
        );
    }

    echo json_encode($data);
}

function uploadCatalogFiles()
{

    $folder = $_POST['folder'];
    $module_name = $_POST['module_name'];
    //$file_name = $_POST['name'];
    $extension_file = basename($_FILES["formData"]["type"]);
    $file_name = $folder . "_" . time() . ".$extension_file";

    //$route = '/xampp/htdocs/documentos_alumnos/' . $_POST['student'] . '/' . $folder;
    $route2 =  dirname(__DIR__ . '', 2) . '/uploads_documents/' . $module_name . "/" . $folder . "/";
    $route =  dirname(__DIR__ . '', 2) . '/uploads_documents/' . $module_name . "/" . $folder . "/" . $file_name;
    $route_db = '/uploads_documents/' . $module_name . '/' . $folder . "/" . $file_name;
    if (!file_exists($route2)) {
        mkdir($route2, 0777, true);
    }
    if (move_uploaded_file($_FILES["formData"]["tmp_name"], $route)) {

        $groups = new Groups;
        $attendance = new Attendance;
        $stmt_get = "select * from expected_learning.expected_learning_archives 
        where id_expected_learning_catalog = '$_POST[id_catalog]'";
        $catalog_item_1 = $groups->getGroupFromTeachers($stmt_get);
        if (!empty($catalog_item_1)) {
            $stmt = "UPDATE expected_learning.expected_learning_archives 
        SET url_archive = '$route_db'
        WHERE id_expected_learning_catalog = '$_POST[id_catalog]'";
        } else {
            $stmt = "INSERT INTO expected_learning.expected_learning_archives (
                id_expected_learning_catalog,
                url_archive,
                active_archive,
                no_teacher_upload,
                date_log,
                link_type
                 ) VALUES (
                '$_POST[id_catalog]',
                '$route_db',
                '1',
                '$_SESSION[colab]',
                NOW(),
                '2')";
        }

        if ($attendance->saveAttendance($stmt)) {

            $data = array(
                'response' => true,
                'message' => 'Se ha subido correctamente el archivo',
                'route' => $route_db,
                'url' => $route_db
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'No se ha podido subir el archivo'
            );
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se ha podido subir el archivo'
        );
    }

    echo json_encode($data);
}
function deleteEvidence()
{
    $id_expected_learning_catalog = $_POST['id_expected_learning_catalog'];
    $url_evidence = $_POST['url'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "DELETE from expected_learning.expected_learning_archives 
            where id_expected_learning_catalog = '$id_expected_learning_catalog'";
    if ($attendance->saveAttendance($stmt)) {

        $data = array(
            'response' => true,
            'message' => 'Se ha guardado correctamente la evidencia'
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'No se ha podido guardar la evidencia'
        );
    }

    echo json_encode($data);
}
function getAcademiclevels()
{
    $id_academic_area = $_POST['id_academic_area'];
    $groups = new Groups;
    $attendance = new Attendance;
    $grants = $_SESSION['grantsITEQ'];
    if ($grants & 8) {

        $stmt = "SELECT * FROM (
            SELECT acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
            INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
            INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
            INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
            UNION
            SELECT  acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
            FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
            INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
            INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
            INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
             )
            AS u
            WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic_area";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT al.academic_level, al.id_academic_level
            FROM school_control_ykt.assignments AS asg 
            INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
            INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
            INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
            INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = aclg.id_academic_level
            INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
            WHERE asg.no_teacher = '$_SESSION[colab]' AND aca.id_academic_area='$id_academic_area'";
    }

    /* INSERTAR CATALOGO DE AE  */

    $academicLevels = $groups->getGroupFromTeachers($stmt);
    if (!empty($academicLevels)) {

        $data = array(
            'response' => true,
            'message' => '',
            'academicLevels' => $academicLevels
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se ha podido obtener los niveles académicos'
        );
    }

    echo json_encode($data);
}
function getAcademiclevelGrades()
{
    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT DISTINCT groups.id_level_grade, acdlvldg.degree AS level_grade_write
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.academic_levels AS al ON al.id_academic_level = acdlvldg.id_academic_level
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas AS aca ON sbj.id_academic_area = aca.id_academic_area
        WHERE rel_coord_aca.no_teacher = $_SESSION[colab] AND aca.id_academic_area = $id_academic_area AND al.id_academic_level = $id_academic_level
        ORDER BY level_grade_write";
    $academicLevels = $groups->getGroupFromTeachers($stmt);
    if (!empty($academicLevels)) {

        $data = array(
            'response' => true,
            'message' => '',
            'academicLevels' => $academicLevels
        );
    } else {

        /* INSERTAR CATALOGO DE AE  */
        $stmt = "SELECT * FROM 

        (SELECT groups.id_level_grade, acdlvldg.id_academic_level, acdlvldg.degree AS level_grade_write, rel_coord_aca.no_teacher, sbj.id_academic_area, assg.print_school_report_card, assg.assignment_active
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        
        UNION 

        SELECT gps.id_level_grade, aclg.id_academic_level, aclg.degree AS level_grade_write, rel_coord_aca.no_teacher, sbj.id_academic_area, asgm.print_school_report_card, asgm.assignment_active
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador

        UNION 

        SELECT gps.id_level_grade, aclg.id_academic_level, aclg.degree AS level_grade_write, asg.no_teacher, sbj.id_academic_area, asg.print_school_report_card, asg.assignment_active
        FROM school_control_ykt.assignments AS asg
        INNER JOIN school_control_ykt.groups AS gps ON asg.id_group = gps.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asg.id_subject = sbj.id_subject
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asg.no_teacher = col.no_colaborador)

        AS u

        WHERE u.no_teacher = $_SESSION[colab] AND u.id_academic_area = $id_academic_area AND u.print_school_report_card = 1 AND u.assignment_active = 1 AND u.id_academic_level = $id_academic_level";
        $academicLevels = $groups->getGroupFromTeachers($stmt);

        if (!empty($academicLevels)) {

            $data = array(
                'response' => true,
                'message' => '',
                'academicLevels' => $academicLevels
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'No se ha podido obtener los niveles académicos'
            );
        }
    }
    echo json_encode($data);
}
function getPeriods()
{
    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];

    $grants = $_SESSION['grantsITEQ'];

    $groups = new Groups;
    $attendance = new Attendance;
    if (($grants & 8)) {
        $stmt = "SELECT DISTINCT lvl_com.id_level_combination
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON lvl_com.id_academic_level  = acdlvldg.id_academic_level AND groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
        WHERE rel_coord_aca.no_teacher = $_SESSION[colab] AND lvl_com.id_academic_area = $id_academic_area AND lvl_com.id_academic_level = $id_academic_level
        ";
    } else {
        $stmt = "SELECT DISTINCT lvl_com.id_level_combination
                FROM school_control_ykt.assignments AS assg 
                INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = assg.id_group
                INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade = acdlvldg.id_level_grade
                INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = assg.id_subject
                INNER JOIN school_control_ykt.level_combinations AS lvl_com ON sbj.id_academic_area = lvl_com.id_academic_area
                AND acdlvldg.id_academic_level = lvl_com.id_academic_level AND groups.id_campus = lvl_com.id_campus AND groups.id_section = lvl_com.id_section
                WHERE assg.no_teacher = $_SESSION[colab] AND lvl_com.id_academic_area = $id_academic_area AND lvl_com.id_academic_level = $id_academic_level
                ";
    }

    $getLevelCombination = $groups->getGroupFromTeachers($stmt);
    

    if (empty($getLevelCombination)) {
        $stmt = "SELECT DISTINCT lvl_com.id_level_combination, rel_coord_aca.no_teacher, sbj.id_academic_area, CASE WHEN lvl_com.id_section = 1 THEN ' - V' WHEN lvl_com.id_section = 2 THEN ' - M'  WHEN lvl_com.id_section = 3 THEN ' - MX' END AS section_descr
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS assg ON assg.coordinators_group_id = rel_coord_aca.coordinators_group_id
        INNER JOIN school_control_ykt.groups AS groups ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade = acdlvldg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.academic_areas as acar ON acar.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON lvl_com.id_academic_area = sbj.id_academic_area
         AND lvl_com.id_academic_level = acdlvldg.id_academic_level  AND groups.id_campus = lvl_com.id_campus AND groups.id_section = lvl_com.id_section
        INNER JOIN colaboradores_ykt.colaboradores AS col ON assg.no_teacher = col.no_colaborador
        WHERE rel_coord_aca.no_teacher = $_SESSION[colab] AND sbj.id_academic_area = $id_academic_area AND acdlvldg.id_academic_level = $id_academic_level ORDER BY lvl_com.id_section
        ";


        $getLevelCombination = $groups->getGroupFromTeachers($stmt);

        /* INSERTAR CATALOGO DE AE  */
        if (count($getLevelCombination) > 1) {
            $id_level_combination = $getLevelCombination[0]->id_level_combination;

            $stmt = "SELECT percal.*, CASE WHEN lvc.id_section = 1 THEN ' - V' WHEN lvc.id_section = 2 THEN ' - M'  WHEN lvc.id_section = 3 THEN ' - MX' END AS section_descr
            FROM school_control_ykt.level_combinations AS lvc
            INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON lvc.id_level_combination = percal.id_level_combination
            WHERE lvc.id_level_combination = '$id_level_combination' ORDER BY lvc.id_section";
            $Periods = $groups->getGroupFromTeachers($stmt);

            $id_level_combination2 = $getLevelCombination[1]->id_level_combination;

            $stmt = "SELECT percal.*, CASE WHEN lvc.id_section = 1 THEN ' - V' WHEN lvc.id_section = 2 THEN ' - M'  WHEN lvc.id_section = 3 THEN ' - MX' END AS section_descr
            FROM school_control_ykt.level_combinations AS lvc
            INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON lvc.id_level_combination = percal.id_level_combination
            WHERE lvc.id_level_combination = '$id_level_combination2' ORDER BY lvc.id_section";
            $Periods2 = $groups->getGroupFromTeachers($stmt);

            $Periods = array_merge($Periods, $Periods2);
        } else {
            $id_level_combination = $getLevelCombination[0]->id_level_combination;

            $stmt = "SELECT percal.*, CASE WHEN lvc.id_section = 1 THEN ' - V' WHEN lvc.id_section = 2 THEN ' - M'  WHEN lvc.id_section = 3 THEN ' - MX' END AS section_descr
                FROM school_control_ykt.level_combinations AS lvc
                INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON lvc.id_level_combination = percal.id_level_combination
                WHERE lvc.id_level_combination = '$id_level_combination' ORDER BY lvc.id_section";
                        $Periods = $groups->getGroupFromTeachers($stmt);
        }
    }else{
        $id_level_combination = $getLevelCombination[0]->id_level_combination;

        $stmt = "SELECT percal.*
    FROM school_control_ykt.level_combinations AS lvc
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON lvc.id_level_combination = percal.id_level_combination
    WHERE lvc.id_level_combination = '$id_level_combination'";
        $Periods = $groups->getGroupFromTeachers($stmt);
    }


    if (!empty($Periods)) {

        $data = array(
            'response' => true,
            'message' => '',
            'Periods' => $Periods
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se ha podido obtener los niveles académicos'
        );
    }

    echo json_encode($data);
}
function getSubjects()
{
    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    //$id_level_grade = $_POST['id_level_grade'];
    $no_teacher = $_SESSION['colab'];
    $grants = $_SESSION['grantsITEQ'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    if ($grants & 8) {
        $stmt = "SELECT * FROM 

        (SELECT sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section AND groups.id_level_grade ='$id_academic_level'
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        UNION
        SELECT  sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group AND gps.id_level_grade ='$id_academic_level'
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg 
        
    )
        AS u
        WHERE no_teacher = $no_teacher AND id_academic_area = '$id_academic_area' ORDER BY name_subject ASC
    ";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT sbj.name_subject, sbj.id_subject
        FROM school_control_ykt.assignments AS asgm 
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade AND aclg.id_academic_level = '$id_academic_level'
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
        WHERE (asgm.no_teacher = '$no_teacher' OR asgm.no_teacher = '$no_teacher') AND sbj.id_academic_area = '$id_academic_area' ORDER BY sbj.name_subject ASC
        
    ";
    }

    $Subjects = $groups->getGroupFromTeachers($stmt);
    if (!empty($Subjects)) {

        $data = array(
            'response' => true,
            'message' => '',
            'Subjects' => $Subjects
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se ha podido obtener los niveles académicos'
        );
    }

    echo json_encode($data);
}

function getSubjectsLevel()
{
    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    //$id_level_grade = $_POST['id_level_grade'];
    $no_teacher = $_SESSION['colab'];
    $grants = $_SESSION['grantsITEQ'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    if ($grants & 8) {
        $stmt = "SELECT * FROM 

        (SELECT sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS acdlvldg ON groups.id_level_grade AND acdlvldg.id_academic_level ='$id_academic_level'
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        UNION
        SELECT  sbj.name_subject, sbj.id_subject, sbj.id_academic_area, rel_coord_aca.no_teacher
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON aclg.id_academic_level ='$id_academic_level'
        
    )
        AS u
        WHERE no_teacher = $no_teacher AND id_academic_area = '$id_academic_area' ORDER BY name_subject ASC
    ";
    } else if ($grants & 4) {
        $stmt = "SELECT DISTINCT sbj.name_subject, sbj.id_subject
        FROM school_control_ykt.assignments AS asgm 
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group 
        INNER JOIN school_control_ykt.campus AS cmp ON gps.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade AND aclg.id_academic_level = '$id_academic_level'
        INNER JOIN colaboradores_ykt.colaboradores AS col ON asgm.no_teacher = col.no_colaborador
        WHERE (asgm.no_teacher = '$no_teacher' OR asgm.no_teacher = '$no_teacher') AND sbj.id_academic_area = '$id_academic_area' ORDER BY sbj.name_subject ASC
        
    ";
    }

    $Subjects = $groups->getGroupFromTeachers($stmt);
    if (!empty($Subjects)) {

        $data = array(
            'response' => true,
            'message' => '',
            'Subjects' => $Subjects
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se ha podido obtener los niveles académicos'
        );
    }

    echo json_encode($data);
}

function getMutualCriteria()
{

    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_level_grade = $_POST['id_level_grade'];
    $id_period = $_POST['id_period'];

    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT DISTINCT elc.short_description 
    FROM iteach_academic.relationship_managers_assignments AS rma
    INNER JOIN school_control_ykt.assignments AS asg ON asg.id_assignment = rma.id_assignment
    INNER JOIN expected_learning.expected_learning_subindex AS els ON  els.id_level_grade = '$id_level_grade'
    INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
    INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
    INNER JOIN expected_learning.expected_learning_catalog AS elc ON els.id_expected_learning_subindex = elc.id_expected_learning_subindex
    INNER JOIN expected_learning.expected_learning_catalog as t2 ON elc.short_description = t2.short_description
    WHERE els.id_period_calendar = '$id_period' and rma.no_teacher = '$_SESSION[colab]' 
    and rela.id_assignment = asg.id_assignment and t2.id_expected_learning_subindex != elc.id_expected_learning_subindex";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    $descripcion = "<strong>Descripción de aprendizaje esperado:</strong> <br><br>";
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'catalog_item' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
        );
    }

    echo json_encode($data);
}
function saveCommentAE()
{
    $id_period_calendar = $_POST['id_period_calendar'];
    $id_assignment = $_POST['id_assignment'];
    $comment = $_POST['comment'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "INSERT INTO expected_learning.comment_period_not_qualified (id_assignment, id_period_calendar, comment, no_teacher, datelog)VALUES(
        '$id_assignment',
        '$id_period_calendar',
        '$comment',
        '$_SESSION[colab]',
        NOW()
    )";
    if ($attendance->saveAttendance($stmt)) {

        $data = array(
            'response' => true,
            'message' => 'Se ha guardado correctamente el comentario'
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'No se ha podido guardar la evidencia'
        );
    }

    echo json_encode($data);
}
function editCommentAE()
{
    $id_comment = $_POST['id_comment'];
    $edit_commentary = $_POST['edit_commentary'];
    $comment = $_POST['comment'];
    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "UPDATE expected_learning.comment_period_not_qualified  SET  comment = '$edit_commentary' WHERE id_comment_period_not_qualified = '$id_comment'";
    if ($attendance->saveAttendance($stmt)) {

        $data = array(
            'response' => true,
            'message' => 'Se ha actualizado correctamente el comentario'
        );
    } else {
        $data = array(
            'response' => false,
            'message'                => 'No se ha podido guardar la evidencia'
        );
    }

    echo json_encode($data);
}

function getExportableSubjects()
{

    $id_assignment = $_POST['id_assignment'];
    $id_period = $_POST['id_period'];
    $id_academic_area = $_POST['id_academic_area'];


    $groups = new Groups;
    $attendance = new Attendance;



    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM (
        SELECT rel_coord_aca.no_teacher, sbj.id_academic_area, sbj.name_subject, sbj.id_subject, els.id_expected_learning_subindex
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination AND percal.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
        
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON  rela.id_assignment = assg.id_assignment
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = rela.id_expected_learning_index
        LEFT JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index AND els.id_period_calendar = percal.id_period_calendar

        UNION
        SELECT  rel_coord_aca.no_teacher, sbj.id_academic_area, sbj.name_subject, sbj.id_subject, els.id_expected_learning_subindex
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
        
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON  rela.id_assignment = asgm.id_assignment
        INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = rela.id_expected_learning_index
        LEFT JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index AND els.id_period_calendar = percal.id_period_calendar
         )
        AS u
    
        WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic_area AND id_expected_learning_subindex IS NULL  ORDER BY name_subject";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'exportableSubjects' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
        );
    }

    echo json_encode($data);
}
function getExportableGroups()
{

    $id_academic_area = $_POST['id_academic_area'];
    $id_period = $_POST['id_period'];
    $id_assignment = $_POST['id_assignment'];


    $groups = new Groups;
    $attendance = new Attendance;

    $getSubjectInfo = "SELECT * FROM school_control_ykt.subjects AS sbj
    INNER JOIN school_control_ykt.assignments AS asg ON asg.id_subject = sbj.id_subject
    WHERE id_assignment = $id_assignment";
    $subjectInfo = $groups->getGroupFromTeachers($getSubjectInfo);
    $id_subject = $subjectInfo[0]->id_subject;

    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT DISTINCT  els.subindex_title, els.id_expected_learning_subindex, gps.id_group, gps.group_code, asg.id_subject,
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS nombre_colaborador
    FROM school_control_ykt.assignments AS asg
    INNER JOIN school_control_ykt.subjects AS sub ON sub.id_subject = asg.id_subject
    INNER JOIN school_control_ykt.groups AS gps ON gps.id_group = asg.id_group
    INNER JOIN iteach_grades_quantitatives.period_calendar AS percal ON percal.id_period_calendar = $id_period
    INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_subject = sub.id_subject AND els.id_level_grade = gps.id_level_grade AND els.no_period = percal.no_period
    INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
    INNER JOIN expected_learning.expected_learning_index AS eli ON eli.id_expected_learning_index = els.id_expected_learning_index
    INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = eli.no_teacher_created
    WHERE asg.id_assignment = '$id_assignment' AND rela.id_assignment != '$id_assignment' ORDER BY eli.index_description
        ";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'exportableSubjects' => $catalog_item,
            'id_subject' => $id_subject,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No hay ningún A.E. registrado para este periodo'
        );
    }

    echo json_encode($data);
}
function getExportablePeriods()
{

    $id_academic_area = $_POST['id_academic_area'];
    $id_academic_level = $_POST['id_academic_level'];
    $id_level_grade = $_POST['id_level_grade'];
    $id_period = $_POST['id_period'];

    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM (
        SELECT es.*, groups.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.level_combinations AS lvl_com ON rel_coord_aca.id_level_combination = lvl_com.id_level_combination
        INNER JOIN school_control_ykt.groups AS groups ON lvl_com.id_campus = groups.id_campus AND lvl_com.id_section = groups.id_section
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON lvl_com.id_academic_level = aclg.id_academic_level AND groups.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.campus AS cmp ON groups.id_campus = cmp.id_campus 
        INNER JOIN school_control_ykt.assignments AS assg ON groups.id_group = assg.id_group
        INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = assg.id_assignment AND ep.id_period_calendar = $id_period
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
        INNER JOIN school_control_ykt.subjects AS sbj ON assg.id_subject = sbj.id_subject AND lvl_com.id_academic_area = sbj.id_academic_area
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level = aclg.id_academic_level
        UNION
        SELECT  es.*, gps.id_level_grade, acdlvldg.id_academic_level, acdlvldg.academic_level, rel_coord_aca.no_teacher, sbj.id_academic_area
        FROM iteach_academic.relationship_coordinators_academic_areas AS rel_coord_aca
        INNER JOIN school_control_ykt.assignments AS asgm ON rel_coord_aca.coordinators_group_id = asgm.coordinators_group_id
        INNER JOIN iteach_grades_quantitatives.evaluation_plan as ep ON ep.id_assignment = asgm.id_assignment AND ep.id_period_calendar = $id_period
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS es ON es.id_evaluation_source = ep.id_evaluation_source
        INNER JOIN school_control_ykt.groups AS gps ON asgm.id_group = gps.id_group  
        INNER JOIN school_control_ykt.academic_levels_grade AS aclg ON gps.id_level_grade = aclg.id_level_grade
        INNER JOIN school_control_ykt.subjects AS sbj ON asgm.id_subject = sbj.id_subject 
        INNER JOIN school_control_ykt.academic_levels AS acdlvldg ON acdlvldg.id_academic_level  =  aclg.id_academic_level
         )
        AS u
    
        WHERE no_teacher = $_SESSION[colab] AND id_academic_area = $id_academic_area AND id_level_grade = $id_level_grade AND id_evaluation_source !=1 ";

    $catalog_item = $groups->getGroupFromTeachers($stmt);
    $descripcion = "<strong>Descripción de aprendizaje esperado:</strong> <br><br>";
    if (!empty($catalog_item)) {

        $data = array(
            'response' => true,
            'catalog_item' => $catalog_item
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Al parecer no hay ningún criterio de aprendizaje esperado en común'
        );
    }

    echo json_encode($data);
}
function exportToAnotherAssignment()
{

    $id_subject = $_POST['id_subject'];
    $id_period = $_POST['id_period'];
    $id_academic_area = $_POST['id_academic_area'];
    $id_assignment = $_POST['id_assignment'];
    $id_group = $_POST['id_group'];
    $id_expected_learning_subindex_origin = $_POST['id_expected_learning_subindex'];

    $groups = new Groups;
    $attendance = new Attendance;

    $id_assignment_destiny = 0;
    $sqlGetssignmentDestiny = "SELECT * FROM school_control_ykt.assignments WHERE id_subject = $id_subject AND id_group = $id_group";
    $assignmentDestiny = $groups->getGroupFromTeachers($sqlGetssignmentDestiny);
    if (!empty($assignmentDestiny)) {
        $id_assignment_destiny = $assignmentDestiny[0]->id_assignment;
    }

    if ($id_assignment_destiny != 0) {
        $checkIfEPExist = "SELECT rela.id_assignment
        FROM expected_learning.expected_learning_index AS eli
        INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
        INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
         WHERE id_assignment = $id_assignment_destiny AND els.id_period_calendar = $id_period";

        $EPExist = $groups->getGroupFromTeachers($checkIfEPExist);
        if (empty($EPExist)) {

            $checkIfEPExist3 = "SELECT els.id_expected_learning_subindex
            FROM expected_learning.expected_learning_index AS eli
            INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
            WHERE id_assignment = $id_assignment_destiny AND els.id_period_calendar = $id_period ";
            $EPExist3 = $groups->getGroupFromTeachers($checkIfEPExist3);


            if (empty($EPExist3)) {

                $checkIfEPExist2 = "SELECT eli.id_expected_learning_index
                FROM expected_learning.expected_learning_index AS eli
                INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
                WHERE id_assignment = $id_assignment_destiny ";
                $EPExist2 = $groups->getGroupFromTeachers($checkIfEPExist2);
                $id_expected_learning_index = $EPExist2[0]->id_expected_learning_index;

                /* $stmt = "SELECT DISTINCT els.*
                FROM expected_learning.expected_learning_index AS eli
                INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
                 WHERE id_assignment = $id_assignment AND els.id_period_calendar = $id_period";
                $getSubindexOrigin = $groups->getGroupFromTeachers($stmt); */

                $getSubjectInfo = "SELECT * FROM school_control_ykt.subjects WHERE id_subject = $id_subject";
                $subjectInfo = $groups->getGroupFromTeachers($getSubjectInfo);
                $name_subject = $subjectInfo[0]->name_subject;

                $getLevelGradeInfo = "SELECT acl.*, gps.group_code FROM school_control_ykt.groups AS gps
                INNER JOIN school_control_ykt.academic_levels_grade AS acl ON acl.id_level_grade = gps.id_level_grade
                 WHERE id_group = $id_group";
                $levelGradeInfo = $groups->getGroupFromTeachers($getLevelGradeInfo);

                $level_grade_write = $levelGradeInfo[0]->degree;
                $id_level_grade = $levelGradeInfo[0]->id_level_grade;

                $getPeriodInfo = "SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = $id_period";
                $periodInfo = $groups->getGroupFromTeachers($getPeriodInfo);
                $no_period = $periodInfo[0]->no_period;

                $subindex_info = 'A.E. ' . $name_subject . ' ' . $levelGradeInfo[0]->degree . ' | PERIODO ' . $no_period;

                $insertSubindex = "INSERT INTO expected_learning.expected_learning_subindex(
                    subindex_title,
                    id_level_grade,
                    id_subject,
                    level_grade_write,
                    evaluation_type,
                    id_period_calendar,
                    no_period,
                    teacher_created,
                    logdate,
                    id_expected_learning_index 
                ) VALUES(
                    '$subindex_info',
                    $id_level_grade,
                    $id_subject,
                    '$level_grade_write',
                    NULL,
                    $id_period,
                    $no_period,
                    '$_SESSION[colab]',
                    NOW(),
                    $id_expected_learning_index
                )";
                $attendance->saveAttendance($insertSubindex);
                $id_new_subindex = $attendance->getLastId();


                $stmt3 = "SELECT elc.*
                FROM expected_learning.expected_learning_index AS eli
                INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
                 WHERE id_expected_learning_subindex = $id_expected_learning_subindex_origin";

                $evaluationSubindexOrigin = $groups->getGroupFromTeachers($stmt3);

                if (!empty($evaluationSubindexOrigin)) {
                    $countEvalPlan = count($evaluationSubindexOrigin);
                    $count = 0;
                    foreach ($evaluationSubindexOrigin as $evalplan_origin) {

                        $sqlInsertNewEvalPlan = "INSERT INTO expected_learning.expected_learning_catalog
                        (
                            id_expected_learning_subindex,
                            short_description,
                            learning_description,
                            no_teacher_registered,
                            datelog,
                            no_position,
                            abbr_lena
                        )
                        VALUES(
                            '$id_new_subindex',
                            '$evalplan_origin->short_description',
                            '$evalplan_origin->learning_description',
                            '$evalplan_origin->no_teacher_registered',
                            NOW(),
                            '$evalplan_origin->no_position',
                            '$evalplan_origin->abbr_lena'
                        )
                        ";
                        if ($insertNewEvalPlan = $attendance->saveAttendance($sqlInsertNewEvalPlan)) {
                            $count++;
                        }
                    }


                    if ($count == $countEvalPlan) {

                        $data = array(
                            'response' => true,
                            'message' => 'Se han importado los aprendizajes esperados de manera exitosa'
                        );
                    } else {
                        $data = array(
                            'response' => false,
                            'message' => ''
                        );
                    }


                    /////////////////
                } else {
                    $data = array(
                        'response' => false,
                        'message' => 'No hay un aprendizajes esperados para este periodo'
                    );
                }
            } else {
                $id_expected_learning_subindex = $EPExist3[0]->id_expected_learning_subindex;




                /* $stmt = "SELECT DISTINCT els.*
                FROM expected_learning.expected_learning_index AS eli
                INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
                 WHERE id_assignment = $id_assignment AND els.id_period_calendar = $id_period";
                $getSubindexOrigin = $groups->getGroupFromTeachers($stmt); */

                $getSubjectInfo = "SELECT * FROM school_control_ykt.subjects WHERE id_subject = $id_subject";
                $subjectInfo = $groups->getGroupFromTeachers($getSubjectInfo);
                $name_subject = $subjectInfo[0]->name_subject;

                $getLevelGradeInfo = "SELECT acl.*, gps.group_code FROM school_control_ykt.groups AS gps
                INNER JOIN school_control_ykt.academic_levels_grade AS acl ON acl.id_level_grade = gps.id_level_grade
                 WHERE id_group = $id_group";
                $levelGradeInfo = $groups->getGroupFromTeachers($getLevelGradeInfo);

                $level_grade_write = $levelGradeInfo[0]->degree;
                $id_level_grade = $levelGradeInfo[0]->id_level_grade;

                $getPeriodInfo = "SELECT * FROM iteach_grades_quantitatives.period_calendar WHERE id_period_calendar = $id_period";
                $periodInfo = $groups->getGroupFromTeachers($getPeriodInfo);
                $no_period = $periodInfo[0]->no_period;



                $stmt3 = "SELECT elc.*
                FROM expected_learning.expected_learning_index AS eli
                INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
                INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
                 WHERE els.id_expected_learning_subindex = $id_expected_learning_subindex_origin";

                $evaluationSubindexOrigin = $groups->getGroupFromTeachers($stmt3);

                if (!empty($evaluationSubindexOrigin)) {
                    $countEvalPlan = count($evaluationSubindexOrigin);
                    $count = 0;
                    foreach ($evaluationSubindexOrigin as $evalplan_origin) {

                        $sqlInsertNewEvalPlan = "INSERT INTO expected_learning.expected_learning_catalog
                        (
                            id_expected_learning_subindex,
                            short_description,
                            learning_description,
                            no_teacher_registered,
                            datelog,
                            no_position,
                            abbr_lena
                        )
                        VALUES(
                            '$id_expected_learning_subindex',
                            '$evalplan_origin->short_description',
                            '$evalplan_origin->learning_description',
                            '$evalplan_origin->no_teacher_registered',
                            NOW(),
                            '$evalplan_origin->no_position',
                            '$evalplan_origin->abbr_lena'
                        )
                        ";
                        if ($insertNewEvalPlan = $attendance->saveAttendance($sqlInsertNewEvalPlan)) {
                            $count++;
                        }
                    }


                    if ($count == $countEvalPlan) {

                        $data = array(
                            'response' => true,
                            'message' => 'Se han importado los aprendizajes esperados de manera exitosa'
                        );
                    } else {
                        $data = array(
                            'response' => false,
                            'message' => ''
                        );
                    }


                    /////////////////
                } else {
                    $data = array(
                        'response' => false,
                        'message' => 'No hay un aprendizajes esperados para este periodo!'
                    );
                }
            }
        } else {

            $data = array(
                'response' => false,
                'message' => 'Ya existen aprendizajes esperados para este periodo'
            );
            echo json_encode($data);
            return;
        }
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se pudo obtener el id de la asignación'
        );
    }


    echo json_encode($data);
}

function getCatalogueFromAnotherAssignment()
{

    $id_subject = $_POST['id_subject'];
    $id_period = $_POST['id_period'];
    $id_academic_area = $_POST['id_academic_area'];
    $id_assignment = $_POST['id_assignment'];
    $id_group = $_POST['id_group'];
    $id_expected_learning_subindex_origin = $_POST['id_expected_learning_subindex'];

    $groups = new Groups;
    $attendance = new Attendance;

    $id_assignment_destiny = 0;
    $sqlGetssignmentDestiny = "SELECT * FROM school_control_ykt.assignments WHERE id_subject = $id_subject AND id_group = $id_group";
    $assignmentDestiny = $groups->getGroupFromTeachers($sqlGetssignmentDestiny);

    $stmt3 = "SELECT elc.*
    FROM expected_learning.expected_learning_index AS eli
    INNER JOIN expected_learning.expected_learning_subindex AS els ON els.id_expected_learning_index = eli.id_expected_learning_index
    INNER JOIN expected_learning.relationship_expected_learning_assignments AS rela ON rela.id_expected_learning_index = eli.id_expected_learning_index
    INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
     WHERE els.id_expected_learning_subindex = $id_expected_learning_subindex_origin";

    $evaluationSubindexOrigin = $groups->getGroupFromTeachers($stmt3);
    $html_sweet = '';
    $html_sweet .= '<div class="row" id="divTableCriteria">';
    $html_sweet .= '<div class="col-md-12">';
    $html_sweet .= '<h4>Importará los siguientes criterios:</h4>';
    $html_sweet .= '<div class="table-responsive">';
    $html_sweet .= '<table class="table table-bordered table-striped table-hover">';
    $html_sweet .= '<thead class="table-dark">';
    $html_sweet .= '<tr>';
    $html_sweet .= '<th>Descripción</th>';
    $html_sweet .= '</tr>';
    $html_sweet .= '</thead>';
    $html_sweet .= '<tbody>';
    if (!empty($evaluationSubindexOrigin)) {

        foreach ($evaluationSubindexOrigin as $orign) {
            $html_sweet .= '<tr>';
            $html_sweet .= '<td>' . $orign->short_description . '</td>';
            $html_sweet .= '</tr>';
        }
        $html_sweet .= '</tbody>';
        $html_sweet .= '</table>';
        $html_sweet .= '</div>';
        $html_sweet .= '</div>';
        $html_sweet .= '</div>';

        $data = array(
            'response' => true,
            'message' => $html_sweet
        );
        /////////////////
    } else {
        $data = array(
            'response' => false,
            'message' => 'No hay un aprendizajes esperados para este periodo!'
        );
        echo json_encode($data);
    }


    echo json_encode($data);
}
function syncAEEVAL()
{
    $id_assignment = $_POST['id_assignment'];
    $id_period_calendar_post = $_POST['id_period_calendar'];

    $groups = new Groups;
    $attendance = new Attendance;

    // REVISAR SI EXISTE ESTRUCTURA DE CALIFICACIONES PARA EL PERIODO
    $sqlCheckStructure = "SELECT * FROM iteach_grades_quantitatives.evaluation_plan WHERE id_period_calendar = $id_period_calendar_post AND id_assignment =  $id_assignment AND id_evaluation_source = 34";

    $CheckStructure = $groups->getGroupFromTeachers($sqlCheckStructure);
    if (!empty($CheckStructure)) {
        $sqlGroupsByLevelCombination = "SELECT DISTINCT groups.id_group,groups.group_code,lvl_comb.id_academic_area, aclvg.degree,CONCAT (colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador, ' ', colab.nombres_colaborador) AS tutor_name
    FROM school_control_ykt.level_combinations AS lvl_comb
    INNER JOIN school_control_ykt.groups AS groups ON lvl_comb.id_campus = groups.id_campus AND lvl_comb.id_section = groups.id_section
    INNER JOIN school_control_ykt.assignments AS assgn ON assgn.id_group = groups.id_group
    INNER JOIN school_control_ykt.academic_levels AS aclv ON lvl_comb.id_academic_level = aclv.id_academic_level
    INNER JOIN school_control_ykt.academic_levels_grade AS aclvg ON groups.id_level_grade = aclvg.id_level_grade AND lvl_comb.id_academic_level = aclvg.id_academic_level
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = groups.no_tutor
    WHERE assgn.id_assignment = '$id_assignment'";
        $sqlGroupsByLevelCombinationResult = $groups->getGroupFromTeachers($sqlGroupsByLevelCombination);

        foreach ($sqlGroupsByLevelCombinationResult as $groupsByLevelCombination) {

            $id_group = $groupsByLevelCombination->id_group;

            $sqlAssignmentByGroup = "SELECT sbj.id_subject, sbj.name_subject, asg.id_assignment, sbj.short_name
        FROM school_control_ykt.assignments AS asg
        INNER JOIN colaboradores_ykt.colaboradores as colab ON asg.no_teacher = colab.no_colaborador
        INNER JOIN school_control_ykt.subjects AS sbj ON sbj.id_subject = asg.id_subject
        WHERE  asg.id_group = '$id_group' AND asg.id_assignment = $id_assignment";

            $sqlAssignmentByGroupResult = $groups->getGroupFromTeachers($sqlAssignmentByGroup);
            foreach ($sqlAssignmentByGroupResult as $assignments) {
                $id_assignment = $assignments->id_assignment;

                $info_grade = "SELECT *, elc.id_expected_learning_subindex FROM expected_learning.expected_learning_deliverables AS eld
            INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_catalog = eld.id_expected_learning_catalog
            INNER JOIN expected_learning.expected_learning_subindex AS els ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex AND els.id_period_calendar = $id_period_calendar_post
            INNER JOIN expected_learning.expected_learning_index AS eli ON els.id_expected_learning_index = eli.id_expected_learning_index
            INNER JOIN expected_learning.relationship_expected_learning_assignments AS rel ON eli.id_expected_learning_index = rel.id_expected_learning_index
            WHERE (rel.id_assignment = $id_assignment)
            ";
                $info_grade_result = $groups->getGroupFromTeachers($info_grade);
                if (!empty($info_grade_result)) {
                    $total_faltantes = 0;
                    foreach ($info_grade_result as $grade_result) {
                        $id_expected_learning_deliverables = $grade_result->id_expected_learning_deliverables;
                        $id_expected_learning_catalog = $grade_result->id_expected_learning_catalog;
                        $id_student = $grade_result->id_student;
                        $id_period_calendar = $grade_result->id_period_calendar;
                        $id_expected_learning_catalog = $grade_result->id_expected_learning_catalog;
                        $date_log = $grade_result->date_log;
                        $id_expected_learning_subindex = $grade_result->id_expected_learning_subindex;

                        $total_faltantes++;

                        $get_id_assignment = "SELECT id_assignment FROM `expected_learning`.`expected_learning_catalog` AS elc 
                    INNER JOIN expected_learning.expected_learning_subindex AS els ON elc.id_expected_learning_subindex = els.id_expected_learning_subindex
                    INNER JOIN expected_learning.expected_learning_index AS eli ON els.id_expected_learning_index = eli.id_expected_learning_index
                    INNER JOIN expected_learning.relationship_expected_learning_assignments AS rel ON eli.id_expected_learning_index = rel.id_expected_learning_index
                    WHERE elc.id_expected_learning_catalog = $id_expected_learning_catalog AND els.id_period_calendar = $id_period_calendar_post";
                        $get_id_assignment_result = $groups->getGroupFromTeachers($get_id_assignment);
                        if (!empty($get_id_assignment_result)) {
                            $id_assignment = $get_id_assignment_result[0]->id_assignment;
                            $stmt_get_avg = "SELECT AVG(teacher_evidence_quailification) AS student_avg
                    FROM expected_learning.expected_learning_deliverables AS eld
                    INNER JOIN expected_learning.expected_learning_catalog AS elc ON elc.id_expected_learning_catalog = eld.id_expected_learning_catalog
                    WHERE  id_student = '$id_student' 
                        AND teacher_evidence_quailification IS NOT NULL 
                        AND eld.id_period_calendar = '$id_period_calendar' AND id_expected_learning_subindex = '$id_expected_learning_subindex'";

                            $getAvg = $groups->getGroupFromTeachers($stmt_get_avg);
                            $student_avg = 'NULL';
                            if (!empty($getAvg)) {
                                $student_avg = $getAvg[0]->student_avg;
                                if ($student_avg == '') {
                                    $student_avg = 'NULL';
                                } else {
                                    $student_avg = number_format($student_avg, 1);
                                }
                            } else {
                                $student_avg = 'NULL';
                            }


                            $stmt_check_avg_structure = "SELECT * FROM expected_learning.expected_learning_period_average
                    WHERE id_student = '$id_student' AND id_period_calendar = '$id_period_calendar' AND id_assignment = '$id_assignment'";
                            $check_avg_structure = $groups->getGroupFromTeachers($stmt_check_avg_structure);

                            if (empty($check_avg_structure)) {
                                $stmt_insert_avg = "INSERT INTO expected_learning.expected_learning_period_average (
                            id_student,
                            id_period_calendar,
                            id_assignment,
                            student_average,
                            datelog
                             ) VALUES (
                            '$id_student',
                            '$id_period_calendar',
                            '$id_assignment',
                            '$student_avg',
                            NOW())";

                                $attendance->saveAttendance($stmt_insert_avg);
                            } else {

                                $stmt_get_st_avg = "UPDATE expected_learning.expected_learning_period_average
                        SET student_average = $student_avg
                        WHERE id_student = '$id_student' AND id_period_calendar = '$id_period_calendar' AND id_assignment = '$id_assignment'";

                                $attendance->saveAttendance($stmt_get_st_avg);
                            }
                            $infoCriteriaPE = checkCriteriaPE($id_student, $id_period_calendar, $id_assignment);
                            if (!empty($infoCriteriaPE)) {


                                $grade = '';

                                if ($student_avg == '' || $student_avg == 'NULL') {
                                    $grade = 'NULL';
                                } else {
                                    $grade = $student_avg;
                                }

                                foreach ($infoCriteriaPE as $data) {
                                    $id_grades_evaluation_criteria = $data->id_grades_evaluation_criteria;
                                    $id_final_grade = $data->id_final_grade;
                                    $id_grade_period = $data->id_grade_period;

                                    $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria SET grade_evaluation_criteria_teacher = $grade WHERE id_grades_evaluation_criteria = $id_grades_evaluation_criteria";

                                    if ($attendance->saveAttendance($sql)) {
                                        calculateAveragePerPeriod($id_final_grade, $id_period_calendar);
                                        $grade_period =  getGradePeriod($id_grade_period);

                                        /* if (!empty(checkDynamicCalculationByAssg($id_assignment))) {
                                    calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period[0]->grade_period, $id_period_calendar);
                                    $grade_period = getGradePeriod($id_grade_period);
                                } */


                                        /*  $someCriteriaOperational = checkAnyCriteriaOperational($id_final_grade, $id_grade_period);

                                foreach ($someCriteriaOperational as $info_operational) {
                                    $grade_period = getGradePeriod($id_grade_period);
                                    calculateAveragePeriodByCriteriaDynamic($id_grade_period, $info_operational->note_criteria, $info_operational->id_evaluation_plan, $grade_period[0]->grade_period_calc);
                                } */
                                    } else {
                                    }
                                }
                            }
                            $data = array(
                                'response' => 'true',
                                'message' => ''
                            );
                        } else {
                        }
                    }
                } else {
                    $data = array(
                        'response' => 'false',
                        'message' => 'Revisar estructura de A.E.'
                    );
                }
            }
        }
    } else {
        $data = array(
            'response' => 'false',
            'message' => 'Revisar estructura de calificaciones'
        );
    }
    echo json_encode($data);
}


function checkCriteriaPE($id_student, $id_period_calendar, $id_assignment)
{

    $results = array();

    $groups = new Groups;
    $attendance = new Attendance;

    $query = "    SELECT evp.id_evaluation_plan, gec.id_grades_evaluation_criteria, fga.id_final_grade, gp.id_grade_period
        FROM iteach_grades_quantitatives.final_grades_assignment AS fga
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON fga.id_final_grade = gec.id_final_grade
        INNER JOIN iteach_grades_quantitatives.evaluation_plan AS evp ON gec.id_evaluation_plan = evp.id_evaluation_plan AND fga.id_assignment = evp.id_assignment
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS ev_source ON evp.id_evaluation_source = ev_source.id_evaluation_source
        INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON evp.id_period_calendar = gp.id_period_calendar AND fga.id_final_grade = gp.id_final_grade
        WHERE fga.id_student = $id_student AND fga.id_assignment = $id_assignment AND gp.id_period_calendar = $id_period_calendar AND ev_source.id_evaluation_source = 34
        ";
    $results = $groups->getGroupFromTeachers($query);



    return $results;
}


function calculateAveragePerPeriod($id_final_grade, $id_period_calendar)
{

    $groups = new Groups;
    $attendance = new Attendance;
    $results              = true;
    $divide_percentage    = false;
    $percentage_calculate = 0;

    $id_grade_period = null;

    $stmt = "
    SELECT ev_plan.id_evaluation_plan, ev_plan.percentage, grade_period.id_grade_period
    FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
    INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ev_plan.id_assignment = fg.id_assignment
    INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON fg.id_final_grade = grade_period.id_final_grade
    INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON grade_period.id_period_calendar  = pc.id_period_calendar AND ev_plan.id_period_calendar = pc.id_period_calendar
    INNER JOIN iteach_grades_quantitatives.evaluation_type AS evt ON ev_plan.evaluation_type_id = evt.evaluation_type_id
    WHERE fg.id_final_grade = '$id_final_grade' AND grade_period.id_period_calendar = '$id_period_calendar' AND ev_plan.affects_evaluation = 1 AND evt.group_id != 2
    ";

    $query = $groups->getGroupFromTeachers($stmt);

    //--- PROCESO PARA VERIFICAR SI TODOS ESTÁN EN 0% Y DIVIDIR EL PORCENTAJE EN PARTES IGUALES ---//
    $totalResult = count($query);

    $query1 = $groups->getGroupFromTeachers("
        SELECT ev_plan.id_evaluation_plan, ev_plan.percentage, grade_period.id_grade_period
        FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ev_plan.id_assignment = fg.id_assignment
        INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON fg.id_final_grade = grade_period.id_final_grade
        INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON grade_period.id_period_calendar  = pc.id_period_calendar AND ev_plan.id_period_calendar = pc.id_period_calendar
        INNER JOIN iteach_grades_quantitatives.evaluation_type AS evt ON ev_plan.evaluation_type_id = evt.evaluation_type_id
        WHERE fg.id_final_grade = '$id_final_grade' AND grade_period.id_period_calendar = '$id_period_calendar' AND (ev_plan.percentage = '0' OR ev_plan.percentage = '' OR ev_plan.percentage = null) AND ev_plan.affects_evaluation = 1 AND evt.group_id != 2
        ");

    $totalResult0 = count($query1);
    if ($totalResult == $totalResult0) {
        $divide_percentage    = true;
        $percentage_calculate = 100 / $totalResult;
    }
    //--- --- ---//

    $final_grade_period = null;

    $query = $groups->getGroupFromTeachers($stmt);
    foreach ($query as $row) {
        //--- --- ---//
        $id_evaluation_plan = $row->id_evaluation_plan;

        if ($divide_percentage) {
            $percentage = $percentage_calculate;
        } else {
            $percentage = $row->percentage;
        }
        $id_grade_period = $row->id_grade_period;
        $grade           = 0;
        //--- --- ---//
        $query1 = $groups->getGroupFromTeachers("
            SELECT ev_critera.id_grades_evaluation_criteria, ev_critera.grade_evaluation_criteria_teacher
            FROM iteach_grades_quantitatives.grades_evaluation_criteria AS ev_critera
            INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg on ev_critera.id_final_grade = fg.id_final_grade
            INNER JOIN iteach_grades_quantitatives.grades_period AS gp ON ev_critera.id_grade_period = gp.id_grade_period
            WHERE ev_critera.id_evaluation_plan = '$id_evaluation_plan' AND fg.id_final_grade = '$id_final_grade' AND ev_critera.id_grade_period = '$id_grade_period'
            ");

        foreach ($query1 as $row1) {
            $grade                         = $row1->grade_evaluation_criteria_teacher;
            $id_grades_evaluation_criteria = $row1->id_grades_evaluation_criteria;
            //--- --- ---//
            $fragment_set_sql = "";

            if ($grade != null) {
                //--- OBTENEMOS A QUE PORCENTAJE EQUIVALE LA CALIFIFACIÓN ---//
                $final_percentage_criteria = ($grade * $percentage) / 10;

                //--- OBTENEMOS A QUE CALIFICACIÓN REAL EQUIVALE ---//
                $grade_real_criteria = ($final_percentage_criteria * 10) / 100;
                $grade_real_criteria = number_format($grade_real_criteria, 1);
                $final_grade_period += $grade_real_criteria;
                //--- --- ---//

                $fragment_set_sql = "SET grade_evaluation_criteria_system = '$grade_real_criteria', percentage_evaluation_criteria = '$final_percentage_criteria'";
            } else {
                $grade_real_criteria       = 0;
                $final_percentage_criteria = 0;

                $fragment_set_sql = "SET grade_evaluation_criteria_system = NULL, percentage_evaluation_criteria = NULL";
            }

            //--- ACTUALIZAMOS EL PROMEDIO Y CALIFICACION CALCULADAS POR SISTEMA ---//
            $sql = "UPDATE iteach_grades_quantitatives.grades_evaluation_criteria " . $fragment_set_sql . " WHERE id_grades_evaluation_criteria = '$id_grades_evaluation_criteria'";
            $attendance->saveAttendance($sql);
            //--- --- ---//
        }
    }

    if ($final_grade_period != null) {
        //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
        $final_grade_period = number_format($final_grade_period, 1);
        $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period = '$final_grade_period' WHERE id_grade_period = '$id_grade_period'";
        $attendance->saveAttendance($sql);
    } else {
        //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
        $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period = NULL WHERE id_grade_period = '$id_grade_period'";
        $attendance->saveAttendance($sql);
    }

    calculateFinalGrade($id_final_grade);
}

function calculateFinalGrade($id_final_grade)
{
    $final_grade = null;


    $groups = new Groups;
    $attendance = new Attendance;

    $query = $groups->getGroupFromTeachers("SELECT gp.grade_period
        FROM iteach_grades_quantitatives.grades_period AS gp
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
        WHERE fg.id_final_grade = '$id_final_grade' AND gp.grade_period != '0' AND gp.grade_period != ''
        ");

    foreach ($query as $row) {
        $final_grade += floatval($row->grade_period);
    }

    $query = $groups->getGroupFromTeachers("
        SELECT gp.grade_period
        FROM iteach_grades_quantitatives.grades_period AS gp
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON gp.id_final_grade = fg.id_final_grade
        WHERE fg.id_final_grade = '$id_final_grade' AND gp.grade_period != '0' AND gp.grade_period != ''
        ");

    $fragment_set_sql = '';
    if ($final_grade > 0) {
        $final_grade      = $final_grade / count($query);
        $fragment_set_sql = "SET final_grade = '$final_grade'";
    } else if ($final_grade == null) {
        $fragment_set_sql = "SET final_grade = NULL";
    }

    //--- ACTUALIZAMOS EL PROMEDIO FINAL DE LA ASIGNATURA ---//
    $sql = "UPDATE iteach_grades_quantitatives.final_grades_assignment " . $fragment_set_sql . " WHERE id_final_grade = '$id_final_grade'";
    $attendance->saveAttendance($sql);
}
function getGradePeriod($id_grade_period)
{


    $groups = new Groups;
    $attendance = new Attendance;
    $results = array();

    $query = $groups->getGroupFromTeachers("SELECT grade_period, id_extraordinary_exams, grade_extraordinary_examen, gp.grade_period_calc
            FROM iteach_grades_quantitatives.grades_period AS gp
            LEFT JOIN iteach_grades_quantitatives.extraordinary_exams AS ex_ex ON gp.id_grade_period = ex_ex.id_grade_period AND gp.id_final_grade = ex_ex.id_final_grade
            WHERE gp.id_grade_period = $id_grade_period");

    if (!empty($query)) {
        $results = $query;
    }

    return $results;
}

function checkDynamicCalculationByAssg($id_assignment)
{
    $results = array();

    $groups = new Groups;
    $attendance = new Attendance;

    $query = $groups->getGroupFromTeachers("SELECT om.operation_model_img
        FROM iteach_dynamic_calculations.operation_model_assignment AS omass
        INNER JOIN iteach_dynamic_calculations.operations_models AS om ON omass.operation_model_id = om.operation_model_id
        WHERE omass.id_assignment = '$id_assignment' LIMIT 1");

    if (!empty($query)) {
        $results = $query;
    }


    return $results;
}

function calculateAveragePerPeriodDynamic($id_assignment, $id_grade_period, $grade_period_base, $id_period_calendar)
{
    $op_model_assg_id = 0;

    $groups = new Groups;
    $attendance = new Attendance;

    $query1 = $groups->getGroupFromTeachers("
        SELECT op_model_assg_id
        FROM iteach_dynamic_calculations.operation_model_assignment 
        WHERE id_assignment = '$id_assignment'
        ORDER BY op_model_assg_id DESC LIMIT 1");

    foreach ($query1 as $row1) {
        $op_model_assg_id = $row1->op_model_assg_id;
    }

    //--- OBTENEMOS TODOS LOS CRITERIO BASE PARA BUSCAR SU ID EN RELLOCATION ---//
    $query = $groups->getGroupFromTeachers("
        SELECT ev_plan.id_evaluation_plan, ev_plan.percentage AS base_percentage, fg.final_grade
        FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
        INNER JOIN iteach_grades_quantitatives.final_grades_assignment AS fg ON ev_plan.id_assignment = fg.id_assignment
        INNER JOIN iteach_grades_quantitatives.grades_period AS grade_period ON fg.id_final_grade = grade_period.id_final_grade
        INNER JOIN iteach_grades_quantitatives.period_calendar AS pc ON grade_period.id_period_calendar  = pc.id_period_calendar AND ev_plan.id_period_calendar = pc.id_period_calendar
        INNER JOIN iteach_grades_quantitatives.evaluation_type AS evt ON ev_plan.evaluation_type_id = evt.evaluation_type_id
        WHERE grade_period.id_grade_period = '$id_grade_period' AND grade_period.id_period_calendar = '$id_period_calendar' AND ev_plan.affects_evaluation = 1 AND evt.group_id != 2
        ");
    //--- --- ---//

    $calculated_percentage = 0;
    $final_grade_period = null;

    foreach ($query as $row) {
        //--- --- ---//
        $id_evaluation_plan = $row->id_evaluation_plan;
        $final_grade = $row->final_grade;
        $calculated_percentage = $row->base_percentage;
        //--- --- ---//
        $query2 = $groups->getGroupFromTeachers("
            SELECT condition_grade, percentage
            FROM iteach_dynamic_calculations.reallocation_percentages
            WHERE id_evaluation_plan = '$id_evaluation_plan' AND op_model_assg_id = '$op_model_assg_id'
            ");

        foreach ($query2 as $row2) {

            $condition_grade = $row2->condition_grade;
            $validation_final = null;

            //--- Buscamos gradeP ---//
            $position = strpos($condition_grade, ':gradeP:');
            if ($position !== false) {
                $validation_final = str_replace(":gradeP:", $grade_period_base, $condition_grade);
            }

            //--- Buscamos gradeFG ---//
            $position = strpos($condition_grade, ':gradeFG:');
            if ($position !== false) {
                $validation_final = str_replace(":gradeFG:", $final_grade, $condition_grade);
            }

            if ($grade_period_base != null || $final_grade != null) {
                if ($validation_final != null) {
                    eval("\$fn_validation = $validation_final;");

                    if ($fn_validation) {
                        $calculated_percentage = $row2->percentage;
                    }
                }
            }
        }

        $query1 = $groups->getGroupFromTeachers("
            SELECT ev_critera.grade_evaluation_criteria_teacher
            FROM iteach_grades_quantitatives.grades_evaluation_criteria AS ev_critera
            WHERE ev_critera.id_evaluation_plan = '$id_evaluation_plan' AND ev_critera.id_grade_period = '$id_grade_period'
            ");

        foreach ($query1 as $row1) {
            $grade  = $row1->grade_evaluation_criteria_teacher;
            //--- --- ---//
            $fragment_set_sql = "";

            if ($grade != null) {
                //--- OBTENEMOS A QUE PORCENTAJE EQUIVALE LA CALIFIFACIÓN ---//
                $final_percentage_criteria = ($grade * $calculated_percentage) / 10;

                //--- OBTENEMOS A QUE CALIFICACIÓN REAL EQUIVALE ---//
                $grade_real_criteria = ($final_percentage_criteria * 10) / 100;
                $grade_real_criteria = number_format($grade_real_criteria, 1);
                $final_grade_period += $grade_real_criteria;
                //--- --- ---//
            }
        }
    }

    if ($final_grade_period != null) {
        //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
        $final_grade_period = number_format($final_grade_period, 1);

        $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = '$final_grade_period' WHERE id_grade_period = '$id_grade_period'";

        $stmt = $attendance->saveAttendance($sql);
    } else {
        //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
        $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = NULL WHERE id_grade_period = '$id_grade_period'";

        $stmt = $attendance->saveAttendance($sql);
    }
}

function checkAnyCriteriaOperational($id_final_grade, $id_grade_period)
{

    $results = array();


    $groups = new Groups;
    $attendance = new Attendance;

    $query = $groups->getGroupFromTeachers("
        SELECT gec.grade_evaluation_criteria_teacher AS note_criteria, ev_source.criteria_set_id, ev_plan.id_evaluation_plan
        FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
        INNER JOIN iteach_grades_quantitatives.evaluation_source AS ev_source ON ev_plan.id_evaluation_source = ev_source.id_evaluation_source
        INNER JOIN iteach_grades_quantitatives.grades_evaluation_criteria AS gec ON ev_plan.id_evaluation_plan = gec.id_evaluation_plan
        WHERE gec.id_final_grade = '$id_final_grade' AND gec.id_grade_period = '$id_grade_period' AND ev_source.criteria_set_id = 2
        ");

    if (!empty($query)) {
        $results = $query;
    }


    return $results;
}

function calculateAveragePeriodByCriteriaDynamic($id_grade_period, $note_criteria, $id_evaluation_plan, $grade_period_base)
{



    $groups = new Groups;
    $attendance = new Attendance;
    //--- OBTENEMOS TODOS LOS CRITERIO BASE PARA BUSCAR SU ID EN RELLOCATION ---//
    $query = $groups->getGroupFromTeachers("
        SELECT fml.formulation_operations
        FROM iteach_grades_quantitatives.evaluation_plan AS ev_plan
        INNER JOIN iteach_grades_quantitatives.formulation_operations AS fml ON ev_plan.formulation_operations_id = fml.formulation_operations_id
        WHERE ev_plan.id_evaluation_plan = '$id_evaluation_plan' AND fml.formulation_operations_id != 1
        ");
    //--- --- ---//

    $calculated_percentage = 0;
    $final_grade_period = null;

    if (is_float($note_criteria)) {
        $note_criteria = floatval($note_criteria);
    } else {
        $note_criteria = strval($note_criteria);
    }

    foreach ($query as $row) {
        //--- --- ---//
        $arr_data = array();
        $arr_data = $row->formulation_operations;
        $arr_data = explode(";", $arr_data);

        //intval(':value:') < 85;:gradeP:-(:gradeP:*.15)

        if (count($arr_data) == 2) {
            $condition_grade = $arr_data[0];
            $formulation_grade = $arr_data[1];
            $final_grade_period = $grade_period_base;
            //--- --- ---//
            $validation_final = null;

            //--- Buscamos :value: ---//
            $position = strpos($condition_grade, ':value:');
            if ($position !== false) {
                $validation_final = str_replace(":value:", $note_criteria, $condition_grade);
            }

            //--- Buscamos :gradeP: ---//
            $position = strpos($validation_final, ':gradeP:');
            if ($position !== false) {
                $validation_final = str_replace(":gradeP:", $grade_period_base, $validation_final);
            }

            if ($validation_final != null) {
                try {
                    eval("\$fn_validation = $validation_final;");
                } catch (ParseError $e) {
                    echo 'Errorsito';
                    echo $validation_final;
                    echo '<br/>';
                    print_r($e);
                }

                if ($fn_validation && $grade_period_base != '') {
                    //--- Re-calculamos el valor de GradePeriod ---//
                    //--- Buscamos :value: ---//
                    $position = strpos($formulation_grade, ':value:');
                    if ($position !== false) {
                        $formulation_grade = str_replace(":value:", $note_criteria, $formulation_grade);
                    }

                    $position = strpos($formulation_grade, ':gradeP:');
                    if ($position !== false) {
                        $f_grade_period = str_replace(":gradeP:", $grade_period_base, $formulation_grade);
                        try {
                            eval("\$final_grade_period = $f_grade_period;");
                        } catch (ParseError $e) {
                            echo 'Errorsito';
                            echo $f_grade_period;
                            echo '<br/>';
                            print_r($e);
                        }
                        $final_grade_period = $final_grade_period;
                    }
                }
            }
        }
    }

    if ($final_grade_period != null) {
        //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
        $final_grade_period = number_format($final_grade_period, 1);

        $final_grade_period = $final_grade_period > 10 ? 10 : $final_grade_period;

        $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = '$final_grade_period' WHERE id_grade_period = '$id_grade_period'";

        $stmt = $attendance->saveAttendance($sql);
    } else {
        //--- ACTUALIZAMOS EL PROMEDIO FINAL POR PERIODO ---//
        $sql = "UPDATE iteach_grades_quantitatives.grades_period SET grade_period_calc = NULL WHERE id_grade_period = '$id_grade_period'";

        $stmt = $attendance->saveAttendance($sql);
    }
}
