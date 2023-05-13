//--- --- ---//
console.log("general_attendance_report.js loaded");
//--- --- ---//
//--- --- ---//
$(document).ready(function () {
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
});
$(document).on("click", "#btn_search_attendance", function () {
  var date_search = $(".date-input").val();
  if (validate(date_search) && date_search != "") {
    var id_assignment = $("#id_subject option:selected").attr("id");
    searchAttendance(date_search, id_assignment);
  } else {
    Swal.fire("Atención!", "Ingrese una fecha correcta :D", "info");
  }
});
$(document).on("click", "#get_week_attendance", function () {
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    //const id_assignment = $("#id_group option:selected").attr('id');
    const id_academic = $("#id_academic").val();
    const week_picker = $("#week_picker").val();
    console.log(week_picker);
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_academic=" +
      id_academic +
      "&week=" +
      week_picker;
    //--- --- ---//
  }
  if (urlParams.has("id_group")) {
    $("#get_week_attendance").show();
  }
});
$(document).on("change", "#id_group", function () {
  var id_group = $(this).val();
  console.log(id_group);
});

$(document).on("change", "#id_academic", function () {
  $("#div_week").show();
});
//--- --- ---//
$(document).on("click", ".btn_absence", function () {
  str_id_attendance = $(this).attr("id");

  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getGroupAssistanceDetails",
      str_id_attendance: str_id_attendance,
    },
  })
    .done(function (data) {
      data = $.parseJSON(data);
      console.log(data);
      Swal.fire({
        title: "<strong>Detalle de inasistencias</strong>",
        icon: "info",
        html: "" + data.html + "",
        showCloseButton: true,
        heightAuto: false,
        focusConfirm: false,
        confirmButtonText: "Aceptar",
      });
    })
    .fail(function (message) {});
});
$(document).on("click", ".btn_attendance_indexs", function () {
  var indexs = $(this).attr("data-ids-index");
  /* console.log(indexs); */
  getAttendDetails("Asistencias Registradas", indexs);
});

function getAttendDetails(name_title, indexs) {
  loading();
  /* console.log(id_student); */
  /* console.log(indexs); */

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
      VanillaToasts.create({
        title: "Error",
        text: "Ocurrió un error, intentelo nuevamente",
        type: "error",
        timeout: 1200,
        positionClass: "topRight",
      });
    });
}
function mostrarSweet(html) {}

function getGroups(id_subject) {
  //--- --- ---//
  loading();
  $("#id_group").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getGroupsByNoTeacher",
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

function getAssistanceDetails(
  id_student,
  date,
  student_code,
  student_name,
  id_group
) {
  loading();
  var group_name = $("#txt_grupo").text();
  arr_date = date.split("-");
  date_format = arr_date[2] + "/" + arr_date[1] + "/" + arr_date[0];
  loading();

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
          " | " +
          "" +
          student_name +
          " | " +
          group_name +
          "</br>" +
          "" +
          date_format +
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
/* if ($('#tStudentsReport').length > 0) {
    var tf = new TableFilter('tStudentsReport', {
        base_path: '../general/js/vendor/tablefilter/tablefilter/',
        col_0: '',
        //col_1: 'select',
        col_2: 'select',
        col_3: 'select',
        col_4: 'select',
        col_5: 'select',
        col_6: 'select',
        auto_filter: {
            delay: 100 //milliseconds
        },
        btn_reset: true,
    });
    tf.init();
}*/
//--- --- ---//
