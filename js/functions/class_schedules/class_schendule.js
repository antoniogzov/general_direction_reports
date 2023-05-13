$(document).ready(function () {
  $("#select_subject").select2({
    dropdownParent: $("#setSubject"),
  });

  $("#select_aula").select2({
    dropdownParent: $("#setClassroom"),
  });

  $(document).on("click", ".btnSetSubject", function () {
    const id_day = $(this).attr("data-id-day");
    const id_block = $(this).attr("data-id-block");
    const no_block = $(this).attr("data-no-block");
    const name_day = $(this).attr("data-name-day");
    const id_period_calendar = $(this).attr("data-id-period-calendar");
    const id_academic_level = $(this).attr("data-id-academic-level");
    const id_teacher_sbj = $("#id_teacher_sbj").val();
    $.ajax({
      url: "php/controllers/class_schedules.php",
      method: "POST",
      data: {
        mod: "getAviablesAssignments",
        id_day: id_day,
        id_block: id_block,
        id_teacher_sbj: id_teacher_sbj,
        id_period_calendar: id_period_calendar,
        id_academic_level: id_academic_level,
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
              data.data[i].id_assignment +
              '">' +
              data.data[i].group_code +
              " | " +
              data.data[i].name_subject;
            ("</option>");
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
        $("#select_assignment").empty();
        $("#select_assignment").html(options);

        var title_modal_select =
          "Seleccione materia para día " + name_day + " - Bloque " + no_block;

        $("#title_modal_select").text(title_modal_select);
        $("#title_modal_select").attr("id_day", id_day);
        $("#title_modal_select").attr("id_block", id_block);
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
      });
  });

  $(document).on("click", ".btnSetAssignment", function () {
    loading();
    var id_day = $("#title_modal_select").attr("id_day");
    var id_block = $("#title_modal_select").attr("id_block");
    const id_teacher_sbj = $("#id_teacher_sbj").val();
    const id_assignment = $("#select_assignment").val();
    const id_period_calendar = $("#id_period").val();

    $.ajax({
      url: "php/controllers/class_schedules.php",
      method: "POST",
      data: {
        mod: "saveRelationShipCBAS",
        id_day: id_day,
        id_block: id_block,
        id_teacher_sbj: id_teacher_sbj,
        id_assignment: id_assignment,
        id_period_calendar: id_period_calendar,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        if (data.response == true) {
          const description_assignment = $(
            "#select_assignment option:selected"
          ).text();

          arr_descr = description_assignment.split("|");
          var name_gps = arr_descr[0];
          var name_assg = arr_descr[1];
          if (name_assg.length > 18) {
            name_assg = replaceString(name_assg);
          }

          $("#ButtonAssignmentBlock" + id_block + "Day" + id_day).html(
            name_gps + "<br>" + name_assg
          );
          $("#setSubject").modal("hide");

          Swal.fire({
            title: data.message,
            icon: data.icon,
            confirmButtonText: "Aceptar",
          });
        } else {
          Swal.fire({
            title: data.message,
            html: data.text,
            icon: data.icon,
            confirmButtonText: "Aceptar",
          });
        }
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

  function replaceString(string) {
    string = string.toUpperCase();
    var salida = "";
    var count = 0;
    for (i = 0; i < string.length; i++) {
      count++;
      if (count > 18) {
        count = 0;
        if (string.charAt(i) == " ") {
          salida += "<br>";
        } else {
          salida += string.charAt(i) + "-" + "<br>";
        }
      } else {
        salida += string.charAt(i);
      }
    }
    return salida;
  }

  $(document).on("click", ".btnSetClassroom", function () {
    const id_day = $(this).attr("data-id-day");
    const id_block = $(this).attr("data-id-block");
    const no_block = $(this).attr("data-no-block");
    const name_day = $(this).attr("data-name-day");
    const id_teacher_sbj = $("#id_teacher_sbj").val();
    const id_period_calendar = $("#id_period").val();

    $.ajax({
      url: "php/controllers/class_schedules.php",
      method: "POST",
      data: {
        mod: "getAviablesClassrooms",
        id_day: id_day,
        id_block: id_block,
        id_teacher_sbj: id_teacher_sbj,
        id_period_calendar:id_period_calendar
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
              data.data[i].id_classrooms +
              '">' +
              data.data[i].name_classroom;
            ("</option>");
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
        $("#select_aula").empty();
        $("#select_aula").html(options);

        var title_modal_classroom =
          "Seleccione aula para día " + name_day + " - Bloque " + no_block;

        $("#title_modal_classroom").text(title_modal_classroom);
        $("#title_modal_classroom").attr("id_day", id_day);
        $("#title_modal_classroom").attr("id_block", id_block);
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
      });
  });

  $(document).on("click", ".btnSetAulas", function () {
    var id_day = $("#title_modal_classroom").attr("id_day");
    var id_block = $("#title_modal_classroom").attr("id_block");

    const id_teacher_sbj = $("#id_teacher_sbj").val();
    const id_classroom = $("#select_aula").val();
    const id_period_calendar = $("#id_period").val();

    $.ajax({
      url: "php/controllers/class_schedules.php",
      method: "POST",
      data: {
        mod: "saveRelationShipCBASClassroom",
        id_day: id_day,
        id_block: id_block,
        id_teacher_sbj: id_teacher_sbj,
        id_classroom: id_classroom,
        id_period_calendar:id_period_calendar
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        if (data.response == true) {
          const description_classroom = $(
            "#select_aula option:selected"
          ).text();

          $("#ButtonClassroomBlock" + id_block + "Day" + id_day).html(
            description_classroom
          );
          $("#setClassroom").modal("hide");
          Swal.fire({
            title: data.message,
            icon: data.icon,
            confirmButtonText: "Aceptar",
          });
        } else {
          console.log("asdafasd");
          Swal.fire({
            title: data.message,
            icon: data.icon,
            confirmButtonText: "Aceptar",
          });
        }
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

  $(document).on("change", "#id_academic", function () {
    $("#div_tabla").empty();
    var id_academic = $(this).val();
    getTeachers(id_academic);
  });

  $(document).on("change", "#id_period", function () {
    $("#div_tabla").empty();
    var id_academic = $("#id_academic").val();
    var id_teacher = $("#id_teacher_sbj").val();
    var id_academic_level = $("#id_academic_level").val();
    var id_period = $(this).val();

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
        "&id_teacher_sbj=" +
        id_teacher +
        "&id_academic_level=" +
        id_academic_level +
        "&id_period=" +
        id_period;
      //--- --- ---//
    }
  });

  $(document).on("change", "#id_teacher_sbj", function () {
    loading();
    var id_teacher = $(this).val();
    $.ajax({
      url: "php/controllers/class_schedules.php",
      method: "POST",
      data: {
        mod: "getAcademicLevelsTeacher",
        id_teacher_sbj: id_teacher,
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
              data.data[i].id_academic_level +
              '">' +
              data.data[i].academic_level +
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
  $(document).on("change", "#id_academic_level", function () {
    loading();
    var id_academic_level = $(this).val();
    var id_teacher = $("#id_teacher_sbj").val();
    var id_academic = $("#id_academic").val();

    $.ajax({
      url: "php/controllers/class_schedules.php",
      method: "POST",
      data: {
        mod: "getPeriods",
        id_academic_level: id_academic_level,
        id_teacher: id_teacher,
        id_academic: id_academic,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        var options =
          '<option selected value="" disabled>Elija una opción</option>';
        options += '<option value="all">TODOS LOS PERIODOS</option>';
        if (data.response) {
          for (var i = 0; i < data.data.length; i++) {
            options +=
              '<option value="' +
              data.data[i].id_period_calendar +
              '">' +
              data.data[i].no_period +
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
        $("#id_period").empty();
        $("#id_period").html(options);
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
  function getTeachers(id_academic) {
    //--- --- ---//
    loading();
    $("#id_teacher_sbj").html("");
    //--- --- ---//
    $.ajax({
      url: "php/controllers/class_schedules.php",
      method: "POST",
      data: {
        mod: "getTeachersByGroup",
        id_academic: id_academic,
      },
    })
      .done(function (data) {
        console.log(data);
        var data = JSON.parse(data);
        var options =
          '<option selected value="" disabled>Elija una opción</option>';
        if (data.response) {
          for (var i = 0; i < data.data.length; i++) {
            options +=
              '<option value="' +
              data.data[i].no_colaborador +
              '">' +
              data.data[i].teacher_name +
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
        $("#id_teacher_sbj").html(options);
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
      });
  }

  function getSubjectsTeacher(id_teacher, id_day, id_block) {
    loading();
    if (grade_period != "-") {
      $.ajax({
        url: "php/controllers/students.php",
        method: "POST",
        data: {
          mod: "getCriteriaDetailsArchive",
          id_student: id_student,
          id_assignment: id_assignment,
          id_period_calendar: id_period_calendar,
          id_group: id_group,
        },
      })
        .done(function (info) {
          info = $.parseJSON(info);
          // console.log(info);
          Swal.fire({
            title: "<h2>DESGLOSE DE CALIFICACIÓN</h2>",
            icon: "info",
            html: info.data,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: "Aceptar",
          });
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
    } else {
      Swal.fire({
        title: "<strong>DESGLOSE DE CALIFICACIÓN</strong>",
        icon: "info",
        html: "<h3>Aún no se asigna calificación para este periodo</h3>",
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: "Aceptar",
      });
    }
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
});
