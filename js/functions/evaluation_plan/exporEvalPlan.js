// A $( document ).ready() block.
$(document).ready(function () {
  $(document).on("click", ".export_planAnotherSubject", function (e) {
    //--- --- ---//
    $("#exportable_subject").html("");
    $("#select_ExportableGroups").html("");
    $("#select_ExportablePeriod").html("");
    var id_assignment = $(this).attr("data-id-assignment");
    var id_period = $(this).attr("data-id-period");
    var id_academic_area = $(this).attr("data-id-academic-area");
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
      url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "getExportableSubjects",
        id_assignment: id_assignment,
        id_period: id_period,
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
          if (data.exportableSubjects.length > 0) {
            for (var i = 0; i < data.exportableSubjects.length; i++) {
              options_reports +=
                '<option value="' +
                data.exportableSubjects[i].id_subject +
                '">' +
                data.exportableSubjects[i].name_subject.toUpperCase() +
                "</option>";
            }
          }

          //--- --- ---//
          $("#exportable_subject").html(options_reports);
          $("#exportable_subject").attr("data-id-period", id_period);
          $("#exportable_subject").attr(
            "data-id-academic-area",
            id_academic_area
          );
          $("#exportable_subject").attr("data-id-assignment", id_assignment);
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
  });

  //--- --- ---//

  $(document).on("change", "#exportable_subject", function (e) {
    //--- --- ---//
    var id_subject = $(this).val();
    var id_period = $(this).attr("data-id-period");
    var id_academic_area = $(this).attr("data-id-academic-area");
    var id_assignment = $(this).attr("data-id-assignment");
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
      url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "getExportableGroups",
        id_subject: id_subject,
        id_period: id_period,
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
          if (data.exportableSubjects.length > 0) {
            for (var i = 0; i < data.exportableSubjects.length; i++) {
              options_reports +=
                '<option value="' +
                data.exportableSubjects[i].id_group +
                '">' +
                data.exportableSubjects[i].group_code.toUpperCase() +
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
          $("#select_ExportableGroups").attr("data-id-subject", id_subject);
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
  });

  //--- --- ---//
  $(document).on("change", "#select_ExportableGroups", function (e) {
    //--- --- ---//
    var id_subject = $("#exportable_subject").val();
    var id_group = $(this).val();
    var id_period = $(this).attr("data-id-period");
    var id_academic_area = $(this).attr("data-id-academic-area");
    var id_assignment = $(this).attr("data-id-assignment");
    //--- --- ---//
    loading();
    //--- --- ---//

    $(".btnExportAnotherAssignment").attr("data-id-group", id_group);
    $(".btnExportAnotherAssignment").attr("data-id-period", id_period);
    $(".btnExportAnotherAssignment").attr(
      "data-id-academic-area",
      id_academic_area
    );
    $(".btnExportAnotherAssignment").attr("data-id-subject", id_subject);
    $(".btnExportAnotherAssignment").attr("data-id-assignment", id_assignment);
    //--- --- ---//
    Swal.close();
  });

  //--- --- ---//

  $(document).on("click", ".btnExportAnotherAssignment", function (e) {
    //--- --- ---//
    var id_subject = $("#exportable_subject").val();
    var id_group = $(this).attr("data-id-group");
    var id_period = $(this).attr("data-id-period");
    var id_academic_area = $(this).attr("data-id-academic-area");
    var id_assignment = $(this).attr("data-id-assignment");
    //--- --- ---//
    loading();

    //--- --- ---//
    $.ajax({
      url: "php/controllers/evaluation_plan_controller.php",
      method: "POST",
      data: {
        mod: "exportToAnotherAssignment",
        id_subject: id_subject,
        id_period: id_period,
        id_academic_area: id_academic_area,
        id_assignment: id_assignment,
        id_group: id_group,
      },
    })
      .done(function (data) {
        Swal.close();
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
          Swal.fire("Éxito!", data.message, "success");

          var myOpts = document.getElementById(
            "select_ExportableGroups"
          ).options;
          var myOptsSubject = document.getElementById(
            "exportable_subject"
            ).options;


          if (myOpts.length == 2) {
            $("#exportable_subject option:selected").remove();
            $("#select_ExportableGroups option:selected").remove();
            $("#select_ExportableGroups").val("");
            $("#exportable_subject").val("");
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
