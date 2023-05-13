setTimeout(function() {
    const id_sbjt = $('#id_sbjt').val();
    $('#id_subject').val(id_sbjt);
    var txt_group = $('#id_group option:selected').text();
    var txt_subject = $('#id_subject option:selected').text();
    $('#txt_grupo').text(txt_group + ' | ' + txt_subject);
    console.log(id_sbjt);
}, 1000);
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
$(document).on('change', '#id_subject', function() {
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has('submodule')) {
        //--- --- ---//
        const id_subject = $(this).val();
        const submodule = urlParams.get('submodule');
        window.location.search = 'submodule=' + submodule + '&id_subject=' + id_subject;
        //--- --- ---//
    }
});
//--- --- ---//
$(document).on('change', '#id_group', function() {
    //var id_group = $(this).val();
    //getSubjectsByTeacher(id_group);
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has('submodule')) {
        const id_group = $(this).val();
        const submodule = urlParams.get('submodule');
        window.location.search = 'submodule=' + submodule + '&id_group=' + id_group;
    }
});

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
if ($('#tStudentsSubject').length > 0) {
    var tf = new TableFilter('tStudentsSubject', {
        base_path: '../general/js/vendor/tablefilter/tablefilter/',
        alternate_rows: true,
        rows_counter: true,
        btn_reset: true,
        col_2: 'select',
        col_3: 'select',
        loader: true,
        status_bar: true,
        responsive: true,
        extensions: [{
            name: 'sort'
        }]
    });
    tf.init();
}