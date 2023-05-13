$(document).ready(function () {
  $(document).on("change", "#id_academic", function () {
    var id_academic = $(this).val();

    var url = window.location.search;
    const urlParams = new URLSearchParams(url);

    if (urlParams.has("submodule")) {
      //--- --- ---//
      loading();
      const submodule = urlParams.get("submodule");
      window.location.search =
        "submodule=" + submodule + "&id_academic=" + id_academic;
      //--- --- ---//
    }
    /*  $.ajax({
      url: "php/controllers/admisions.php",
      method: "POST",
      data: {
        mod: "getAcademicLevels",
        id_academic: id_academic,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        var options =
          '<option selected value="" disabled>Elija una opción</option>';
        if (data.response) {
          for (var i = 0; i < data.data.length; i++) {
            options +=
              '<option value="' +
              data.data[i].id_level_grade +
              '">' +
              data.data[i].degree +
              "</option>";
          }
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
        $("#id_academic_level").empty();
        $("#id_academic_level").html(options);
        swal.close();
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
      }); */

    /*  if (urlParams.has("submodule")) {
        //--- --- ---//
        loading();
        const submodule = urlParams.get("submodule");
        window.location.search =
          "submodule=" +
          submodule +
          "&id_academic=" +
          id_academic +
          "&id_teacher_sbj=" +
          id_teacher;
        //--- --- ---//
      } */
  });

  $(document).on("change", "#id_academic_level", function () {
    var id_academic = $("#id_academic").val();
    var id_academic_level = $(this).val();
    loading();
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);

    if (urlParams.has("submodule")) {
      //--- --- ---//
      loading();
      const submodule = urlParams.get("submodule");
      window.location.search =
        "submodule=" +
        submodule +
        "&id_academic=" +
        id_academic +
        "&id_academic_level=" +
        id_academic_level;
      //--- --- ---//
    }
  });

  $(document).on("click", ".btnInfoStudent", function () {
    var id_student = $(this).attr("data-id-student");
    //--- --- ---//

    $.ajax({
      url: "php/controllers/admisions.php",
      method: "POST",
      data: {
        mod: "getStudentsInfo",
        id_student: id_student,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        var options =
          '<option selected value="" disabled>Elija una opción</option>';
        if (data.response) {
          Swal.fire({
            title: "INFOMRACIÓN DE ALUMNO",
            html: data.html,
            width: "1200px",
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

    /*  if (urlParams.has("submodule")) {
        //--- --- ---//
        loading();
        const submodule = urlParams.get("submodule");
        window.location.search =
          "submodule=" +
          submodule +
          "&id_academic=" +
          id_academic +
          "&id_teacher_sbj=" +
          id_teacher;
        //--- --- ---//
      } */
  });

  $(document).on("change", ".repeatSchoolCycle", function () {
    loading();
    var id_student = $(this).attr("data-id-student");
    var student_code = $(this).attr("data-student-code");
    //--- --- ---//
    var checked = 0;
    if (this.checked) {
      checked = 1;
    } else {
      checked = 0;
    }

    $.ajax({
      url: "php/controllers/admisions.php",
      method: "POST",
      data: {
        mod: "setRepeatCycle",
        id_student: id_student,
        checked: checked,
        student_code:student_code,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        var options =
          '<option selected value="" disabled>Elija una opción</option>';
        if (data.response) {
          Swal.fire({
            title: data.message,
            icon: data.icon,
            timer: 1000,
            showConfirmButton: false,
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

    /*  if (urlParams.has("submodule")) {
        //--- --- ---//
        loading();
        const submodule = urlParams.get("submodule");
        window.location.search =
          "submodule=" +
          submodule +
          "&id_academic=" +
          id_academic +
          "&id_teacher_sbj=" +
          id_teacher;
        //--- --- ---//
      } */
  });

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
