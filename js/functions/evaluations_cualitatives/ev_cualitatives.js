//--- --- ---//
swal.close();
//--- --- ---//
$(document).on('change', '#slct_learning_map', function(e) {
    //--- --- ---//
    var ascc_lm_assgn = $(this).val();
    getGroupsQuestionsMPA(ascc_lm_assgn);
    //--- --- ---//
    $('.tbl-students').html('');
    $('#slct_installment').html('<option selected value="" disabled>Elija una opción</option>');
    $('#slct_topic').html('<option selected value="" disabled>Elija una opción</option>');
    //--- --- ---//
});
//--- --- ---//
$(document).on('change', '#slct_topic', function(e) {
    //--- --- ---//
    var assc_mpa_id = $(this).val();
    //--- --- ---//
    var options = '<option selected value="" disabled>Elija una opción</option>';
    for (var i = 1; i < 5; i++) {
        options += '<option value="' + i + '">' + i + '</option>';
    }
    //--- --- ---//
    loading();
    setTimeout(function() {
        swal.close();
    }, 800);
    //--- --- ---//
    $('#slct_installment').html(options);
    $('.tbl-students').html('');
    //--- --- ---//
});
//--- --- ---//
$(document).on('change', '#slct_installment', function(e) {
    //--- --- ---//
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has('id_assignment')) {
        //--- --- ---//
        const id_assignment = urlParams.get('id_assignment');
        window.location.search = 'id_assignment=' + id_assignment + '&ascc_lm_assgn=' + $('#slct_learning_map').val() + '&assc_mpa_id=' + $('#slct_topic').val() + '&no_installment=' + $('#slct_installment').val();
        //--- --- ---//
    }
    //--- --- ---//
});
//--- --- ---//
//--- --- ---//
function getGroupsQuestionsMPA(ascc_lm_assgn) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'getGroupsQuestionsMPA',
            ascc_lm_assgn: ascc_lm_assgn
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            var options = '<option selected value="" disabled>Elija una opción</option>';
            if (data.groupsQuestions.length > 0) {
                for (var i = 0; i < data.groupsQuestions.length; i++) {
                    options += '<option value="' + data.groupsQuestions[i].assc_mpa_id + '">' + data.groupsQuestions[i].name_question_group + '</option>';
                }
                options += '<option value="comments">COMENTARIOS FINALES</option>';
            }
            $('#slct_topic').html(options);
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Al parecer no hay temas disponibles', 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
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