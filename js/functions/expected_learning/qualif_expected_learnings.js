swal.close();
var value_before;
var validate_enter_key = false;
var name_before_gathering = "";
$("#id_period_calendar").change(function () {
  $("#formPeriod").submit();
});
$(document).on("keypress", ".td-grade-evaluation", function (evt) {
  //--- --- ---//
  const td = $(this);
  var charCode = evt.which ? evt.which : evt.keyCode;
  if (charCode == 13) {
    evt.preventDefault();
    $(evt.target)
      .parent()
      .next()
      .children()
      .eq($(evt.target).parent().find(td).index())
      .focus();
    validate_enter_key = true;
  }
  //--- --- ---//
});
$(document).on("focusin", ".td-grade-evaluation", function () {
  //--- --- ---//
  var grade = $(this).text().trim();
  value_before = grade;
  //--- --- ---//
});

$(document).on("focusout", ".td-grade-evaluation", function () {
  //--- --- ---//
  var tr = $(this).closest("tr");
  var id_grade_evaluation_catalog = $(this).attr("id");
  var id_assignment = $("#id_assignment").val();
  var grade = $(this).text().trim();
  var is_averaged = parseInt($(this).attr("data-is-averaged"));
  if (value_before != grade) {
    $(this).addClass("Proccesing");
    //--- --- ---//
    if (grade == "") {
      grade = null;
    }
    //--- --- ---//
    saveGrade(
      id_grade_evaluation_catalog,
      grade,
      tr,
      is_averaged,
      id_assignment
    );
    //--- --- ---//
  }
  //--- --- ---//
});

