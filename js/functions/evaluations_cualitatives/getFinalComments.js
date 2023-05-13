//--- --- ---//
swal.close();
addScript(
  "https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
);
//--- --- ---//
$(document).on("change", "#slct_group", function (e) {
  $("#div_button").hide();
  //--- --- ---//
  const id_group = $("#slct_group option:selected").val();
  console.log(id_group);
  $("#slct_report").html(
    '<option selected value="" disabled>Elija una opción</option>'
  );
  $("#slct_student").html(
    '<option selected value="" disabled>Elija una opción</option>'
  );
  $(".div_content_mda").html("");
  $("#slct_installment").val("");
  //--- --- ---//
  getStudList(id_group);
  //--- --- ---//
});
$(document).on("change", "#slct_learning_map", function (e) {});
$(document).ready(function () {
  startGetGroupsMDA();
});

function startGetGroupsMDA() {
  //--- --- ---//
  var lmp_id = $("#slct_learning_map").val();
  $("#slct_installment").val("");
  $("#slct_group").html("");
  //--- --- ---//
  $(".div_content_mda").html("");
  $(".slct_option_student").html(
    '<option selected value="" disabled>Elija una opción</option>'
  );
  //--- --- ---//
  getGroupsMDA(lmp_id);
  //--- --- ---//
}
//--- --- ---//
$(document).on("change", "#slct_student", function (e) {
  //--- --- ---//

  const id_group = $("#slct_group option:selected").val();
  const installment = $("#slct_installment").val();
  const report_function = $("#slct_report").val();
  const id_student = $("#slct_student").val();
  if (id_group != null && installment != null) {
    //--- --- ---//
    $("#div_button").show();
    window[report_function](id_group, id_student, installment);
    //--- --- ---//
  }
});
//--- --- ---//
$(document).on("change", "#slct_installment", function (e) {
  //--- --- ---//
  const id_group = $("#slct_group option:selected").val();
  var lmp_id = $("#slct_learning_map").val();
  const installment = $(this).val();
  $("#div_button").show();
  console.log(id_group);
  console.log(lmp_id);
  console.log(installment);
  //--- --- ---//
  generateFinalCommentsReport(id_group, lmp_id, installment);
  //--- --- ---//
});
//--- --- ---//
//--- --- ---//
function getStudList(id_group) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "getStudList",
      id_group: id_group,
    },
  })
    .done(function (data) {
      //console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        var options_students =
          '<option selected value="" disabled>Elija una opción</option>';

        options_students +=
          '<option selected value="0">TODOS LOS ALUMNOS</option>';
        //--- --- ---//
        /*  if (data.reports.length > 0) {
          for (var i = 0; i < data.reports.length; i++) {
            options_reports +=
              '<option value="' +
              data.reports[i].function_js +
              '">' +
              data.reports[i].qualitative_report_name +
              "</option>";
          }
        } */
        //--- --- ---//
        if (data.students.length > 0) {
          for (var i = 0; i < data.students.length; i++) {
            options_students +=
              '<option value="' +
              data.students[i].id_student +
              '">' +
              data.students[i].student_name +
              "</option>";
          }
        }
        //--- --- ---//
        $("#slct_student").html(options_students);
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
//--- --- ---//
//--- --- ---//
//--- --- ---//
$(document).on("click", ".generatePDF2", function (e) {
  var NameDoc = $("#NameDoc").val();
  kendo.drawing
    .drawDOM($("#div_content_mda"))
    .then(function (group) {
      // Render the result as a PDF file
      return kendo.drawing.exportPDF(group, {
        paperSize: "auto",
        margin: { left: "1cm", top: "1cm", right: "1cm", bottom: "1cm" },
      });
    })
    .done(function (data) {
      // Save the PDF file
      kendo.saveAs({
        dataURI: data,
        fileName: NameDoc + ".pdf",
        proxyURL: "https://demos.telerik.com/kendo-ui/service/export",
      });
    });
});
function addScript(url) {
  var script = document.createElement("script");
  script.type = "application/javascript";
  script.src = url;
  document.head.appendChild(script);
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
function getGroupsMDA(lmp_id) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "getGroupsMDA",
      lmp_id: lmp_id,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        var options =
          '<option selected value="" disabled>Elija una opción</option>';
        if (data.groups.length > 0) {
          for (var i = 0; i < data.groups.length; i++) {
            options +=
              '<option value="' +
              data.groups[i].id_group +
              '" data-toggle="tooltip" data-placement="top" title="' +
              data.groups[i].string_group +
              '">' +
              data.groups[i].group_code +
              "</option>";
          }
        }
        //--- --- ---//
        $("#slct_group").html(options);
        //--- --- ---//
        $('[data-toggle="tooltip"]').tooltip();
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

function generateFinalCommentsReport(id_group, lmp_id, installment) {
  //--- --- ---//
  console.log("generateFinalCommentsReport");
  /* loading(); */
  //--- --- ---//
  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "getReportMDAGeneral",
      lmp_id: lmp_id,
      group_id: id_group,
      installment: installment,
      id_student: "0",
    },
  })
    .done(function (data) {
      //console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        generateReportGroupMDAGeneral(data);
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

function generateReportGroupMDAGeneral(data) {
  //--- --- ---//
  var all_data = "";
  var group_code = $("#slct_group option:selected").text();
  var installment = $("#slct_installment option:selected").text();
  //--- --- ---//
  if (data.topics.length > 0) {
    //--- COMENTARIOS FINALES ---//
    if (data.final_comments.length > 0) {
      //--- --- ---//all_data +=
      all_data +=
        '<button type="button" class="btn btn-outline-danger btn-dowload-report-final-comments" data-student-code = "' +
        group_code +
        " | PERIODO: " +
        installment +
        '" data-name-group="' +
        "AREAS DESARROLLO PERSONAL Y SOCIAL HEBREO" +
        '">Descargar PDF&nbsp;&nbsp;<i class="fas fa-file-pdf fa-lg"></i></button><br/><br/>';
      all_data += '<div class="card divFinalComments" >';
      all_data +=
        '<div class="card-header border-0"><h3 class="mb-0">COMENTARIOS FINALES</h3><button class="btn btn-success" id="print_tables" data-table_to_print="tableMDA"><i class="fas fa-print"></i></button></div>';
      //--- --- ---//
      var table = "<div>";
      table +=
        '<table class="table align-items-center" id="tableMDA" style="width: 100%;">';
      table += "<thead class='thead-light'>";
      table += "<tr>";
      //--- RECORREMOS TODAS LA ASIGNATURAS ---//
      table +=
        '<th class="text-center" colspan="4"><h4>Grupo: ' +
        group_code +
        "| Periodo: " +
        installment +
        "</h4></th>";
      //--- --- ---//
      table += "</tr>";
      table += "<tr>";
      //--- RECORREMOS TODAS LA ASIGNATURAS ---//
      table += '<th class="text-center" colspan="3"></th>';
      table +=
        '<th class="text-center">' +
        data.final_comments[0].assg.name_subject +
        "<br/>" +
        data.final_comments[0].assg.teacher_name +
        "</th>";
      //--- --- ---//
      table += "</tr>";
      table += "<tr>";
      //--- RECORREMOS TODAS LA ASIGNATURAS ---//
      table += '<th class="text-center" style="width:30px">N° ALUMNO</th>';
      table += '<th class="text-center">CÓDIGO DE ALUMNO</th>';
      table +=
        '<th class="text-center" style="white-space: normal; vertical-align: middle; border-left: 1px dotted; letter-spacing: 0;" >ALUMNO</th>';
      table +=
        '<th class="text-center" style="white-space: normal; vertical-align: middle; border-left: 1px dotted; letter-spacing: 0;" >COMENTARIO</th>';

      table += "</tr>";
      //--- CABECERA COMENTARIOS ---//
      //--- --- ---//
      table += "</thead>";
      table += '<tbody class="list">';
      //--- LISTA ALUMNOS ---//
      var cont = 1;
      for (var e = 0; e < data.students.length; e++) {
        //--- --- ---//
        table += "<tr>";
        table +=
          '<td style="white-space: normal; vertical-align: middle; border-left: 1px dotted; letter-spacing: 0;" >' +
          cont +
          "</td>";
        table +=
          '<td style="white-space: normal; vertical-align: middle; border-left: 1px dotted; letter-spacing: 0;" >' +
          data.students[e].student_code +
          "</td>";
        table +=
          '<td style="white-space: normal; vertical-align: middle; border-left: 1px dotted; letter-spacing: 0;" >' +
          data.students[e].student_name +
          "</td>";
        //--- --- ---//
        //--- RECORREMOS TODOS LOS COMENTARIOS ---//
        for (var i = 0; i < data.final_comments.length; i++) {
          var answer_find = false;
          for (var x = 0; x < data.final_comments[i].comments.length; x++) {
            if (
              data.final_comments[i].comments[x].id_student ==
                data.students[e].id_student &&
              data.final_comments[i].comments[x].ascc_lm_assgn ==
                data.final_comments[i].assg.ascc_lm_assgn
            ) {
              //--- --- ---//
              answer_find = true;
              table +=
                '<td style="white-space: normal; vertical-align: middle; border-left: 1px dotted; letter-spacing: 0;"  class="comments1' +
                cont +
                '" data-id-comments="' +
                data.final_comments[i].comments[x].id_comments +
                '" >' +
                data.final_comments[i].comments[x].comments1 +
                "</td>";
              //--- --- ---//
            }
          }
          //--- ---//
          if (!answer_find) {
            //--- --- ---//
            table +=
              '<td style="white-space: normal; vertical-align: middle; border-left: 1px dotted; letter-spacing: 0;" ></td>';
          }
          //--- --- ---//
          cont++;
          //--- --- ---//
        }
        //--- --- ---//
        table += "</tr>";
        //--- --- ---//
      }
      //--- --- ---//
      table += "</tbody>";
      table += "</table>";
      table += "</div>";
      //--- --- ---//
      all_data += '<div class="card-body">';
      all_data += '<div style="position:relative">';
      all_data += table;
      all_data += "</div>";
      all_data += "</div>";
      all_data += "</div>";
      all_data += "</div>";
      //--- --- ---//
    }
  }
  //--- --- ---//
  $(".div_content_mda").html(all_data);
  //--- --- ---//
  $('[data-toggle="tooltip"]').tooltip();
  swal.close();
  //--- --- ---//
}

$(document).on("click", "#print_tables", function () {
  let id_table = $(this).attr("data-table_to_print");
  let group_name = $("#slct_group").children(":selected").prop("textContent");
  var installment = $("#slct_installment option:selected").text();
  group_name = group_name + " | PERIODO: " + installment;
  let mda_name = $(this).siblings()[0].textContent.trim();
  let dom_element = document.getElementById(id_table);
  var wb = XLSX.utils.table_to_book(dom_element);
  XLSX.writeFile(wb, `${group_name}-${mda_name}.xlsx`);
});

$(document).on("click", ".btn-dowload-report-final-comments", function () {
  //--- --- ---//

  //--- --- ---//
  const id_group = $("#slct_group option:selected").val();
  var lmp_id = $("#slct_learning_map").val();
  const installment = $("#slct_installment").val();
  //--- --- ---//
  reportFinalComments(id_group, lmp_id, installment);
});
//--- --- ---//
//-- - GENERAR PDF COMENTARIOS FINALES - ---//
//--- --- ---//

function reportFinalComments(id_group, lmp_id, installment) {
  //--- --- ---//
  loading();
  //--- --- ---//
  //--- --- ---//

  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "getReportMDAGeneral",
      lmp_id: lmp_id,
      group_id: id_group,
      installment: installment,
      id_student: "0",
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      if (data.response) {
        console.log(data);
        var group_code = $("#slct_group option:selected").text();
        //--- --- ---//
        if (data.final_comments[0].comments.length) {
            //--- --- ---//
            downloadReportPrimaryBangueoloHebrew(data, installment, group_code);
          
        } else {
          var all_data =
            '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
        }
        //--- --- ---//
        $(".div_content_mda").html(all_data);
        $('[data-toggle="tooltip"]').tooltip();
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
