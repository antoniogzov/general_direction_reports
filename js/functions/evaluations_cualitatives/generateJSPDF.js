$(document).ready(function () {
  $(".generateHebrewPrimaryPDF").click(function () {
    loading();

    var id_group = $("#slct_group option:selected").val();
    var id_student = $("#slct_student").val();
    var installment = $("#slct_installment").val();
    $.ajax({
      url: "php/controllers/reports_evaluations_cualitatives.php",
      method: "POST",
      data: {
        mod: "reportPrimaryBangueoloMDAHebrew",
        id_group: id_group,
        installment: installment,
        id_student: id_student,
      },
    }).done(function (data) {
      var data = JSON.parse(data);
      if (data.response) {
        console.log(data);
        //--- --- ---//
        if (data.results_evc_normal.length) {
        } else {
        }
        //--- --- ---//
        swal.close();
        //--- --- ---//
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    });
  });

  function loading() {
    Swal.fire({
      title: "Cargando...",
      html: '<img src="images/loading_iteach.gif" width="300" height="175">',
      allowOutsideClick: false,
      allowEscapeKey: false,
      showCloseButton: false,
      showCancelButton: false,
      showConfirmButton: false,
    });
  }
});
