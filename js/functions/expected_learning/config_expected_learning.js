$(document).on("change", "#select_periodo", function () {
  var id_period = $(this).val();
  var id_assignment = $("#id_assignment").val();
  var id_academic_area = $("#id_academic_area").val();
  var url =
    "configuracion_aprendizajes.php?id_academic_area=" +
    id_academic_area +
    "&id_assignment=" +
    id_assignment +
    "&id_period=" +
    id_period;
  window.location.href = url;
  //href="?evaluation=<?>">
  /* var planes = '<input type="hidden" id="id_period_selected"><?=$queries->getPlan($sj)?>';
    $("#div_planes").html(planes); */
});

$(document).on("click", ".addExpectedLearning", function () {
  var no_period = $(this).attr("data-id-period");
  $(".saveLearning").attr("data-id-period", no_period);
});

$(document).on("click", ".saveLearning", function () {
  var learning_name = $("#learning_name").val();
  var learning_description = $("#learning_description").val();
  var id_period_calendar = $(this).attr("data-id-period");
  var id_assignment = $("#id_assignment").val();
  var id_academic_area = $("#id_academic_area").val();
  loading();
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "saveExpectedLearning",
      learning_name: learning_name,
      learning_description: learning_description,
      id_period_calendar: id_period_calendar,
      id_assignment: id_assignment,
      id_academic_area: id_academic_area,
    },
  }).done(function (data) {
    Swal.close();
    data = JSON.parse(data);
    console.log(data);
    if (data.response) {
      id_catalog = data.last_id;
      no_position = data.no_position;
      no_period = data.no_period;
      message = data.message;
      VanillaToasts.create({
        title: "Criterio Agregado",
        text: message,
        type: "success",
        timeout: 2000,
        positionClass: "topRight",
      });

      var html_card = "";

      html_card +=
        '<div class="card-body" data-id-expected-learning-catalog="' +
        id_catalog +
        '" id="itemCatalog' +
        id_catalog +
        '" data-position="' +
        no_position +
        '">';
      html_card += '<div class="card text-white bg-primary mb-3">';
      html_card += '<div class="card-header bg-primary">';
      html_card += "<strong> " + learning_name + " </strong>";
      html_card +=
        '<button title="Eliminar AE" id="' +
        id_catalog +
        '" class="btn btn-danger btn-sm deleteLearning" style=" float: right;" type="button"><i class="fas fa-trash-alt"></i></button>';
      html_card +=
        '<button title="Editar AE" id="' +
        id_catalog +
        '" class="btn btn-info btn-sm editLearning" data-no-period="'+no_period+'" style=" float: right;" data-toggle="modal" data-target="#editAE" type="button"><i class="fas fa-edit"></i></button>';
      html_card +=
        '<button title="Cambiar de periodo" id="' +
        id_catalog +
        '" class="btn btn-success btn-sm changePeriodLearning" style=" float: right;" data-toggle="modal" data-target="#changePeriodAE" type="button"><i class="fas fa-sync"></i></button>';
      html_card +=
        '<button title="Detalle de AE" id="' +
        id_catalog +
        '" class="btn btn-success btn-sm infoCatalog" style=" float: right;" type="button"><i class="fas fa-info-circle"></i></button>';
      html_card += "</div>";
      html_card += "</div>";
      html_card += "</div>";
      $(".desglosePeriodo_" + id_period_calendar).append(html_card);
      $("#learning_name").val("");
      $("#learning_description").val("");
    } else {
      VanillaToasts.create({
        title: "Error",
        text: data.message,
        type: "error",
        timeout: 2000,
        positionClass: "topRight",
      });
    }
  });
});

$(document).on("click", ".btn_save_incident", function () {
  var incident = $(
    "input[name=incident_list]:checked",
    "#form_incidents"
  ).val();
  var commit = $("#incident_commit").val();
  var date = $("#incident_date").val();
  var id_assignment = $("#id_subject_incident").val();
  var id_student = $(this).attr("id");
  id_student = $(this).attr("id");
  if (incident == undefined) {
    swal.close();
    swal.fire({
      icon: "error",
      title: "Seleccione una incidencia",
    });
  } else if (date == undefined) {
    swal.close();
    swal.fire({
      icon: "error",
      title: "Seleccione una fecha",
    });
  } else if (commit == "") {
    swal.close();
    swal.fire({
      icon: "error",
      title: "Ingrese un comentario",
    });
  } else {
    loading();
  }
});

