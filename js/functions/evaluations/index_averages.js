// --- --- ---//
$(document).ready(function() {
  $(document).on('click', '.btnAveragePeriod', function () {
    var id_assignment = $(this).attr("id");
    console.log("AveragePeriod");
    loading();
    $.ajax({
      url: "php/controllers/evaluations.php",
      method: "POST",
      data: {
        mod: "getAveragesByIdAssignments",
        id_assignment: id_assignment
      },
    }).done(function (data) {
        console.log(data);
        var data = JSON.parse(data);
       swal.close();
        if (data.response) {

         Swal.fire({
              title: "Resumen de promedio grupal",
              html: data.html_sweet_alert,
              icon: "success",
              showCancelButton: false,
              confirmButtonColor: "#3085d6",
              confirmButtonText: "Ok",
              allowOutsideClick: false,
              allowEnterKey: false,
         });
          // --- --- ---//
        } else {
          typeToast = "error";
        }
       
      }).fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });

  function loading() {
    Swal.fire({
        text: 'Cargando...',
        html: '<img src="images/loading_iteach.gif" width="300" height="300">',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false,
        showCancelButton: false,
      showConfirmButton: false,
    })
  }
});
