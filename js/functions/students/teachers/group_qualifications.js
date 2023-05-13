//getGroups2();
//--- INICIALIZAMOS TABLA ---//
var txt_group = $("#id_group option:selected").text();
var txt_period = $("#id_period option:selected").text();
let tableDT = $("#tQualifications").DataTable({
  colReorder: false,
  dom: "Bfrtip",
  "ordering": false,
  lengthMenu: [
    [40, 25, 50, -1],
    ["10 rows", "25 rows", "50 rows", "Show all"],
  ],
  buttons: [
    {
      extend: "excel",
      text: "Excel",
      className: "exportExcel",
      filename: txt_group + " | Periodo:" + txt_period,
      exportOptions: {
        modifier: {
          page: "all",
        },
      },
    },
    {
      extend: "csv",
      text: "CSV",
      className: "exportExcel",
      filename: txt_group + " | Periodo:" + txt_period,
      exportOptions: {
        modifier: {
          page: "all",
        },
      },
    },
    {
      extend: "pdfHtml5",
      text: "PDF",
      messageTop:
        "Reporte de Calificaciones - " + txt_group + " | Periodo:" + txt_period,
      className: "exportExcel",
      filename: txt_group + " | Periodo:" + txt_period,
      orientation: "landscape",
      pageSize: "LEGAL",
      exportOptions: {
        modifier: {
          page: "all",
        },
      },
    },
  ],
});
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
Swal.close();
//--- --- ---//
$(document).on("click", "#btn_search_attendance", function () {
  var date_search = $(".date-input").val();
  if (validate(date_search) && date_search != "") {
    var id_assignment = $("#id_subject option:selected").attr("id");
    searchAttendance(date_search, id_assignment);
  } else {
    Swal.fire("Atención!", "Ingrese una fecha correcta :D", "info");
  }
});
//--- --- ---//
$(document).on("change", "#id_subject", function () {
  loading();
  const id_group = $("#id_group").val();
  const id_subject = $(this).val();
});
//--- --- ---//
$(document).on("change", "#id_period", function () {
  var id_period = $(this).val();
  var id_academic = $("#id_academic").val();
  var id_group = $("#id_group").val();
  const average_type = document.querySelector(
    'input[name="radio-averages-type"]:checked'
  ).value;
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const id_subject = $(this).val();
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_academic=" +
      id_academic +
      "&id_group=" +
      id_group +
      "&id_period=" +
      id_period +
      "&average_type=" +
      average_type;
    //--- --- ---//
  }
});
const radios = document.querySelectorAll('input[name="radio-averages-type"]');
radios.forEach((radio) => {
  radio.addEventListener("click", function () {
    const id_academic = document.querySelector("#id_academic").value;
    const id_group = document.querySelector("#id_group").value;
    const id_period = document.querySelector("#id_period").value;
    const average_type = document.querySelector(
      'input[name="radio-averages-type"]:checked'
    ).value;
    if (average_type != "" && id_group != "" && id_period != "") {
      var url = window.location.search;
      const urlParams = new URLSearchParams(url);
      if (urlParams.has("submodule")) {
        //--- --- ---//
        const id_subject = $(this).val();
        const submodule = urlParams.get("submodule");
        window.location.search =
          "submodule=" +
          submodule +
          "&id_academic=" +
          id_academic +
          "&id_group=" +
          id_group +
          "&id_period=" +
          id_period +
          "&average_type=" +
          average_type;
        //--- --- ---//
      }
    }
  });
});
//--- --- ---//
$(document).on("change", "#id_period_teacher", function () {
  var id_period = $(this).val();
  var id_academic_area = $("#id_academic_area").val();
  var id_level_combination = $(".id_academic_level option:selected").attr("id");
  var id_academic_level = $(".id_academic_level").val();
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const id_subject = $(this).val();
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_academic_level=" +
      id_academic_level +
      "&id_level_combination=" +
      id_level_combination +
      "&id_academic_area=" +
      id_academic_area +
      "&id_period=" +
      id_period;
    //--- --- ---//
  }
});
//--- --- ---//
function getPeriodsByAcademicLevel(id_level_combination) {
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getPeriodsByIdLevelCombination",
      id_level_combination: id_level_combination,
      add_option_all_periods: "add_option_all_periods",
    },
  })
    .done(function (data) {
      // console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          var no_period = data.data[i].no_period;
          var options =
            '<option selected value="" disabled>Elija una opción</option>';
          if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
              options +=
                '<option value="' +
                data.data[i].id_period_calendar +
                '">' +
                data.data[i].no_period +
                "</option>";
            }
          }
        }
        console.log(no_period);
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
      /* $('#id_subject').val(id_subject);
        $('#id_group').val(id_group); */
      $("#id_period").html(options);
      $("#id_period_teacher").html(options);
      /*  var url = window.location.search;
         const urlParams = new URLSearchParams(url);
         if (urlParams.has('submodule')) {
             //--- --- ---//
             const submodule = urlParams.get('submodule');
             window.location.search = 'submodule=' + submodule + '&id_subject=' + id_subject + '&id_group=' + id_group + '&id_assignment=' + id_assignment;
             //--- --- ---//
         } */
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
$(document).on("change", "#id_academic", function () {
  $("#div_tabla").empty();
  var id_academic = $(this).val();
  getGroups(id_academic);
});
//--- --- ---//
$(document).on("change", "#id_group", function () {
  var id_group = $(this).val();
  var id_academic = $("#id_academic").val();
  loading();
  $("#div_tabla").empty();
  console.log(id_academic);
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getPeriodsGroupAcademic",
      id_group: id_group,
      id_academic: id_academic,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      //--- --- ---//
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          var no_period = data.data[i].no_period;
          var options =
            '<option selected value="" disabled>Elija una opción</option>';
          if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
              options +=
                '<option value="' +
                data.data[i].id_period_calendar +
                '">' +
                data.data[i].no_period +
                "</option>";
            }
            options += '<option value="all_periods">TODOS PERIODOS</option>';
          }
        }
        //--- ---//
        $("#id_period").html(options);
        //--- ---//
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
$(document).on("change", ".id_academic_level", function () {
  var id_level_combination = $(".id_academic_level option:selected").attr("id");
  console.log(id_level_combination);
  loading();
  $("#div_tabla").empty();
  getPeriodsByAcademicLevel(id_level_combination);
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
$(document).on("click", "#btn_guardar_asistencia", function (e) {
  //--- --- ---//
  e.preventDefault();
  //--- --- ---//
  var data = [];
  var presents = 0;
  var missing = 0;
  var compulsory_class = $(".compulsory-class").is(":checked");
  var id_assignment = $("#id_subject option:selected").attr("id");
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
        saveAttendance(data, compulsory_class, id_assignment);
      }
    });
  }
  //--- --- ---//
});
//--- --- ---//

