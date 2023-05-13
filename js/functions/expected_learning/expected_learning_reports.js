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
  var id_academic_area = $("#slct_academic_area option:selected").val();
  var id_academic_level = $("#slct_academic_level option:selected").val();

  loading();
  getSubjects(id_academic_area, id_academic_level);
  //--- --- ---//
});

$(document).on("change", "#slct_subject", function (e) {
  //--- --- ---//
  var id_academic_area = $("#slct_academic_area option:selected").val();
  var id_academic_level = $("#slct_academic_level option:selected").val();
  var id_subject = $("#slct_subject option:selected").val();

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
      "&id_subject=" +
      id_subject;
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
function getSubjects(id_academic_area, id_academic_level) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getSubjectsLevel",
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
function getCriteria(id_academic_area, id_academic_level, id_subject) {
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
