//--- --- ---//
//--- --- ---//
//--- --- ---//
$(document).ready(function () {
    $("#week_picker")
      .datepicker({
        autoclose: true,
        format: "YYYY-MM-DD",
        forceParse: false,
      })
      .on("changeDate", function (e) {
        //console.log(e.date);
        var date = e.date;
        startDate = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate() - date.getDay()
        );
        endDate = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate() - date.getDay() + 6
        );
        //$('#week_picker').datepicker("setDate", startDate);
        $("#week_picker").datepicker("update", startDate);
        $("#week_picker").val(
          startDate.getMonth() +
            1 +
            "/" +
            startDate.getDate() +
            "/" +
            startDate.getFullYear() +
            "-" +
            (endDate.getMonth() + 1) +
            "/" +
            endDate.getDate() +
            "/" +
            endDate.getFullYear()
        );
        $("#get_week_attendance").show();
      });
  
    $("#week_picker_teacher")
      .datepicker({
        autoclose: true,
        format: "YYYY-MM-DD",
        forceParse: false,
      })
      .on("changeDate", function (e) {
        //console.log(e.date);
        var date = e.date;
        startDate = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate() - date.getDay()
        );
        endDate = new Date(
          date.getFullYear(),
          date.getMonth(),
          date.getDate() - date.getDay() + 6
        );
        //$('#week_picker_teacher').datepicker("setDate", startDate);
        $("#week_picker_teacher").datepicker("update", startDate);
        $("#week_picker_teacher").val(
          startDate.getMonth() +
            1 +
            "/" +
            startDate.getDate() +
            "/" +
            startDate.getFullYear() +
            "-" +
            (endDate.getMonth() + 1) +
            "/" +
            endDate.getDate() +
            "/" +
            endDate.getFullYear()
        );
        $("#get_week_attendance_teacher").show();
      });
  });
  $(document).on("click", "#get_week_attendance", function () {
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has("submodule")) {
      //--- --- ---//
      //const id_assignment = $("#id_group option:selected").attr('id');
      const id_group = $("#id_group").val();
      const week_picker = $("#week_picker").val();
      console.log(week_picker);
      const submodule = urlParams.get("submodule");
      window.location.search =
        "submodule=" +
        submodule +
        "&id_group=" +
        id_group +
        "&week=" +
        week_picker;
      //--- --- ---//
    }
    if (urlParams.has("id_group")) {
      $("#get_week_attendance").show();
    }
  });
  
  $(document).on("change", "#id_academic__level", function () {
    var id_academic_level = $(this).val();
    getGroups(id_academic_level);
  });

  $(document).on("click", "#get_week_attendance_teacher", function () {
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has("submodule")) {
      //--- --- ---//
      const id_academic_level = $("#id_academic__level").val();
      const id_group = $("#id_group").val();
      const week = $("#week_picker_teacher").val();
      const submodule = urlParams.get("submodule");
      window.location.search =
        "submodule=" +
        submodule +
        "&id_academic_level=" +
        id_academic_level +
        "&id_group=" +
        id_group +
        
        "&week=" +
        week;
      //--- --- ---//
    }
  });
  function getPassedAttendanceDetails(arr_ids_attendance_index, no_teacher) {
    //console.log(arr_ids_attendance_index);
    //console.log(id_student);
    data = "";
  
    var day_list = arr_ids_attendance_index.split(",");
    var c = day_list.length;
    if (c > 1) {
      $.ajax({
        url: "php/controllers/students.php",
        method: "POST",
        data: {
          mod: "getTeacherAttendanceDetailsJSON",
          ids_attendance_index: arr_ids_attendance_index,
          no_teacher: no_teacher,
        },
      })
        .done(function (info) {
          info = $.parseJSON(info);
          // console.log(info);
          Swal.fire({
            title: "<h2>PASES DE LISTA</h2>",
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
        title: "<strong>DETALLE DE AUSENCIAS</strong>",
        icon: "info",
        html: "<h3>Este alumno no cuenta con inasistencias</h3>",
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: "Aceptar",
      });
    }
  }
  //--- --- ---//
  /* $(document).on('click', '.btn_std_attendance', function () {
      id_std = $(this).attr('id');
      console.log(id_std);
  
      Swal.fire({
          title: '<strong>Detalle de asistencia</strong>',
          icon: 'info',
          html:
              'You can use <b>bold text</b>, ' +
              '<a href="//sweetalert2.github.io">links</a> ' +
              'and other HTML tags',
          showCloseButton: true,
          showCancelButton: true,
          focusConfirm: false,
          confirmButtonText:
              '<i class="fa fa-thumbs-up"></i> Great!',
          confirmButtonAriaLabel: 'Thumbs up, great!',
          cancelButtonText:
              '<i class="fa fa-thumbs-down"></i>',
          cancelButtonAriaLabel: 'Thumbs down'
      })
  }); */
  
  function getGroups(id_academic_level) {
    //--- --- ---//
    loading();
    $("#id_group").html("");
    //--- --- ---//
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getGroupsByIdAcademicLevel",
        id_academic_level:id_academic_level
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
              data.data[i].id_group +
              '">' +
              data.data[i].group_code +
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
        $("#id_group").html(options);
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
  function getAssistanceDetails(
    id_student,
    date,
    student_code,
    student_name,
    id_group
  ) {
    loading();
    var group_name = $("#txt_grupo").text();
    arr_date = date.split("-");
    date_format = arr_date[2] + "/" + arr_date[1] + "/" + arr_date[0];
    loading();
  
    //--- --- ---//
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getAssistanceDetails",
        id_student: id_student,
        date: date,
        student_code: student_code,
        student_name: student_name,
        id_group: id_group,
      },
    })
      .done(function (data) {
        data = $.parseJSON(data);
        console.log(data.data);
  
        Swal.fire({
          title: "<strong>Detalle de asistencia</strong>",
          icon: "info",
          html:
            "" +
            student_code +
            " | " +
            "" +
            student_name +
            " | " +
            group_name +
            "</br>" +
            "" +
            date_format +
            "</b></br>" +
            "<b>Registro de clases:</b></br>" +
            "" +
            data.data +
            "",
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
        });
        /* var data = JSON.parse(data);
          var options = '<option selected value="" disabled>Elija una opción</option>';
          if (data.response) {
              for (var i = 0; i < data.data.length; i++) {
                  options += '<option value="' + data.data[i].id_group + '">' + data.data[i].group_code + '</option>';
              }
          } else {
              VanillaToasts.create({
                  title: 'Error',
                  text: data.message,
                  type: 'error',
                  timeout: 1200,
                  positionClass: 'topRight'
              });
          }
          //--- --- ---//
          $('#id_group').html(options);
          swal.close(); */
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
  //--- --- ---//
  $(document).on("click", ".btn_teacher_assignments", function () {
    loading();
    var id_teacher = $(this).attr("id-teacher");
    var id_academic_level = $("#academic_level").val();
    var id_academic_area = $(this).attr("data-id-academic-area");
    $.ajax({
      url: "php/controllers/academic_mesh.php",
      method: "POST",
      data: {
        mod: "getTeacherAssignments",
        id_teacher: id_teacher,
        id_academic_level: id_academic_level,
        id_academic_area: id_academic_area,
      },
    })
      .done(function (data) {
        // console.log(data);
        Swal.close();
        var data = JSON.parse(data);
        if (data.response) {
          var html_sweet_alert = "<h2>" + data.data[0].teacher_name + "</h2>";
          html_sweet_alert +=
            '<div style="height: 500px; overflow: auto;"><table class="table align-items-center table-flush" id="tablaAsignaturas">';
          html_sweet_alert += '<thead class="thead-light">';
          html_sweet_alert += "<tr>";
          html_sweet_alert += "<th onclick='sortTable(0)'>Materia</th>";
          html_sweet_alert += "<th onclick='sortTable(0)'>Grupo</th>";
          html_sweet_alert += "</tr>";
          html_sweet_alert += "</thead>";
          html_sweet_alert += "<tbody class='list'>";
          for (var i = 0; i < data.data.length; i++) {
            html_sweet_alert += "<tr id='" + data.data[i].id_assignment + "'>";
            html_sweet_alert += "<td>" + data.data[i].name_subject + "</td>";
            html_sweet_alert += "<td>" + data.data[i].group_code + "</td>";
            html_sweet_alert += "</tr>";
          }
          html_sweet_alert += "</tbody>";
          html_sweet_alert += "</table></div>";
          Swal.fire({
            title: "Asignaturas",
            html: html_sweet_alert,
            showCancelButton: false,
            customClass: "swal-wide",
            confirmButtonText: "Aceptar",
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
        //swal.close();
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
  
  $(document).on("click", ".PasslistDetail", function () {
    loading();
    var ids_attendance_index = $(this).attr("data-id-index");
    /*   console.log(ids_attendance_index); */
  
    //--- --- ---//
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getPassListDetails",
        id_attendance_index: ids_attendance_index,
      },
    })
      .done(function (data) {
        data = $.parseJSON(data);
        /* console.log(data.data); */
  
        Swal.fire({
          title: "<strong>DETALLE DE PASE DE LISTA</strong>",
          icon: "info",
          html: data.data + "",
          showCloseButton: true,
          focusConfirm: false,
          width: "800px",
          confirmButtonText: "Aceptar",
        });
  
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
  
  $(document).on("click", ".attendanceStdDetail", function () {
    loading();
    var ids_attendance_record = $(this).attr("data-id-attendance-record");
    /* console.log(ids_attendance_index); */
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getAttendanceRecordDetails",
        id_attendance_record: ids_attendance_record,
      },
    })
      .done(function (data) {
        data = $.parseJSON(data);
        /* console.log(data.data); */
  
        Swal.fire({
          title: "<strong>DETALLE DE ASISTENCIA</strong>",
          icon: "info",
          html: data.data + "",
          showCloseButton: true,
          focusConfirm: false,
          width: "800px",
          confirmButtonText: "Aceptar",
        });
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
  /* if ($('#tStudentsReport').length > 0) {
      var tf = new TableFilter('tStudentsReport', {
          base_path: '../general/js/vendor/tablefilter/tablefilter/',
          col_0: '',
          //col_1: 'select',
          col_2: 'select',
          col_3: 'select',
          col_4: 'select',
          col_5: 'select',
          col_6: 'select',
          auto_filter: {
              delay: 100 //milliseconds
          },
          btn_reset: true,
      });
      tf.init();
  }*/
  //--- --- ---//
  