$(document).on("change", "#id_subject", function () {
  loading();
  const id_group = $("#id_group").val();
  const id_subject = $(this).val();
});
$(document).on("change", "#id_period", function () {
  var id_period = $(this).val();
  var id_academic = $("#id_academic").val();
  var id_group = $("#id_group").val();
  var id_assignment = $("#id_materia").val();

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_academic=" +
      id_academic +
      "&id_group=" +
      id_group +
      "&id_period=" +
      id_period+
      "&id_assignment=" +
      id_assignment;

    //--- --- ---//
  }
});
$(document).on("change", "#id_group", function () {
  var id_group = $(this).val();
  var id_academic = $("#id_academic").val();
  loading();
  $(".card-table-evaluations").empty();
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
      getAssignmentsPDA(id_group);
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

$(document).on("change", "#id_materia", function () {
  var id_group = $("#id_group").val();
  var id_academic = $("#id_academic").val();
  $(".card-table-evaluations").empty();
  var id_assignment = $(this).val();
loading();
  getPeriods(id_assignment);

});

$(document).on("change", "#id_academic", function () {
  $("#div_tabla").empty();
  $("#id_group").empty();
  var id_academic = $(this).val();
  getGroups(id_academic);
});

function getGroups(id_academic) {
  //--- --- ---//
  loading();
  $("#id_group").html("");
  //--- --- ---//
  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getGroupByAcademicAreaPDA",
      id_academic: id_academic,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      var options =
        '<option selected disabled value="" disabled>Elija una opción</option>';
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
function getAssignmentsPDA(id_group) {
  var id_academic = $("#id_academic").val();

  console.log("id_group" + id_group);
  console.log("id_academic" + id_academic);

  $.ajax({
    url: "php/controllers/students.php",
    method: "POST",
    data: {
      mod: "getAssignmentsPDA",
      id_group: id_group,
      id_academic: id_academic,
    },
  })
    .done(function (data) {
      // console.log(data);
      var data = JSON.parse(data);
      $("#id_materia").empty();
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          var name_subject = data.data[i].name_subject;
          var options =
            '<option selected value="" disabled>Elija una opción</option>';
          if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
              options +=
                '<option value="' +
                data.data[i].id_assignment +
                '">' +
                data.data[i].name_subject +
                "</option>";
            }
          }
        }
        console.log(name_subject);
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
      $("#id_materia").html(options);

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
