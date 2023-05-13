<?php
include '../../../general/php/models/Connection.php';
include '../models/groups.php';
include '../models/attendance.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once '../../../general/php/src/Exception.php';
include_once '../../../general/php/src/PHPMailer.php';
include_once '../../../general/php/src/SMTP.php';


session_start();
date_default_timezone_set('America/Mexico_City');
$function = $_POST['mod'];
$function();


function saveInterview()
{

    $id_student = $_POST['id_student'];
    $terapeuta = $_POST['terapeuta'];
    $tipo_intervencion = $_POST['tipo_intervencion'];
    $referido_por = $_POST['referido_por'];
    $motivo = $_POST['motivo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $causa_cocluyo = $_POST['causa_cocluyo'];
    $causa_cocluyo = br2nl($causa_cocluyo);


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "INSERT INTO psychopedagogy.therapeutic_cards
                (
                    id_student,
                    name_of_proffessional,
                    kind_of_interview,
                    who_reffered,
                    reason_why_reffered,
                    start_date,
                    end_date,
                    cause_why_conclused,
                    logdate,
                    no_colab_registered
                    ) VALUES (
                        $id_student,
                        '$terapeuta',
                        '$tipo_intervencion',
                        '$referido_por',
                        '$motivo',
                        '$fecha_inicio',
                        '$fecha_fin',
                        '$causa_cocluyo',
                        NOW(),
                        '$_SESSION[colab]'
                    )";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();
        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}

function deleteInterview()
{
    $id = $_POST['id_card'];
    $attendance = new Attendance;

    $stmt = "DELETE FROM psychopedagogy.therapeutic_cards_archives WHERE id_therapeutic_cards = $id;
    DELETE FROM psychopedagogy.terapeutic_cards_chat WHERE id_therapeutic_cards = $id;
    DELETE FROM psychopedagogy.therapeutic_cards WHERE id_therapeutic_cards = $id;";

    if ($attendance->saveAttendance($stmt)) {
        $data = array(
            'response' => true,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}

function getInterviewTracking()
{
    $id_card = $_POST['id_card'];
    $groups = new Groups;
    $attendance = new Attendance;

    $stmt = "SELECT tcc.*, 
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name, tcca.route_archive, tcca.archive_type
    FROM psychopedagogy.terapeutic_cards_chat AS tcc
    LEFT JOIN psychopedagogy.tcc_archives AS tcca ON tcca.id_tcc_archives = tcc.id_tcc_archives
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = tcc.no_colab_registered
    WHERE id_therapeutic_cards = $id_card";

    $data_interview = $groups->getGroupFromTeachers($stmt);

    if (!empty($data_interview)) {
        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron registros'
        );
    }

    echo json_encode($data);
}

function saveInterviewTracking()
{

    $id_card = $_POST['id_card'];
    $id_teacher_tracking = $_POST['id_teacher_tracking'];
    $comentario_seguimientos = $_POST['comentario_seguimientos'];
    $comentario_seguimientos = br2nl($comentario_seguimientos);


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "INSERT INTO psychopedagogy.terapeutic_cards_chat
                (
                    id_therapeutic_cards,
                    chat_message,
                    datelog,
                    no_colab_registered
                    ) VALUES (
                        $id_card,
                        '$comentario_seguimientos',
                        NOW(),
                        '$id_teacher_tracking'
                    )";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function deleteInterviewTrackingComment()
{

    $id_commentary = $_POST['id_commentary'];

    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "DELETE  FROM psychopedagogy.terapeutic_cards_chat WHERE id_terapeutic_cards_chat = $id_commentary";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function deleteInterviewTrackingCommentParents()
{

    $id_commentary = $_POST['id_commentary'];

    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "DELETE  FROM psychopedagogy.parents_tracking_chat WHERE id_parents_tracking_chat = $id_commentary";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function getInterviewDetails()
{
    $id_card = $_POST['id_card'];
    $groups = new Groups;
    $attendance = new Attendance;

    $stmt = "SELECT tc.*, 
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name
    FROM psychopedagogy.therapeutic_cards AS tc
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = tc.no_colab_registered
    WHERE id_therapeutic_cards = $id_card";

    $data_interview = $groups->getGroupFromTeachers($stmt);

    if (!empty($data_interview)) {
        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron registros'
        );
    }

    echo json_encode($data);
}

function updateInterview()
{
    $id_card = $_POST['id_card'];
    $terapeuta = $_POST['terapeuta'];
    $tipo_intervencion = $_POST['tipo_intervencion'];
    $referido_por = $_POST['referido_por'];
    $motivo = $_POST['motivo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $causa_cocluyo = $_POST['causa_cocluyo'];
    $causa_cocluyo = br2nl($causa_cocluyo);

    $groups = new Groups;
    $attendance = new Attendance;

    $stmt = "UPDATE psychopedagogy.therapeutic_cards SET 
    name_of_proffessional = '$terapeuta',
    kind_of_interview = '$tipo_intervencion',
    who_reffered = '$referido_por',
    reason_why_reffered = '$motivo',
    start_date = '$fecha_inicio',
    end_date = '$fecha_fin',
    cause_why_conclused = '$causa_cocluyo'
    WHERE id_therapeutic_cards = $id_card
    ";

    if ($attendance->saveAttendance($stmt)) {

        $data = array(
            'response' => true
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }
    echo json_encode($data);
}

function saveTccArchive()
{

    $attendance = new Attendance;


    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;


    $nm_Archivo = "upload_" . $fecha_archivo . "-" . time();
    $extension_img = basename($_FILES["archive_tracking"]["type"]);

    $directorio_archive =  dirname(__DIR__ . '', 2) . '/uploads_documents/psychopedagogy_archives';

    $archivo_img = $directorio_archive . "/" .  $nm_Archivo . "." . $extension_img;

    $ruta_sql_img = 'uploads_documents/psychopedagogy_archives/' .  $nm_Archivo . "." . $extension_img;

    if (!file_exists($directorio_archive)) {
        mkdir($directorio_archive, 0777, true);
    }

    if (move_uploaded_file($_FILES["archive_tracking"]["tmp_name"], $archivo_img)) {

        $data = array(
            'response' => true,
            'message' => 'Se guardó el archivo correctamente'
        );
        //echo json_encode($data);

        $stmt = "INSERT INTO psychopedagogy.tcc_archives
    (
        archive_name,
        archive_type,
        route_archive,
        datelog,
        no_colab_upload
    ) VALUES(
        '$nm_Archivo',
        '$extension_img',
        '$ruta_sql_img',
        NOW(),
        '$_SESSION[colab]'
    )";

        if ($attendance->saveAttendance($stmt)) {
            $last_id = $attendance->getLastId();

            $data = array(
                'response' => true,
                'id' => $last_id,
                'ruta_sql_img' => $ruta_sql_img,
                'extension_img' => $extension_img
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al guardar el registro'
            );
        }
        echo json_encode($data);
    }
}
function saveTccArchiveParents()
{

    $attendance = new Attendance;


    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;


    $nm_Archivo = "upload_" . $fecha_archivo . "-" . time();
    $extension_img = basename($_FILES["archive_tracking_parents"]["type"]);

    $directorio_archive =  dirname(__DIR__ . '', 2) . '/uploads_documents/psychopedagogy_archives';

    $archivo_img = $directorio_archive . "/" .  $nm_Archivo . "." . $extension_img;

    $ruta_sql_img = 'uploads_documents/psychopedagogy_archives/' .  $nm_Archivo . "." . $extension_img;

    if (!file_exists($directorio_archive)) {
        mkdir($directorio_archive, 0777, true);
    }

    if (move_uploaded_file($_FILES["archive_tracking_parents"]["tmp_name"], $archivo_img)) {

        $data = array(
            'response' => true,
            'message' => 'Se guardó el archivo correctamente'
        );
        //echo json_encode($data);

        $stmt = "INSERT INTO psychopedagogy.parents_tracking_archives
    (
        archive_name,
        archive_type,
        route_archive,
        datelog,
        no_colab_upload
    ) VALUES(
        '$nm_Archivo',
        '$extension_img',
        '$ruta_sql_img',
        NOW(),
        '$_SESSION[colab]'
    )";

        if ($attendance->saveAttendance($stmt)) {
            $last_id = $attendance->getLastId();

            $data = array(
                'response' => true,
                'id' => $last_id,
                'ruta_sql_img' => $ruta_sql_img,
                'extension_img' => $extension_img
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al guardar el registro'
            );
        }
        echo json_encode($data);
    }
}
function saveTccArchiveChat()
{

    $attendance = new Attendance;


    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;


    $nm_Archivo = "upload_" . $fecha_archivo . "-" . time();
    $extension_img = basename($_FILES["archive_tracking"]["type"]);

    $directorio_archive =  dirname(__DIR__ . '', 2) . '/uploads_documents/psychopedagogy_archives';

    $archivo_img = $directorio_archive . "/" .  $nm_Archivo . "." . $extension_img;

    $ruta_sql_img = 'uploads_documents/psychopedagogy_archives/' .  $nm_Archivo . "." . $extension_img;

    if (!file_exists($directorio_archive)) {
        mkdir($directorio_archive, 0777, true);
    }

    if (move_uploaded_file($_FILES["archive_tracking"]["tmp_name"], $archivo_img)) {

        $data = array(
            'response' => true,
            'message' => 'Se guardó el archivo correctamente'
        );
        //echo json_encode($data);

        $stmt = "INSERT INTO psychopedagogy.tcc_archives
    (
        archive_name,
        archive_type,
        route_archive,
        datelog,
        no_colab_upload
    ) VALUES(
        '$nm_Archivo',
        '$extension_img',
        '$ruta_sql_img',
        NOW(),
        '$_SESSION[colab]'
    )";

        if ($attendance->saveAttendance($stmt)) {
            $last_id = $attendance->getLastId();

            $stmt = "UPDATE psychopedagogy.terapeutic_cards_chat SET id_tcc_archives = $last_id WHERE id_terapeutic_cards_chat = $_POST[id_chat]";
            if ($attendance->saveAttendance($stmt)) {
                $data = array(
                    'response' => true,
                    'id' => $last_id,
                    'ruta_sql_img' => $ruta_sql_img,
                    'extension_img' => $extension_img
                );
            } else {
                $data = array(
                    'response' => false,
                    'message' => 'Ocurrió un error al guardar el registro'
                );
            }
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al guardar el registro'
            );
        }
        echo json_encode($data);
    }
}
function saveTccArchiveChatParents()
{

    $attendance = new Attendance;


    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;


    $nm_Archivo = "upload_" . $fecha_archivo . "-" . time();
    $extension_img = basename($_FILES["archive_tracking"]["type"]);

    $directorio_archive =  dirname(__DIR__ . '', 2) . '/uploads_documents/psychopedagogy_archives';

    $archivo_img = $directorio_archive . "/" .  $nm_Archivo . "." . $extension_img;

    $ruta_sql_img = 'uploads_documents/psychopedagogy_archives/' .  $nm_Archivo . "." . $extension_img;

    if (!file_exists($directorio_archive)) {
        mkdir($directorio_archive, 0777, true);
    }

    if (move_uploaded_file($_FILES["archive_tracking"]["tmp_name"], $archivo_img)) {

        $data = array(
            'response' => true,
            'message' => 'Se guardó el archivo correctamente'
        );
        //echo json_encode($data);

        $stmt = "INSERT INTO psychopedagogy.parents_tracking_archives
    (
        archive_name,
        archive_type,
        route_archive,
        datelog,
        no_colab_upload
    ) VALUES(
        '$nm_Archivo',
        '$extension_img',
        '$ruta_sql_img',
        NOW(),
        '$_SESSION[colab]'
    )";

        if ($attendance->saveAttendance($stmt)) {
            $last_id = $attendance->getLastId();

            $stmt = "UPDATE psychopedagogy.parents_tracking_chat SET id_ptc_archive = $last_id WHERE id_parents_tracking_chat = $_POST[id_chat]";
            if ($attendance->saveAttendance($stmt)) {
                $data = array(
                    'response' => true,
                    'id' => $last_id,
                    'ruta_sql_img' => $ruta_sql_img,
                    'extension_img' => $extension_img
                );
            } else {
                $data = array(
                    'response' => false,
                    'message' => 'Ocurrió un error al guardar el registro'
                );
            }
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al guardar el registro'
            );
        }
        echo json_encode($data);
    }
}
function saveInterviewTrackingArchive()
{

    $id_card = $_POST['id_card'];
    $id_teacher_tracking = $_POST['id_teacher_tracking'];
    $comentario_seguimientos = $_POST['comentario_seguimientos'];
    $comentario_seguimientos = br2nl($comentario_seguimientos);
    $id_archive = $_POST['id_archive'];


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "INSERT INTO psychopedagogy.terapeutic_cards_chat
                (
                    id_therapeutic_cards,
                    chat_message,
                    datelog,
                    no_colab_registered,
                    id_tcc_archives
                    ) VALUES (
                        $id_card,
                        '$comentario_seguimientos',
                        NOW(),
                        '$id_teacher_tracking',
                        $id_archive
                    )";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}

function saveInterviewTrackingArchiveParents()
{

    $id_parents_tracking = $_POST['id_parents_tracking'];
    $id_teacher_tracking_parents = $_POST['id_teacher_tracking_parents'];
    $comentario_seguimientos_padres = $_POST['comentario_seguimientos_padres'];
    $comentario_seguimientos_padres = br2nl($comentario_seguimientos_padres);
    $id_archive = $_POST['id_archive'];


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "INSERT INTO psychopedagogy.parents_tracking_chat
                (
                    id_parents_tracking,
                    chat_message,
                    datelog,
                    no_colab_registered,
                    id_ptc_archive
                    ) VALUES (
                        $id_parents_tracking,
                        '$comentario_seguimientos_padres',
                        NOW(),
                        '$id_teacher_tracking_parents',
                        $id_archive
                    )";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function sendInterviewMails()
{
    $id_card = $_POST['id_card'];
    $colabs = $_POST['colabs'];
    $attendance = new Attendance;
    $groups = new Groups;

    $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "S&aacute;bado", "Domingo");
    $meses = array("", "Ene.", "Feb.", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic");

    $stmt = "SELECT tc.*, 
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name,
    DATE(logdate) AS fecha_registro
    FROM psychopedagogy.therapeutic_cards AS tc
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = tc.no_colab_registered
    WHERE id_therapeutic_cards = $id_card";
    $getCardInfo = $groups->getGroupFromTeachers($stmt);

    $getCardInfo = $groups->getGroupFromTeachers($stmt);
    if (!empty($getCardInfo)) {
        $name_of_proffessional = $getCardInfo[0]->name_of_proffessional;
        $kind_of_interview = $getCardInfo[0]->kind_of_interview;
        $who_reffered = $getCardInfo[0]->who_reffered;
        $reason_why_reffered = $getCardInfo[0]->reason_why_reffered;
        $start_date = explode("-", $getCardInfo[0]->start_date);
        $end_date = explode("-", $getCardInfo[0]->end_date);
        $fecha_registro = explode("-", $getCardInfo[0]->fecha_registro);
        $cause_why_conclused = $getCardInfo[0]->cause_why_conclused;
        $logdate = $getCardInfo[0]->logdate;
        $no_colab_registered = $getCardInfo[0]->no_colab_registered;
        $teacher_name = $getCardInfo[0]->teacher_name;

        $start_date_format = $start_date[2] . " de " . $meses[(int) $start_date[1]] . " del " . $start_date[0];
        $end_date_format = $end_date[2] . " de " . $meses[(int) $end_date[1]] . " del " . $end_date[0];
        $fecha_registro_format = $fecha_registro[2] . " de " . $meses[(int) $fecha_registro[1]] . " del " . $fecha_registro[0];
        $i = 0;
        $i_mails = 0;
        $url_tracking = 'http://servykt.homeip.net:8083/wykt/interno/erp_realtime/iTeach/alumnos.php?submodule=tracking_shared&id_terapheutic_card=' . $id_card;
        $url_tracking_colabs = '';
        foreach ($colabs as $colab) {
            $no_colab = $colab;
            $url_tracking_colabs .= $no_colab . '-';
        }

        $url_tracking_colabs = substr($url_tracking_colabs, 0, -1);
        $url_tracking_colabs = encrypt($url_tracking_colabs, 'wykt2022');

        $url_tracking .= '&colabs=' . $url_tracking_colabs;

        foreach ($colabs as $colab) {

            $no_colab = $colab;

            $stmt_getInfoColabFrom = "SELECT colab.*, 
         CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS colaborador
          FROM colaboradores_ykt.colaboradores AS colab
          WHERE no_colaborador = '$_SESSION[colab]'";
            $getInfoColabFrom = $groups->getGroupFromTeachers($stmt_getInfoColabFrom);
            if (!empty($getInfoColabFrom)) {
                $colaborador_from = $getInfoColabFrom[0]->colaborador;
            }
            $stmt_getInfoColab = "SELECT colab.*, 
         CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador, ' ', colab.apellido_materno_colaborador) AS colaborador
          FROM colaboradores_ykt.colaboradores AS colab
          WHERE no_colaborador = '$no_colab'";
            $getInfoColab = $groups->getGroupFromTeachers($stmt_getInfoColab);
            if (!empty($getInfoColab)) {
                $colaborador = $getInfoColab[0]->colaborador;
                $email = $getInfoColab[0]->correo_institucional;
                $html_cuerpo = '
                                <!DOCTYPE html>
                                <html xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">

                                <head>
                                    <title></title>
                                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <!--[if mso]><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch><o:AllowPNG/></o:OfficeDocumentSettings></xml><![endif]-->
                                    <!--[if !mso]><!-->
                                    <link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet" type="text/css">
                                    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css">
                                    <link href="https://fonts.googleapis.com/css?family=Bitter" rel="stylesheet" type="text/css">
                                    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
                                    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet" type="text/css">
                                    <link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet" type="text/css">
                                    <!--<![endif]-->
                                    <style>
                                        * {
                                            box-sizing: border-box;
                                        }

                                        body {
                                            margin: 0;
                                            padding: 0;
                                        }

                                        a[x-apple-data-detectors] {
                                            color: inherit !important;
                                            text-decoration: inherit !important;
                                        }

                                        #MessageViewBody a {
                                            color: inherit;
                                            text-decoration: none;
                                        }

                                        p {
                                            line-height: inherit
                                        }

                                        .desktop_hide,
                                        .desktop_hide table {
                                            mso-hide: all;
                                            display: none;
                                            max-height: 0px;
                                            overflow: hidden;
                                        }

                                        @media (max-width:720px) {
                                            .desktop_hide table.icons-inner {
                                                display: inline-block !important;
                                            }

                                            .icons-inner {
                                                text-align: center;
                                            }

                                            .icons-inner td {
                                                margin: 0 auto;
                                            }

                                            .row-content {
                                                width: 100% !important;
                                            }

                                            .mobile_hide {
                                                display: none;
                                            }

                                            .stack .column {
                                                width: 100%;
                                                display: block;
                                            }

                                            .mobile_hide {
                                                min-height: 0;
                                                max-height: 0;
                                                max-width: 0;
                                                overflow: hidden;
                                                font-size: 0px;
                                            }

                                            .desktop_hide,
                                            .desktop_hide table {
                                                display: table !important;
                                                max-height: none !important;
                                            }
                                        }
                                    </style>
                                </head>

                                <body style="background-color: #eee5f8; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
                                    <table class="nl-container" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #eee5f8;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table class="row row-1" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #20252c; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-top:65px;width:100%;padding-right:0px;padding-left:0px;padding-bottom:5px;">
                                                                                                <div class="alignment" align="right" style="line-height:10px"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/4261/arrow_sx.png" style="display: block; height: auto; border: 0; width: 175px; max-width: 100%;" width="175" alt="Design Element" title="Design Element"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-top:75px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #ffffff; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:28px;">Seguimiento</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-4" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-bottom:75px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #ffffff; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Psicopedagógico</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td class="column column-3" width="25%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-top:65px;width:100%;padding-right:0px;padding-left:0px;padding-bottom:5px;">
                                                                                                <div class="alignment" align="left" style="line-height:10px"><img src="https://d1oco4z2z1fhwp.cloudfront.net/templates/default/4261/arrow_dx.png" style="display: block; height: auto; border: 0; width: 175px; max-width: 100%;" width="175" alt="Design Element" title="Design Element"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-2" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 20px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-1" width="100%" border="0" cellpadding="10" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad">
                                                                                                <div style="font-family: Trebuchet MS, Tahoma, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 16.8px; color: #20252c; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 22px; text-align: center; mso-line-height-alt: 26.4px;"><span style="font-size:24px;"><strong>Estimado colaborador:</strong></span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-bottom:45px;padding-left:50px;padding-right:50px;padding-top:15px;">
                                                                                                <div style="font-family: Trebuchet MS, Tahoma, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 16.8px; color: #20252c; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:22px;">Te hacemos llegar este correo electrónico para que nos brindes de tu valiosa ayuda dando seguimiento a la intervención psicopedagógica de cuyos detalles se muestran a continuación:</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-3" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #eee5f8;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e5cfff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <div class="spacer_block" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-4" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:15px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Terapeuta:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $name_of_proffessional . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-4" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Referido por:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-5" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $who_reffered . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:15px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Tipo de intervención:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $kind_of_interview . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-4" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Motivo:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-5" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $reason_why_reffered . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-5" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:15px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Fecha de inicio:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $start_date_format . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td class="column column-2" width="50%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:15px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Fecha de fin:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;padding-bottom:5px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $end_date_format . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-6" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Fecha de registro:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $fecha_registro_format . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-3" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #7d608a; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:20px;">Colaborador que registró la intervención:&nbsp;</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                    <table class="text_block block-4" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:10px;padding-top:10px;">
                                                                                                <div style="font-family: sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; mso-line-height-alt: 16.8px; color: #393d47; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:24px;">' . $teacher_name . '</span></p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-7" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #eee5f8;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #e5cfff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <div class="spacer_block" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-8" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 20px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-bottom:45px;padding-left:50px;padding-right:50px;padding-top:15px;">
                                                                                                <div style="font-family: Trebuchet MS, Tahoma, sans-serif">
                                                                                                    <div class style="font-size: 14px; font-family: Trebuchet MS, Lucida Grande, Lucida Sans Unicode, Lucida Sans, Tahoma, sans-serif; mso-line-height-alt: 16.8px; color: #20252c; line-height: 1.2;">
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:22px;">Para darle seguimiento por favor siga los siguientes pasos:</span></p><br><br>
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:22px;">1.- Asegurese de tener sesión iniciada en la plataforma iTeach.</span></p><br><br>
                                                                                                        <p style="margin: 0; font-size: 14px; text-align: center; mso-line-height-alt: 16.8px;"><span style="font-size:22px;">2.- Una vez con la sesión iniciada en iTeach, haga click en el botón que esta en seguida, lo dirigirá a una nueva pestaña en su navegador.</span></p><br><br>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-9" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="button_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-bottom:15px;text-align:center;padding-top:20px;">
                                                                                                <div class="alignment" align="center">
                                                                                                    <!--[if mso]><v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="' . $url_tracking . '" style="height:84px;width:322px;v-text-anchor:middle;" arcsize="0%" strokeweight="0.75pt" strokecolor="#e5cfff" fillcolor="#e5cfff"><w:anchorlock/><v:textbox inset="0px,0px,0px,0px"><center style="color:#20252c; font-family:Tahoma, Verdana, sans-serif; font-size:18px"><![endif]--><a href="' . $url_tracking . '" target="_blank" style="text-decoration:none;display:inline-block;color:#20252c;background-color:#e5cfff;border-radius:0px;width:auto;border-top:1px solid transparent;font-weight:700;border-right:1px solid transparent;border-bottom:1px solid transparent;border-left:1px solid transparent;padding-top:5px;padding-bottom:5px;font-family:Ubuntu, Tahoma, Verdana, Segoe, sans-serif;text-align:center;mso-border-alt:none;word-break:keep-all;"><span style="padding-left:20px;padding-right:20px;font-size:18px;display:inline-block;letter-spacing:3px;"><span dir="ltr" style="word-break: break-word; line-height: 36px;"><strong>Abrir seguimiento psicopedagógico</strong></span></span></a>
                                                                                                    <!--[if mso]></center></v:textbox></v:roundrect><![endif]-->
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-10" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <div class="spacer_block" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-11" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #eee5f8;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <div class="spacer_block" style="height:20px;line-height:20px;font-size:1px;">&#8202;</div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-12" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-top:5px;padding-bottom:5px;">
                                                                                                <div style="color:#101112;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:19.2px;"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td class="column column-2" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="image_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                        <tr>
                                                                                            <td class="pad" style="width:100%;padding-right:0px;padding-left:0px;padding-top:5px;padding-bottom:5px;">
                                                                                                <div class="alignment" align="center" style="line-height:10px"><img src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/807136_791017/logo.png" style="display: block; height: auto; border: 0; width: 233px; max-width: 100%;" width="233"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td class="column column-3" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="paragraph_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-top:5px;padding-bottom:5px;">
                                                                                                <div style="color:#101112;direction:ltr;font-family:Arial, Helvetica Neue, Helvetica, sans-serif;font-size:16px;font-weight:400;letter-spacing:0px;line-height:120%;text-align:left;mso-line-height-alt:19.2px;"></div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-13" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #ffffff; background-image: url(https://d1oco4z2z1fhwp.cloudfront.net/templates/default/4261/bg_footer.png); background-repeat: no-repeat; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <div class="spacer_block" style="height:70px;line-height:5px;font-size:1px;">&#8202;</div>
                                                                                </td>
                                                                                <td class="column column-2" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="text_block block-2" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;">
                                                                                        <tr>
                                                                                            <td class="pad" style="padding-left:15px;padding-right:15px;padding-top:20px;padding-bottom:65px;">
                                                                                                <div style="font-family: Arial, sans-serif">
                                                                                                    <div class style="font-size: 12px; mso-line-height-alt: 14.399999999999999px; color: #ffffff; line-height: 1.2; font-family: Oswald, Arial, Helvetica Neue, Helvetica, sans-serif;">
                                                                                                        <p style="margin: 0; font-size: 12px; mso-line-height-alt: 14.399999999999999px;">&nbsp;</p>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td class="column column-3" width="33.333333333333336%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <div class="spacer_block" style="height:70px;line-height:5px;font-size:1px;">&#8202;</div>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    <table class="row row-14" align="center" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <table class="row-content stack" align="center" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000; width: 700px;" width="700">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td class="column column-1" width="100%" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 5px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;">
                                                                                    <table class="icons_block block-1" width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                        <tr>
                                                                                            <td class="pad" style="vertical-align: middle; color: #9d9d9d; font-family: inherit; font-size: 15px; padding-bottom: 5px; padding-top: 5px; text-align: center;">
                                                                                                <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
                                                                                                    <tr>
                                                                                                        <td class="alignment" style="vertical-align: middle; text-align: center;">
                                                                                                            <!--[if vml]><table align="left" cellpadding="0" cellspacing="0" role="presentation" style="display:inline-block;padding-left:0px;padding-right:0px;mso-table-lspace: 0pt;mso-table-rspace: 0pt;"><![endif]-->
                                                                                                            <!--[if !vml]><!-->
                                                                                                            <table class="icons-inner" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; display: inline-block; margin-right: -4px; padding-left: 0px; padding-right: 0px;" cellpadding="0" cellspacing="0" role="presentation">
                                                                                                                <!--<![endif]-->
                                                                                                                <tr>
                                                                                                                    <td style="vertical-align: middle; text-align: center; padding-top: 5px; padding-bottom: 5px; padding-left: 5px; padding-right: 6px;"><a href="https://www.designedwithbee.com/?utm_source=editor&utm_medium=bee_pro&utm_campaign=free_footer_link" target="_blank" style="text-decoration: none;"><img class="icon" alt="Designed with BEE" src="https://d15k2d11r6t6rl.cloudfront.net/public/users/Integrators/BeeProAgency/53601_510656/Signature/bee.png" height="32" width="34" align="center" style="display: block; height: auto; margin: 0 auto; border: 0;"></a></td>
                                                                                                                    <td style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif; font-size: 15px; color: #9d9d9d; vertical-align: middle; letter-spacing: undefined; text-align: center;"><a href="https://www.designedwithbee.com/?utm_source=editor&utm_medium=bee_pro&utm_campaign=free_footer_link" target="_blank" style="color: #9d9d9d; text-decoration: none;">Designed with BEE</a></td>
                                                                                                                </tr>
                                                                                                            </table>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table><!-- End -->
                                </body>

                                </html>
                        ';

                mb_internal_encoding('UTF-8');
                $html_cuerpo = utf8_decode($html_cuerpo);
                // Esto le dice a PHP que generaremos cadenas UTF-8
                mb_http_output('UTF-8');



                $mail = new PHPMailer(true);
                /* 
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'l.gonzalez@ae.edu.mx';                     //SMTP username
                $mail->Password   = 'Cruzazul02';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;

                $mail->SMTPDebug = false;
                $mail->do_debug = 0;                           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('l.gonzalez@ae.edu.mx', 'Seguimientos iTeach');
                $mail->addAddress('antoniogonzalez.rt@gmail.com', utf8_decode($colaborador));     //Add a recipient
                $mail->addAddress('i.sistemas@ae.edu.mx');               //Name is optional
                $mail->addAddress('cesar.sistemas@ae.edu.mx');               
                $mail->addReplyTo('l.gonzalez@ae.edu.mx', $colaborador_from); */

                try {
                    //Server settings
                    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                    $mail->isSMTP();                                            //Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                    $mail->Username   = 'l.gonzalez@ae.edu.mx';                     //SMTP username
                    $mail->Password   = 'Cruzazul02';                               //SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                    $mail->Port       = 465;

                    $mail->SMTPDebug = false;
                    $mail->do_debug = 0;                           //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                    //Recipients
                    $mail->setFrom('l.gonzalez@ae.edu.mx', 'Seguimientos iTeach');
                    $mail->addAddress('antoniogonzalez.rt@gmail.com', utf8_decode($colaborador));     //Add a recipient
                    //$mail->addAddress('i.sistemas@ae.edu.mx');               //Name is optional
                    //$mail->addAddress('cesar.sistemas@ae.edu.mx');               //Name is optional
                    /* $mail->addAddress($correo_personal); */
                    $mail->addReplyTo('l.gonzalez@ae.edu.mx', $colaborador_from);
                    /* $mail->addCC('cc@example.com');
                 $mail->addBCC('bcc@example.com'); */

                    //Attachments
                    /* $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments */
                    /* $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name */

                    //Content
                    $mail->isHTML(true);                                  //Set email format to HTML
                    $mail->Subject = utf8_decode('Seguimientos iTeach');
                    $mail->Body    = $html_cuerpo;
                    /* $mail->AltBody = 'Estimado colaborador. El departamento de contabilidad le hace llegar este correo de prueba.'; */

                    $mail->send();
                    $i_mails++;
                    /* echo 'Message has been sent'; */
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $data = array(
                    'response' => false,
                    'message' => 'Ocurrió un error al enviar el correo'
                );
            }
        }
    }

    $data = array(
        'response' => true,
        'data' => $getCardInfo
    );
    echo json_encode($data);
}

function saveParentsTracking()
{


    $motivo = $_POST['motivo'];
    $tipo_seguimiento = $_POST['tipo_seguimiento'];
    $responsable_seguimiento = $_POST['responsable_seguimiento'];
    $fecha_contacto = $_POST['fecha_contacto'];
    $descripcion = br2nl($_POST['descripcion']);
    $acuerdos = br2nl($_POST['acuerdos']);
    $id_student = $_POST['id_student'];
    $seguimiento_a  = $_POST['seguimiento_a'];


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "INSERT INTO psychopedagogy.parents_tracking
                (
                    id_student,
                    reason,
                    id_tracking_type,
                    agreements,
                    monitoring_manager,
                    contact_date,
                    descripcion,
                    no_colab_registered,
                    logdate,
                    tracing_to
                    ) VALUES (
                        $id_student,
                        '$motivo',
                        '$tipo_seguimiento',
                        '$acuerdos',
                        '$responsable_seguimiento',
                        '$fecha_contacto',
                        '$descripcion',
                        '$_SESSION[colab]',
                        NOW(),
                        '$seguimiento_a'
                    )";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();
        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function deleteParentTracking()
{
    $id = $_POST['id_tracking'];
    $attendance = new Attendance;

    $stmt = "DELETE FROM psychopedagogy.parents_tracking WHERE id_parents_tracking = $id;";

    if ($attendance->saveAttendance($stmt)) {
        $data = array(
            'response' => true,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al eliminar el registro'
        );
    }

    echo json_encode($data);
}


function getParetTrackingDetails()
{
    $id_parents_tracking = $_POST['id_parents_tracking'];
    $groups = new Groups;
    $attendance = new Attendance;

    $stmt = "SELECT tpt.*
    FROM psychopedagogy.parents_tracking AS tpt
    WHERE id_parents_tracking = $id_parents_tracking";

    $data_interview = $groups->getGroupFromTeachers($stmt);

    if (!empty($data_interview)) {
        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron registros'
        );
    }

    echo json_encode($data);
}
function updateParetTracking()
{

    /* $("#edit_motivo").val(data.data[0].reason);
          $("#edit_responsable_seguimiento").val(data.data[0].monitoring_manager);
          $("#edit_tipo_seguimiento").val(data.data[0].id_tracking_type ); // select
          $("#edit_fecha_contacto").val(data.data[0].contact_date);
          $("#edit_descripcion").val(data.data[0].descripcion);
          $("#edit_acuerdos").val(data.data[0].agreements); */
    $id_parents_tracking = $_POST['id_parents_tracking'];
    $reason = $_POST['reason'];
    $monitoring_manager = $_POST['monitoring_manager'];
    $id_tracking_type = $_POST['id_tracking_type'];
    $contact_date = $_POST['contact_date'];
    $descripcion = br2nl($_POST['descripcion']);
    $agreements = br2nl($_POST['agreements']);
    $seguimiento_a = $_POST['seguimiento_a'];

    $groups = new Groups;
    $attendance = new Attendance;

    $stmt = "UPDATE psychopedagogy.parents_tracking
    SET
    reason = '$reason',
    monitoring_manager = '$monitoring_manager',
    id_tracking_type = '$id_tracking_type',
    contact_date = '$contact_date',
    descripcion = '$descripcion',
    agreements = '$agreements',
    tracing_to = '$seguimiento_a'
    WHERE id_parents_tracking = $id_parents_tracking";

    $data_interview = $attendance->saveAttendance($stmt);

    if (!empty($data_interview)) {
        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron registros'
        );
    }

    echo json_encode($data);
}


function br2nl($string)
{
    return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
}

function saveStudentDocument()
{

    $attendance = new Attendance;


    $fecha_archivo = date('Y_m_d');
    $hora_archivo = date('H:i:s');
    $fyh = $fecha_archivo . ' ' . $hora_archivo;



    $extension_doc = basename($_FILES["student_document"]["type"]);
    $id_student = $_POST['id_student'];
    $description_document = $_POST['description_document'];

    $nm_Archivo = "std_doc_" . $id_student . "_" . $fecha_archivo . "-" . time();

    $directorio_archive =  dirname(__DIR__ . '', 2) . '/uploads_documents/psychopedagogy_archives/students_documents/';

    $archivo_img = $directorio_archive . "/" .  $nm_Archivo . "." . $extension_doc;

    $ruta_sql_img = 'uploads_documents/psychopedagogy_archives/students_documents/' .  $nm_Archivo . "." . $extension_doc;

    if (!file_exists($directorio_archive)) {
        mkdir($directorio_archive, 0777, true);
    }

    if (move_uploaded_file($_FILES["student_document"]["tmp_name"], $archivo_img)) {

        $data = array(
            'response' => true,
            'message' => 'Se guardó el archivo correctamente'
        );
        //echo json_encode($data);

        $stmt = "INSERT INTO psychopedagogy.student_documents
                (
                    id_student,
                    deocument_description,
                    document_type,
                    archive_route,
                    no_colab_upload,
                    logdate
                ) VALUES(
                    '$id_student',
                    '$description_document',
                    '$extension_doc',
                    '$ruta_sql_img',
                    '$_SESSION[colab]',
                    NOW()
                )";

        if ($attendance->saveAttendance($stmt)) {
            $last_id = $attendance->getLastId();

            $data = array(
                'response' => true,
                'id' => $last_id,
                'ruta_sql_img' => $ruta_sql_img,
                'extension_doc' => $extension_doc,
                'description_document' => $description_document,
            );
        } else {
            $data = array(
                'response' => false,
                'message' => 'Ocurrió un error al guardar el registro'
            );
        }
        echo json_encode($data);
    }
}
function deleteStudentDocument()
{
    $id_student_document = $_POST['id_archive'];
    $attendance = new Attendance;

    $stmt = "DELETE FROM psychopedagogy.student_documents
    WHERE id_student_documents = $id_student_document";

    if ($attendance->saveAttendance($stmt)) {
        $data = array(
            'response' => true,
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al eliminar el registro'
        );
    }

    echo json_encode($data);
}
function getStudentsDocuments()
{
    $id_student = $_POST['id_student'];
    $groups = new Groups;
    $stmt = "SELECT * FROM psychopedagogy.student_documents
    WHERE id_student = $id_student";

    $data_interview = $groups->getGroupFromTeachers($stmt);

    if (!empty($data_interview)) {
        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron registros'
        );
    }

    echo json_encode($data);
}
function encrypt($string, $key)
{
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
    }
    return base64_encode($result);
}
function updateCommentaryTracking()
{

    $id_commentary = $_POST['id_commentary'];
    $commentary = $_POST['commentary'];
    $commentary = br2nl($commentary);


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "UPDATE psychopedagogy.terapeutic_cards_chat
                SET chat_message = '$commentary' 
                WHERE id_terapeutic_cards_chat = $id_commentary";


    if ($attendance->saveAttendance($stmt)) {
        //$last_id = $attendance->getLastId();

        $data = array(
            'response' => true
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function getDocInfoCommentaryTracking()
{

    $id_commentary = $_POST['id_commentary'];


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM psychopedagogy.terapeutic_cards_chat AS terc
    INNER JOIN psychopedagogy.tcc_archives AS tcc ON tcc.id_tcc_archives = terc.id_tcc_archives
                WHERE id_terapeutic_cards_chat = $id_commentary";
    $data_interview = $groups->getGroupFromTeachers($stmt);



    if (!empty($data_interview)) {
        //$last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
        );
    }

    echo json_encode($data);
}
function getTrackingParents()
{
    $id_parents_tracking = $_POST['id_parents_tracking'];
    $groups = new Groups;
    $attendance = new Attendance;

    $stmt = "SELECT ptc.*, 
    CONCAT(colab.nombres_colaborador, ' ', colab.apellido_paterno_colaborador ,' ', colab.apellido_materno_colaborador) AS teacher_name, pta.route_archive, pta.archive_type
    FROM psychopedagogy.parents_tracking_chat AS ptc
    LEFT JOIN psychopedagogy.parents_tracking_archives AS pta ON pta.id_parents_tracking_archives  = ptc.id_ptc_archive
    INNER JOIN colaboradores_ykt.colaboradores AS colab ON colab.no_colaborador = ptc.no_colab_registered
    WHERE ptc.id_parents_tracking  = $id_parents_tracking";

    $data_interview = $groups->getGroupFromTeachers($stmt);

    if (!empty($data_interview)) {
        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'No se encontraron registros'
        );
    }

    echo json_encode($data);
}
function saveInterviewTrackingParents()
{

    $id_parents_tracking = $_POST['id_parents_tracking'];
    $id_teacher_tracking = $_POST['id_teacher_tracking'];
    $comentario_seguimientos = $_POST['comentario_seguimientos'];
    $comentario_seguimientos = br2nl($comentario_seguimientos);


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "INSERT INTO psychopedagogy.parents_tracking_chat
                (
                    id_parents_tracking,
                    chat_message,
                    datelog,
                    no_colab_registered
                    ) VALUES (
                        $id_parents_tracking,
                        '$comentario_seguimientos',
                        NOW(),
                        '$id_teacher_tracking'
                    )";


    if ($attendance->saveAttendance($stmt)) {
        $last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'id' => $last_id
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function updateCommentaryTrackingParents()
{

    $id_commentary = $_POST['id_commentary'];
    $commentary = $_POST['commentary'];
    $commentary = br2nl($commentary);


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "UPDATE psychopedagogy.parents_tracking_chat
                SET chat_message = '$commentary' 
                WHERE id_parents_tracking_chat = $id_commentary";


    if ($attendance->saveAttendance($stmt)) {
        //$last_id = $attendance->getLastId();

        $data = array(
            'response' => true
        );
    } else {
        $data = array(
            'response' => false,
            'message' => 'Ocurrió un error al guardar el registro'
        );
    }

    echo json_encode($data);
}
function getDocInfoCommentaryTrackingParents()
{

    $id_commentary = $_POST['id_commentary'];


    $groups = new Groups;
    $attendance = new Attendance;


    /* INSERTAR CATALOGO DE AE  */
    $stmt = "SELECT * FROM psychopedagogy.parents_tracking_chat AS terc
    INNER JOIN psychopedagogy.parents_tracking_archives AS tcc ON tcc.id_parents_tracking_archives = terc.id_ptc_archive
                WHERE id_parents_tracking_chat = $id_commentary";
    $data_interview = $groups->getGroupFromTeachers($stmt);



    if (!empty($data_interview)) {
        //$last_id = $attendance->getLastId();

        $data = array(
            'response' => true,
            'data' => $data_interview
        );
    } else {
        $data = array(
            'response' => false,
        );
    }

    echo json_encode($data);
}