//--- --- ---//
$(document).on("click", ".addPeriodGradeCommentary", function (e) {
  //--- --- ---//
  var commentary = $("#PeriodGradeCommentary").val();
  var id_grade_period = $(this).attr("data-id-grade-period");
  loading();
  if (id_grade_period != "-" && commentary != "") {
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "saveGradePeriodCommentary",
        commentary: commentary,
        id_grade_period: id_grade_period,
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        console.log(id_grade_period);
        $("#tdGP" + id_grade_period).css("border", "red solid 1px");
        Swal.fire({
          title: "Atención!",
          icon: "info",
          html: "Se ha guardado el comentario",
        });
      })
      .fail(function (message) {
        Swal.fire({
          title: "Atención!",
          icon: "info",
          html: "No se ha podido guardar el comentario",
        });
      });
  } else {
    Swal.fire({
      title: "Atención!",
      icon: "info",
      html: "Debe ingresar un comentario",
    });
  }
  //--- --- ---//
});
//--- --- ---//

//--- --- ---//
$(document).on("click", ".deletePeriodGradeCommentary", function (e) {
  //--- --- ---//
  var id_grade_period = $(this).attr("data-id-grade-period");
  loading();
  if (id_grade_period != "-") {
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "deleteGradePeriodCommentary",
        id_grade_period: id_grade_period,
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        console.log(id_grade_period);
        $("#tdGP" + id_grade_period).css("border", "white solid 1px");
        Swal.fire({
          title: "Atención!",
          icon: "info",
          html: "Se ha eliminado el comentario",
        });
      })
      .fail(function (message) {
        Swal.fire({
          title: "Atención!",
          icon: "info",
          html: "No se ha podido eliminar el comentario",
        });
      });
  } else {
    Swal.fire({
      title: "Atención!",
      icon: "info",
      html: "Debe ingresar un comentario",
    });
  }
  //--- --- ---//
});
//--- --- ---//

