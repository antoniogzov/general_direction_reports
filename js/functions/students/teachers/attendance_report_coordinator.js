//--- --- ---//

setTimeout(function () {
  var txt_group = $("#id_group option:selected").text();
  $("#txt_grupo").text(txt_group);
}, 800);
$(document).on("change", "#id_group", function () {
  $("#attendance_report_coordinator").html("");
  $("#att_type").prop("disabled", false);
  $("#att_type").val("");
});

var url = window.location.search;

const urlParams = new URLSearchParams(url);
if (urlParams.has("att_type")) {
  const att_type = urlParams.get("att_type");
  if (att_type.length > 0) {
    $("#att_type").val(att_type);
  }
}
function getAssistanceDetailsJustified(days, id_student) {
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
        '<li class="list-group-item list-group-item-dark">' +
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
//--- --- ---//
$(document).on("change", "#att_type", function () {
  var id_group = $("#id_group").val();
  getSubjectsByTeacher(id_group);

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const att_type = $(this).val();
    const id_group = $("#id_group").val();

    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_group=" +
      id_group +
      "&att_type=" +
      att_type;
    //--- --- ---//
  }
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
      console.log(date_arr);
      var date_arr = day_list[i].split("/");
      var date = date_arr[0] + "/" + date_arr[1] + "/" + date_arr[2];
      var subject = date_arr[3];
      var teacher = date_arr[4];
      var period = "PERIODO " + date_arr[5];
      var bloque = "Bloque:  " + date_arr[6];

      data +=
        '<li class="list-group-item list-group-item-danger" style="text-align:left !important; font-size:13px !important;"><strong>' +
        date +
        "</strong>  | " +
         bloque +" | " +
        subject +
        " | " +
        teacher +
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
          title: "<br/><strong>" + period + "</strong><br/>",
          icon: "info",
          html:
            "<h2>DETALLE DE AUSENCIAS</h2><br/>" +
            info.data[0].student_name +
            "<br/>" +
            info.data[0].student_code +
            "  | " +
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

$(document).on("click", ".btn_attendance_indexs", function () {
  var indexs = $(this).attr("data-ids-index");
  console.log(indexs);
  getAttendDetails("Asistencias Registradas", indexs);
  
});

function getAttendDetails(name_title, indexs) {
    loading();
  /* console.log(id_student); */
  console.log(indexs);
  
  /* Swal.fire({
    icon: "info",
    title: name_title,
    text: indexs,
  }); */
  $.ajax({
    url: "php/controllers/attendance_reports_cotroller.php",
    method: "POST",
    data: {
      mod: "getAttends",
      indexs:indexs
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
