//--- --- ---//
$(".date-input").datepicker({
  format: "yyyy-mm-dd",
});
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
$(document).on("click", "#btn_search_attendance", function () {
  var date_search = $(".date-input").val();
  var class_block = $('input[name="class_block"]:checked').val();
  if (validate(date_search) && date_search != "") {
    var id_assignment = $("#id_subject option:selected").attr("id");
    searchAttendance(date_search, id_assignment, class_block);
  } else {
    Swal.fire("Atención!", "Ingrese una fecha correcta :D", "info");
  }
});
$(document).on("click", "#btn_search_attendance_coordinator", function () {
  var date_search = $(".date-input").val();
  var class_block = $('input[name="class_block"]:checked').val();
  if (validate(date_search) && date_search != "") {
    var id_assignment = $("#id_subject option:selected").attr("id");
    searchAttendanceCoordinator(date_search, id_assignment, class_block);
  } else {
    Swal.fire("Atención!", "Ingrese una fecha correcta :D", "info");
  }
});
//--- --- ---//
$(document).on("change", "#id_subject", function () {
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const id_group = $("#id_group").val();
    const id_subject = $(this).val();
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_subject=" +
      id_subject +
      "&id_group=" +
      id_group;
    //--- --- ---//
  }
});
//--- --- ---//
$(document).on("change", "#id_group", function () {
  var id_group = $(this).val();
  if ($(".card-attendance").length > 0) {
    $(".card-attendance").html("");
  }
  getSubjectsByTeacher(id_group);
});
//--- --- ---//
$(document).on("click", "#btn_actualizar_asistencia", function (e) {
  //--- --- ---//
  e.preventDefault();
  var id_attendance_index = $(this).attr("data-id-index");
  var compulsory_class = $(".compulsory-class").is(":checked");
  var data = [];
  var presents = 0;
  var missing = 0;
  //--- --- ---//
  //--- --- ---//
  $(".check-student").each(function (i, obj) {
    //--- --- ---//
    if ($(this).is(":checked")) {
      presents++;
    } else {
      missing++;
    }
    //--- --- ---//
    var incident_id = $(this).closest("tr");
    incident_id = incident_id
      .find(".td-incidents-rollcall")
      .find(".form-group")
      .find(".select-incidents-rollcall");
    var attendance = {
      id_student: $(this).attr("id"),
      present: $(this).is(":checked"),
      incident_id: incident_id.val(),
    };
    //--- --- ---//
    data.push(attendance);
    //--- --- ---//
  });
  //--- --- ---//
  if (data.length > 0) {
    Swal.fire({
      title: "Atención!",
      icon: "info",
      html:
        "Se actualizará la asistencia: <br><br> Alumnos totales: <strong>" +
        $(".check-student").length +
        "</strong> <br> Alumnos presentes: <strong> " +
        presents +
        "</strong> <br> Alumnos ausentes: <strong> " +
        missing +
        ' </strong> <br> <br> <font color="red"> Registrará esta clase como: ' +
        (compulsory_class ? "Obligatoria" : "No obligatoria") +
        "</font>",
      showCancelButton: true,
      confirmButtonText: "Guardar",
    }).then((result) => {
      if (result.isConfirmed) {
        updateAttendance(data, compulsory_class, id_attendance_index);
      }
    });
  }
  //--- --- ---//
});
//--- --- ---//
$(document).on("change", "#select_observation", function (e) {
  id_student = $(this).attr("data-id-student");
  if ($(this).val() === "9") {
    var html_input =
      '<input type="text" class="form-control incidentManualInput" id="incidentManualInput' +
      id_student +
      '" placeholder="Describa la incidenca">';
    $(this).closest(".td-incidents-rollcall").append(html_input);
  } else {
    $("#incidentManualInput" + id_student).remove();
  }
});
$(document).on("click", "#btn_guardar_asistencia", function (e) {
  //--- --- ---//
  e.preventDefault();
  //--- --- ---//
  var data = [];
  var presents = 0;
  var missing = 0;
  var compulsory_class = $(".compulsory-class").is(":checked");
  var id_assignment = $("#id_subject option:selected").attr("id");
  var class_block = $('input[name="class_block"]:checked').val();
  //--- --- ---//
  $(".check-student").each(function (i, obj) {
    //--- --- ---//
    if ($(this).is(":checked")) {
      presents++;
    } else {
      missing++;
    }
    var std_id = $(this).attr("id");
    var apply_justification = 0;
    if ($("#check_justification" + std_id).is(":checked")) {
      apply_justification = 1;
    }
    var manual_incident_input = "";
    manual_incident_input = $("#incidentManualInput" + std_id).val();
    //--- --- ---//
    var incident_id = $(this).closest("tr");
    incident_id = incident_id
      .find(".td-incidents-rollcall")
      .find(".form-group")
      .find(".select-incidents-rollcall");
    var attendance = {
      id_student: $(this).attr("id"),
      present: $(this).is(":checked"),
      incident_id: incident_id.val(),
      apply_justification: apply_justification,
      manual_incident_input: manual_incident_input,
    };
    //--- --- ---//
    data.push(attendance);
    //--- --- ---//
  });
  //--- --- ---//
  //--- --- ---//
  if (data.length > 0) {
    Swal.fire({
      title: "Atención!",
      icon: "info",
      html:
        "Guardará los siguientes datos: <br><br> Alumnos totales: <strong>" +
        $(".check-student").length +
        "</strong> <br> Alumnos presentes: <strong> " +
        presents +
        "</strong> <br> Alumnos ausentes: <strong> " +
        missing +
        ' </strong> <br> <br> <font color="red"> Registrará esta clase como: ' +
        (compulsory_class ? "Obligatoria" : "No obligatoria") +
        "</font>",
      showCancelButton: true,
      confirmButtonText: "Guardar",
    }).then((result) => {
      if (result.isConfirmed) {
        saveAttendance(data, compulsory_class, id_assignment, class_block);
      }
    });
  }
  //--- --- ---//
});
$(document).on("click", ".btnGetStudentAttedanceHistoric", function (e) {
  //--- --- ---//
  var id_subject = $(this).attr("data-id-subject");
  var id_group = $(this).attr("data-id-group");
  var id_student = $(this).attr("data-id-student");
  var student_code = $(this).attr("data-student-code");
  var name_student = $(this).attr("data-name-student");
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getStudentAttendanceHistoric",
      id_subject: id_subject,
      id_group: id_group,
      id_student: id_student,
      student_code: student_code,
      name_student: name_student,
    },
  })
    .done(function (json) {
      console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          Swal.fire({
            icon: "info",
            html: json.html,
            customClass: 'swal-wide',
            showCancelButton: false,
          }).then((result) => {
            //location.reload();
          });
          //--- --- ---/
        } else {
          swal("Atención!", "No se registro asistencia", "error");
        }
        //--- --- ---//
      } else {
        swal("Atención!", "Ocurrió un error al registrar asistencias", "error");
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

  //--- --- ---//
});
$(document).on("change", ".periodAttendanceSwal", function (e) {
  //--- --- ---//
  var id_subject = $(this).attr("data-id-subject");
  var id_group = $(this).attr("data-id-group");
  var id_student = $(this).attr("data-id-student");
  var student_code = $(this).attr("data-student-code");
  var name_student = $(this).attr("data-name-student");

  var id_period_calendar = $(".periodAttendanceSwal  option:selected").val();
  console.log(id_period_calendar);
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getStudentAttendanceHistoricSwal",
      id_subject: id_subject,
      id_group: id_group,
      id_student: id_student,
      student_code: student_code,
      name_student: name_student,
      id_period_calendar: id_period_calendar,
    },
  })
    .done(function (json) {
      console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          Swal.fire({
            icon: "info",
            html: json.html,
            customClass: 'swal-wide',
            showCancelButton: false,
          }).then((result) => {
            //location.reload();
          });
          //--- --- ---/
        } else {
          swal("Atención!", "No se registro asistencia", "error");
        }
        //--- --- ---//
      } else {
        swal("Atención!", "Ocurrió un error al registrar asistencias", "error");
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

  //--- --- ---//
});
$(document).on("click", ".btnViewCommentary", function (e) {
  //--- --- ---//
  var commentary = $(this).attr("data-comentario");
  Swal.fire({
    title: "Comentario",
    icon: "info",
    html: "<h3>" + commentary + "</h3>",
    showCancelButton: false,
    confirmButtonText: "Aceptar",
  });
  //--- --- ---//
});
//--- --- ---//
//--- --- ---//
function updateAttendance(data, compulsory_class, id_attendance_index) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "updateAttendance",
      data: data,
      compulsory_class: compulsory_class,
      id_attendance_index: id_attendance_index,
    },
  })
    .done(function (json) {
      console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          Swal.fire({
            title: "Atención!",
            icon: "info",
            text: json.message,
            showCancelButton: false,
          }).then((result) => {
            location.reload();
          });
          //--- --- ---/
        } else {
          swal(
            "Atención!",
            "No se pudieron actualizar los datos :( intentelo nuevamente",
            "error"
          );
        }
        //--- --- ---//
      } else {
        swal("Atención!", "Ocurrió un error al registrar asistencias", "error");
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
}
//--- --- ---//
function saveAttendance(data, compulsory_class, id_assignment, class_block) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "saveAttendance",
      data: data,
      compulsory_class: compulsory_class,
      class_block: class_block,
      id_assignment: id_assignment,
    },
  })
    .done(function (json) {
      console.log(json);
      var json = JSON.parse(json);
      if (json != "") {
        //--- --- ---//
        if (json.response) {
          //--- --- ---//
          Swal.fire({
            title: "Atención!",
            icon: "info",
            html: json.message,
            showCancelButton: false,
          }).then((result) => {
            //location.reload();
          });
          //--- --- ---/
        } else {
          swal("Atención!", "No se registro asistencia", "error");
        }
        //--- --- ---//
      } else {
        swal("Atención!", "Ocurrió un error al registrar asistencias", "error");
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
}
//--- --- ---//
function getGroups(id_subject) {
  //--- --- ---//
  loading();
  $("#id_group").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getGroups",
      id_subject: id_subject,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      var options =
        '<option selected value="" disabled>Elija una opción</option>';
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          options +=
            '<option value="' +
            data.data[i].id_group +
            '">' +
            data.data[i].group_code +
            "</option>";
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
      $("#id_group").html(options);
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
}

function getGroups2() {
  //--- --- ---//
  loading();
  $("#id_group").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getGroups2",
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      var options =
        '<option selected value="" disabled>Elija una opción</option>';
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          options +=
            '<option value="' +
            data.data[i].id_group +
            '">' +
            data.data[i].group_code +
            "</option>";
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
      $("#id_group").html(options);
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
}

function getSubjectsByTeacher(id_group) {
  //--- --- ---//
  loading();
  $("#id_subject").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getSubjectsByTeacher",
      id_group: id_group,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      var options =
        '<option selected value="" disabled>Elija una opción</option>';
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          options +=
            "<option id=" +
            data.data[i].id_assingment +
            ' value="' +
            data.data[i].id_subject +
            '">' +
            data.data[i].name_subject +
            "</option>";
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
      $("#id_subject").html(options);
      $("#id_group").val(id_group);
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
}
//--- --- ---//
function searchAttendance(date, id_assignment, class_block) {
  //--- --- ---//
  loading();
  $(".container-list-students").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "searchAttendance",
      date: date,
      id_assignment: id_assignment,
      class_block: class_block,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        var disabled = data.editable ? "" : "disabled";
        var datos =
          '<h3 class="ml-2">REGISTRO DE ASISTENCIA DEL DÍA: ' + date + "</h3>";
        datos +=
          '<h3 class="heading mb-0 ml-2">Alumnos: ' +
          data.records.length +
          "</h3>";
        datos += '<div class="table-responsive">';
        datos +=
          '<table class="table align-items-center table-flush" id="tStudents">';
        datos += '<thead class="thead-light">';
        datos += "<tr>";
        datos += '<th class="font-weight-bold col-md-2">Cód. alumno</th>';
        datos += '<th class="font-weight-bold col-md-4">Nombre</th>';
        datos += '<th class="font-weight-bold col-md-2">Presente</th>';
        datos += '<th class="col-md-4">Observaciones</th>';
        datos += "</tr>";
        datos += "</thead>";
        datos += '<tbody class="list">';
        for (var i = 0; i < data.records.length; i++) {
          datos += '<tr id="' + data.records[i].id_student + '">';
          datos += "<td>" + data.records[i].student_code + "</td>";
          datos += "<td>" + data.records[i].name_student + "</td>";
          //--- --- ---//
          datos += '<td class="text-center">';
          datos += '<label class="custom-toggle custom-toggle-success">';
          //--- --- ---//
          var checked = data.records[i].present == "1" ? "checked" : "";
          //--- --- ---//
          datos +=
            '<input type="checkbox" class="check-student" id="' +
            data.records[i].id_student +
            '" ' +
            disabled +
            " " +
            checked +
            ">";
          datos +=
            '<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>';
          datos += "</label>";
          datos += "</td>";
          //--- --- ---//
          datos += '<td class="td-incidents-rollcall">';
          datos += '<div class="form-group">';
          datos +=
            '<select  class="form-control-sm select-incidents-rollcall" ' +
            disabled +
            ">";
          for (var e = 0; e < data.listIncidents.length; e++) {
            if (
              data.listIncidents[e].incident_id == data.records[i].incident_id
            ) {
              datos +=
                '<option value="' +
                data.listIncidents[e].incident_id +
                '" selected>' +
                data.listIncidents[e].incident +
                "</option>";
            } else {
              datos +=
                '<option value="' +
                data.listIncidents[e].incident_id +
                '">' +
                data.listIncidents[e].incident +
                "</option>";
            }
          }
          datos += "</select>";
          datos += "</div>";
          datos += "</td>";
          //--- --- ---//
          datos += "</tr>";
        }
        datos += "</tbody>";
        datos += "</table>";
        datos += "</div>";
        //--- --- ---//
        datos += '<div class="col mt-4 d-flex">';
        datos += '<div class="col float-left">';
        datos += "<label>¿Lección obligatoria?</label>";
        datos += '<label class="custom-toggle custom-toggle-info">';
        //--- --- ---//
        var checked = data.obligatory == "1" ? "checked" : "";
        //--- --- ---//
        datos +=
          '<input type="checkbox" class="compulsory-class" ' +
          checked +
          " " +
          disabled +
          ">";
        datos +=
          '<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>';
        datos += "</label>";
        datos += "</div>";
        if (data.editable) {
          datos +=
            '<input type="button" class="btn btn-warning float-right mr-5" data-id-index="' +
            data.id_attendance_index +
            '" id="btn_actualizar_asistencia" value="Actualizar asistencia"/>';
        }
        //--- --- ---//
        $(".container-list-students").html(datos);
        //--- --- ---//
      } else {
        $(".container-list-students").html(
          '<h2 class="text-center">NO HAY REGISTROS EN ESTA FECHA</h2>'
        );
      }
      //--- --- ---//
      $(".div-button-refresh").show("slow");
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
}

function searchAttendanceCoordinator(date, id_assignment, class_block) {
  //--- --- ---//
  loading();
  $(".container-list-students").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "searchAttendance",
      date: date,
      id_assignment: id_assignment,
      class_block: class_block,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        var disabled = data.editable ? "" : "disabled";
        var datos =
          '<h3 class="ml-2">REGISTRO DE ASISTENCIA DEL DÍA: ' + date + "</h3>";
        datos +=
          '<h3 class="heading mb-0 ml-2">Alumnos: ' +
          data.records.length +
          "</h3>";
        datos += '<div class="table-responsive">';
        datos +=
          '<table class="table align-items-center table-flush" id="tStudents">';
        datos += '<thead class="thead-light">';
        datos += "<tr>";
        datos += '<th class="font-weight-bold col-md-2">Cód. alumno</th>';
        datos += '<th class="font-weight-bold col-md-4">Nombre</th>';
        datos += '<th class="font-weight-bold col-md-2">Presente</th>';
        datos += '<th class="col-md-4">Observaciones</th>';
        datos += "</tr>";
        datos += "</thead>";
        datos += '<tbody class="list">';
        for (var i = 0; i < data.records.length; i++) {
          datos += '<tr id="' + data.records[i].id_student + '">';
          datos += "<td>" + data.records[i].student_code + "</td>";
          datos += "<td>" + data.records[i].name_student + "</td>";
          //--- --- ---//
          datos += '<td class="text-center">';
          datos += '<label class="custom-toggle custom-toggle-success">';
          //--- --- ---//
          var checked = data.records[i].present == "1" ? "checked" : "";
          //--- --- ---//
          datos +=
            '<input type="checkbox" class="check-student" id="' +
            data.records[i].id_student +
            '" ' +
            disabled +
            " " +
            checked +
            ">";
          datos +=
            '<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>';
          datos += "</label>";
          datos += "</td>";
          //--- --- ---//
          datos += '<td class="td-incidents-rollcall">';
          datos += '<div class="form-group">';
          datos +=
            '<select class="form-control-sm select-incidents-rollcall" ' +
            disabled +
            ">";
          for (var e = 0; e < data.listIncidents.length; e++) {
            if (
              data.listIncidents[e].incident_id == data.records[i].incident_id
            ) {
              datos +=
                '<option value="' +
                data.listIncidents[e].incident_id +
                '" selected>' +
                data.listIncidents[e].incident +
                "</option>";
            } else {
              datos +=
                '<option value="' +
                data.listIncidents[e].incident_id +
                '">' +
                data.listIncidents[e].incident +
                "</option>";
            }
          }
          datos += "</select>";
          datos += "</div>";
          datos += "</td>";
          //--- --- ---//
          datos += "</tr>";
        }
        datos += "</tbody>";
        datos += "</table>";
        datos += "</div>";
        //--- --- ---//
        datos += '<div class="col mt-4 d-flex">';
        datos += '<div class="col float-left">';
        datos += "<label>¿Lección obligatoria?</label>";
        datos += '<label class="custom-toggle custom-toggle-info">';
        //--- --- ---//
        var checked = data.obligatory == "1" ? "checked" : "";
        //--- --- ---//
        datos +=
          '<input type="checkbox" class="compulsory-class" ' +
          checked +
          " " +
          disabled +
          ">";
        datos +=
          '<span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Sí"></span>';
        datos += "</label>";
        datos += "</div>";
        if (data.editable) {
          datos +=
            '<input type="button" class="btn btn-warning float-right mr-5" data-id-index="' +
            data.id_attendance_index +
            '" id="btn_actualizar_asistencia" value="Actualizar asistencia"/>';
        }
        //--- --- ---//
        $(".container-list-students").html(datos);
        //--- --- ---//
      } else {
        $(".container-list-students").html(
          '<h2 class="text-center">NO HAY REGISTROS EN ESTA FECHA</h2>'
        );
      }
      //--- --- ---//
      $(".div-button-refresh").show("slow");
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
}
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
function validate(dateString) {
  var regEx = /^\d{4}-\d{2}-\d{2}$/;
  if (!dateString.match(regEx)) return false; // Invalid format
  var d = new Date(dateString);
  var dNum = d.getTime();
  if (!dNum && dNum !== 0) return false; // NaN value, Invalid date
  return d.toISOString().slice(0, 10) === dateString;
}
