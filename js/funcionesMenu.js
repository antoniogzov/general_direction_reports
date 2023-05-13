function setInputFilter(textbox, inputFilter) {
  [
    "input",
    "keydown",
    "keyup",
    "mousedown",
    "mouseup",
    "select",
    "contextmenu",
    "drop",
  ].forEach(function (event) {
    textbox.addEventListener(event, function () {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
      } else {
        this.value = "";
      }
    });
  });
}
setInputFilter(document.getElementById("percentage"), function (value) {
  disponible = parseInt($("#percentage_asigned").val());
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
$(document).ready(function () {
  loadPlan();

  /*  url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "exportToAnotherAssignment",
        id_subject: id_subject,
        id_period: id_period,
        id_academic_area: id_academic_area,
        id_assignment: id_assignment,
        id_group: id_group,
      }, */

  function loadPercentage() {
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
        var data = JSON.parse(data);
        var porcentaje_asignado = data.suma_percentage;
        console.log(porcentaje_asignado);
        if (porcentaje_asignado === null) {
          var disponible = 100;
          disponible2 = 100;
        } else {
          var disponible = parseInt(porcentaje_asignado);
          disponible2 = 100 - disponible;
        }

        $("#percentage_asigned").val(disponible2);
        $("#percentage_asigned").attr("data-percentage", disponible2);
        $("#txt_percentage_asigned").text(
          "Tiene un: " + disponible2 + "% disponible"
        );
      })
      .fail(function (message) {
        //console.log(message);
      });
  }

  function loadPlan() {
    var input_period = $("#id_period_selected").val();
    if (input_period != "") {
      document.getElementById("select_periodo").value = input_period;
      $("#btn_agregar_criterio").show();
      $("#div_criterios").show();
      $("#div_grafica").show();
    } else if ((input_period = "")) {
      $("#btn_agregar_criterio").hide();
      $("#div_criterios").hide();
      $("#div_grafica").hide();
    }
  }
  $(document).on("change", "#select_periodo", function () {
    var id_period = $(this).val();
    var id_assignment = $("#id_assignment").val();
    var id_academic_area = $("#id_academic_area").val();
    $("#id_period_selected").val(id_period);
    var url =
      "configuracion_evaluaciones.php?ac_ar=" +
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
  $(document).on("change", "#select_eval_name", function () {
    id_tipo = $("#select_eval_name").find(":selected").val();
    console.log(id_tipo);
    if (id_tipo == 1) {
      $("#LabelAditionalName").show();
      $("#AditionalName").show();
      $("#AditionalName").val("");
    } else if (id_tipo != 1) {
      $("#LabelAditionalName").hide();
      $("#AditionalName").hide();
      $("#AditionalName").val("");
    }
  });
  $(document).on("change", "#id_criterio", function () {
    id_criterio = $("#id_criterio").find(":selected").val();
    console.log(id_criterio);
    if (id_criterio == 0) {
      $("#div_checkbox_sub").show();
    } else if (id_criterio != 0) {
      $("#div_checkbox_sub").hide();
    }
  });
  $(document).on("change", "#check_subcriterios", function () {
    var check_sub = $(this);
    if ($(check_sub).is(":checked")) {
      $("#lbl_subcriterios").show();
      $("#subcriterios").show();
      $("#subcriterios").val("");
    } else {
      $("#lbl_subcriterios").hide();
      $("#subcriterios").hide();
      $("#subcriterios").val("");
    }
  });
  $(document).on("change", "#sid_criterio", function () {
    id_criterio = $("#id_criterio").find(":selected").val();
    console.log(id_criterio);
    if (id_criterio == 1) {
      $("#subcriterios").show();
      $("#lbl_subcriterios").show();
      $("#subcriterios").val("");
    } else if (id_criterio != 1) {
      $("#subcriterios").hide();
      $("#subcriterios").val("");
      $("#lbl_subcriterios").hide();
    }
  });
  $(document).on("click", ".delete_ev_plan", function () {
    //--- --- ---//
    var id_ev = $(this).attr("id");
    //--- --- ---//
    $("#modal_elm").modal("show");
    $("#id_ev_eliminar").val(id_ev);
    $("#texto_confirmar").text(
      "¿Desea eliminar criterio de evaluación " + id_ev + "?"
    );
  });
  $(document).on("click", "#btn_import_plan", function () {
    //--- --- ---//
    $("#import_plan").modal("show");
  });
  $(document).on("click", "#btn_cancel_import_per_config", function () {
    //--- --- ---//
    $("#import_plan").modal("hide");
    $(".modal-backdrop").hide();
  });
  $(document).on("click", "#btn_new_plan", function () {
    //--- --- ---//
    loadPercentage();
  });
  $(document).on("click", "#cont_delete", function () {
    //--- --- ---//
    //--- --- ---//
    /* */
    var id_eliminar = $("#id_ev_eliminar").val();
    var no_colabt = $("#no_colabt").val();
    console.log(id_eliminar);
    loading();
    $.ajax({
      url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "deleteEvalCriteria",
        id_eliminar: id_eliminar,
        colaborator: no_colabt,
      },
    })
      .done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        var titulo = data[0].mensaje;
        $("#modal_elm").modal("hide");
        if (data[0].resultado == "correcto") {
          Swal.fire({
            icon: "success",
            title: titulo,
            showConfirmButton: false,
            timer: 3500,
          }).then((result) => {
            window.location.reload();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: titulo,
            showConfirmButton: false,
            timer: 3500,
          });
        }
      })
      .fail(function (message) {
        //console.log(message);
      });
  });
  $(document).on("click", "#cerrar_m_eliminar", function () {
    //--- --- ---//
    $("#modal_elm").modal("hide");
  });
  $(document).on("click", "#cerrar_b_actualizar", function () {
    //--- --- ---//
    $("#modify_evaluation_plan").modal("hide");
    $(".modal-backdrop").hide();
  });
  $(document).on("click", "#btn_guardar_criterio", function () {
    //--- --- ---//
    var select_eval_name = $("#select_eval_name").find(":selected").val();
    var id_criterio = $("#id_criterio").find(":selected").val();
    var periodo = $("#id_period_selected").val();
    var assignment = $("#assignment").val();
    var add_name = $("#AditionalName").val();
    var check_subcriterios = 0;
    var check_afectar_calificacion = 0;
    var subcriterios = $("#subcriterios").val();
    var percentage = $("#percentage").val();
    var fechaFin = $("#fechaFin").val();
    var id_evaluation_type = $("#select_eval_type").val();
    var check_sub = $("#check_subcriterios");
    var check_afc = $("#check_afectar_calificacion");
    if ($(check_afc).is(":checked")) {
      check_afectar_calificacion = 1;
    }
    if ($(check_sub).is(":checked")) {
      check_subcriterios = 1;
    }

    var percentage_assigned = $("#percentage_asigned").attr("data-percentage");
    percentage_assigned = parseInt(percentage + percentage_assigned);
    console.log(percentage_assigned);
    if (percentage_assigned >= 100 && percentage > 0) {
      //console.log("Afectar: " + check_subcriterios);
      /* console.log("ID evaluacion: "+select_eval_name);
            console.log("ID método de captura: "+id_criterio);
            console.log("ID nombre adicional: "+add_name);
            console.log("Asignatura: "+assignment);
            console.log("Periodo: "+periodo);
            console.log("Check subcriterios: "+check_subcriterios);
            console.log("No° subcriterios: "+subcriterios);
            console.log("Porcentaje asignado: "+percentage);
            console.log("Fecha: "+fechaFin); */
      loading();
      $.ajax({
        url: "php/controllers/evaluation_plan_controller.php",
        method: "POST",
        data: {
          mod: "createEvalCriteria",
          id_evaluation: select_eval_name,
          periodo: periodo,
          assignment: assignment,
          id_metodo: id_criterio,
          add_name: add_name,
          check_subcriterios: check_subcriterios,
          check_afectar_calificacion: check_afectar_calificacion,
          subcriterios: subcriterios,
          percentage: percentage,
          id_evaluation_type: id_evaluation_type,
          fechaFin: fechaFin,
        },
        dataType: "json",
        success: function (data) {
          console.log(data);
          console.log(data[0].resultado);
          var titulo = data[0].mensaje;
          $("#cerrar_mdl_criterio").click();
          if (data[0].resultado == "correcto") {
            Swal.fire({
              icon: "success",
              title: titulo,
              showConfirmButton: false,
              timer: 2500,
            }).then((result) => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: "error",
              title: titulo,
              showConfirmButton: false,
              timer: 2500,
            });
          }
        },
        error: function (xhr, status) {
          //alert('Disculpe, existió un problema');
          console.log("Ocurrió un error: " + xhr.responseText);
        },
      });
      if (id_criterio == 0) {
      }
    } else if (percentage == 0 || percentage == "") {
      //console.log("Afectar: " + check_subcriterios);
      /* console.log("ID evaluacion: "+select_eval_name);
              console.log("ID método de captura: "+id_criterio);
              console.log("ID nombre adicional: "+add_name);
              console.log("Asignatura: "+assignment);
              console.log("Periodo: "+periodo);
              console.log("Check subcriterios: "+check_subcriterios);
              console.log("No° subcriterios: "+subcriterios);
              console.log("Porcentaje asignado: "+percentage);
              console.log("Fecha: "+fechaFin); */
      loading();
      $.ajax({
        url: "php/controllers/evaluation_plan_controller.php",
        method: "POST",
        data: {
          mod: "createEvalCriteria",
          id_evaluation: select_eval_name,
          periodo: periodo,
          assignment: assignment,
          id_metodo: id_criterio,
          add_name: add_name,
          check_subcriterios: check_subcriterios,
          check_afectar_calificacion: check_afectar_calificacion,
          subcriterios: subcriterios,
          percentage: percentage,
          id_evaluation_type: id_evaluation_type,
          fechaFin: fechaFin,
        },
        dataType: "json",
        success: function (data) {
          console.log(data);
          console.log(data[0].resultado);
          var titulo = data[0].mensaje;
          $("#cerrar_mdl_criterio").click();
          if (data[0].resultado == "correcto") {
            Swal.fire({
              icon: "success",
              title: titulo,
              showConfirmButton: false,
              timer: 2500,
            }).then((result) => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: "error",
              title: titulo,
              showConfirmButton: false,
              timer: 2500,
            });
          }
        },
        error: function (xhr, status) {
          //alert('Disculpe, existió un problema');
          console.log("Ocurrió un error: " + xhr.responseText);
        },
      });
      if (id_criterio == 0) {
      }
    } else {
      Swal.fire({
        icon: "error",
        title: "No puede agregar mas criterios",
        text: "El total del porcentaje ya ha sido distribuido",
        showConfirmButton: false,
        timer: 2500,
      }).then((result) => {
        location.reload();
      });
    }

    //--- --- ---//
    /*  $('#modal_elm').modal('show');
         $('#id_ev_eliminar').val(id_ev);
         $('#texto_confirmar').text("¿Desea eliminar criterio de evaluación "+id_ev+"?");  */
  });
  $(document).on("click", ".btn_editar_sub_criterios", function () {
    //--- --- ---//
    var id_criterio = $(this).attr("id");
    console.log(id_criterio);
    $.ajax({
      url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "getSubcriterios",
        id_criterio: id_criterio,
      },
    })
      .done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        if (data[0].resultado == "correcto") {
          var subcriterios = [];
          for (i = 0; i < data[0].registros.length; i++) {
            console.log(data[0].registros[i]);
            var id_og = data[0].registros[i].id_subcriterio;
            var name_og = data[0].registros[i].item;
            var id_ep = data[0].registros[i].id_ep;
            subcriterios += '<div class="form-group">';
            subcriterios +=
              '<label for="sc_new_name" id="lbl_sc_new_name"  class="form-label text-dark">*Nuevo nombre para: ' +
              name_og +
              "</label>";
            subcriterios +=
              '<input type="text" data-original-name="' +
              name_og +
              '" class="form-control new_name_subcr" id="' +
              id_og +
              '" required placeholder="Ingrese el nuevo nombre..." >';
            subcriterios += "</div>";
          }
          subcriterios +=
            '<input type="hidden"  id="m_id_ep" class="form-control" value="' +
            id_ep +
            '">';
          $("#div_subcriterios").html(subcriterios);
          $("#edit_subcriterios").modal("show");
        } else {
          Swal.fire({
            icon: "error",
            title: "Error al obtener los subcriterios",
            showConfirmButton: false,
            timer: 3500,
          });
        }
      })
      .fail(function (message) {
        //console.log(message);
      });
    if (id_criterio == 0) {
    }
    //--- --- ---//
    /*  $('#modal_elm').modal('show');
         $('#id_ev_eliminar').val(id_ev);
         $('#texto_confirmar').text("¿Desea eliminar criterio de evaluación "+id_ev+"?");  */
  });
  $(document).on("click", "#btn_actualizar_subcriterios", function () {
    //--- --- ---//
    var array_names = [];
    $(".new_name_subcr").each(function (i, obj) {
      var id_input = $(this).attr("id");
      var new_name = $(this).val();
      if (new_name == "") {
        new_name = $(this).attr("data-original-name");
        console.log(new_name);
      }
      //--- --- ---//
      var obj = {
        id_input: id_input,
        new_name: new_name,
      };
      array_names.push(obj);
      //--- --- ---//
    });
    var id_criterio = $("#m_id_ep").val();
    console.log(array_names);
    console.log(id_criterio);
    loading();
    $.ajax({
      url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "updateSubcriterios",
        array_names: array_names,
        id_criterio: id_criterio,
      },
    })
      .done(function (data) {
        console.log(data);
        data = JSON.parse(data);
        if (data[0].resultado == "correcto") {
          var titulo = data[0].mensaje;
          $("#cerrar_b_actualizar").click();
          Swal.fire({
            icon: "success",
            title: titulo,
            showConfirmButton: false,
            timer: 2500,
          });
        } else {
          Swal.fire({
            icon: "error",
            title: titulo,
            showConfirmButton: false,
            timer: 2500,
          });
        }
      })
      .fail(function (message) {
        //console.log(message);
      });
  });
  $(document).on("hidden", "#edit_subcriterios", function () {
    $($this).removeData("modal");
  });
  $(document).on("click", "#btn_import_per_config", function () {
    //--- --- ---//
    var period_from = $("#select_import_period").find(":selected").val();
    var import_on_period = $("#import_on_period").val();
    var colaborator = $("#no_colabt").val();
    console.log("From: " + period_from);
    console.log("To: " + import_on_period);
    loading();
    $.ajax({
      url: "php/models/copy.php",
      method: "POST",
      data: {
        period_from: period_from,
        import_on_period: import_on_period,
        colaborator: colaborator,
      },
      dataType: "json",
      success: function (data) {
        console.log(data);
        var titulo = data[0].mensaje;
        if (data[0].resultado == "correcto") {
          $("#btn_cancel_import_per_config").click();
          Swal.fire({
            icon: "success",
            title: titulo,
            showConfirmButton: false,
          }).then((result) => {
            location.reload();
          });
        } else {
          console.log(data);
          Swal.fire({
            icon: "error",
            title: titulo,
            showConfirmButton: false,
            timer: 2500,
          });
        }
      },
    });
  });
  $(document).on("click", "#btn_export_plan", function () {
    //--- --- ---//
    $("#export_plan").modal("show");
  });
  $(document).on("click", "#btn_delete_period_plan", function () {
    //--- --- ---//
    var id_period = $("#select_periodo").val();
    var id_assignment = $("#id_assignment").val();
    Swal.fire({
      title:
        "<strong>¿EN REALIDAD DESEA ELIMINAR TODOS LOS CRITERIOS DE EVALUACIÓN DE ESTE PERIODO PARA ESTA MATERIA?</strong>",
      icon: "question",
      html: "</br>",
      showCloseButton: true,
      showCancelButton: true,
      allowOutsideClick: false,
      allowEscapeKey: false,
      confirmButtonText: "Aceptar",
      cancelButtonText: "Volver",
      focusConfirm: false,
    }).then((result) => {
      if (result.isConfirmed) {
        console.log(id_period);
        console.log(id_assignment);
        $.ajax({
          url: "php/controllers/controllerConfigEvaluationPlan.php",
          method: "POST",
          data: {
            fun: "deletePeriodSubjectConfig",
            id_assignment: id_assignment,
            id_period: id_period,
          },
          dataType: "json",
        })
          .done(function (data) {
            console.log(data);
            if (data[0].resultado == "correcto") {
              var icon = "success";
            } else {
              var icon = "error";
            }
            Swal.fire({
              title: "<strong>" + data[0].mensaje + "</strong>",
              icon: icon,
              showCloseButton: true,
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonText: "Aceptar",
              focusConfirm: true,
            }).then((result) => {
              location.reload();
            });
            /* 
                    
                    
                    var listahtml = '';
                    for (var i = 0; i < data[0].info.length; i++) {
                        console.log(data[0].info[i]);
                       listahtml+='<li class="list-group-item list-group-item-'+data[0].info[i][5]+'">  Grupo: '+data[0].info[i][0]+' | Materia: '+data[0].info[i][2]+'</li>';
                    }
                    
                    if (data[0].resultado = "correcto") {
                        var mensaje = data[0].mensaje;
                       
                       
                        $('#export_subject_plan').modal('hide');
                    }
                 */
          })
          .fail(function (error) {
            console.log(error);
            Swal.fire({
              title: "<strong>" + data[0].mensaje + "</strong>",
              icon: "success",
              showCloseButton: true,
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonText: "Aceptar",
              focusConfirm: true,
            });
          });
        //deleteEvalPlan(id_assignment,id_period);
      } else if (result.isDenied) {
      }
    });
  });
  $(document).on("click", "#btn_export_per_config", function () {
    //--- --- ---//
    var val = [];
    var id = [];
    $(".checks_periodos:checkbox:checked").each(function (i) {
      val[i] = $(this).val();
      id[i] = $(this).attr("id");
    });
    var id_assignment = $("#id_assignment_export").val();
    var import_on_period = id + ",";
    var period_from = $("#export_from_period").val();
    var colaborator = $("#no_colabt").val();
    console.log("From: " + period_from);
    console.log("To: " + import_on_period);
    loading();
    $.ajax({
      url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "internalExport",
        period_from: period_from,
        import_on_period: import_on_period,
        id_assignment: id_assignment,
        colaborator: colaborator,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);

        var titulo = data[0].mensaje;
        console.log(data);
        if (data[0].resultado == "correcto") {
          $("#btn_cancel_export_per_config").click();
          Swal.fire({
            icon: "success",
            title: titulo,
            showConfirmButton: false,
            timer: 2500,
          }).then((result) => {
            location.reload();
          });
        } else {
          $("#btn_cancel_export_per_config").click();
          Swal.fire({
            icon: "error",
            title: titulo,
            showConfirmButton: false,
            timer: 2500,
          });
        }
      })
      .fail(function (message) {
        //console.log(message);
      });
  });
  $(document).on("click", "#btn_cancel_export_per_config", function () {
    //--- --- ---//
    $("#export_plan").modal("hide");
    $(".modal-backdrop").hide();
  });

  function deleteEvalPlan(id_assignment, id_period) {}

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
});
