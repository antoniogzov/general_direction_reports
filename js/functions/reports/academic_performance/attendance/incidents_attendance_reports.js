$(document).ready(function () {
  $("#search_date").datepicker("setDate", new Date());
  $("#search_date").datepicker({
    autoclose: true,
    format: "dd/mm/yyyy",
    forceParse: false,
    endDate: "+1d",
  });

  $(document).on("click", "#btn_search", function () {
    var date_search = $("#search_date").val();
    var type_incident = $("#type_incident").val();
    console.log(date_search);
    if (date_search.indexOf("/") >= 0) {
      console.log("formato alternativo");
      arr_date_search = date_search.split("/");
      date_search =
        arr_date_search[2] +
        "-" +
        arr_date_search[0] +
        "-" +
        arr_date_search[1];
    }
    if (
      type_incident == "" ||
      type_incident == null ||
      type_incident == undefined
    ) {
      Swal.fire({
        title: "Error",
        text: "Debe seleccionar un tipo de incidencia",
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    } else {
      loading();
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "getAbsencesByDateAndType",
          date_search: date_search,
          type_incident: type_incident,
        },
      })
        .done(function (json) {
          //console.log(json);
          var json = JSON.parse(json);
          if (json != "") {
            Swal.close();
            //--- --- ---//
            if (json.response) {
              //--- --- ---//
              $("#student_list_container").empty();
              $("#student_list_container").append(json.html);
              //--- --- ---/
            } else {
              $("#student_list_container").empty();
              $("#student_list_container").append(json.html);
            }
            //--- --- ---//
            TableProperties();
          } else {
            swal(
              "Atención!",
              "Ocurrió un error al registrar la justificación",
              "error"
            );
          }
        })
        .fail(function () {
          swal(
            "Error!",
            "Error al intentar conectarse con la Base de Datos :/",
            "error"
          );
        });
    }
  });

  $(document).on("click", ".btnBreakdownAbsence", function () {
    var id_student = $(this).attr("id-student");
    var today_date = $(this).attr("data-today-date");
    var type_incident = $("#type_incident").val();

    if (id_student != null && today_date != null) {
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "breakdownAbsencesHistorical",
          id_student: id_student,
          type_incident: type_incident,
        },
      })
        .done(function (json) {
          var json = JSON.parse(json);
          if (json != "") {
            //--- --- ---//
            if (json.response) {
              //--- --- ---//
              //--- --- ---//
              Swal.fire({
                title: "Faltas registradas",
                icon: "info",
                html: json.html_sweet_alert,
                showCancelButton: false,
                width: "800px",
              }).then((result) => {
                //window.location.reload();
              });
              //--- --- ---/
            } else {
              swal(
                "Atención!",
                "No se pudieron guardar los datos :( intentelo nuevamente",
                "error"
              );
            }
            //--- --- ---//
          } else {
            swal(
              "Atención!",
              "Ocurrió un error al registrar la justificación",
              "error"
            );
          }
        })
        .fail(function () {
          swal(
            "Error!",
            "Error al intentar conectarse con la Base de Datos :/",
            "error"
          );
        });
    } else {
      Swal.fire({
        icon: "error",
        title: "Debe completar todos los campos requeridos",
      });
    }
  });

  $(document).on("click", ".btnSubjectsAbsences", function () {
    var id_student = $(this).attr("id-student");
    var absence_date = $(this).attr("data-absence-date");
    loading();
    $.ajax({
      url: "php/controllers/justifyController.php",
      method: "POST",
      data: {
        mod: "getSubjectsByAbsence",
        id_student: id_student,
        absence_date: absence_date,
      },
    })
      .done(function (json) {
        //console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
          Swal.close();
          //--- --- ---//
          if (json.response) {
            //--- --- ---//
            Swal.fire({
              title: "",
              text: "",
              html: json.html,
              showCancelButton: false,
              showConfirmButton: true,
              focusConfirm: false,
              confirmButtonText: "Cerrar",
              cancelButtonColor: "#3085d6",
              confirmButtonColor: "#3085d6",
              allowOutsideClick: false,
            });
            //--- --- ---/
          } else {
            $("#student_list_container").empty();
            $("#student_list_container").append(json.html);
          }
          //--- --- ---//
        } else {
          swal(
            "Atención!",
            "Ocurrió un error al registrar la justificación",
            "error"
          );
        }
      })
      .fail(function () {
        swal(
          "Error!",
          "Error al intentar conectarse con la Base de Datos :/",
          "error"
        );
      });
  });

  $(document).on("click", ".getStudentContactInfo", function () {
    var id_student = $(this).attr("data-id_student");
    $(".btn_save_incident").attr("id", id_student);
    loading();
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "StudentContactInfo",
        id_student: id_student,
      },
    })
      .done(function (data) {
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
          swal.close();
          // console.log(data.data);
          Swal.fire({
            title: "",
            icon: "info",
            html: data.data,
            width: "900px",
          });
        } else {
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
          });
        }
        //--- --- ---//

        //--- --- ---//
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
  });

  $(document).on("click", ".btnJustifyJS", function () {
    var code_student = $(this).attr("code-student");
    var name_student = $(this).attr("name-student");
    var id_student = $(this).attr("id-student");
    $(".btn_SaveJustify").attr("id-student", id_student);

    $("#lbl_std_name").text(code_student + " | " + name_student);
  });

  $(document).on("change", "#type_incident", function () {
    $("#student_list_container").empty();
  });

  $(document).on("click", ".getStudentContactInfo", function () {
    var id_student = $(this).attr("data-id_student");
    $(".btn_save_incident").attr("id", id_student);
    loading();
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "StudentContactInfo",
        id_student: id_student,
      },
    })
      .done(function (data) {
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
          swal.close();
          // console.log(data.data);
          Swal.fire({
            title: "",
            icon: "info",
            html: data.data,
            width: "900px",
          });
        } else {
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
          });
        }
        //--- --- ---//

        //--- --- ---//
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
  });
  function TableProperties() {
    if ($("#tabResults").length > 0) {
      var tf = new TableFilter("tabResults", {
        base_path: "../general/js/vendor/tablefilter/tablefilter/",
        col_0: "",
        //col_1: 'select',
        col_2: "select",
        col_3: "select",
        col_4: "select",
        col_5: "select",
        col_6: "select",
        auto_filter: {
          delay: 100, //milliseconds
        },
        btn_reset: true,
      });
      //tf.init();

      $("#tabResults").DataTable({
        colReorder: false,
        dom: "Bfrtip",
        lengthMenu: [
          [40, 25, 50, -1],
          ["10 rows", "25 rows", "50 rows", "Show all"],
        ],
        buttons: ["excel"],
      });
    }
  }
});