$(document).on("click", ".deleteLearning", function () {
  var id = $(this).attr("id");
  var no_period = $(this).attr("data-no-period");
  if ($(".infoCatalog").length == 1 && no_period == 1) {
    Swal.fire({
      icon: "error",
      title:
        'Al ser este el ultimo criterio, es necesario seleccione la opción de "Eliminar todo", para eliminar la estructura y tener la posibilidad de importar una estructura existente.',
    });
  } else {
    Swal.fire({
      title: "¿Está seguro?",
      text: "¡No podrá revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminarlo!",
    }).then((result) => {
      if (result.value) {
        loading();
        $.ajax({
          url: "php/controllers/expected_learning_controller.php",
          method: "POST",
          data: {
            mod: "deleteExpectedLearning",
            id_expected_learning_catalog: id,
          },
        }).done(function (data) {
          Swal.close();
          data = JSON.parse(data);
          //console.log(data);
          if (data.response) {
            $("#itemCatalog" + id).remove();
            id_catalog = data.last_id;
            no_position = data.no_position;
            message = data.message;
            VanillaToasts.create({
              title: "Criterio Eliminado",
              text: message,
              type: "success",
              timeout: 2000,
              positionClass: "topRight",
            });
          } else {
            VanillaToasts.create({
              title: "Error",
              text: data.message,
              type: "error",
              timeout: 2000,
              positionClass: "topRight",
            });
          }
        });
      }
    });
  }
});
$(document).on("click", ".infoCatalog", function () {
  var id = $(this).attr("id");
  loading();
  $("#titulo_detalle").text("");
  $("#contenido_detalle").text("");
  var no_period = $(this).attr("data-no-period");
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getCatalogDetail",
      id_expected_learning_catalog: id,
    },
  }).done(function (data) {
    data = JSON.parse(data);
    //console.log(data);
    if (data.response) {
      Swal.fire({
        icon: "info",
        title: data.titulo,
        html: data.descripcion,
      });
      //Swal.close();
    } else {
      VanillaToasts.create({
        title: "Error",
        text: data.message,
        type: "error",
        timeout: 2000,
        positionClass: "topRight",
      });
    }
  });
});
$(document).on("click", ".deleteStructure", function () {
  var id_assignment = $(this).attr("id");
  Swal.fire({
    title: "¿Está seguro?",
    text: "¡No podrá revertir esto!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminarlo!",
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: "php/controllers/expected_learning_controller.php",
        method: "POST",
        data: {
          mod: "deleteExpectedLearningStructure",
          id_assignment: id_assignment,
        },
      }).done(function (data) {
        Swal.close();
        data = JSON.parse(data);
        //console.log(data);
        if (data.response) {
          Swal.fire({
            title: "Éxito!!",
            text: data.message,
            icon: "success",
            confirmButtonText: "Aceptar",
            timer: 3000,
          }).then((result) => {
            location.reload();
          });
        } else {
          Swal.fire({
            title: "Error",
            text: data.message,
            icon: "error",
            confirmButtonText: "Aceptar",
            timer: 3000,
          });
        }
      });
    }
  });
});
$(document).on("click", ".changePeriodLearning", function () {
  var id = $(this).attr("id");
  var id_period_calendar = $(this).attr("data-id-period-calendar");

  $(".bntChangePeriodAE").attr("id", id);
  $(".bntChangePeriodAE").attr("data-id-period-calendar", id_period_calendar);
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getCatalogDetail",
      id_expected_learning_catalog: id,
    },
  })
    .done(function (data) {
      data = JSON.parse(data);
      //console.log(data);
      if (data.response) {
        $("#titulo_detalle").text(data.titulo);
        $("#contenido_detalle").text(data.descripcion);
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
    })
    .fail(function () {
      Swal.close();
    });
});
$(document).on("click", ".bntChangePeriodAE", function () {
  loading();
  var id_catalog = $(this).attr("id");
  var id_period_origin = $(this).attr("data-id-period-calendar");
  var id_period_destiny = $("#selectChangePeriodAE").val();
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "changePeriodCatalog",
      id_period_origin: id_period_origin,
      id_period_destiny: id_period_destiny,
      id_catalog: id_catalog,
    },
  }).done(function (data) {
    data = JSON.parse(data);
    //console.log(data);
    if (data.response) {
      $("#itemCatalog" + id_catalog).remove();
      no_position = data.no_position;
      short_description = data.short_description;
      no_period = data.no_period;
      console.log(id_period_destiny);
      html_card = "";
      html_card +=
        '<div class="card-body" data-id-expected-learning-catalog="' +
        id_catalog +
        '" id="itemCatalog' +
        id_catalog +
        '" data-position="' +
        no_position +
        '">';
      html_card += '<div class="card text-white bg-primary mb-3">';
      html_card += '<div class="card-header bg-primary">';
      html_card += "<strong> " + short_description + " </strong>";
      html_card +=
        '<button title="Eliminar AE" id="' +
        id_catalog +
        '" class="btn btn-danger btn-sm deleteLearning" data-no-period="'+no_period+'" style=" float: right;" type="button"><i class="fas fa-trash-alt"></i></button>';
      html_card +=
        '<button title="Editar AE" id="' +
        id_catalog +
        '" class="btn btn-info btn-sm editLearning" style=" float: right;" data-toggle="modal" data-target="#editAE" type="button"><i class="fas fa-edit"></i></button>';
      html_card +=
        '<button title="Cambiar de periodo" id="' +
        id_catalog +
        '" data-id-period-calendar="' +
        id_period_destiny +
        '"  class="btn btn-success btn-sm changePeriodLearning" style=" float: right;" data-toggle="modal" data-target="#changePeriodAE" type="button"><i class="fas fa-sync"></i></button>';
      html_card +=
        '<button title="Detalle de AE" id="' +
        id_catalog +
        '"  data-no-period="' +
        no_period +
        '" class="btn btn-success btn-sm infoCatalog" style=" float: right;" type="button"><i class="fas fa-info-circle"></i></button>';
      html_card += "</div>";
      html_card += "</div>";
      html_card += "</div>";
      $(".desglosePeriodo_" + id_period_destiny).append(html_card);
      Swal.close();
    } else {
      Swal.close();
      VanillaToasts.create({
        title: "Error",
        text: data.message,
        type: "error",
        timeout: 4000,
        positionClass: "topRight",
      });
    }
  });
});
$(document).on("click", ".editLearning", function () {
  var id = $(this).attr("id");
  loading();
  $("#learning_name_edit").val("");
  $("#learning_description_edit").text("");
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getCatalogDetail",
      id_expected_learning_catalog: id,
    },
  }).done(function (data) {
    data = JSON.parse(data);
    //console.log(data);
    if (data.response) {
      $("#learning_name_edit").val(data.titulo);
      $("#learning_description_edit").val(data.descripcion);
      $(".saveChangesLearning").attr("id", id);
      Swal.close();
    } else {
      VanillaToasts.create({
        title: "Error",
        text: data.message,
        type: "error",
        timeout: 2000,
        positionClass: "topRight",
      });
    }
  });
});
$(document).on("click", ".saveChangesLearning", function () {
  var id = $(this).attr("id");
  loading();
  var learning_name_edit = $("#learning_name_edit").val();
  var learning_description = $("#learning_description_edit").val();
  if (learning_name_edit == "" || learning_description == "") {
    Swal.close();
    VanillaToasts.create({
      title: "Error",
      text: "El nombre del AE no puede estar vacio",
      type: "error",
      timeout: 2000,
      positionClass: "topRight",
    });
  } else {
    $.ajax({
      url: "php/controllers/expected_learning_controller.php",
      method: "POST",
      data: {
        mod: "updateCatalogDetail",
        id_expected_learning_catalog: id,
        learning_name_edit: learning_name_edit,
        learning_description: learning_description,
      },
    }).done(function (data) {
      data = JSON.parse(data);
      //console.log(data);
      if (data.response) {
        location.reload();
        Swal.close();
      } else {
        VanillaToasts.create({
          title: "Error",
          text: data.message,
          type: "error",
          timeout: 2000,
          positionClass: "topRight",
        });
      }
    });
  }
});
$(document).on("click", ".btnImportConfig", function () {
  var id_expected_learning = $(this).attr("data-id-expected-learning-index");
  var id_assignment = $("#id_assignment").val();
  loading();
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "importExpectedLearning",
      id_expected_learning: id_expected_learning,
      id_assignment: id_assignment,
    },
  }).done(function (data) {
    Swal.close();
    data = JSON.parse(data);
    //console.log(data);
    if (data.response) {
      message = data.message;
      Swal.fire({
        title: "Éxito",
        text: message,
        icon: "success",
        confirmButtonText: "Aceptar",
      }).then((result) => {
        location.reload();
      });
    } else {
      Swal.fire({
        title: "Error",
        text: data.message,
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    }
  });
});
$(document).ready(function () {
  $(".sortable").sortable({
    update: function (event, ui) {
      // console.log("update");
      $(this)
        .children()
        .each(function (index) {
          if ($(this).attr("data-position") != index + 1) {
            var no_position = index + 1;
            var id_catalog = $(this).attr("data-id-expected-learning-catalog");
            // console.log(id_catalog);
            // console.log(no_position);

            $(this)
              .attr("data-position", index + 1)
              .addClass("updated");
          }
        });

      guardandoPosiciones();
    },
  });
});

function guardandoPosiciones() {
  var positions = [];
  $(".updated").each(function () {
    positions.push([
      $(this).attr("data-id-expected-learning-catalog"),
      $(this).attr("data-position"),
    ]);
    $(this).removeClass("updated");
  });
  // console.log(positions);
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "updatePositions",
      positions: positions,
    },
    success: function (response) {
      console.log(response);
    },
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
