$(document).on("click", ".btn_std_attendance", function () {
  var id_student = $(this).attr("data-id-student");
  var indexs = $(this).attr("data-ids-index");

  getAttendDetails("Asistencias del Alumno", id_student, indexs);
});
$(document).on("click", ".btn_std_absneces", function () {
  var id_student = $(this).attr("data-id-student");
  var indexs = $(this).attr("data-ids-index");

  getAttendDetails("Inasistencias del Alumno", id_student, indexs);
});

$(document).on("click", ".btnBreakdownAbsence", function () {
  var id_student = $(this).attr("id-student");
  var today_date = $(this).attr("data-today-date");

  if (id_student != null && today_date != null) {
    $.ajax({
      url: "php/controllers/justifyController.php",
      method: "POST",
      data: {
        mod: "breakdownJustify",
        id_student: id_student,
        today_date: today_date,
      },
    })
      .done(function (json) {
        var json = JSON.parse(json);
        if (json != "") {
          //--- --- ---//
          if (json.response) {
            //--- --- ---//
            $("#cuerpo_desglose").empty();
            $("#cuerpo_desglose").html(json.html_sweet_alert);
            //--- --- ---//
            /*  Swal.fire({
              title: "Faltas anticipadas",
              icon: "info",
              html: json.html_sweet_alert,
              showCancelButton: false,
              width: "800px",
            }).then((result) => {
              //window.location.reload();
            }); */
            //--- --- ---/
          } else {
            swal(
              "Atención!",
              "No se pudieron guardar los datos :( intentelo nuevamente",
              "error"
            );
          }
          //--- --- ---//
        } else {
          swal(
            "Atención!",
            "Ocurrió un error al registrar la justificación",
            "error"
          );
        }
      })
      .fail(function () {
        swal(
          "Error!",
          "Error al intentar conectarse con la Base de Datos :/",
          "error"
        );
      });
  } else {
    Swal.fire({
      icon: "error",
      title: "Debe completar todos los campos requeridos",
    });
  }
});
$(document).on("click", ".addTrackingComment", function () {
  var id_absences_excuse = $(this).attr("id");
  $(".commentaryTracing").attr("id", id_absences_excuse);
  loading();

  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getCommentaryTracing",
      id_absences_excuse: id_absences_excuse,
    },
  })
    .done(function (json) {
      Swal.close();
      //console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          $("#div_timeline").empty();
          //--- --- ---/
          $("#div_timeline").append(json.html);
          student_name_code = json.student_name_code;
          $("#header_lbl_tracking").text(student_name_code);
        } else {
          student_name_code = json.student_name_code;
          $("#header_lbl_tracking").text(student_name_code);
        }

        //--- --- ---//
      } else {
      }
    })
    .fail(function () {});
});
function getAttendDetails(name_title, id_student, indexs) {
  loading();
  /* console.log(id_student); */
  /* console.log(indexs); */

  /* Swal.fire({
    icon: "info",
    title: name_title,
    text: indexs,
  }); */
  $.ajax({
    url: "php/controllers/student_archive.php",
    method: "POST",
    data: {
      mod: "getAttends",
      id_student: id_student,
      indexs: indexs,
    },
  })
    .done(function (data) {
      /* console.log(data); */
      var data = JSON.parse(data);
      if (data.response) {
        swal.close();
        // console.log(data.data);
        Swal.fire({
          title: name_title,
          icon: "info",
          html: data.html,
          width: "900px",
        });
      } else {
        Swal.close();
        VanillaToasts.create({
          title: "Error",
          text: data.message,
          type: "error",
          timeout: 3000,
          positionClass: "topRight",
        });
      }
      //--- --- ---//

      //--- --- ---//
    })
    .fail(function (message) {
      console.log(message);
      VanillaToasts.create({
        title: "Error",
        text: "Ocurrió un error, intentelo nuevamente",
        type: "error",
        timeout: 1200,
        positionClass: "topRight",
      });
    });
}
$(document).on("click", ".commentaryTracing", function () {
  loading();
  var id_absences_excuse = $(this).attr("id");
  var id_teacher_tracking = $("#id_teacher_tracking").val();
  const days = [
    "Domingo",
    "Lunes",
    "Martes",
    "Miercoles",
    "Jueves",
    "Viernes",
    "Sabado",
  ];

  const d = new Date();
  let day = days[d.getDay()];
  let date = d.getDate();
  let month = d.getMonth() + 1;
  let year = d.getFullYear();
  let hour = d.getHours();
  let minutes = d.getMinutes();

  var today_datetime =
    day +
    ", " +
    date +
    " de " +
    month +
    " de " +
    year +
    " a las " +
    hour +
    ":" +
    minutes;
  var txt_commentary = $("#comentario_seguimientos").val();
  var teacher_name = $("#teacher_name_registered_tracking").val();
  /* console.log(txt_commentary);
console.log(teacher_name); */
  var html_timeline = "";
  if (txt_commentary != "") {
    $.ajax({
      url: "php/controllers/justifyController.php",
      method: "POST",
      data: {
        mod: "saveCommentaryTracing",
        txt_commentary: txt_commentary,
        id_absences_excuse: id_absences_excuse,
        id_teacher_tracking: id_teacher_tracking,
      },
    })
      .done(function (json) {
        Swal.close();
        console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
          //--- --- ---//
          if (json.response) {
            //--- --- ---//
            VanillaToasts.create({
              title: "Éxito!!",
              text: "Se actualizó correctamente la justificación",
              timeout: 1200,
              positionClass: "topRight",
            });
            html_timeline +=
              '<div class="timeline-block" id="div' + json.last_id + '">';
            html_timeline += '<span class="timeline-step badge-success">';
            html_timeline += '<i class="ni ni-email-83"></i>';
            html_timeline += "</span>";
            html_timeline += '<div class="timeline-content">';
            html_timeline +=
              '<small class="text-muted font-weight-bold">' +
              today_datetime +
              "</small>";
            html_timeline +=
              '<h5 class=" mt-3 mb-0">' + txt_commentary + "</h5>";
            html_timeline +=
              '<p class=" text-sm mt-1 mb-0">' + teacher_name + "</p>";
            html_timeline += '<div class="mt-3">';
            html_timeline +=
              '<button type="button" id="' +
              json.last_id +
              '" class="btn btn-dribbble btn-icon-only rounded-circle btnDeleteTrackingCommit">';
            html_timeline +=
              '<span class="btn-inner--icon"><i class="ni ni-basket"></i></span>';
            html_timeline += "</button>";
            html_timeline += "</div>";
            html_timeline += "</div>";
            html_timeline += "</div>";
            //--- --- ---/
            $("#div_timeline").append(html_timeline);
          } else {
            Swal.fire({
              title: "Error",
              icon: "error",
              html: json.fechas_sobrepuestas,
              showCancelButton: false,
            });
          }
          //--- --- ---//
        } else {
          swal(
            "Atención!",
            "Ocurrió un error al registrar la justificación",
            "error"
          );
        }
      })
      .fail(function () {
        swal(
          "Error!",
          "Error al intentar conectarse con la Base de Datos :/",
          "error"
        );
      });
  }

  $("#comentario_seguimientos").val("");
});

