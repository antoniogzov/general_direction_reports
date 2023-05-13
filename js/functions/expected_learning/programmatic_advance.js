$(document).on("change", "#slct_academic_area", function (e) {
  //--- --- ---//
  const id_academic_area = $("#slct_academic_area option:selected").val();
  getAcademicLevels(id_academic_area);
  /* 
      console.log(id_academic_area);
    
      var url = window.location.search;
      const urlParams = new URLSearchParams(url);
    
      if (urlParams.has("submodule")) {
        //--- --- ---//
        loading();
        var type_report = $(this).val();
        const id_group = $("#id_group").val();
        const submodule = urlParams.get("submodule");
        window.location.search = "submodule=" + submodule + "&id_academic_area=" + id_academic_area;
        //--- --- ---//
      } */
  //--- --- ---//
});

$(document).on("change", "#slct_academic_level", function (e) {
  //--- --- ---//
  $("#slct_period").val("");
  $("#slct_grade").val("");
  $("#slct_criteria").val("");

  var id_academic_area = $("#slct_academic_area option:selected").val();
  var id_academic_level = $("#slct_academic_level option:selected").val();

  loading();
  getAcademiclevelGrades(id_academic_area, id_academic_level);
  //--- --- ---//
});
$(document).on("change", "#slct_grade", function (e) {
  //--- --- ---//
  var id_academic_area = $("#slct_academic_area option:selected").val();
  var id_academic_level = $("#slct_academic_level option:selected").val();
  var id_level_grade = $("#slct_grade option:selected").val();

  loading();
  getPeriods(id_academic_area, id_academic_level);
  //--- --- ---//
});

$(document).on("change", "#slct_period", function (e) {
  //--- --- ---//
  var id_academic_area = $("#slct_academic_area option:selected").val();
  var id_academic_level = $("#slct_academic_level option:selected").val();
  var id_level_grade = $("#slct_grade option:selected").val();
  var id_period = $("#slct_period option:selected").val();
  var no_period = $("#slct_period option:selected").attr("data-no-period");

  loading();
  var id_level_grade = $("#slct_grade").val();
  var id_period = $("#slct_period option:selected").val();

  console.log(id_academic_area);

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);

  if (urlParams.has("submodule")) {
    //--- --- ---//
    loading();
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_academic_area=" +
      id_academic_area +
      "&id_academic_level=" +
      id_academic_level +
      "&id_level_grade=" +
      id_level_grade +
      "&id_period=" +
      id_period +
      "&no_period=" +
      no_period;
    //--- --- ---//
  }
  //--- --- ---//
});

$(document).on("click", ".btnShowCommentary", function (e) {
  //--- --- ---//
  var id = $(this).attr("data-id");
  $("#div" + id).show();
  $("#iconSubind" + id).removeClass("fa-eye");
  $("#iconSubind" + id).addClass("fa-eye-slash");
  $(this).removeClass("btnShowCommentary");
  $(this).addClass("btnHideCommentary");
  //--- --- ---//
});

$(document).on("click", ".btnHideCommentary", function (e) {
  //--- --- ---//
  var id = $(this).attr("data-id");
  $("#div" + id).hide();
  $("#iconSubind" + id).addClass("fa-eye");
  $("#iconSubind" + id).removeClass("fa-eye-slash");
  $(this).removeClass("btnHideCommentary");
  $(this).addClass("btnShowCommentary");
  //--- --- ---//
});

$(document).on("click", ".btnShowCatalogue", function (e) {
  //--- --- ---//
  var id = $(this).attr("data-id");
  var group = $(this).attr("data-group");
  var subject = $(this).attr("data-subject");

  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getCatalogue",
      id_expected_learning_subindex: id,
    },
  })
    .done(function (data) {
      Swal.close();
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        Swal.fire({
          title: "Catálogo | " + group + " | " + subject,
          html: data.catalog_item,
          showCloseButton: true,
          showCancelButton: false,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
        });
        //--- --- ---//
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
  //--- --- ---//
  //--- --- ---//
});

