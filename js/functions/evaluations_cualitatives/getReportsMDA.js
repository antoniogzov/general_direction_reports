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
  getReports(id_group);
  //--- --- ---//
});
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
  const report_function = $("#slct_report").val();
  const id_student = $("#slct_student").val();
  const installment = $(this).val();
  $("#div_button").show();
  /* console.log(id_group);
  console.log(report_function);
  console.log(id_student);
  console.log(installment); */
  //--- --- ---//
  window[report_function](id_group, id_student, installment);
  //--- --- ---//
});
//--- --- ---//
//--- --- ---//
function getReports(id_group) {
  //--- --- ---//
  loading();
  //--- --- ---//
  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "getReports",
      id_group: id_group,
    },
  })
    .done(function (data) {
      //console.log(data);
      var data = JSON.parse(data);
      if (data.response) {
        //--- --- ---//
        var options_reports =
          '<option selected value="" disabled>Elija una opción</option>';
        var options_students =
          '<option selected value="" disabled>Elija una opción</option>';
        //--- --- ---//
        if (data.reports.length > 0) {
          for (var i = 0; i < data.reports.length; i++) {
            options_reports +=
              '<option value="' +
              data.reports[i].function_js +
              '">' +
              data.reports[i].qualitative_report_name +
              "</option>";
          }
        }
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
        $("#slct_report").html(options_reports);
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
        fileName: NameDoc+".pdf",
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
