//getGroups2();
//--- --- ---//
var d = new Date(),
    month = '' + (d.getMonth() + 1),
    day = '' + d.getDate(),
    year = d.getFullYear();
if (month.length < 2) month = '0' + month;
if (day.length < 2) day = '0' + day;
const today = [year, month, day].join('-');
//--- --- ---//
$('.date-input').val(today);
//--- --- ---//
$(document).on('click', '.new_incident', function() {
    var id_student = $(this).attr('id');
    $('.btn_save_incident').attr('id', id_student);
    $('#newIncident').modal('show');

    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'StudentInfoByID',
            id_student: id_student
        }
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
                $('#txt_modal_incidence').text(data.data[i].student_name);

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

        swal.close();
        //--- --- ---//
    }).fail(function(message) {
        VanillaToasts.create({
            title: 'Error',
            text: 'Ocurrió un error, intentelo nuevamente',
            type: 'error',
            timeout: 1200,
            positionClass: 'topRight'
        });
    });

});
$(document).on("click", ".new_incident_teacher", function () {
  var id_student = $(this).attr("id");

      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "getStudentsSubjectsTeachers",
          id_student: id_student,
        },
      })
        .done(function (data) {
          //console.log(json);
          var data = JSON.parse(data);
          if (data.response) {
            //--- --- ---//
            var html =
              "<option value='' selected disabled>Seleccione una materia</option>";
            subjects = data.data;
            for (let i = 0; i < subjects.length; i++) {
              html +=
                "<option value='" +
                subjects[i].id_subject +
                "'>" +
                subjects[i].name_subject +
                "</option>";
            }
            $("#id_subject_incident").html(html);
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
$("#incident_commit").keypress(function (e) {
    var Max_Length = $("#incident_commit").attr("maxlength");
    var length = $("#incident_commit").val().length;
    var available = Max_Length - length;
    var text = "Dispone de " +
      available +
        " caracteres para describir lo ocurrido.";
    $("#lbl_longitud").text("");
    $("#lbl_longitud").text(text);
   
  });
$(document).on('click', '.btn_save_incident', function() {
    var incident = $('input[name=incident_list]:checked', '#form_incidents').val();
    var commit = $('#incident_commit').val();
    var date = $('#incident_date').val();
    var id_assignment = $("#id_subject_incident").val();
    var id_student = $(this).attr('id');
    id_student = $(this).attr('id');
    if (incident == undefined) {
        swal.close();
        swal.fire({
            icon: 'error',
            title: 'Seleccione una incidencia'
        });
    } else if (date == undefined) {
        swal.close();
        swal.fire({
            icon: 'error',
            title: 'Seleccione una fecha'
        });

    } else if (commit == '') {
        swal.close();
        swal.fire({
            icon: 'error',
            title: 'Ingrese un comentario'
        });
    } else {

        loading();
        $.ajax({
            url: 'php/controllers/students.php',
            method: 'POST',
            data: {
                mod: 'SaveIncident',
                id_student: id_student,
                incident: incident,
                commit: commit,
                date: date,
                id_assignment
            }
        }).done(function(data) {
            console.log(data);

            swal.close();
            swal.fire({
                icon: 'success',
                title: "INCIDENCIA REGISTRADA CON ÉXITO"
            });
            $('#newIncident').modal('hide');

            $('#' + incident).prop('checked', false);
            $('#incident_commit').val('');
            $('#incident_date').val('');
            //--- --- ---//
        }).fail(function(message) {
            VanillaToasts.create({
                title: 'Error',
                text: 'Ocurrió un error, intentelo nuevamente',
                type: 'error',
                timeout: 1200,
                positionClass: 'topRight'
            });
        });
    }

});
$('#week_picker').datepicker({
    autoclose: true,
    format: 'YYYY-MM-DD',
    forceParse: false
}).on("changeDate", function(e) {
    //console.log(e.date);
    var date = e.date;
    startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
    endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
    //$('#week_picker').datepicker("setDate", startDate);
    $('#week_picker').datepicker('update', startDate);
    $('#week_picker').val((startDate.getMonth() + 1) + '/' + startDate.getDate() + '/' + startDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '/' + endDate.getDate() + '/' + endDate.getFullYear());
    $('#get_week_attendance').show();
});
//--- --- ---//

$(document).on('change', '#id_group', function() {
    var id_group = $(this).val();
    loading();
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has('submodule')) {
        //--- --- ---//

        const submodule = urlParams.get('submodule');
        window.location.search = 'submodule=' + submodule + '&id_group=' + id_group;
    }

    loading();
});

$(document).on("change", "#check_subject_incident", function () {
    if (this.checked) {
      $("#div_materia_incidente").show();
      $("#id_subject_incident").val("");
    } else {
        $("#id_subject_incident").val("");
        $("#div_materia_incidente").hide();
      //$("#guardar_gasto").prop("disabled", false);
    }
  });
//--- --- ---//

//--- --- ---//
//--- --- ---//
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
//--- --- ---//