$(document).on("click", ".btnSubirEvidenciaCatalogo", function () {
  //--- --- ---//

  var id_catalog = $(this).attr("data-id-catalog");
  $(".uploadCatalogDocument").attr("data-id-catalog", id_catalog);
  //--- --- ---//
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "getCatalogItemDetail",
      id_expected_learning_catalog: id_catalog,
    },
  }).done(function (data) {
    data = JSON.parse(data);
    console.log(data.catalog_item[0].short_description);
    $("#info_catalog_item_name").text(data.catalog_item[0].short_description);
    if (data.response) {
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
$("input[type=radio][name=radio_upload]").change(function () {
  if (this.value == "radio_upload_url") {
    $("#div_url").show();
    $("#div_archivo").hide();
    $(".uploadCatalogDocument").attr("data-type", "url");
  } else if (this.value == "radio_upload_archive") {
    $("#div_url").hide();
    $("#div_archivo").show();
    $(".uploadCatalogDocument").attr("data-type", "archive");
  }
});

$("#comprobante_catalogo").on("change", function () {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});

$(document).on("click", ".uploadCatalogDocument", function () {
  //--- --- ---//
  var fileName = "Elegir un archivo";
  $("#comprobante_catalogo")
    .siblings(".custom-file-label")
    .addClass("selected")
    .html(fileName);

  var id_catalog = $(this).attr("data-id-catalog");
  var type = $(this).attr("data-type");
  loading();
  if (type == "url") {
    var url = $("#url").val();
    if (url == "") {
      Swal.close();
      Swal.fire({
        title: "Error",
        text: "Debe ingresar una URL",
        icon: "error",
        confirmButtonText: "Cerrar",
      });
    } else {
      $.ajax({
        url: "php/controllers/expected_learning_controller.php",
        method: "POST",
        data: {
          mod: "saveURLEvidence",
          id_expected_learning_catalog: id_catalog,
          url: url,
        },
      }).done(function (data) {
        Swal.close();
        Swal.fire({
          icon: "success",
          title: "¡Éxito!",
          text: "Se ha subido la evidencia correctamente",
          type: "success",
          confirmButtonText: "Cerrar",
          confirmButtonColor: "#2185d0",
        });
        $("#menu_archivos" + id_catalog).empty();
        var html = "";
        html +=
          '<div class="col"><a id="link_' +
          id_catalog +
          '" href="' +
          url +
          '" target="_blank"><button type="button" title="Ver evidencia" data-toggle="modal" data-target="#showCatalogDocument" data-id-catalog="' +
          id_catalog +
          '" class="btn btn-primary btn-sm btnVerEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_' +
          id_catalog +
          '" class="fas fa-eye"></i></span></button></a></div>';
        html +=
          '<div class="col"><a><button type="button" title="Sustituir evidencia"   data-toggle="modal" data-target="#addCatalogDocument" data-id-catalog="' +
          id_catalog +
          '" data-toggle="modal" data-target="#addCatalogDocument" class="btn btn-info btn-sm btnSubirEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_change_' +
          id_catalog +
          '" class="fas fa-exchange-alt"></i></span></button></a></div>';
        html +=
          '<div class="col"><a><button type="button" title="Eliminar evidencia" data-id-catalog="' +
          id_catalog +
          '" class="btn btn-danger btn-sm deleteCatalogEvidence"><span class="btn-inner--icon"><i id="icon_delete_' +
          id_catalog +
          '" class="fas fa-trash-alt"></i></span></button></a></div>';

        $("#menu_archivos" + id_catalog).append(html);

        /* $("#btnEvidence_" + id_catalog)
          .removeClass("btnSubirEvidenciaCatalogo")
          .addClass("btnVerEvidenciaCatalogo");
        $("#btnEvidence_" + id_catalog).attr(
          "data-target",
          "showCatalogDocument"
        );
        $("#link_" + id_catalog).attr("href", "");
        $("#link_" + id_catalog).attr("href", url);
        $("#icon_" + id_catalog)
          .removeClass("fa-upload")
          .addClass("fa-eye"); */
      });
    }
  } else if (type == "archive") {
    //--- --- ---//
    const file_input = document.querySelector("#comprobante_catalogo");
    const file = file_input.files[0];
    vidFileLength = file_input.files.length;

    if (vidFileLength == 0) {
      /* $(".inputAddStudentDocument")
          .siblings(".custom-file-label")
          .removeClass("selected")
          .html("Elegir un archivo"); */
      //Swal.close();
      Swal.fire("Atención!", "Debe elegir un archivo", "info");
      file_input.value = "";
    } else {
      var file_n = file.name;
      var f = file_n.split(".");

      //--- --- ---//
      var name = file_input.getAttribute("name");
      //--- --- ---//
      name += ".";
      name += f[1];
      if (
        f[f.length - 1] != "png" &&
        f[f.length - 1] != "jpg" &&
        f[f.length - 1] != "jpeg" &&
        f[f.length - 1] != "pdf"
      ) {
        Swal.fire(
          "Atención!",
          "El archivo debe ser una imagen o un archivo PDF",
          "info"
        );
        file_input.value = "";
      } else {
        if (file_input.files[0].size > 20000000) {
          Swal.close();
          Swal.fire(
            "Atención!",
            "El tamaño máximo del archivo a subir es de 20MB",
            "info"
          );
          file_input.value = "";
          return;
        } else {
          folder = "expected_learning_archives";
          module_name = "expected_learning";

          var fData = new FormData();
          fData.append("formData", file);
          fData.append("name", name);
          fData.append("folder", folder);
          fData.append("module_name", module_name);
          fData.append("id_catalog", id_catalog);
          fData.append("mod", "uploadCatalogFiles");
          $.ajax({
            url: "php/controllers/expected_learning_controller.php",
            method: "POST",
            data: fData,
            contentType: false,
            processData: false,
          })
            .done(function (response) {
              //console.log(response);

              var json = JSON.parse(response);
              if (json.response) {
                var id_imagen = json.id_archivo;
                Swal.fire({
                  title: "¡Archivo subido!",
                  text: json.message,
                  icon: "success",
                  confirmButtonText: "Cerrar",
                }).then((result) => {});
                $(this)
                  .removeClass("btnSubirEvidenciaCatalogo")
                  .addClass("btnVerEvidenciaCatalogo");
                $(this).attr("data-target", "showCatalogDocument");

                $("#menu_archivos" + id_catalog).empty();
                var html = "";
                html +=
                  '<div class="col"><a id="link_' +
                  id_catalog +
                  '" href="../iTeach' +
                  json.url +
                  '" target="_blank"><button type="button" title="Ver evidencia" data-toggle="modal" data-target="#showCatalogDocument" data-id-catalog="' +
                  id_catalog +
                  '" class="btn btn-primary btn-sm btnVerEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_' +
                  id_catalog +
                  '" class="fas fa-eye"></i></span></button></a></div>';
                html +=
                  '<div class="col"><a><button type="button" title="Sustituir evidencia"   data-toggle="modal" data-target="#addCatalogDocument" data-id-catalog="' +
                  id_catalog +
                  '" data-toggle="modal" data-target="#addCatalogDocument" class="btn btn-info btn-sm btnSubirEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_change_' +
                  id_catalog +
                  '" class="fas fa-exchange-alt"></i></span></button></a></div>';
                html +=
                  '<div class="col"><a><button type="button" title="Eliminar evidencia" data-id-catalog="' +
                  id_catalog +
                  '" class="btn btn-danger btn-sm deleteCatalogEvidence"><span class="btn-inner--icon"><i id="icon_delete_' +
                  id_catalog +
                  '" class="fas fa-trash-alt"></i></span></button></a></div>';

                $("#menu_archivos" + id_catalog).append(html);

                /*  $("#icon_" + id_catalog)
                  .removeClass("fa-upload")
                  .addClass("fa-eye");
                $("#btnEvidence_" + id_catalog)
                  .removeClass("btnSubirEvidenciaCatalogo")
                  .addClass("btnVerEvidenciaCatalogo");
                $("#btnEvidence_" + id_catalog).attr(
                  "data-target",
                  "showCatalogDocument"
                );
                url = "../iTeach" + json.url;
                $("#link_" + id_catalog).attr("href", url);
                $("#icon_" + id_catalog)
                  .removeClass("fa-upload")
                  .addClass("fa-eye"); */
              } else {
                Swal.fire(
                  "Error!",
                  "Ocurrió un error al intentar subir el documento, intentelo nuevamente por favor",
                  "error"
                );
              }
            })
            .fail(function (error) {
              Swal.fire(
                "Error!",
                "Ocurrió un error al intentar comunicarse con la base de datos :(",
                "error"
              );
              console.log(error);
            });
        }
      }
    }
  } else {
    Swal.fire("Atención!", "Debe elegir una opción", "info");
  }
});
$(document).on("click", ".deleteCatalogEvidence", function () {
  //--- --- ---//
  var fileName = "Elegir un archivo";
  $("#comprobante_catalogo")
    .siblings(".custom-file-label")
    .addClass("selected")
    .html(fileName);

  var id_catalog = $(this).attr("data-id-catalog");
  var type = $(this).attr("data-type");
  Swal.fire({
    title: "¿Está seguro?",
    text: "¡No podrá revertir esta acción!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, eliminar!",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: "php/controllers/expected_learning_controller.php",
        method: "POST",
        data: {
          mod: "deleteEvidence",
          id_expected_learning_catalog: id_catalog,
        },
      }).done(function (data) {
        Swal.close();
        Swal.fire({
          icon: "success",
          title: "¡Éxito!",
          text: "Se ha eliminado la evidencia correctamente",
          type: "success",
          confirmButtonText: "Cerrar",
          confirmButtonColor: "#2185d0",
        });
        $("#menu_archivos" + id_catalog).empty();
        var html = "";
        html += '<div class="col"></div>';
        html +=
          '<div class="col"><a class="dropdown-item" id="link_' +
          id_catalog +
          '" target="_blank"><button type="button" title="Adjuntar evidencia" id="btnEvidence_' +
          id_catalog +
          '" data-toggle="modal" data-target="#addCatalogDocument" data-id-catalog="' +
          id_catalog +
          '" class="btn btn-primary btn-sm btnSubirEvidenciaCatalogo"><span class="btn-inner--icon"><i id="icon_' +
          id_catalog +
          '" class="fas fa-upload"></i></span></button></a></div>';
        html += '<div class="col"></div>';
        $("#menu_archivos" + id_catalog).append(html);
      });
    }
  });
});
function evaluate_character(classification, evaluation_scale, td, evt) {
  switch (classification) {
      case "rank":
          //--- --- ---//
          if (!validate_enter_key) {
              var caracter = document.getElementById("" + td.id).innerHTML;
              if (caracter == "<br>") {
                  caracter = "";
              }
              var values = evaluation_scale.split("-");
              //--- --- ---//
              console.log("caracter: " + caracter);
              if (caracter != "") {
                  caracter = parseFloat(caracter);
                  //--- Sólo admitimos números ---//
                  var charCode = evt.which ? evt.which : evt.keyCode;
                  console.log("charCode: " + charCode);
                  if (charCode == 229 || charCode == 8 || charCode == 9 || charCode == 13 || charCode == 46 || charCode == 110 || charCode == 190 || (charCode >= 35 && charCode <= 40) || (charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105)) {
                      if (caracter < values[0] || caracter > values[1] || isNaN(caracter)) {
                          document.getElementById("" + td.id).innerHTML = value_before;
                      }
                  } else {
                      document.getElementById("" + td.id).innerHTML = value_before;
                  }
              }
          }
          validate_enter_key = false;
          //--- --- ---//
          break;
          /*case 'group':
                              //--- --- ---//
                              var caracter = document.getElementById('' + td.id).innerHTML;
                              var values = evaluation_scale.split(",");
                              //--- --- ---//
                              if (caracter != '') {
                                  var keypressed = evt.which
                                  //--- Admitir solon caracteres alfanuméricos ---//
                                  if ((keypressed >= 65 && keypressed <= 90) || (keypressed >= 48 && keypressed <= 57) || (keypressed >= 96 && keypressed <= 105)) {
                                      var contn = false;
                                      for (var a = 0; a < values.length; a++) {
                                          if (caracter == values[a]) {
                                              contn = true;
                                          }
                                      }
                                      //--- --- ---//
                                      if (!contn) {
                                          document.getElementById('' + td.id).innerHTML = value_before;
                                      }
                                      //--- --- ---//
                                  } else {
                                      document.getElementById('' + td.id).innerHTML = value_before;
                                  }
                              }
                              //--- --- ---//
                              break;*/
  }
}
$(document).on("click", ".addCommentAE", function () {
  //--- --- ---//
  var id_assignment = $(this).attr("data-id-assignment");
  var id_period_calendar = $(this).attr("data-id-period-calendar");
  var comment = $("#commentary_ae").val();

  loading();
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "saveCommentAE",
      id_period_calendar: id_period_calendar,
      id_assignment: id_assignment,
      comment: comment,
    },
  }).done(function (data) {
    Swal.close();
    Swal.fire({
      icon: "success",
      title: "¡Éxito!",
      text: "Se ha guardado ",
      type: "success",
      confirmButtonText: "Cerrar",
      confirmButtonColor: "#2185d0",
    }).then((result) => {
      loading();
      location.reload();
    });
  });
});

