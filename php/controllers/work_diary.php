<?php
include '../../../general/php/models/Connection.php';
include '../models/Studyplan.php';
include '../models/helpers.php';

include '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('America/Mexico_City');
session_start();

if (isset($_POST['func'])){
	$func = $_POST['func'];
	$func();
}

function getAllAssgCoordinator(){

	$no_teacher = $_SESSION['colab'];
	$studyPlan = new StudyPlan;
	$assgs = $studyPlan->getAssgCoordinator($no_teacher);

	echo json_encode($assgs);
}

function getAllCommentsRegistered(){
	$studyPlan = new StudyPlan;

	$finalComments = array();

	$comments = '';
	if($_SESSION['grantsITEQ'] == 15 || $_SESSION['grantsITEQ'] == 31){
		$comments = $studyPlan->getAllcomments();
	} else {
		$comments = $studyPlan->getCommentsByAssignment($_SESSION['colab']);
	}

	if(!empty($comments)){
		foreach($comments AS $comment){
			//--- --- ---//
			$color_bkg = 'purple';
			if($comment->color_hex != ''){
				$color_bkg = "#{$comment->color_hex}";
			}
			//--- --- ---//
			$finalComments[] = array(
				'id' => $comment->work_diary_id,
				'title' => $comment->comments,
				'start' => $comment->comment_to_date,
				'allDay' => true,
				'color' => $color_bkg,
				'name_subject' => "{$comment->name_subject} / {$comment->group_code}",
				'teacher_name' => $comment->teacher_name,
				'evidence' => $comment->evidence_attached
			);
		}
	}
	
	echo json_encode($finalComments);
}

function addCommentPE(){
	$id_assignment = $_POST['id_assignment'];
	$eventTitle = $_POST['eventTitle'];
	$eventDate = $_POST['eventDate'];
	$evidence_attached = $_POST['evidence_attached'];

	if($evidence_attached == ''){
		$evidence_attached = NULL;
	}

	$no_teacher = $_SESSION['colab'];
	$currentdate = $date = date('Y-m-d h:i:s');

	$helpers = new Helpers;
	$studyPlan = new StudyPlan;
	$result = false;
	$id_event = 0;
	$name_subject = '';
	$teacher_name = '';

	if (!empty($id_level_combination = $helpers->getIdsLevelCombination($id_assignment))) {
		$id_level_combination = $id_level_combination->id_level_combination;

		$infoPeriod = $helpers->getPeriodByLevelCombinationAndDate($id_level_combination, $eventDate);

		//if(!empty($infoPeriod)){

		$sql = "INSERT INTO iteach_grades_quantitatives.work_diary (id_assignment, id_period_calendar, comment_to_date, comments, evidence_attached, date_comment_made, teacher_commented) VALUES (?,?,?,?,?,?,?)";
		//$data = [$id_assignment, $infoPeriod[0]->id_period_calendar, $eventDate, $eventTitle, $evidence_attached, $currentdate, $no_teacher];
		$data = [$id_assignment, 0, $eventDate, $eventTitle, $evidence_attached, $currentdate, $no_teacher];

		$idInsert = $studyPlan->InsertCommentTeacher($sql, $data);

		if($idInsert > 0){			
			//--- --- ---//
			$infoAssg = $helpers->getInfoSubjectAndGroupByIdAssignment($id_assignment);
			$name_subject = $infoAssg->name_subject;
			$teacher_name = $infoAssg->name;
			//--- --- ---//
			$result = true;
			$id_event = $idInsert;
		}
	}

	$response = array(
		'response' => $result,
		'idEvent' => $id_event,
		'name_subject' => $name_subject,
		'teacher_name' => $teacher_name
	);

	echo json_encode($response);
}

function updateCommentPE(){
	
	$eventTitle = $_POST['eventTitle'];
	$newEvidence = $_POST['newEvidence'];
	$eventId = $_POST['eventId'];
	$dlte = intval($_POST['dlte']);

	if($newEvidence == ''){
		$newEvidence = NULL;
	}

	$no_teacher = $_SESSION['colab'];
	$currentdate = $date = date('Y-m-d H:i:s');

	$studyPlan = new StudyPlan;
	$result = false;

	if($dlte){
		$sql = "UPDATE iteach_grades_quantitatives.work_diary SET active_comment = ?, no_teacher_deleted = ?, date_deleted = ? WHERE work_diary_id = ?";
		$data = [0, $no_teacher, $currentdate, $eventId];
	} else {
		$sql = "UPDATE iteach_grades_quantitatives.work_diary SET comments = ?, evidence_attached = ?, date_comment_made = ?, teacher_commented = ? WHERE work_diary_id = ?";
		$data = [$eventTitle, $newEvidence, $currentdate, $no_teacher, $eventId];
	}
	

	$update = $studyPlan->updateDeleteCommentTeacher($sql, $data);

	$name_subject = '';
	$teacher_name = '';

	if($update){
		$result = true;
		$id_event = $eventId;
		//--- --- ---//
		if(!$dlte){
			$infoComment = $studyPlan->getInfoCommentByID($eventId);
			$name_subject = $infoComment->name_subject;
			$teacher_name = $infoComment->teacher_name;
		}
		//--- --- ---//
	}

	$response = array(
		'response' => $result,
		'idEvent' => $id_event,
		'name_subject' => $name_subject,
		'teacher_name' => $teacher_name
	);

	echo json_encode($response);
}

