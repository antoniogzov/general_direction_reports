//--- --- ---//
$('.date-input').datepicker({
    format: 'yyyy-mm-dd'
});
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
$(document).on('click', '#btn_search_attendance', function() {
    var date_search = $('.date-input').val();
    if (validate(date_search) && date_search != '') {
        var id_assignment = $('#id_subject option:selected').attr('id');
        searchAttendance(date_search, id_assignment);
    } else {
        Swal.fire('Atención!', 'Ingrese una fecha correcta :D', 'info')
    }
});
//--- --- ---//
$(document).on('change', '#id_subject', function() {

});
//--- --- ---//
$(document).on('click', '.btn_new_incident', function(e) {
    //--- --- ---//
    e.preventDefault();
    var id_attendance_record = $(this).attr('id');
    //--- --- ---//
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'getStudentInfo',
            id_attendance_record: id_attendance_record
        }
    }).done(function(json) {
        console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
            //--- --- ---//
            if (json.response) {
                //--- --- ---//
                $('#datos_alumno').text(json.data[0].student_code + ' | ' + json.data[0].student_name);
                $('.btn_add_incident').attr('id', id_attendance_record);
                $('#incidencia_seleccion').val(json.data[0].incident);
                $('#id_incident').val(json.data[0].incident_id);
                $('#addIncident').modal('show');

                /* const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: json.message
                })
 */
                //--- --- ---/
            } else {
                swal('Atención!', 'No se pudieron actualizar los datos :( intentelo nuevamente', 'error');
            }
            //--- --- ---//
        } else {
            swal('Atención!', 'Ocurrió un error al registrar asistencias', 'error');
        }
    }).fail(function() {
        swal('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
});

$(document).on('click', '.btn_add_incident', function(e) {
    //--- --- ---//
    e.preventDefault();
    var id_attendance_record = $(this).attr('id');
    var incident_id = $('#id_incident').val();
    //--- --- ---//
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'UpdateWeekAttendanceStudentsIncident',
            id_attendance_record: id_attendance_record,
            incident_id: incident_id
        }
    }).done(function(json) {
        console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
            //--- --- ---//
            if (json.response) {
                //--- --- ---//
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                $('#addIncident').modal('hide');
                $('.modal-backdrop').modal('hide');

                Toast.fire({
                    icon: 'success',
                    title: json.message
                })

                //--- --- ---/
            } else {
                swal('Atención!', 'No se pudieron actualizar los datos :( intentelo nuevamente', 'error');
            }
            //--- --- ---//
        } else {
            swal('Atención!', 'Ocurrió un error al registrar asistencias', 'error');
        }
    }).fail(function() {
        swal('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
});
$(document).on('change', '#id_group', function() {
    var id_group = $(this).val();
    getSubjectsByTeacher(id_group);
});
//--- --- ---//
$(document).on('click', '#btn_actualizar_asistencia', function(e) {
    //--- --- ---//
    e.preventDefault();
    var id_attendance_index = $(this).attr('data-id-index');
    var compulsory_class = $('.compulsory-class').is(":checked");
    var data = [];
    var presents = 0;
    var missing = 0;
    //--- --- ---//
    //--- --- ---//
    $('.check-student').each(function(i, obj) {
        //--- --- ---//
        if ($(this).is(":checked")) {
            presents++;
        } else {
            missing++;
        }
        //--- --- ---//
        var incident_id = $(this).closest('tr');
        incident_id = incident_id.find('.td-incidents-rollcall').find('.form-group').find('.select-incidents-rollcall');
        var attendance = {
                id_student: $(this).attr('id'),
                present: $(this).is(":checked"),
                incident_id: incident_id.val()
            }
            //--- --- ---//
        data.push(attendance);
        //--- --- ---//
    });
    //--- --- ---//
    if (data.length > 0) {
        Swal.fire({
            title: 'Atención!',
            icon: 'info',
            html: 'Se actualizará la asistencia: <br><br> Alumnos totales: <strong>' + $('.check-student').length + '</strong> <br> Alumnos presentes: <strong> ' + presents + '</strong> <br> Alumnos ausentes: <strong> ' + missing + ' </strong> <br> <br> <font color="red"> Registrará esta clase como: ' + (compulsory_class ? "Obligatoria" : "No obligatoria") + '</font>',
            showCancelButton: true,
            confirmButtonText: 'Guardar'
        }).then((result) => {
            if (result.isConfirmed) {
                updateAttendance(data, compulsory_class, id_attendance_index);
            }
        })
    }
    //--- --- ---//
});
//--- --- ---//
//--- --- ---//
$(document).on('click', '#get_week_attendance', function() {
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has('submodule')) {
        //--- --- ---//
        const id_group = $('#id_group').val();
        const id_subject = $('#id_subject').val();
        const week = $('#week_picker').val();
        const class_block = $('input[name="class_block"]:checked').val();
        const submodule = urlParams.get('submodule');
        window.location.search = 'submodule=' + submodule + '&id_subject=' + id_subject + '&id_group=' + id_group + '&week=' + week+ '&class_block=' + class_block;
        //--- --- ---//
    }
});
//--- --- ---//
$(document).on('change', '.check-student-update', function() {
    var id_attendance_record = $(this).attr('id');
    console.log(id_attendance_record);
    if ($(this).is(":checked")) {
        var present = 1;
    } else {
        var present = 0;

    }
    console.log('upda:' + present);
    UpdateAttendance(id_attendance_record, present);
});
$(document).on('change', '.check-student-insert', function() {
    var id_group = $('#id_group').val();
    var values = $(this).attr('id');
    var propieties = values.split('/');
    var class_change = $(this).attr("data-target");
    var id_assingment = propieties[0];
    var date = propieties[1];
    var id_student = propieties[2];
    var class_block = $('input[name="class_block"]:checked').val();

    if ($(this).is(":checked")) {
        var present = 1;
    } else {
        var present = 0;

    }
    console.log('insert:' + present);
    InsertAttendance(id_group, id_assingment, date, id_student, present, class_change, class_block);
});