$(document).on("click", ".uploadComentaryAE", function () {
  //--- --- ---//
  var id_comment = $(this).attr("data-id-comment");
  var edit_commentary = $("#edit_commentary").val();

  loading();
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "editCommentAE",
      id_comment: id_comment,
      edit_commentary: edit_commentary,
    },
  }).done(function (data) {
    Swal.close();
    Swal.fire({
      icon: "success",
      title: "¡Éxito!",
      text: "Se ha guardado ",
      type: "success",
      confirmButtonText: "Cerrar",
      confirmButtonColor: "#2185d0",
    }).then((result) => {
      loading();
      location.reload();
    });
  });
});
$(document).on("click", "#syncAEEVAL", function () {
  //--- --- ---//
  var id_assignment = $(this).attr("data-id-assignment");
  var id_period_calendar = $(this).attr("data-id-period-calendar");
  Swal.fire({
    title: "¿Está seguro de sincronizar?",
    text: "Este proceso puede tardar hasta 5 minutos",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#2185d0",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, sincronizar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.value) {
      loading();
      $.ajax({
        url: "php/controllers/expected_learning_controller.php",
        method: "POST",
        data: {
          mod: "syncAEEVAL",
          id_assignment: id_assignment,
          id_period_calendar: id_period_calendar,
        },
      })
        .done(function (data) {
          data = JSON.parse(data);
          /* console.log(data); */
          if (data.response == "true") {
            Swal.close();
            Swal.fire({
              icon: "success",
              title: "¡Éxito!",
              text: "Se ha sincronizado correctamente",
              confirmButtonText: "Cerrar",
              confirmButtonColor: "#2185d0",
            }).then((result) => {
              //loading();
              //location.reload();
            });
          } else {
            Swal.close();
            /* console.log(data.message); */
            Swal.fire({
              icon: "error",
              title: "¡Error!",
              text: data.message,
            });
          }
        })
        .fail(function (data) {
          Swal.close();
          Swal.fire({
            icon: "error",
            title: "¡Error!",
            text: "Ha ocurrido un error",
          });
        });
    }
  });
});

