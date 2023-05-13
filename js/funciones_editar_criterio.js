$(document).on("click", ".modify_ev_plan", function () {
  //--- --- ---//
  var id_plan = $(this).attr("id");
  console.log(id_plan + "s");

  $.ajax({
    url: "php/controllers/controllerConfigEvaluationPlan.php",
    method: "POST",
    data: {
      fun: "getEvaluationConfig",
      id_criterio: id_plan,
    },
    dataType: "json",
  })
    .done(function (data) {
      console.log(data);
      $("#txt_evaluation_nameOG").text(
        "Usted seleccionó: " + data[0].evaluation_name
      );
      $("#txt_manual_nameOG").text("Usted ingresó: " + data[0].manual_name);
      $("#txt_percentageOG").text("Usted ingresó: " + data[0].percentage + "%");
      $("#manual_name").val(data[0].manual_name);
      $("#edit_percentage").val(data[0].percentage);
      $("#eval_type").val(data[0].evaluation_type_id);
      $("#affect_final_calification").prop("checked", false);

      if (data[0].id_evaluation_source == 1) {
        $("#div_manual_name_edit").show();
      } else {
        $("#div_manual_name_edit").hide();
      }
      if (data[0].affects_evaluation == 1) {
        $("#affect_final_calification").prop("checked", true);
      }
      $("#name_edit_criteria").val(data[0].id_evaluation_source);

      if (data[0].gathering == 1) {
        var gathering_configured = data[0].gathering_configured;
        var html_gathering =
          ' <label for="evaluation" class="form-label text-dark">Subcriterios</label>' +
          '<h5 class="form-label text-grey"><em>Usted registró ' +
          gathering_configured +
          " subcriterios</em></h5>" +
          '<div class="input-group">' +
          '<input type="number" data-original-gathering_configured="' +
          gathering_configured +
          '" class="form-control new_name_subcr" id="edit_gathering" required value="' +
          gathering_configured +
          '">' +
          "</div>";
        $("#div_gathering").html(html_gathering);
        $("#div_gathering").show();
      } else {
        $("#div_gathering").hide();
        $("#div_gathering").html("");
      }
      if (data[0].deadline != undefined) {
        var sql_date = data[0].deadline;
      } else {
        var sql_date = "";
      }

      var sDateParts = sql_date.split(" ");
      var fDateParts = sDateParts[0].split("-");
      var shDate = fDateParts[2] + "/" + fDateParts[1] + "/" + fDateParts[0];
      $("#txt_deadline").text("Usted seleccionó: " + shDate);
      //$("#in_deadline").val(shDate);
      var botones =
        ' <button type="button" class="btn btn-primary btn_update_criteria" id="' +
        data[0].id_evaluation_plan +
        '">Guardar</button><button type="button" class="btn btn-secondary" id="cerrar_b_actualizar">Volver</button>';
      $("#buttons").html(botones);
    })
    .fail(function (error) {
      console.log(error);
    });
  $("#modify_evaluation_plan").modal("show");
  loadEditPercentage();
  //$('.modal-backdrop').hide();
});

$(document).on("focusout", "#edit_gathering", function () {
  var original_gathering_configured = $(this).attr(
    "data-original-gathering_configured"
  );
  var new_val = $(this).val();
  if (new_val < original_gathering_configured) {
    Swal.fire({
      icon: "error",
      title: "Atención!!",
      text: "No puede ingresar un número de subcriterios menor al original",
    });
    $(this).val(original_gathering_configured);
  }
});
$(document).on("change", "#name_edit_criteria", function () {
  //--- --- ---//
  var criteria_name = $(this).val();
  if (criteria_name == 1) {
    $("#div_manual_name_edit").show();
  } else {
    $("#div_manual_name_edit").hide();
    $("#manual_name").val("");
  }
});
$(document).on("click", ".btn_update_criteria", function () {
  //--- --- ---//
  var id_criterio = $(this).attr("id");

  var criteria_name = $("#name_edit_criteria").val();
  var manual_name = $("#manual_name").val();
  var eval_type = $("#eval_type").val();
  var affect_final_calification = 0;
  var edit_percentage = $("#edit_percentage").val();
  var in_deadline = $("#in_deadline").val();
  var nmb_gathering = 0;
  var original_gathering_configured = 0;
  if ($("#edit_gathering").length) {
    nmb_gathering = $("#edit_gathering").val();
    original_gathering_configured = $("#edit_gathering").attr(
      "data-original-gathering_configured"
    );
  }

  var check_afc = $("#affect_final_calification");
  if ($(check_afc).is(":checked")) {
    affect_final_calification = 1;
  }

  console.log(id_criterio);
  console.log(criteria_name);
  console.log(manual_name);
  console.log(eval_type);
  console.log(edit_percentage);
  console.log(affect_final_calification);
  console.log(in_deadline);

  if (
    id_criterio == null ||
    criteria_name < 1 ||
    (criteria_name == 1 && manual_name == "")
  ) {
    Swal.fire({
      icon: "error",
      title: "Debe llenar todas los datos obligatorios!!",
      showConfirmButton: false,
      timer: 3500,
    });
  } else {
    loading();
    $.ajax({
      url: "php/controllers/controllerConfigEvaluationPlan.php",
      method: "POST",
      data: {
        fun: "updateEvaluationConfig",
        id_criterio: id_criterio,
        criteria_name: criteria_name,
        manual_name: manual_name,
        eval_type: eval_type,
        edit_percentage: edit_percentage,
        affect_final_calification: affect_final_calification,
        in_deadline: in_deadline,
        nmb_gathering: nmb_gathering,
        original_gathering_configured: original_gathering_configured,
      },
      dataType: "json",
    })
      .done(function (data) {
        console.log(data);
        if ((data[0].resultado = "correcto")) {
          var mensaje = data[0].mensaje;
          Swal.fire({
            icon: "success",
            title: mensaje,
            showConfirmButton: false,
            timer: 1500,
          });
          setTimeout(function () {
            window.location.reload(1);
          }, 500);
        }
      })
      .fail(function (error) {
        console.log(error);
      });
    $("#modify_evaluation_plan").modal("hide");
  }
});

setInputFilter(document.getElementById("edit_percentage"), function (value) {
  disponible = parseInt($("#edit_percentage_asigned").val());
  return (
    /^\d*$/.test(value) &&
    (value == "" || (parseInt(value) >= 0 && parseInt(value) <= disponible))
  );
});

function isNumberKey(evt) {
  var charCode = evt.which ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
  return true;
}

function loadEditPercentage() {
  var periodo = $("#id_period_selected").val();
  var assignment = $("#assignment").val();

  $.ajax({
    url: "php/controllers/evaluation_plan_controller.php",
    method: "POST",
    data: {
      mod: "get_percentage",
      periodo: periodo,
      assignment: assignment,
    },
  })
    .done(function (data) {
      data = JSON.parse(data);
      var porcentaje_asignado = (porcentaje_asignado = data.suma_percentage);
      var disponible = parseInt(porcentaje_asignado);
      var pd = 100-disponible;
      console.log(disponible);
      $("#edit_percentage_asigned").val(disponible);
      $("#txt_percentage_asigned_edit").text(
        "Tiene un: " + pd + "% disponible"
      );
    })
    .fail(function (message) {
      //console.log(message);
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
