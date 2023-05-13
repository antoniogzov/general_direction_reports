$(document).ready(function () {
  if ($("#tablaDesgloseIncidencias").length) {
  }
  //--- --- ---/

  $("#search_date").datepicker("setDate", new Date());
  $("#search_date").datepicker({
    autoclose: true,
    format: "dd/mm/yyyy",
    forceParse: false,
    endDate: "+1d",
  });
});

$("#incident_commit").keypress(function (e) {
  var Max_Length = $("#incident_commit").attr("maxlength");
  var length = $("#incident_commit").val().length;
  var available = Max_Length - length;
  var text =
    "Dispone de " + available + " caracteres para describir lo ocurrido.";
  $("#lbl_longitud").text("");
  $("#lbl_longitud").text(text);
});

$(document).on("click", ".btn_save_incident", function () {
  var incident = $("input[name=incident_list]:checked").val();
  var commit = $("#incident_commit").val();
  var date = $("#incident_date").val();
  var id_assignment = $("#id_subject_incident").val();
  var id_student = $(this).attr("id");
  id_student = $(this).attr("id");
  if (incident == undefined) {
    swal.close();
    swal.fire({
      icon: "error",
      title: "Seleccione una incidencia",
    });
  } else if (date == undefined || date == "" || date == "00-00-0000") {
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
    console.log(date);
    //loading();
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "SaveIncident",
        id_student: id_student,
        incident: incident,
        commit: commit,
        date: date,
        id_assignment,
      },
    })
      .done(function (data) {
        console.log(data);

        swal.close();
        swal
          .fire({
            icon: "success",
            title: "INCIDENCIA REGISTRADA CON ÉXITO",
            timer: 1500,
          })
          .then(function () {
            window.location.reload();
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
$(document).on("click", ".addStudentIncident", function () {
  var id_student = $(this).attr("id-student");
  $(".btn_save_incident").attr("id", id_student);
  //$("#newIncident").modal("show");

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
          $("#txt_modal_incidence").text(
            data.data[i].student_name.toUpperCase()
          );
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
$(document).on("click", ".getStudentContactInfo", function () {
  var id_student = $(this).attr("data-id_student");
  $(".btn_save_incident").attr("id", id_student);
  loading();
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "StudentContactInfo",
      id_student: id_student,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        swal.close();
        // console.log(data.data);
        Swal.fire({
          title: "",
          icon: "info",
          html: data.data,
          width: "900px",
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
$(document).on("change", "#id_group", function () {
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    var type_report = $("#type_report").val();
    const id_group = $(this).val();
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" + submodule + "&type_report=" + type_report;
    //--- --- ---//
  }
});
$(document).on("click", ".btnSuspend", function () {
  var code_student = $(this).attr("code-student");
  var name_student = $(this).attr("name-student");

  $("#lbl_std_name").text(code_student + " | " + name_student);
});
$(document).on("click", ".btnJustify", function () {
  var code_student = $(this).attr("code-student");
  var name_student = $(this).attr("name-student");
  var id_student = $(this).attr("id-student");
  $(".btn_SaveJustify").attr("id-student", id_student);

  $("#lbl_std_name").text(code_student + " | " + name_student);
});

$(document).on("click", ".btnJustifyJS", function () {
  var code_student = $(this).attr("code-student");
  var name_student = $(this).attr("name-student");
  var id_student = $(this).attr("id-student");
  $(".btn_SaveJustify").attr("id-student", id_student);

  $("#lbl_std_name").text(code_student + " | " + name_student);
});
$(document).on("click", ".btn_SaveJustify", function () {
  loading();
  var id_student = $(this).attr("id-student");
  var id_excuse_types = $("#id_excuse_types").val();
  var dates_apply = $("#dates_apply").val();
  var teacher_commit = $("#teacher_commit").val();
  var justifyed = 0;
  if ($("#checknIndexJustified").is(":checked")) {
    justifyed = 1;
  }

  console.log("id_student: " + id_student);
  console.log("id_excuse_types: " + id_excuse_types);
  console.log("vardates_apply: " + dates_apply);
  console.log("teacher_commit: " + teacher_commit);

  if (id_excuse_types != null && dates_apply != null) {
    $.ajax({
      url: "php/controllers/justifyController.php",
      method: "POST",
      data: {
        mod: "saveJustify",
        id_student: id_student,
        id_excuse_types: id_excuse_types,
        dates_apply: dates_apply,
        teacher_commit: teacher_commit,
        justifyed: justifyed,
      },
    })
      .done(function (json) {
        Swal.close();
        //console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
          //--- --- ---//
          if (json.response) {
            var id_absences_excuse_breakdown = json.last_id;
            $.ajax({
              url: "php/controllers/justifyController.php",
              method: "POST",
              data: {
                mod: "updateBreakdownJustifyAttendanceNew",
                id_absences_excuse_breakdown: id_absences_excuse_breakdown,
                justifyed: justifyed,
              },
            })
              .done(function (json) {
                //console.log(json);
                var json = JSON.parse(json);
                if (json != "") {
                  //--- --- ---//
                  if (json.response) {
                    
                    //--- --- ---//
                    Swal.fire({
                      title: "Éxito!!",
                      icon: "info",
                      text: "El registro se guardó correctamente!!",
                      showCancelButton: false,
                      timer: 3000,
                    }).then((result) => {
                      window.location.reload();
                    });
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
            //--- --- ---//

            //--- --- ---/
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
  } else {
    Swal.fire({
      icon: "error",
      title: "Debe completar todos los campos requeridos",
    });
  }
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
$(document).on("click", ".btnBreakdownIncidents", function () {
  var id_student = $(this).attr("id-student");

  if (id_student != null) {
    $.ajax({
      url: "php/controllers/justifyController.php",
      method: "POST",
      data: {
        mod: "breakdownincidents",
        id_student: id_student,
      },
    })
      .done(function (json) {
        //        //console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
          //--- --- ---//
          if (json.response) {
            //--- --- ---//
            $("#cuerpo_desglose_incidencias").empty();
            $("#cuerpo_desglose_incidencias").html(json.html_sweet_alert);
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
$(document).on("change", "#check_subject_incident", function () {
  if (this.checked) {
    $("#div_materia_incidente").show();
    $("#id_subject_incident").val("");
  } else {
    $("#div_materia_incidente").hide();
    $("#id_subject_incident").val("");
    //$("#guardar_gasto").prop("disabled", false);
  }
});
$(document).on("click", ".deleteJustifyAbsence", function () {
  var id_absences_excuse = $(this).attr("id");

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Este proceso no se podrá revertir!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, borrarlo!",
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "deleteJustify",
          id_absences_excuse: id_absences_excuse,
        },
      })
        .done(function (json) {
          //          //console.log(json);
          var json = JSON.parse(json);
          if (json != "") {
            //--- --- ---//
            if (json.response) {
              $("#" + id_absences_excuse)
                .closest("tr")
                .remove();
              //--- --- ---//
              Swal.fire({
                title: "Éxito!!",
                icon: "info",
                text: "Se eliminó correctamente la justificación!!",
                showCancelButton: false,
                timer: 3000,
              }).then((result) => {
                if ($("#tablaDesgloseAusencias").find("tbody tr").length == 0) {
                  window.location.reload();
                }
              });
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
    }
  });
});
$(document).on("click", ".deleteAbsenceDocument", function () {
  var id_absence_vouchers = $(this).attr("data-id-archive");

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Este proceso no se podrá revertir!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, borrarlo!",
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "deleteAbsenceDocument",
          id_absence_vouchers: id_absence_vouchers,
        },
      })
        .done(function (json) {
          //          //console.log(json);
          var json = JSON.parse(json);
          if (json != "") {
            //--- --- ---//
            if (json.response) {
              $("#tr" + id_absence_vouchers)
                .closest("tr")
                .remove();
              //--- --- ---//
              Swal.fire({
                title: "Éxito!!",
                icon: "info",
                text: "Se eliminó correctamente el documento!!",
                showCancelButton: false,
                timer: 3000,
              }).then((result) => {
                if ($("#tablaDesgloseAusencias").find("tbody tr").length == 0) {
                  //window.location.reload();
                }
              });
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
    }
  });
});
$(document).on("click", ".deleteIncident", function () {
  var id_student_incidents_log = $(this).attr("id");

  Swal.fire({
    title: "¿Estás seguro?",
    text: "Este proceso no se podrá revertir!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, borrarlo!",
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "deleteIncident",
          id_student_incidents_log: id_student_incidents_log,
        },
      })
        .done(function (json) {
          //console.log(json);
          var json = JSON.parse(json);
          if (json != "") {
            //--- --- ---//
            if (json.response) {
              $("#" + id_student_incidents_log)
                .closest("tr")
                .remove();
              //--- --- ---//
              Swal.fire({
                title: "Éxito!!",
                icon: "info",
                text: "Se eliminó correctamente la justificación!!",
                showCancelButton: false,
                timer: 3000,
              }).then((result) => {
                if (
                  $("#tablaDesgloseIncidencias").find("tbody tr").length == 0
                ) {
                  window.location.reload();
                }
              });
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
    }
  });
});
$(document).on("click", ".new_incident", function () {
  var id_student = $(this).attr("id-student");

  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getStudentsSubjects",
      id_student: id_student,
    },
  })
    .done(function (data) {
      //console.log(json);
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        var html =
          "<option value='' selected disabled>Seleccione una materia</option>";
        subjects = data.data;
        for (let i = 0; i < subjects.length; i++) {
          html +=
            "<option value='" +
            subjects[i].id_subject +
            "'>" +
            subjects[i].name_subject +
            "</option>";
        }
        $("#id_subject_incident").html(html);
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
});

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
$(document).on("click", ".commentaryTracingIncidents", function () {
  var id_student_incidents_log = $(this).attr("id");
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
  var txt_commentary = $("#comentario_seguimiento").val();
  var teacher_name = $("#teacher_name_registered_tracking").val();
  var html_timeline = "";
  if (txt_commentary != "") {
    $.ajax({
      url: "php/controllers/justifyController.php",
      method: "POST",
      data: {
        mod: "saveCommentaryTracingIncident",
        txt_commentary: txt_commentary,
        id_student_incidents_log: id_student_incidents_log,
        id_teacher_tracking: id_teacher_tracking,
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
            $("#div_timeline_incidents").append(html_timeline);
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

  $("#comentario_seguimiento").val("");
});
$(document).on("click", ".btnDeleteTrackingCommit", function () {
  var id_absences_excuse = $(this).attr("id");
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¡No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí, eliminar!",
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "deleteCommentaryTracing",
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
              $("#div" + id_absences_excuse).remove();
              //--- --- ---//
            } else {
              Swal.fire({
                title: "Error",
                icon: "error",
                html: "Ocurrió un error al intentar eliminar el comentario!!",
                showCancelButton: false,
              });
            }
            //--- --- ---//
          } else {
            swal(
              "Atención!",
              "Ocurrió un error al consultar la información",
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
  });

  $("#comentario_seguimiento").val("");
});

$(document).on("click", ".btnDeleteTrackingCommit", function () {
  var id_absences_excuse = $(this).attr("id");
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¡No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí, eliminar!",
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "deleteCommentaryTracing",
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
              $("#div" + id_absences_excuse).remove();
              //--- --- ---//
            } else {
              Swal.fire({
                title: "Error",
                icon: "error",
                html: "Ocurrió un error al intentar eliminar el comentario!!",
                showCancelButton: false,
              });
            }
            //--- --- ---//
          } else {
            swal(
              "Atención!",
              "Ocurrió un error al consultar la información",
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
  });

  $("#comentario_seguimiento").val("");
});

$(document).on("click", ".btnDeleteTrackingCommitIncident", function () {
  var id_incident_tracking = $(this).attr("id");
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¡No podrás revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "¡Sí, eliminar!",
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "deleteCommentaryTracingIncident",
          id_incident_tracking: id_incident_tracking,
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
              $("#div" + id_incident_tracking).remove();
              //--- --- ---//
            } else {
              Swal.fire({
                title: "Error",
                icon: "error",
                html: "Ocurrió un error al intentar eliminar el comentario!!",
                showCancelButton: false,
              });
            }
            //--- --- ---//
          } else {
            swal(
              "Atención!",
              "Ocurrió un error al consultar la información",
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
  });

  $("#comentario_seguimiento").val("");
});
/* $(document).on("click", "#search_absences", function () {}); */

$("#comprobante_inasistencia").on("change", function () {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
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
$(document).on("click", ".addTrackingIncidentComment", function () {
  var id_student_incidents_log = $(this).attr("id");
  $(".commentaryTracingIncidents").attr("id", id_student_incidents_log);
  loading();

  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getCommentaryTracingIncidents",
      id_student_incidents_log: id_student_incidents_log,
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
          $("#div_timeline_incidents").empty();
          //--- --- ---/
          $("#div_timeline_incidents").append(json.html);
          student_name_code = json.student_name_code;
          $("#header_lbl_tracking_incidents").text(student_name_code);
        } else {
          student_name_code = json.student_name_code;
          $("#header_lbl_tracking_incidents").text(student_name_code);
        }

        //--- --- ---//
      } else {
      }
    })
    .fail(function () {});
});
$(document).on("click", ".editExcuseBreakdown", function () {
  var id_absences_excuse_breakdown = $(this).attr("id");
  $(".saveBreakdownChanges").attr("id", id_absences_excuse_breakdown);
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getBreakdownDetails",
      id_absences_excuse_breakdown: id_absences_excuse_breakdown,
    },
  })
    .done(function (json) {
      //console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          $("#breakdown_details_edit").empty();
          $("#breakdown_details_edit").html(json.html_sweet_alert);
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
});
$(document).on("click", ".saveBreakdownChanges", function () {
  var id_absences_excuse_breakdown = $(this).attr("id");
  var comment = $("#day_absence_comment").val();
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "updateBreakdownJustify",
      id_absences_excuse_breakdown: id_absences_excuse_breakdown,
      column: "day_absence_comment",
      aplica: comment,
    },
  })
    .done(function (json) {
      //console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          $("#comment" + id_absences_excuse_breakdown).empty();
          $("#comment" + id_absences_excuse_breakdown).html(comment);
          VanillaToasts.create({
            title: "Éxito!!",
            text: "Se actualizó correctamente la justificación",
            timeout: 1200,
            positionClass: "topRight",
          });
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
});

$(document).on("change", ".checkAusenciaAplica", function () {
  var id_absences_excuse_breakdown = $(this).attr("data-id-breakdown");
  var column = "apply_excuse";
  if (this.checked) {
    var aplica = 1;
  } else {
    var aplica = 0;
  }

  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "updateBreakdownJustify",
      id_absences_excuse_breakdown: id_absences_excuse_breakdown,
      column: column,
      aplica: aplica,
    },
  })
    .done(function (json) {
      //console.log(json);
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
});
$(document).on("change", ".checkAusenciaActiva", function () {
  var id_absences_excuse_breakdown = $(this).attr("data-id-breakdown");
  var column = "active_excuse";
  if (this.checked) {
    var aplica = 1;
  } else {
    var aplica = 0;
  }

  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "updateBreakdownJustify",
      id_absences_excuse_breakdown: id_absences_excuse_breakdown,
      column: column,
      aplica: aplica,
    },
  })
    .done(function (json) {
      //console.log(json);
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
});
$(document).on("click", ".updateAttendanceRecords", function () {
  var id_absences_excuse_breakdown = $(this).attr("data-id-breakdown");
  var justifyed = 0;
  if ($("#apply" + id_absences_excuse_breakdown).is(":checked")) {
    justifyed = 1;
  }
  Swal.fire({
    title: "¿Estás seguro?",
    text: "¿Desea actualizar el registro de asistencia?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, actualizar!",
    cancelButtonText: "No, cancelar!",
  }).then((result) => {
    if (result.value) {
      Swal.fire({
        title: "Se actualizó correctamente",
        text: "El registro de asistencia se actualizó correctamente",
        icon: "success",
        showConfirmButton: false,
        timer: 1500,
      });
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "updateBreakdownJustifyAttendance",
          id_absences_excuse_breakdown: id_absences_excuse_breakdown,
          justifyed: justifyed,
        },
      })
        .done(function (json) {
          //console.log(json);
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
    }
  });
  /*  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "updateBreakdownJustify",
      id_absences_excuse_breakdown: id_absences_excuse_breakdown,
      column: column,
      aplica: aplica,
    },
  })
    .done(function (json) {
      //console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          VanillaToasts.create({
            title: "Success",
            text: "Se actualizó correctamente la justificación",
            timeout: 1200,
            positionClass: "topRight",
          });
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
    }); */
});
$(document).on("click", ".search_incidents", function () {
  var date_search = $("#search_date").val();
  console.log(date_search);
  if (date_search.indexOf("/") >= 0) {
    console.log("formato alternativo");
    arr_date_search = date_search.split("/");
    date_search =
      arr_date_search[2] + "-" + arr_date_search[0] + "-" + arr_date_search[1];
  }
  loading();
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getIncidentsByDate",
      date_search: date_search,
    },
  })
    .done(function (json) {
      /* console.log(json); */
      var json = JSON.parse(json);
      if (json != "") {
        Swal.close();
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          $("#student_list_container").empty();
          $("#student_list_container").append(json.html);
          //--- --- ---/
        } else {
          $("#student_list_container").empty();
          $("#student_list_container").append(json.html);
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
});
$(document).on("click", ".search_absences", function () {
  var date_search = $("#search_date").val();
  console.log(date_search);
  loading();
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getAbsencesByDate",
      date_search: date_search,
    },
  })
    .done(function (json) {
      //console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        Swal.close();
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          $("#student_list_container").empty();
          $("#student_list_container").append(json.html);
          //--- --- ---/
        } else {
          $("#student_list_container").empty();
          $("#student_list_container").append(json.html);
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
});
$(document).on("click", ".btnSubjectsAbsences", function () {
  var id_student = $(this).attr("id-student");
  var absence_date = $(this).attr("data-absence-date");
  loading();
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getSubjectsByAbsence",
      id_student: id_student,
      absence_date: absence_date,
    },
  })
    .done(function (json) {
      //console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        Swal.close();
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          Swal.fire({
            title: "",
            text: "",
            html: json.html,
            showCancelButton: false,
            showConfirmButton: true,
            focusConfirm: false,
            confirmButtonText: "Cerrar",
            cancelButtonColor: "#3085d6",
            confirmButtonColor: "#3085d6",
            allowOutsideClick: false,
          });
          //--- --- ---/
        } else {
          $("#student_list_container").empty();
          $("#student_list_container").append(json.html);
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
});
$(document).on("click", ".uploadStudentDocument", function () {
  /* const file_input = $("#comprobante_inasistencia"); */
  const file_input = document.querySelector("#comprobante_inasistencia");
  var id_absences_excused = $(this).attr("id");
  var sql_db_table = "absence_excuse.absence_vouchers";
  var folder = "absence_excuse";
  var module_name = "COMPROBANTE_INASISTENCIA";
  var student_id = $(this).attr("data-id_student");
  console.log("id_absences_excused: " + id_absences_excused);
  console.log("sql_db_table: " + sql_db_table);
  console.log("folder_name: " + folder);
  console.log("student_id: " + student_id);
  uploadFile(
    file_input,
    id_absences_excused,
    sql_db_table,
    folder,
    student_id,
    module_name
  );
  //loading();
});

$(document).on("click", ".addTrackingDocument", function () {
  var id_absences_excuse = $(this).attr("data-id_absences_excuse");
  var student_id = $(this).attr("data-id_student");
  //$("#addAbsenceDocument").modal("show");
  //console.log(id_absences_excuse);
  $(".uploadStudentDocument").attr("id", id_absences_excuse);
  $(".uploadStudentDocument").attr("data-id_student", student_id);
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "getStudentInfo",
      id_student: student_id,
    },
  })
    .done(function (json) {
      //  $(".modal-backdrop").remove();
      //console.log(json);
      var json = JSON.parse(json);
      console.log(json);
      if (json != "") {
        Swal.close();
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          $("#info_student_name").text(json.data[0].name_student);
          $("#info_student_code").text(json.data[0].student_code);
          //--- --- ---/
        } else {
          console.log(json);
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
      // $(".modal-backdrop").remove();
      swal(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
});
$(document).on("click", ".trackingDocumentList", function () {
  loading();
  var id_absences_excuse = $(this).attr("data-id_absences_excuse");
  var student_id = $(this).attr("data-id_student");
  console.log(id_absences_excuse);
  $(".uploadStudentDocument").attr("id", id_absences_excuse);
  $(".uploadStudentDocument").attr("data-id_student", student_id);
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: {
      mod: "trackingDocumentList",
      id_absences_excuse: id_absences_excuse,
    },
  })
    .done(function (json) {
      //console.log(json);
      var json = JSON.parse(json);
      console.log(json);
      if (json != "") {
        Swal.close();
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          $("#documentList").empty();
          $("#documentList").append(json.html_table);
          //--- --- ---/
        } else {
          console.log(json);
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
      //$("body").removeClass("modal-open");
      //$(".modal-backdrop").remove();
      swal(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
});
$(document).on("click", ".btnAddDocumentList", function () {
  //$("#absenceDocumentList").modal("hide");
  $("#addAbsenceDocument").modal("show");
});
$("#type_report").change(function () {
  /* $("#id_group").prop("disabled", false); */
  $("#search_date").prop("disabled", false);
  $("#btn_search").prop("disabled", false);

  var id_type_report = $(this).val();

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("type_report")) {
    //--- --- ---//
    loading();
    var type_report = $(this).val();
    /* const id_group = $("#id_group").val(); */
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" + submodule + "&type_report=" + type_report;
    //--- --- ---//
  } else {
    loading();
    var type_report = $(this).val();
    /* const id_group = $("#id_group").val(); */
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" + submodule + "&type_report=" + type_report;
    //--- --- ---//
  }

  console.log(id_type_report);
  switch (id_type_report) {
    case "1":
      $("#btn_search").addClass("search_absences");
      $("#btn_search").removeClass("search_incidents");
      break;
    case "2":
      break;
    case "3":
      $("#btn_search").addClass("search_incidents");
      $("#btn_search").removeClass("search_absences");
      break;
    default:
    // code block
  }
});
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
function uploadFile(
  file_input,
  id_absences_excused,
  sql_db_table,
  folder,
  student_id,
  module_name
) {
  // loading();
  const file = file_input.files[0];
  vidFileLength = file_input.files.length;

  var file_n = file.name;
  var f = file_n.split(".");
  //--- --- ---//
  var name = file_input.getAttribute("name");
  //--- --- ---//
  name += ".";
  name += f[1];

  //--- --- ---//
  if (vidFileLength == 0) {
    $(".inputAddStudentDocument")
      .siblings(".custom-file-label")
      .removeClass("selected")
      .html("Elegir un archivo");
    Swal.close();
    Swal.fire("Atención!", "Debe elegir un archivo", "info");
    file_input.value = "";
    return;
  } else {
  }
  //--- --- ---//
  if (
    f[f.length - 1] != "pdf" &&
    f[f.length - 1] != "png" &&
    f[f.length - 1] != "jpg" &&
    f[f.length - 1] != "jpeg"
  ) {
    $(".inputAddStudentDocument")
      .siblings(".custom-file-label")
      .removeClass("selected")
      .html("Elegir un archivo");
    Swal.close();
    Swal.fire("Atención!", "Sólo puede subir archivos PDF o imagenes", "info");
    file_input.value = "";
    return;
  }
  //--- --- ---//
  if (file_input.files[0].size > 20000000) {
    Swal.close();
    Swal.fire(
      "Atención!",
      "El tamaño máximo del archivo a subir es de 20MB",
      "info"
    );
    file_input.value = "";
    return;
  }
  //--- --- ---//

  var fData = new FormData();
  fData.append("formData", file);
  fData.append("id_absences_excused", id_absences_excused);
  fData.append("sql_db_table", sql_db_table);
  fData.append("folder", folder);
  fData.append("student_code", student_id);
  fData.append("name", name);
  fData.append("module_name", module_name);
  fData.append("mod", "uploadStudentFiles");

  /* loadPin("Guardando archivo"); */
  $.ajax({
    url: "php/controllers/justifyController.php",
    method: "POST",
    data: fData,
    contentType: false,
    processData: false,
  })
    .done(function (response) {
      //console.log(response);

      var json = JSON.parse(response);
      if (json.response) {
        Swal.fire({
          title: "¡Archivo subido!",
          text: json.message,
          icon: "success",
          confirmButtonText: "Cerrar",
        }).then((result) => {
          $("#addAbsenceDocument").modal("hide");
          $(".inputAddStudentDocument")
            .siblings(".custom-file-label")
            .removeClass("selected")
            .html("Elegir un archivo");
          $("#addTrackingDocument_" + id_absences_excused).removeAttr(
            "data-toggle"
          );
          $("#addTrackingDocument_" + id_absences_excused).removeAttr(
            "data-target"
          );

          $("#addTrackingDocument_" + id_absences_excused).attr(
            "data-target",
            "#absenceDocumentList"
          );
          $("#addTrackingDocument_" + id_absences_excused).attr(
            "data-toggle",
            "modal"
          );

          $("#addTrackingDocument_" + id_absences_excused).removeClass(
            "addTrackingDocument"
          );
          $("#addTrackingDocument_" + id_absences_excused).addClass(
            "trackingDocumentList"
          );

          $("#iconAddTrackingDocument_" + id_absences_excused).removeClass(
            "fa-file-arrow-up"
          );
          $("#iconAddTrackingDocument_" + id_absences_excused).addClass(
            "fa-folder-open"
          );
        });
      } else {
        Swal.fire(
          "Error!",
          "Ocurrió un error al intentar subir el documento, intentelo nuevamente por favor",
          "error"
        );
      }
    })
    .fail(function (error) {
      Swal.fire(
        "Error!",
        "Ocurrió un error al intentar comunicarse con la base de datos :(",
        "error"
      );
      console.log(error);
    });
}