$(document).on("change", "#slct_criteria", function (e) {
  //--- --- ---//
  var id_academic_area = $("#slct_academic_area option:selected").val();
  var id_academic_level = $("#slct_academic_level option:selected").val();
  var criteria = $("#slct_criteria option:selected").val().toLowerCase();
  var id_level_grade = $("#slct_grade").val();
  var id_period = $("#slct_period option:selected").val();
  criteria = criteria.replace(" ", "_");

  console.log(id_academic_area);

  var url = window.location.search;
  const urlParams = new URLSearchParams(url);

  if (urlParams.has("submodule")) {
    //--- --- ---//
    loading();
    const submodule = urlParams.get("submodule");
    window.location.search =
      "submodule=" +
      submodule +
      "&id_academic_area=" +
      id_academic_area +
      "&id_academic_level=" +
      id_academic_level +
      "&id_level_grade=" +
      id_level_grade +
      "&id_period=" +
      id_period +
      "&criteria=" +
      criteria;
    //--- --- ---//
  }
  //--- --- ---//
});
function getAcademicLevels(id_academic_area) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getAcademiclevels",
      id_academic_area: id_academic_area,
    },
  })
    .done(function (data) {
      Swal.close();
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        var options_reports =
          '<option selected value="" disabled>Elija una opción</option>';
        //--- --- ---//
        if (data.academicLevels.length > 0) {
          for (var i = 0; i < data.academicLevels.length; i++) {
            options_reports +=
              '<option value="' +
              data.academicLevels[i].id_academic_level +
              '">' +
              data.academicLevels[i].academic_level.toUpperCase() +
              "</option>";
          }
        }

        //--- --- ---//
        $("#slct_academic_level").html(options_reports);
        //--- --- ---//
        swal.close();
        //--- --- ---//
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
  //--- --- ---//
}
function getAcademiclevelGrades(id_academic_area, id_academic_level) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getAcademiclevelGrades",
      id_academic_area: id_academic_area,
      id_academic_level: id_academic_level,
    },
  })
    .done(function (data) {
      Swal.close();
      console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        var options_reports =
          '<option selected value="" disabled>Elija una opción</option>';
        //--- --- ---//
        if (data.academicLevels.length > 0) {
          for (var i = 0; i < data.academicLevels.length; i++) {
            options_reports +=
              '<option value="' +
              data.academicLevels[i].id_level_grade +
              '">' +
              data.academicLevels[i].level_grade_write.toUpperCase() +
              "</option>";
          }
        }

        //--- --- ---//
        $("#slct_grade").html(options_reports);
        //--- --- ---//
        swal.close();
        //--- --- ---//
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
  //--- --- ---//
}
function getPeriods(id_academic_area, id_academic_level) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getPeriods",
      id_academic_area: id_academic_area,
      id_academic_level: id_academic_level,
    },
  })
    .done(function (data) {
      Swal.close();
      var data = JSON.parse(data);
      
      console.log(data);
      if (data.response) {
        //--- --- ---//
        var options_reports =
          '<option selected value="" disabled>Elija una opción</option>';
        //--- --- ---//
        if (data.Periods.length > 0) {
          for (var i = 0; i < data.Periods.length; i++) {
            var name_academic_area = data.Periods[i].section_descr;
            if (name_academic_area == undefined) {
              name_academic_area = "";
            }
            console.log(name_academic_area);
            options_reports +=
              '<option data-no-period="'+data.Periods[i].no_period +'" value="' +
              data.Periods[i].id_period_calendar +
              '">' +
              data.Periods[i].no_period + " "+ name_academic_area
              "</option>";
          }
        }

        //--- --- ---//
        $("#slct_period").html(options_reports);
        //--- --- ---//
        swal.close();
        //--- --- ---//
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
  //--- --- ---//
}

function getSubjects(id_academic_area, id_academic_level) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getSubjects",
      id_academic_area: id_academic_area,
      id_academic_level: id_academic_level,
    },
  })
    .done(function (data) {
      Swal.close();

      var data = JSON.parse(data);
      console.log(data);
      if (data.response) {
        //--- --- ---//
        var options_reports =
          '<option selected value="" disabled>Elija una materia</option>';
        //--- --- ---//
        if (data.Subjects.length > 0) {
          for (var i = 0; i < data.Subjects.length; i++) {
            options_reports +=
              '<option value="' +
              data.Subjects[i].id_subject +
              '">' +
              data.Subjects[i].name_subject.toUpperCase() +
              "</option>";
          }
        }

        //--- --- ---//
        $("#slct_subject").html(options_reports);
        //--- --- ---//
        swal.close();
        //--- --- ---//
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
  //--- --- ---//
}
function getMutualCriteria(
  id_academic_area,
  id_academic_level,
  id_level_grade,
  id_period
) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getMutualCriteria",
      id_academic_area: id_academic_area,
      id_academic_level: id_academic_level,
      id_level_grade: id_level_grade,
      id_period: id_period,
    },
  })
    .done(function (data) {
      Swal.close();

      var data = JSON.parse(data);
      console.log(data);
      if (data.response) {
        //--- --- ---//
        var options_reports =
          '<option selected value="" disabled>Elija un criterio</option>';
        //--- --- ---//
        if (data.catalog_item.length > 0) {
          for (var i = 0; i < data.catalog_item.length; i++) {
            options_reports +=
              '<option value="' +
              data.catalog_item[i].short_description +
              '">' +
              data.catalog_item[i].short_description.toUpperCase() +
              "</option>";
          }
        }

        //--- --- ---//
        $("#slct_criteria").html(options_reports);
        //--- --- ---//
        swal.close();
        //--- --- ---//
      } else {
        Swal.fire("Atención!", data.message, "info");
        $("#slct_criteria").html("");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
  //--- --- ---//
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
