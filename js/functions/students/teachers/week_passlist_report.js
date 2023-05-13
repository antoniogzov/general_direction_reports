
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
$(document).on('click', '#btn_search_attendance', function () {
    var date_search = $('.date-input').val();
    if (validate(date_search) && date_search != '') {
        var id_assignment = $('#id_subject option:selected').attr('id');
        searchAttendance(date_search, id_assignment);
    } else {
        Swal.fire('Atención!', 'Ingrese una fecha correcta :D', 'info')
    }
});
$('#week_picker').datepicker({
    autoclose: true,
    format: 'YYYY-MM-DD',
    forceParse: false
}).on("changeDate", function (e) {
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
$(document).on('click', '#get_week_attendance', function () {
    loading();

    var id_teacher = $('#id_teacher').val();
    var id_academic = $('#id_academic').val();
    var week = $('#week_picker').val();
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has('submodule')) {
        //--- --- ---//

        const submodule = urlParams.get('submodule');
        window.location.search = 'submodule=' + submodule + '&id_academic=' + id_academic + '&id_teacher=' + id_teacher + '&week=' + week;
        //--- --- ---//
    }

});

$(document).on('change', '#id_teacher', function () {
    $('#div_week').show();
});

$(document).on('change', '#id_academic', function () {
    $('#div_tabla').empty();
    var id_academic = $(this).val();
    getTeachers(id_academic);
});


$(document).on('change', '#id_group', function () {
    var id_group = $(this).val();
    var id_academic = $('#id_academic').val();
    loading();

    $('#div_tabla').empty();
    console.log(id_academic);


});
//--- --- ---//
$(document).on('click', '#btn_actualizar_asistencia', function (e) {
    //--- --- ---//
    e.preventDefault();
    var id_attendance_index = $(this).attr('data-id-index');
    var compulsory_class = $('.compulsory-class').is(":checked");
    var data = [];
    var presents = 0;
    var missing = 0;
    //--- --- ---//
    //--- --- ---//
    $('.check-student').each(function (i, obj) {
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
$(document).on('click', '#btn_guardar_asistencia', function (e) {
    //--- --- ---//
    e.preventDefault();
    //--- --- ---//
    var data = [];
    var presents = 0;
    var missing = 0;
    var compulsory_class = $('.compulsory-class').is(":checked");
    var id_assignment = $('#id_subject option:selected').attr('id');
    //--- --- ---//
    $('.check-student').each(function (i, obj) {
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
    //--- --- ---//
    if (data.length > 0) {
        Swal.fire({
            title: 'Atención!',
            icon: 'info',
            html: 'Guardará los siguientes datos: <br><br> Alumnos totales: <strong>' + $('.check-student').length + '</strong> <br> Alumnos presentes: <strong> ' + presents + '</strong> <br> Alumnos ausentes: <strong> ' + missing + ' </strong> <br> <br> <font color="red"> Registrará esta clase como: ' + (compulsory_class ? "Obligatoria" : "No obligatoria") + '</font>',
            showCancelButton: true,
            confirmButtonText: 'Guardar'
        }).then((result) => {
            if (result.isConfirmed) {
                saveAttendance(data, compulsory_class, id_assignment);
            }
        })
    }
    //--- --- ---//
});
//--- --- ---//
function getTeachers(id_academic) {
    //--- --- ---//
    loading();
    $('#id_group').html('');


    $.ajax({
        url: 'php/controllers/students.php',
        method: 'POST',
        data: {
            mod: 'getAllMyTeachersByAcademic',
            id_academic: id_academic
        }
    }).done(function (data) {
        console.log(data);
        var data = JSON.parse(data);
        var options = '<option selected value="" disabled>Elija una opción</option>';
        if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
                options += '<option value="' + data.data[i].no_colaborador + '">' + data.data[i].teacher_name + '</option>';
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
        $('#id_teacher').html(options);
        swal.close();
        //--- --- ---//
    }).fail(function (message) {
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
    }).done(function (data) {
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
    }).fail(function (message) {
        VanillaToasts.create({
            title: 'Error',
            text: 'Ocurrió un error, intentelo nuevamente',
            type: 'error',
            timeout: 1200,
            positionClass: 'topRight'
        });
    });
}

function getAssistanceDetails(indexs) {
    //--- --- ---//

    /* loading(); */
    console.log(indexs);

        $.ajax({
            url: 'php/controllers/students.php',
            method: 'POST',
            data: {
                id_group: indexs,
                mod: 'getGroupsAttendance'
            }
            
        }).done(function (data) {
             console.log(data);
            var data = JSON.parse(data);

            if (data.response) {
                swal.close();
                Swal.fire({
                    title: '<strong>Lista de grupos</strong>',
                    icon: 'info',
                    html:
                        '<b>Registro de pases de lista:</b></br>' +
                        '' + data.data + '',
                    showCloseButton: true,
                    focusConfirm: false,
                    confirmButtonText:
                        'Aceptar'
                })
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
            
            //--- --- ---//
        }).fail(function (message) {
            VanillaToasts.create({
                title: 'Error',
                text: 'Ocurrió un error, intentelo nuevamente',
                type: 'error',
                timeout: 1200,
                positionClass: 'topRight'
            });
        });
    
    //--- --- ---//
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
    }).done(function (data) {
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
    }).fail(function (message) {
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



function exportTable() {
    console.log('kdv ewrlvn3oirtv3');
    var txt_group = $('#id_group option:selected').text();
    var txt_period = $('#id_period option:selected').text();
    $('#tQualifications').DataTable({
        colReorder: false,
        dom: 'Bfrtip',
        lengthMenu: [
            [40, 25, 50, -1],
            ['10 rows', '25 rows', '50 rows', 'Show all']
        ],
        buttons: [{
            extend: 'excel',
            text: 'Excel',
            className: 'exportExcel',
            filename: txt_group + ' | Periodo:' + txt_period,
            exportOptions: { modifier: { page: 'all' } }
        },
        {
            extend: 'csv',
            text: 'CSV',
            className: 'exportExcel',
            filename: txt_group + ' | Periodo:' + txt_period,
            exportOptions: { modifier: { page: 'all' } }
        },
        {
            extend: 'pdf',
            text: 'PDF',
            className: 'exportExcel',
            filename: txt_group + ' | Periodo:' + txt_period,

            orientation: 'landscape',
            pageSize: 'LEGAL',
            exportOptions: { modifier: { page: 'all' } }
        }]
    });
}

