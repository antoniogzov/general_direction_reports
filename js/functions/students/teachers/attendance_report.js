//--- --- ---//

setTimeout(function () {
  const id_sbjt = $("#id_sbjt").val();
  $("#id_subject").val(id_sbjt);
  var txt_group = $("#id_group option:selected").text();
  var txt_subject = $("#id_subject option:selected").text();
  $("#txt_grupo").text(txt_group + " | " + txt_subject);
  console.log(id_sbjt);
}, 1000);

var url = window.location.search;
const urlParams = new URLSearchParams(url);
if (urlParams.has("att_type")) {
  const att_type = urlParams.get("att_type");
  if (att_type.length > 0) {
    $("#att_type").val(att_type);
  }
}
$(document).on("change", "#att_type", function () {
  var id_group = $("#id_group").val();

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const att_type = $(this).val();
    const id_group = $("#id_group").val();
    const id_subject = $("#id_subject").val();
    const id_assignment = $("#id_subject option:selected").attr("id");
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_group=" +
      id_group +
      "&id_subject=" +
      id_subject +
      "&att_type=" +
      att_type +
      "&id_assignment=" +
      id_assignment;
    //--- --- ---//
  }
});
$(document).on("change", "#id_subject", function () {
  $("#table_attendance_report_teacher").html("");
  $("#att_type").prop("disabled", false);
  $("#att_type").val("");
});
//--- --- ---//
$(document).on("change", "#id_group", function () {
  $("#id_subject").val("");
  $("#att_type").prop("disabled", true);
  $("#table_attendance_report_teacher").html("");
  $("#att_type").val("");
  var id_group = $(this).val();
  getSubjectsByTeacher(id_group);
});

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
            '<option id="' +
            data.data[i].id_assignment +
            '" value="' +
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
//--- --- ---//
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
  //$("#att_type").prop("disabled", false);
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
            '<option id="' +
            data.data[i].id_assignment +
            '" value="' +
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

function getAssistanceDetailsNew(arr_ids_attendance_index, id_student) {
  //console.log(arr_ids_attendance_index);
  //console.log(id_student);
  data = "";

  var day_list = arr_ids_attendance_index.split("-");
  var c = day_list.length;
  if (c > 1) {
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getAttendanceDetailsJSON",
        ids_attendance_index: arr_ids_attendance_index,
        id_student: id_student,
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        // console.log(info);
        Swal.fire({
          title: "<h2>DETALLE DE ASISTENCIAS / AUSENCIAS</h2>",
          icon: "info",
          html: info.data,
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
        });
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
  } else {
    Swal.fire({
      title: "<strong>DETALLE DE AUSENCIAS</strong>",
      icon: "info",
      html: "<h3>Este alumno no cuenta con inasistencias</h3>",
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: "Aceptar",
    });
  }
}
function getAssistanceDetails(days, id_student) {
  console.log(days);
  console.log(id_student);
  data = "";
  var txt_group = $("#id_group option:selected").text();
  var txt_subject = $("#id_subject option:selected").text();

  var day_list = days.split("-");
  var c = day_list.length - 1;
  if (c > 0) {
    for (var i = 0; i < c; i++) {
      data +=
        '<li class="list-group-item list-group-item-danger">' +
        day_list[i] +
        "</li>";
    }
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "StudentInfoByID",
        id_student: id_student,
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        console.log(info.data[0].id_student);
        Swal.fire({
          title: "<h2>DETALLE DE AUSENCIAS</h2>",
          icon: "info",
          html:
            "<strong>" +
            txt_subject +
            "</strong><br/>" +
            info.data[0].student_code +
            " | " +
            "" +
            info.data[0].student_name +
            " | " +
            txt_group +
            "<br/><br/>" +
            data +
            "",
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
        });
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
  } else {
    Swal.fire({
      title: "<strong>DETALLE DE AUSENCIAS</strong>",
      icon: "info",
      html: "<h3>Este alumno no cuenta con inasistencias</h3>",
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: "Aceptar",
    });
  }
}

function getAssistanceDetailsReports(
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

function getAssistancesDetails(
  id_student,
  date,
  student_code,
  student_name,
  id_group
) {
  loading();
  var group_name = $("#txt_grupo").text();

  loading();
  $("#id_group").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getAssistanceDetails",
      id_student: id_student,
      date: date,
      student_code: student_code,
      student_name: student_name,
      id_group: id_group,
    },
  })
    .done(function (data) {
      data = $.parseJSON(data);
      //console.log(data.data);
      Swal.fire({
        title: "<strong>Detalle de asistencia</strong>",
        icon: "info",
        html:
          "" +
          student_code +
          "</b></br> " +
          "" +
          student_name +
          "</b></br>  " +
          "" +
          date +
          "</b></br>" +
          "<b>Registro de clases:</b></br>" +
          "" +
          data.data +
          "",
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: "Aceptar",
      });
      /* var data = JSON.parse(data);
        var options = '<option selected value="" disabled>Elija una opción</option>';
        if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
                options += '<option value="' + data.data[i].id_group + '">' + data.data[i].group_code + '</option>';
            }
        } else {
            VanillaToasts.create({
                title: 'Error',
                text: data.message,
                type: 'error',
                timeout: 1200,
                positionClass: 'topRight'
            });
        }
        //--- --- ---//
        $('#id_group').html(options);
        swal.close(); */
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
