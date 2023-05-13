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
$(document).on("change", "#id_period", function () {
  var id_period = $(this).val();
  var id_academic = $("#id_academic").val();
  var id_group = $("#id_group").val();
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
      id_period;
    //--- --- ---//
  }
});
$(document).on("change", "#id_ciclo", function () {
  console.log('hi')
  var id_period = $(this).val();
  var id_academic = $("#id_academic").val();
  var id_group = $("#id_group").val();
  var id_ciclo = $("#id_ciclo").val();
  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const id_subject = $(this).val();
    const submodule = urlParams.get("submodule");
    id_ciclo = id_ciclo.length > 0 ? "&id_ciclo=" + id_ciclo : "";
    window.location.search =
      "submodule=" +
      submodule +
      "&id_academic=" +
      id_academic +
      "&id_group=" +
      id_group +
      "&id_period=" +
      id_period +
      id_ciclo;
    //--- --- ---//
  }
});

function getPeriods(id_assignment) {
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getPeriodsByAssignment",
      id_assignment: id_assignment,
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
                data.data[i].no_period +
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
      mod: "getAssignmentByGroupAcademic",
      id_group: id_group,
      id_academic: id_academic,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          var id_assignment = data.data[i].id_assignment;
        }
        console.log(id_group);
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
      $("#id_group").val(id_group);
      getPeriods(id_assignment);
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
