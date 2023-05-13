/* $(document).on("change", "#id_period", function () {
  var id_academic_area = $("#id_academic").val();
  var id_level_grade = $("#id_level_grade").val();
  var id_academic_level = $("#id_level_grade option:selected").attr(
    "data-id-academic-level"
  );
  var id_section = $("#id_section option:selected").attr("data-id-section");
  var id_campus = $("#id_section option:selected").attr("data-id-campus");
  var id_period_calendar = $("#id_period option:selected").val();
  var id_level_combination = $("#id_period option:selected").attr(
    "data-id-level-combination"
  );

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);
  if (urlParams.has("submodule")) {
    //--- --- ---//
    const id_subject = $(this).val();
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_section=" +
      id_section +
      "&id_campus=" +
      id_campus +
      "&id_level_grade=" +
      id_level_grade +
      "&id_period_calendar=" +
      id_period_calendar +
      "&id_academic_level=" +
      id_academic_level +
      "&id_academic_area=" +
      id_academic_area +
      "&id_level_combination=" +
      id_level_combination;
    //--- --- ---//
  }
}); */
$(document).on("change", "#id_academic", function () {
  var id_academic_area = $(this).val();
  loading();
  $.ajax({
    url: "php/controllers/academic_reports.php",
    method: "POST",
    data: {
      mod: "getAcademicGradeByArea",
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          var options =
            '<option selected disabled value="" disabled>Elija una opción</option>';
          if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
              options +=
                '<option data-id-academic-level="' +
                data.data[i].id_academic_level +
                '" value="' +
                data.data[i].id_level_grade +
                '">' +
                data.data[i].degree.toUpperCase() +
                "</option>";
            }
          }
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
      $("#id_level_grade").html(options);
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

$(document).on("change", "#id_level_grade", function () {
  var id_level_grade = $(this).val();
  loading();
  $.ajax({
    url: "php/controllers/academic_reports.php",
    method: "POST",
    data: {
      mod: "getSectionAndCampus",
      id_level_grade: id_level_grade,
    },
  })
    .done(function (data) {
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        for (var i = 0; i < data.data.length; i++) {
          var options =
            '<option selected disabled value="" disabled>Elija una opción</option>';
          if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
              options +=
                '<option data-id-section="' +
                data.data[i].id_section +
                '" data-id-campus="' +
                data.data[i].id_campus +
                '">' +
                data.data[i].seccion_campus.toUpperCase() +
                "</option>";
            }
          }
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
      $("#id_section").html(options);
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

$(document).on("change", "#id_section", function () {
  var id_academic_area = $("#id_academic").val();
  var id_level_grade = $("#id_level_grade").val();
  var id_academic_level = $("#id_level_grade option:selected").attr(
    "data-id-academic-level"
  );
  var id_section = $("#id_section option:selected").attr("data-id-section");
  var id_campus = $("#id_section option:selected").attr("data-id-campus");

  loading();
  $.ajax({
    url: "php/controllers/academic_reports.php",
    method: "POST",
    data: {
      mod: "getIdsLevelCombination",
      id_level_grade: id_level_grade,
      id_academic_area: id_academic_area,
      id_academic_level: id_academic_level,
      id_section: id_section,
      id_campus: id_campus,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      for (var i = 0; i < data.data.length; i++) {
        id_level_combination = data.data[i].id_level_combination;
      }
      $.ajax({
        url: "php/controllers/academic_reports.php",
        method: "POST",
        data: {
          mod: "getPeriodsByIdLevelCombination",
          id_level_combination: id_level_combination,
        },
      })
        .done(function (data) {
          console.log(data);
          var data = JSON.parse(data);
          if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
              var options =
                '<option selected disabled value="" disabled>Elija una opción</option>';
              if (data.response) {
                for (var i = 0; i < data.data.length; i++) {
                  options +=
                    '<option data-id-level-combination="' +
                    data.data[i].id_level_combination +
                    '" value="' +
                    data.data[i].id_period_calendar +
                    '">' +
                    data.data[i].no_period +
                    "</option>";
                }
                options +=
                  '<option data-id-level-combination="' +
                  data.data[0].id_level_combination +
                  '" value="all">TODOS LOS PERIODOS</option>';
              }
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
          $("#id_period").html(options);
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
$(document).on("click", ".btnSearchRank", function () {
  var id_academic_area = $("#id_academic").val();
  var id_level_grade = $("#id_level_grade").val();
  var id_academic_level = $("#id_level_grade option:selected").attr(
    "data-id-academic-level"
  );
  var id_section = $("#id_section option:selected").attr("data-id-section");
  var id_campus = $("#id_section option:selected").attr("data-id-campus");
  var id_period_calendar = $("#id_period option:selected").val();
  var id_level_combination = $("#id_period option:selected").attr(
    "data-id-level-combination"
  );
  /* var muestra = $("#muestra").val(); */
  var min = $("#min").val();
  var max = $("#max").val();

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);

  if (
    id_academic_area != "" &&
    id_level_grade != "" &&
    id_academic_level != "" &&
    id_section != "" &&
    id_campus != "" &&
    id_period_calendar != "" &&
    id_level_combination != "" &&
    min != "" &&
    max != ""
  ) {
    loading();
    if (urlParams.has("submodule")) {
      //--- --- ---//
      const id_subject = $(this).val();
      const submodule = urlParams.get("submodule");
      window.location.search =
        "submodule=" +
        submodule +
        "&id_section=" +
        id_section +
        "&id_campus=" +
        id_campus +
        "&id_level_grade=" +
        id_level_grade +
        "&id_period_calendar=" +
        id_period_calendar +
        "&id_academic_level=" +
        id_academic_level +
        "&id_academic_area=" +
        id_academic_area +
        "&id_level_combination=" +
        id_level_combination +
        "&min=" +
        min +
        "&max=" +
        max;
      //--- --- ---//
    }
  } else {
    Swal.fire("Error", "Debe seleccionar todos los campos", "error");
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
function isNumberKey(evt) {
  var charCode = evt.which ? evt.which : event.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;

  return true;
}
//--- --- ---//
