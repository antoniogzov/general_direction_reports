<?php
session_start();
if (!isset($_SESSION['start'])) {
  header('Location:../general/logIn.php');
  exit();
}
include '../general/php/controllers/GeneralController.php';
include '../general/php/models/Connection.php';
include '../general/php/models/GeneralModel.php';
$generalModel = new users;
$infoCol = $generalModel->GetInfoCollaborator($_SESSION['colab']);
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Control escolar de la YKT.">
  <meta name="author" content="YKT">
  <title>iTeach</title>
  <!-- Favicon -->
  <link rel="icon" href="../general/img/imgs/logo.png" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <!-- <link rel="stylesheet" href="../general/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">-->
  <link rel="stylesheet" href="../general/vendor/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="../general/vendor/stickytable/jquery.stickytable.css">
  <!-- Page plugins -->
  <link rel="stylesheet" href="../general/vendor/nucleo/css/nucleo.css" type="text/css">
  <!-- Argon CSS -->
  <link rel="stylesheet" href="css/argon.css" type="text/css">
  <link rel="stylesheet" href="../general/vendor/vanillatoast/vanillatoasts.css" type="text/css">
  <!-- Sweet alert -->
  <link rel="stylesheet" href="../general/vendor/sweetalert2/sweetalert2.min.css" type="text/css">
  <script src="../general/vendor/sweetalert2/sweetalert2.min.js"></script>
  <!-- -->
  <link rel="stylesheet" href="css/tables1.css" type="text/css"/>
  <link rel="stylesheet" href="css/cards_menu.css" type="text/css"/>
  <link rel="stylesheet" href="vendor/bootstrap-datepicker/dist/css/bootstrap-datetimepicker.min.css" type="text/css"/>
  <!-- -->
  <link rel="stylesheet" href="vendor/@fontawesome/fontawesome-free-5.15.4/css/all.css" />
  <link rel="stylesheet" href="vendor/fullcalendar/fullcalendar-5.11.3/main.css">

  <script src="vendor/jquery/dist/jquery.min.js"></script>
  <script src="vendor/bootstrap-datepicker/dist/js/moment.min.js" async></script>
  <script src="vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" async></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" />
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.3.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
	<!--<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>-->
	<script type="text/javascript" src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
  <script type="text/javascript" src="../general/vendor/stickytable/jquery.stickytable.js"></script>
  <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

  </head>

  <body>