function UpdateAttendance(id_attendance_record, attend) {
    //--- --- ---//
    // loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'UpdateWeekAttendanceStudents',
            attend: attend,
            id_attendance_record: id_attendance_record
        }
    }).done(function(json) {
        console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
            //--- --- ---//
            if (json.response) {
                //--- --- ---//
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: json.message
                })

                //--- --- ---/
            } else {
                swal('Atención!', 'No se pudieron actualizar los datos :( intentelo nuevamente', 'error');
            }
            //--- --- ---//
        } else {
            swal('Atención!', 'Ocurrió un error al registrar asistencias', 'error');
        }
    }).fail(function() {
        swal('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}

function InsertAttendance(id_group, id_assignment, date, id_student, present, class_change, class_block) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'InsertWeekAttendanceStudents',
            id_group: id_group,
            id_assignment: id_assignment,
            date: date,
            id_student: id_student,
            present: present,
            class_block: class_block

        }
    }).done(function(json) {
        console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
            //--- --- ---//
            if (json.response) {
                //--- --- ---//
                Swal.fire({
                        title: 'Atención!',
                        icon: 'info',
                        text: json.message,
                        showCancelButton: false,
                    }).then((result) => {
                        loading();
                        location.reload();

                        //$($(this).data("target")).hide();
                    })
                    //--- --- ---/
            } else {
                swal('Atención!', 'No se pudieron actualizar los datos :( intentelo nuevamente', 'error');
            }
            //--- --- ---//
        } else {
            swal('Atención!', 'Ocurrió un error al registrar asistencias', 'error');
        }
    }).fail(function() {
        swal('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}

//--- --- ---//
function getGroups(id_subject) {
    //--- --- ---//
    loading();
    $('#id_group').html('');
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'getGroups',
            id_subject: id_subject
        }
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
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
}

function getGroups2() {
    //--- --- ---//
    loading();
    $('#id_group').html('');
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'getGroups2'
        }
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
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
}

function getSubjectsByTeacher(id_group) {
    //--- --- ---//
    loading();
    $('#id_subject').html('');
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'getSubjectsByTeacher',
            id_group: id_group
        }
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
        var options = '<option selected value="" disabled>Elija una opción</option>';
        if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
                options += '<option id=' + data.data[i].id_assingment + ' value="' + data.data[i].id_subject + '">' + data.data[i].name_subject + '</option>';
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
        $('#id_subject').html(options);
        $('#id_group').val(id_group);
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
}
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
function validate(dateString) {
    var regEx = /^\d{4}-\d{2}-\d{2}$/;
    if (!dateString.match(regEx)) return false; // Invalid format
    var d = new Date(dateString);
    var dNum = d.getTime();
    if (!dNum && dNum !== 0) return false; // NaN value, Invalid date
    return d.toISOString().slice(0, 10) === dateString;
}