function sendMailPDF(){

	$files = json_decode($_POST['files'], true);
	$pre_week = $_POST['week'];


	$pre_week = explode("-", $pre_week);
	$date1 = trim($pre_week[0]);
	$date2 = trim($pre_week[1]);
	//--- --- ---//
	$arr_date1 = explode("/", $date1);
	$fdate1 = $arr_date1[2] . '/' . $arr_date1[1] . '/' . $arr_date1[0];
	//--- --- ---//
	$arr_date2 = explode("/", $date2);
	$fdate2 = $arr_date2[2] . '/' . $arr_date2[1] . '/' . $arr_date2[0];
	//--- --- ---//
	$week = $fdate1 . ' - ' . $fdate2;
	//--- --- ---//

	$helpers = new Helpers;
	$succes = true;

	foreach($files AS $file){

		$info_family = $helpers->GetInfoFamilyByStudent($file["id_student"]);

		$file_name = $info_family->student_code . '_' . $info_family->name_student . '.pdf';
		$family_name = $info_family->family_name;
		$father_mail = null;
		$mother_mail = null;

		if($info_family->father_mail != '' && $info_family->father_mail != null){
			//$father_mail = $info_family->father_mail;
			$father_mail = 'cesar.sistemas@ae.edu.mx';
			SendMail($file["file64"], $family_name, $father_mail, $file_name, $week, $info_family->name_student, $info_family->student_code);
		}

		if($info_family->mother_mail != '' && $info_family->mother_mail != null){
			//$mother_mail = $info_family->mother_mail;
			$mother_mail = 'i.sistemas@ae.edu.mx';
			SendMail($file["file64"], $family_name, $mother_mail, $file_name, $week, $info_family->name_student, $info_family->student_code);
		}

		sleep(3);
	}

	$response = array('response' => true);

	echo json_encode($response);
}

function SendMail($file64, $family_name, $email, $file_name, $week, $student_name, $student_code){
	$succes = true;

	$file64 = $pieces = explode("base64,", $file64);
	$file64 = $file64[1];

	$filename = 'send_mails_logs.txt';
	$myfile = fopen($filename, "a+") or die("Unable to open file!");
	$today = date('Y-m-d H:i:s');


	$mail = new PHPMailer(true);

	$result = array();

	try {
		//Server settings
		//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		$mail->Username   = 'notificacionykt@ae.edu.mx';                     //SMTP username
		$mail->Password   = 'Ykt2020a';                               //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom('notificacionykt@ae.edu.mx', 'YESHIVA KETANÁ');

		$mail->addAddress($email, strtoupper($family_name));
		//$mail->addCC('i.sistemas@ae.edu.mx');

		$mail->addStringAttachment(base64_decode($file64), $file_name);

		//Content
		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'DAFKESHER ' . $week;
		$mail->Body	= getMessageBody($family_name, $student_name, $student_code);

		$mail->CharSet = 'UTF-8';

		if($send = $mail->send()){
			$txt_log = 'Mail: ' . $email . ' Enviado; ' . $today;
			$result = array('send' => true,
				'reason' => '');
		} else {
			$succes = false;
			$txt_log = 'Mail: ' . $email . ' NO enviado, motivo: ' . $mail->ErrorInfo . ' ' . $today;
			$result = array('send' => false,
				'reason' => $mail->ErrorInfo);
		}

	} catch (Exception $e) {
		$succes = false;
		//echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
		$txt_log = 'Mail: ' . $email . ' NO enviado, motivo: ' . $mail->ErrorInfo . ' ' . $today;
		$result = array('send' => false,
			'reason' => $mail->ErrorInfo);
	}
	
	fwrite($myfile, $txt_log);
	fwrite ($myfile, "\n");
	fclose($myfile);

	return $succes;
}

function getMessageBody($family_name, $student_name, $student_code){
    $mssg = '<p style="text-align: center;">Estimada Familia:&nbsp;<strong>' . strtoupper($family_name) . '</strong></p>';
    $mssg .= '<p style="text-align: center;">&nbsp;</p>';
    $mssg .= '<p style="text-align: center;">Le hacemos llegar el informe de los temas vistos en la semana indicada para el alumno:</p>';
    $mssg .= '<p style="text-align: center;">&nbsp;</p>';
    $mssg .= '<p style="text-align: center;"><strong><em>' . strtoupper($student_name) . ' | ' . $student_code . '</em></strong></p>';
    $mssg .= '<p style="text-align: center;"><em>&nbsp;</em></p>';
    $mssg .= '<p style="text-align: center;">Por favor descargue el documento adjunto.</p>';
    $mssg .= '<p style="text-align: center;">&nbsp;</p>';
    $mssg .= '<p style="text-align: center;">Atte.</p>';
    $mssg .= '<p style="text-align: center;"><strong>Carlos Chayo</strong></p>';
    $mssg .= '<p style="text-align: center;">DIRECCI&Oacute;N YESHIVA KETAN&Aacute;</p>';

    return $mssg;
}