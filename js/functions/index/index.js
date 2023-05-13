swal.close();
$('[data-toggle="tooltip"]').tooltip();
//--- --- ---//
if ($('#tableSubjectsCoordinator').length > 0) {
    var tf = new TableFilter('tableSubjectsCoordinator', {
        base_path: '../general/js/vendor/tablefilter/tablefilter/',
        col_widths: ['300px', '140px', '140px', '140px', '290px', '120px', '50px'],
        alternate_rows: true,
        col_1: 'select',
        col_2: 'select',
        col_3: 'select',
        rows_counter: true,
        btn_reset: true,
        loader: true,
        status_bar: true,
        themes: [{
            name: 'skyblue'
        }],
        extensions: [{
            name: 'sort'
        }]
    });
    tf.init();
}
//--- --- ---//
if ($('#tableSubjectsTeachers').length > 0) {
    var tf = new TableFilter('tableSubjectsTeachers', {
        base_path: '../general/js/vendor/tablefilter/tablefilter/',
        col_widths: ['300px', '140px', '140px', '140px', '120px', '50px'],
        alternate_rows: true,
        col_1: 'select',
        col_2: 'select',
        rows_counter: true,
        btn_reset: true,
        loader: true,
        status_bar: true,
        themes: [{
            name: 'skyblue'
        }],
        extensions: [{
            name: 'sort'
        }]
    });
    tf.init();
}
//--- --- ---//
$(document).on('click', '#btn_export_per_config', function() {
    //--- --- ---//
    var val = [];
    var id = [];
    $('.checks_periodos:checkbox:checked').each(function(i) {
        val[i] = $(this).val();
        id[i] = $(this).attr("id");
    });
    var id_assignment = $('#id_assignment_export').val();
    var import_on_period = (id + ",");
    var period_from = $('#export_from_period').val();
    console.log('From: ' + period_from);
    console.log('To: ' + import_on_period);
    /*  $.ajax({
         url: "php/models/export.php",
         method: "POST",
         data: {
             period_from: period_from,
             import_on_period: import_on_period,
             id_assignment: id_assignment
         },
         dataType: "json",
         success: function (data) {
             var titulo = (data[0].mensaje);
             console.log(data);
             if (data[0].resultado == "correcto") {
                 $('#btn_cancel_export_per_config').click();
                 Swal.fire({
                     icon: 'success',
                     title: titulo,
                     showConfirmButton: false,
                     timer: 2500
                 });
             } else {
                 $('#btn_cancel_export_per_config').click();
                 Swal.fire({
                     icon: 'error',
                     title: titulo,
                     showConfirmButton: false,
                     timer: 2500
                 });
             }
         }
     }); */
});
$(document).on('click', '.btn_export_subject_config', function() {
    //--- --- ---//
    var id_assignment = $(this).attr("id");
    console.log(id_assignment);
    $('#export_from_ass').val(id_assignment);
    $('#export_subject_plan').modal('show');
});
$(document).on('click', '#btn_export_sbj_config', function() {
    //--- --- ---//
    var val = [];
    var id = [];
    $('.checks_export_subject_conf:checkbox:checked').each(function(i) {
        val[i] = $(this).val();
        id[i] = $(this).attr("id");
    });
    var import_on_assignment = (id + ",");
    var assignment_from = $('#export_from_ass').val();
    console.log('From: ' + assignment_from);
    console.log('To: ' + import_on_assignment);
    Swal.fire({
        text: 'Cargando...',
        html: '<img src="images/loading_iteach.gif" width="300" height="300">',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false,
        showCancelButton: false,
        showConfirmButton: false,
    })
    $.ajax({
        url: "php/controllers/controllerConfigEvaluationPlan.php",
        method: "POST",
        data: {
            fun: 'exportSubjectConfig',
            assignment_from: assignment_from,
            import_on_assignment: import_on_assignment
        },
        dataType: "json",
    }).done(function(data) {
        console.log(data[0].info);
        var listahtml = '';
        for (var i = 0; i < data[0].info.length; i++) {
            console.log(data[0].info[i]);
            listahtml += '<li class="list-group-item list-group-item-' + data[0].info[i][5] + '">  Grupo: ' + data[0].info[i][0] + ' | Materia: ' + data[0].info[i][2] + '</li>';
        }
        if (data[0].resultado = "correcto") {
            var mensaje = data[0].mensaje;
            Swal.fire({
                title: '<strong>RESULTADOS DE EXPORTACIÓN</strong>',
                icon: 'info',
                html: '</br>' + '' + listahtml + '',
                showCloseButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonText: 'Aceptar',
                focusConfirm: false
            })
            /*  Swal.fire({
                icon: 'success',
                title: mensaje,
                showConfirmButton: false,
                timer: 1500
            }); */
            $('#export_subject_plan').modal('hide');
        }
    }).fail(function(error) {
        console.log(error);
    });
});
$(document).on('click', '#btn_cancel_export_sbj_config', function() {
    $('#export_subject_plan').modal('hide');
    $('.modal-backdrop').hide();
});