$(document).on("change", ".checkEPCommentary", function (e) {
  var id_grade_period = $(this).attr("data-id-grade-period");
  console.log(id_grade_period);
  if ($(this).is(":checked")) {
    var checked = 1;
    $("#tdGP" + id_grade_period).css("border", "#0330fc solid 2px");
  } else {
    var checked = 0;
    $("#tdGP" + id_grade_period).css("border", "red solid 2px");
  }
  console.log(checked);
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "updateCheckedGradePeriodCommentary",
      id_grade_period: id_grade_period,
      checked: checked,
    },
  })
    .done(function (info) {
      info = $.parseJSON(info);
      console.log(id_grade_period);
      /* Swal.fire({
        title: "Atención!",
        icon: "info",
        html: "Se ha actualizado el status del comentario",
      }); */
    })
    .fail(function (message) {
      Swal.fire({
        title: "Atención!",
        icon: "info",
        html: "No se ha podido actualizar el status del comentario",
      });
    });
});
//--- --- ---//
function getCriteriaDetails(
  id_grade_period,
  id_student,
  id_assignment,
  promedio,
  grade_period_calc
) {
  loading();
  if (id_grade_period != "-") {
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getCriteriaDetails",
        id_grade_period: id_grade_period,
        id_student: id_student,
        id_assignment: id_assignment,
        promedio: promedio,
        grade_period_calc: grade_period_calc,
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        // console.log(info);
        Swal.fire({
          title: "<h2>DESGLOSE DE CALIFICACIÓN</h2>",
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
      title: "<strong>DESGLOSE DE CALIFICACIÓN</strong>",
      icon: "info",
      html: "<h3>Aún no se asigna calificación para este periodo</h3>",
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: "Aceptar",
    });
  }
}
  function getCriteriaDetailsStudents(
  id_student,
  id_assignment,
  id_period_calendar,
  id_group
) {
  loading();
  if (id_grade_period != "-") {
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getCriteriaDetailsArchive",
        id_student: id_student,
        id_assignment: id_assignment,
        id_period_calendar:id_period_calendar,
        id_group:id_group
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        // console.log(info);
        Swal.fire({
          title: "<h2>DESGLOSE DE CALIFICACIÓN</h2>",
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
      title: "<strong>DESGLOSE DE CALIFICACIÓN</strong>",
      icon: "info",
      html: "<h3>Aún no se asigna calificación para este periodo</h3>",
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: "Aceptar",
    });
  }
}
function getGroups(id_academic) {
  //--- --- ---//
  loading();
  $("#id_group").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getGroupByAcademicArea",
      id_academic: id_academic,
      add_option_all_periods: "add_option_all_periods",
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

function downloadTable(type, group_code, fn, dl) {
  var elt = document.getElementById(group_code);
  var wb = XLSX.utils.table_to_book(elt, {
    sheet: "sheet1",
  });
  return dl
    ? XLSX.write(wb, {
        bookType: type,
        bookSST: true,
        type: "base64",
      })
    : XLSX.writeFile(wb, fn || group_code + "." + (type || "xlsx"));
}
//--- --- ---//
function exportTable() {
  console.log("kdv ewrlvn3oirtv3");
  var txt_group = $("#id_group option:selected").text();
  var txt_period = $("#id_period option:selected").text();
  $("#tQualifications").DataTable({
    colReorder: false,
    dom: "Bfrtip",
    "ordering": false,
    lengthMenu: [
      [40, 25, 50, -1],
      ["10 rows", "25 rows", "50 rows", "Show all"],
    ],
    buttons: [
      {
        extend: "excel",
        text: "Excel",
        className: "exportExcel",
        filename: txt_group + " | Periodo:" + txt_period,
        exportOptions: {
          modifier: {
            page: "all",
          },
        },
      },
      {
        extend: "csv",
        text: "CSV",
        className: "exportExcel",
        filename: txt_group + " | Periodo:" + txt_period,
        exportOptions: {
          modifier: {
            page: "all",
          },
        },
      },
      {
        extend: "pdf",
        text: "PDF",
        className: "exportExcel",
        filename: txt_group + " | Periodo:" + txt_period,
        orientation: "landscape",
        pageSize: "LEGAL",
        exportOptions: {
          modifier: {
            page: "all",
          },
        },
      },
    ],
  });
}
