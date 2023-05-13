//getGroups2();
//--- --- ---//
var d = new Date(),
  month = "" + (d.getMonth() + 1),
  day = "" + d.getDate(),
  year = d.getFullYear();
if (month.length < 2) month = "0" + month;
if (day.length < 2) day = "0" + day;
const today = [year, month, day].join("-");
//--- --- ---//
$(".date-input").val(today);
//--- --- ---//
$(document).on("click", ".new_incident", function () {
  var id_student = $(this).attr("id");
  $(".btn_save_incident").attr("id", id_student);
  $("#newIncident").modal("show");

  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "StudentInfoByID",
      id_student: id_student,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          $("#txt_modal_incidence").text(data.data[i].student_name);
        }
      } else {
        VanillaToasts.create({
          title: "Error",
          text: data.message,
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      }
      //--- --- ---//

      swal.close();
      //--- --- ---//
    })
    .fail(function (message) {
      VanillaToasts.create({
        title: "Error",
        text: "Ocurrió un error, intentelo nuevamente",
        type: "error",
        timeout: 1200,
        positionClass: "topRight",
      });
    });
});
$(document).on("click", ".btn_save_incident", function () {
  var incident = $(
    "input[name=incident_list]:checked",
    "#form_incidents"
  ).val();
  var commit = $("#incident_commit").val();
  var date = $("#incident_date").val();
  var id_student = $(this).attr("id");
  id_student = $(this).attr("id");
  if (incident == undefined) {
    swal.close();
    swal.fire({
      icon: "error",
      title: "Seleccione una incidencia",
    });
  } else if (date == undefined) {
    swal.close();
    swal.fire({
      icon: "error",
      title: "Seleccione una fecha",
    });
  } else if (commit == "") {
    swal.close();
    swal.fire({
      icon: "error",
      title: "Ingrese un comentario",
    });
  } else {
    loading();
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "SaveIncident",
        id_student: id_student,
        incident: incident,
        commit: commit,
        date: date,
      },
    })
      .done(function (data) {
        console.log(data);

        swal.close();
        swal.fire({
          icon: "success",
          title: "INCIDENCIA REGISTRADA CON ÉXITO",
        });
        $("#newIncident").modal("hide");

        $("#" + incident).prop("checked", false);
        $("#incident_commit").val("");
        $("#incident_date").val("");
        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  }
});
$("#week_picker")
  .datepicker({
    autoclose: true,
    format: "YYYY-MM-DD",
    forceParse: false,
  })
  .on("changeDate", function (e) {
    //console.log(e.date);
    var date = e.date;
    startDate = new Date(
      date.getFullYear(),
      date.getMonth(),
      date.getDate() - date.getDay()
    );
    endDate = new Date(
      date.getFullYear(),
      date.getMonth(),
      date.getDate() - date.getDay() + 6
    );
    //$('#week_picker').datepicker("setDate", startDate);
    $("#week_picker").datepicker("update", startDate);
    $("#week_picker").val(
      startDate.getMonth() +
        1 +
        "/" +
        startDate.getDate() +
        "/" +
        startDate.getFullYear() +
        "-" +
        (endDate.getMonth() + 1) +
        "/" +
        endDate.getDate() +
        "/" +
        endDate.getFullYear()
    );
    $("#get_week_attendance").show();
  });
//--- --- ---//
$("#week_picker_historic")
  .datepicker({
    autoclose: true,
    format: "YYYY-MM-DD",
    forceParse: false,
  })
  .on("changeDate", function (e) {
    //console.log(e.date);
    $("#btnCheckWeekIncidents").prop("disabled", false);
    var date = e.date;
    startDate = new Date(
      date.getFullYear(),
      date.getMonth(),
      date.getDate() - date.getDay()
    );
    endDate = new Date(
      date.getFullYear(),
      date.getMonth(),
      date.getDate() - date.getDay() + 6
    );
    //$('#week_picker_historic').datepicker("setDate", startDate);
    $("#week_picker_historic").datepicker("update", startDate);
    $("#week_picker_historic").val(
      startDate.getMonth() +
        1 +
        "/" +
        startDate.getDate() +
        "/" +
        startDate.getFullYear() +
        "-" +
        (endDate.getMonth() + 1) +
        "/" +
        endDate.getDate() +
        "/" +
        endDate.getFullYear()
    );
    $("#get_week_attendance").show();
  });