function getAssistanceDetailsReportsStudent(
  start_date,
  end_date,
  id_assignment,
  id_student
) {
  console.log(start_date);
console.log(end_date);
console.log(id_assignment);
console.log(id_student);
  data = "";
  $.ajax({
    url: "php/controllers/attendance_reports_cotroller.php",
    method: "POST",
    data: {
      mod: "getAttendanceReportDetails",
      start_date: start_date,
      end_date: end_date,
      id_assignment: id_assignment,
      id_student: id_student,
    },
  })
    .done(function (data) {
      //console.log(data);
      var data = JSON.parse(data);

      if (data.response) {
        Swal.fire({
          title: "<h2>DETALLE DE INASISTENCIAS</h2>",
          icon: "info",
          html: data.data,
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
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
      /* swal.close(); */
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
function getAttendanceReportDetailsAbsences(
  start_date,
  end_date,
  id_assignment,
  id_student
) {
  data = "";
  $.ajax({
    url: "php/controllers/attendance_reports_cotroller.php",
    method: "POST",
    data: {
      mod: "getAttendanceReportDetailsAbsences",
      start_date: start_date,
      end_date: end_date,
      id_assignment: id_assignment,
      id_student: id_student,
    },
  })
    .done(function (data) {
      //console.log(data);
      var data = JSON.parse(data);

      if (data.response) {
        Swal.fire({
          title: "<h2>DETALLE DE ASISTENCIA</h2>",
          icon: "info",
          html: data.data,
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
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
      /* swal.close(); */
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