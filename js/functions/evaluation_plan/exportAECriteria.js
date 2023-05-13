// A $( document ).ready() block.
$(document).ready(function () {
  $(document).on(
    "click",
    ".exportExpectedLearningToAnotherSubject",
    function (e) {
      $("#divTableCriteria").remove();
      //--- --- ---//
      var id_period = $(this).attr("data-id-period");
      var id_academic_area = $(this).attr("data-id-academic-area");
      var id_assignment = $(this).attr("data-id-assignment");
      //--- --- ---//
      loading();
      //--- --- ---//
      $.ajax({
        url: "php/controllers/expected_learning_controller.php",
        method: "POST",
        data: {
          mod: "getExportableGroups",
          id_period: id_period,
          id_academic_area: id_academic_area,
          id_assignment: id_assignment,
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
            if (data.exportableSubjects.length > 0) {
              for (var i = 0; i < data.exportableSubjects.length; i++) {
                options_reports +=
                  '<option data-id-group="' +
                  data.exportableSubjects[i].id_group +
                  '" value="' +
                  data.exportableSubjects[i].id_expected_learning_subindex +
                  '">' +
                  data.exportableSubjects[i].subindex_title.toUpperCase() +
                  "</option>";
              }
            }

            //--- --- ---//
            $("#select_ExportableGroups").html(options_reports);
            $("#select_ExportableGroups").attr("data-id-period", id_period);
            $("#select_ExportableGroups").attr(
              "data-id-academic-area",
              id_academic_area
            );
            $("#select_ExportableGroups").attr(
              "data-id-assignment",
              id_assignment
            );
            $("#select_ExportableGroups").attr(
              "data-id-subject",
              data.id_subject
            );
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
      //--- --- ---//
    }
  );

  //--- --- ---//
  $(document).on("change", "#select_ExportableGroups", function (e) {
    //--- --- ---//

    var id_group = $("#select_ExportableGroups option:selected").attr(
      "data-id-group"
    );
    var id_period = $(this).attr("data-id-period");
    var id_academic_area = $(this).attr("data-id-academic-area");
    var id_assignment = $(this).attr("data-id-assignment");
    var id_subject = $(this).attr("data-id-subject");
    var id_expected_learning_subindex = $(this).val();
    //--- --- ---//
    loading();
    $("#divTableCriteria").remove();
    $.ajax({
      url: "php/controllers/expected_learning_controller.php",
      method: "POST",
      data: {
        mod: "getCatalogueFromAnotherAssignment",
        id_subject: id_subject,
        id_period: id_period,
        id_academic_area: id_academic_area,
        id_assignment: id_assignment,
        id_group: id_group,
        id_expected_learning_subindex: id_expected_learning_subindex,
      },
    })
      .done(function (data) {
        Swal.close();
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
          $("#cuerpo_importAE").append(data.message);
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

    $(".btnExportAEToAnotherAssignment").attr("data-id-group", id_group);
    $(".btnExportAEToAnotherAssignment").attr("data-id-period", id_period);
    $(".btnExportAEToAnotherAssignment").attr(
      "data-id-subindex",
      id_expected_learning_subindex
    );
    $(".btnExportAEToAnotherAssignment").attr(
      "data-id-academic-area",
      id_academic_area
    );
    $(".btnExportAEToAnotherAssignment").attr("data-id-subject", id_subject);
    console.log(id_subject);
    $(".btnExportAEToAnotherAssignment").attr(
      "data-id-assignment",
      id_assignment
    );
    //--- --- ---//
    Swal.close();
  });

  //--- --- ---//

  $(document).on("click", ".btnExportAEToAnotherAssignment", function (e) {
    //--- --- ---//
    var id_subject = $(this).attr("data-id-subject");
    console.log(id_subject);
    var id_group = $(this).attr("data-id-group");
    var id_period = $(this).attr("data-id-period");
    var id_academic_area = $(this).attr("data-id-academic-area");
    var id_assignment = $(this).attr("data-id-assignment");
    var id_expected_learning_subindex = $(this).attr("data-id-subindex");
    console.log(id_expected_learning_subindex);
    //--- --- ---//
    loading();

    //--- --- ---//
    $.ajax({
      url: "php/controllers/expected_learning_controller.php",
      method: "POST",
      data: {
        mod: "exportToAnotherAssignment",
        id_subject: id_subject,
        id_period: id_period,
        id_academic_area: id_academic_area,
        id_assignment: id_assignment,
        id_group: id_group,
        id_expected_learning_subindex: id_expected_learning_subindex,
      },
    })
      .done(function (data) {
        Swal.close();
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
          Swal.fire("Éxito!", data.message, "success").then((result) => {
            location.reload();
          });

          var myOpts = document.getElementById(
            "select_ExportableGroups"
          ).options;
          /* var myOptsSubject = document.getElementById(
              "exportable_subject"
              ).options; */

          if (myOpts.length == 2) {
            /* $("#exportable_subject option:selected").remove();
              $("#select_ExportableGroups option:selected").remove();
              $("#select_ExportableGroups").val("");
              $("#exportable_subject").val(""); */
          } else {
            $("#select_ExportableGroups option:selected").remove();
            $("#select_ExportableGroups").val("");
          }

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
  });

  /* $(document).on("change", "#select_ExportableGroups", function (e) {
      //--- --- ---//
      var id_subject = $("#exportable_subject").val();
      var id_group = $(this).val();
      var id_period = $(this).attr("data-id-period");
      var id_academic_area = $(this).attr("data-id-academic-area");
      //--- --- ---//
      loading();
      //--- --- ---//
      $.ajax({
        url: "php/controllers/evaluation_plan_controller.php",
        method: "POST",
        data: {
          mod: "getExportablePeriodsEP",
          id_subject: id_subject,
          id_period: id_period,
          id_academic_area: id_academic_area,
          id_group: id_group,
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
            if (data.exportableSubjects.length > 0) {
              for (var i = 0; i < data.exportableSubjects.length; i++) {
                options_reports +=
                  '<option value="' +
                  data.exportableSubjects[i].id_period_calendar +
                  '">Periodo ' +
                  data.exportableSubjects[i].no_period +
                  "</option>";
              }
            }
  
            //--- --- ---//
            $("#select_ExportablePeriod").html(options_reports);
            $("#select_ExportablePeriod").attr("data-id-period", id_period);
            $("#select_ExportablePeriod").attr(
              "data-id-academic-area",
              id_academic_area
            );
            $("#select_ExportablePeriod").attr("data-id-subject", id_subject);
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
      //--- --- ---//
    }); */
});