$(document).on("click", "#btnCheckWeekIncidents", function () {
  var week_range = $("#week_picker_historic").val();

  loading();
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//

    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" + submodule + "&week_range=" + week_range;
  }

  loading();
});
$(document).on("change", "#id_group", function () {
  var id_group = $(this).val();
  loading();
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//

    const submodule = urlParams.get("submodule");
    window.location.search = "submodule=" + submodule + "&id_group=" + id_group;
  }

  loading();
});
$(document).on("change", "#academic_level_excuse", function () {
  var id_academic_level = $(this).val();
  loading();
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//

    const submodule = urlParams.get("submodule");
    window.location.search = "submodule=" + submodule + "&id_academic_level=" + id_academic_level;
  }

  loading();
});

$(document).on("change", "#id_teacher", function () {
  var id_teacher = $(this).val();
  loading();
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//

    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" + submodule + "&id_teacher=" + id_teacher;
  }

  loading();
});

$(document).on("click", ".infoIncidentStudent", function () {
  var id_incident = $(this).attr("id");
  console.log(id_incident);
  loading();

  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "StudentIncidentInfo",
      id_incident: id_incident,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        swal.close();
        var txt_fecha_incidencia = data.data[0].incident_date;
        var arr_fecha = txt_fecha_incidencia.split(" ");
        var fecha = arr_fecha[0];

        var arr_fech_registro = data.data[0].date_registered.split(" ");

        var fecha_registro = arr_fech_registro[0];
        var hora_registro = arr_fech_registro[1];
        Swal.fire({
          title: "<h1>Detalle de Insidencia</h1>",
          icon: "info",
          html:
            "<strong>" +
            data.data[0].student_code +
            " | " +
            data.data[0].name_student +
            " | " +
            data.data[0].group_code +
            "</strong><br>" +
            "<strong>Materia: </strong>"+data.data[0].name_subject +
            "<br>" +
            "<strong>Registró: </strong>" +
            data.data[0].name +
            "<br><br>" +
            "<strong>Fecha de inicidencia:</strong> " +
            fecha +
            "<br>" +
            "<strong>Fecha de registro:</strong> " +
            fecha_registro +
            "<br>" +
            "<strong>Hora de registro:</strong> " +
            hora_registro +
            "<br><br>" +
            '<table class="table align-items-center table-dark ">' +
            '<thead class="thead-dark">' +
            "<tr>" +
            '<th scope="col" style="color:#fff !important; width: 50% !important; word-wrap: break-word !important;">Nivel de Incidencia</th>' +
            '<th scope="col" style="color:#fff !important;  width: 50% !important; word-wrap: break-word !important;">Descripción</th>' +
            "</tr>" +
            "<tr>" +
            "<td style'width: 50% !important; word-wrap: break-word !important;'>"+data.data[0].clasification_degree +"</td>" +
            "<td style='width: 50% !important; white-space: normal !important;'>"+data.data[0].incident_description +" - "+data.data[0].incident_description_detail +"</td>" +
            "</tr>" +
            "</thead>" +
            "<tr>" +
            '<thead class="thead-dark">' +
            '<th  style="color:#fff !important;" colspan="100%">Consecuencias</th>' +
            "</tr>" +
            "</thead>" +
            "<tr>" +
            '<td colspan="100%" style="width: 100%% !important; white-space: normal  !important;">'+data.data[0].incidence_consequences +'</td>' +
            "</tr>" +
            "</table>"+
            "<br>" +
            "<strong>Comentario:</strong> " +
            data.data[0].incident_commit ,
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
        });
      } else {
        VanillaToasts.create({
          title: "Error",
          text: data.message,
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      }
      //--- --- ---//

      //--- --- ---//
    })
    .fail(function (message) {
      VanillaToasts.create({
        title: "Error",
        text: "Ocurrió un error, intentelo nuevamente",
        type: "error",
        timeout: 1200,
        positionClass: "topRight",
      });
    });
});
//--- --- ---//

//--- --- ---//
//--- --- ---//
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
//--- --- ---//