function saveGrade(
  id_grade_evaluation_catalog,
  grade,
  tr,
  is_averaged,
  id_assignment
) {
  $.ajax({
    url: "php/controllers/expected_learning_controller.php",
    method: "POST",
    data: {
      mod: "saveGradeCatalog",
      id_grade_evaluation_catalog: id_grade_evaluation_catalog,
      grade: grade,
      is_averaged: is_averaged,
      id_assignment: id_assignment,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      console.log(data);
      var textToast = data.message;
      var typeToast = "";
      if (data.response) {
        typeToast = "success";
        if (tr != null) {
          tr.find(".td-grade-period").html(data.student_avg);

            tr.find("#"+id_grade_evaluation_catalog).removeClass("Proccesing");
            tr.find("#"+id_grade_evaluation_catalog).addClass("Proccesed");
          if (data.learning_avg == "NULL") {
            $("#tf_" + data.id_expected_learning_catalog).html("-");
          } else {
            $("#tf_" + data.id_expected_learning_catalog).html(
              data.learning_avg
            );
          }
          var group_average = data.group_average;
          if (group_average == "NULL") {
            $("#group_avg").html("-");
          } else {
            $("#group_avg").html(data.group_average);
          }
        }
        /* if (is_averaged) {
                if (tr != null) {
                    tr.find(".td-grade-period").html(data.student_avg);
                }
            } */
      } else {
        typeToast = "error";
      }
      VanillaToasts.create({
        title: "Notificación",
        text: textToast,
        type: typeToast,
        timeout: 1200,
        positionClass: "topRight",
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
