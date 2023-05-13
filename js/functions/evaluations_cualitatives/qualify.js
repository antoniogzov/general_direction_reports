var global_btn_qualify = '';
//--- --- ---//
$(document).on('click', '.btn-qualify', function(e) {
    //--- --- ---//
    global_btn_qualify = $(this);
    var ascc_lm_assgn = $('#slct_learning_map').val();
    var assc_mpa_id = $('#slct_topic').val();
    var id_student = $(this).closest('tr').attr('id');
    var no_installment = $('#slct_installment').val();
    var student_name = $(this).closest('tr').find("td:eq(2)").text();
    getFormMPA(assc_mpa_id, id_student, no_installment, student_name);
    //--- --- ---//
});
//--- --- ---//
//--- --- ---//
function getFormMPA(assc_mpa_id, id_student, no_installment, student_name) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'getFormMPA',
            assc_mpa_id: assc_mpa_id
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            var table = '<table class="table table-flush table-striped">';
            table += '<thead class="thead-light">';
            table += '<tr>';
            table += '<th scope="col">#</th>';
            table += '<th scope="col">Criterio</th>';
            table += '<th scope="col">Evaluación</th>';
            //--- --- ---//
            var question = data.data.questions;
            var evaluations = data.data.evaluations;
            //--- --- ---//
            var select = '<select class="form-control slct_evaluations">';
            select += '<option selected value="">Elija una opción</option>';
            for (var i = 0; i < evaluations.length; i++) {
                select += '<option value="' + evaluations[i].id_evaluation_bank + '">' + evaluations[i].evaluation + '</option>';
            }
            select += '</select>';
            //--- --- ---//
            table += '</tr>';
            table += '</thead>';
            table += '<tbody class="list">';
            //--- --- ---//
            for (var e = 0; e < question.length; e++) {
                table += '<tr class="tr-mpa" id="' + question[e].id_question_bank + '">';
                table += '<td scope="col" class="td-question">' + (e + 1) + '</td>';
                table += '<td scope="col" class="td-question">' + question[e].question + '</td>';
                table += '<td scope="col" class="td-evaluation">' + select + '</td>';
                table += '</tr>';
            }
            //--- --- ---//
            table += '</tbody>';
            table += '</table>';
            //--- --- ---//
            var mpa = $('#slct_learning_map option:selected').text();
            var tema = $('#slct_topic option:selected').text();
            var no_installment = $('#slct_installment option:selected').text();
            $('.ttl-modal').html(student_name + ' | ' + mpa + ' | ' + tema + ' | Entrega: ' + no_installment);
            $('.body-modal-form-MPA').html(table);
            $('.div-btn-save-mpa').html('<button type="button" class="btn btn-primary" onclick="getInfoForm(' + assc_mpa_id + ',' + id_student + ',' + no_installment + ')"><i class="fas fa-save"></i>&nbsp&nbspGuardar Evaluación</button>');
            $('#showFormMPA').modal('show');
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Al parecer no hay formularios disponibles :(', 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
function getInfoForm(assc_mpa_id, id_student, no_installment) {
    /*console.log(assc_mpa_id);
    console.log(id_student);
    console.log(no_installment);*/
    var dataMPA = [];
    $('.tr-mpa').each(function(i, obj) {
        var id_question = $(this).attr('id');
        var id_evaluation = $(this).find('.td-evaluation').find('.slct_evaluations').val();
        if (id_evaluation == '') {
            id_evaluation = null;
        }
        //--- --- ---//
        var obj = {
            id_question: id_question,
            id_evaluation: id_evaluation
        }
        dataMPA.push(obj);
        //--- --- ---//
    });
    //--- --- ---//
    saveEvaluationMPA(assc_mpa_id, id_student, no_installment, dataMPA);
    //--- --- ---//
}
//--- --- ---//
function saveEvaluationMPA(assc_mpa_id, id_student, no_installment, dataMPA) {
    //--- --- ---//
    loading();
    //--- --- ---//
    var ascc_lm_assgn = $('#slct_learning_map').val();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'saveEvaluationMPA',
            ascc_lm_assgn: ascc_lm_assgn,
            assc_mpa_id: assc_mpa_id,
            id_student: id_student,
            no_installment: no_installment,
            dataMPA: dataMPA
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            global_btn_qualify.removeClass('btn-qualify');
            global_btn_qualify.removeClass('btn-primary');
            //--- --- ---//
            global_btn_qualify.addClass("btn-update-mpa");
            if (data.incomplete) {
                global_btn_qualify.addClass("btn-warning");
            } else {
                global_btn_qualify.addClass("btn-success");
            }
            global_btn_qualify.html('Editar');
            global_btn_qualify.attr('data-id-historical-map', data.id_historical_learning_maps);
            $('.div-btn-save-mpa').html('');
            global_btn_qualify = '';
            //--- --- ---//
            Swal.fire('Listo!', data.message, 'success');
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Al parecer no hay formularios disponibles :(', 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
//--- UPDATE MPA ---//
$(document).on('click', '.btn-update-mpa', function(e) {
    //--- --- ---//
    global_btn_qualify = $(this);
    var student_name = $(this).closest('tr').find("td:eq(2)").text();
    var id_historical_learning_maps = $(this).attr('data-id-historical-map');
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'getFormMPAFilled',
            id_historical_learning_maps: id_historical_learning_maps
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            var table = '<table class="table table-flush table-striped">';
            table += '<thead class="thead-light">';
            table += '<tr>';
            table += '<th scope="col">#</th>';
            table += '<th scope="col" class="col-sm-4 col-md-4">Criterio</th>';
            table += '<th scope="col">Evaluación</th>';
            //--- --- ---//
            var question = data.data.questions;
            var evaluations = data.data.evaluations;
            //--- --- ---//
            table += '</tr>';
            table += '</thead>';
            table += '<tbody class="list">';
            //--- --- ---//
            for (var e = 0; e < question.length; e++) {
                //--- --- ---//
                var select = '<select class="form-control slct_evaluations">';
                select += '<option selected value="">Elija una opción</option>';
                for (var i = 0; i < evaluations.length; i++) {
                    var find = '';
                    for (var x = 0; x < data.answers.length; x++) {
                        if (question[e].id_question_bank == data.answers[x].id_question_bank && evaluations[i].id_evaluation_bank == data.answers[x].id_evaluation_bank) {
                            find = 'selected';
                        }
                    }
                    //--- --- ---//
                    select += '<option ' + find + ' value="' + evaluations[i].id_evaluation_bank + '">' + evaluations[i].evaluation + '</option>';
                    //--- --- ---//
                }
                select += '</select>';
                //--- --- ---//
                table += '<tr class="tr-mpa" id="' + question[e].id_question_bank + '">';
                table += '<td scope="col" class="td-question col-md-1">' + (e + 1) + '</td>';
                table += '<td scope="col" class="td-question text-wrap col-md-4">' + question[e].question + '</td>';
                table += '<td scope="col" class="td-evaluation text-wrap col-md-4">' + select + '</td>';
                table += '</tr>';
            }
            //--- --- ---//
            table += '</tbody>';
            table += '</table>';
            //--- --- ---//
            var mpa = $('#slct_learning_map option:selected').text();
            var tema = $('#slct_topic option:selected').text();
            var no_installment = $('#slct_installment option:selected').text();
            $('.ttl-modal').html(student_name + ' | ' + mpa + ' | ' + tema + ' | Entrega: ' + no_installment);
            $('.body-modal-form-MPA').html(table);
            $('.div-btn-save-mpa').html('<button type="button" class="btn btn-info" onclick="getInfoFormUpdate(' + id_historical_learning_maps + ')"><i class="fas fa-sync-alt"></i>&nbsp&nbspActualizar Evaluación</button> <button type="button" class="btn btn-danger" onclick="deleteFormMPA(' + id_historical_learning_maps + ')"><i class="fas fa-trash"></i>&nbsp&nbspEliminar Evaluación</button>');
            $('#showFormMPA').modal('show');
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Al parecer no hay formularios disponibles :(', 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
});
//--- --- ---//
function getInfoFormUpdate(id_historical_learning_maps) {
    //--- --- ---//
    var dataMPA = [];
    $('.tr-mpa').each(function(i, obj) {
        var id_question = $(this).attr('id');
        var id_evaluation = $(this).find('.td-evaluation').find('.slct_evaluations').val();
        if (id_evaluation == '') {
            id_evaluation = null;
        }
        //--- --- ---//
        var obj = {
            id_question: id_question,
            id_evaluation: id_evaluation
        }
        dataMPA.push(obj);
        //--- --- ---//
    });
    //--- --- ---//
    UpdateEvaluationMPA(id_historical_learning_maps, dataMPA);
    //--- --- ---//
}
//--- --- ---//
function UpdateEvaluationMPA(id_historical_learning_maps, dataMPA) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'updateEvaluationMPA',
            id_historical_learning_maps: id_historical_learning_maps,
            dataMPA: dataMPA
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            console.log(global_btn_qualify);
            global_btn_qualify.removeClass('btn-warning');
            global_btn_qualify.removeClass('btn-success');
            //--- --- ---//
            if (data.incomplete) {
                global_btn_qualify.addClass("btn-warning");
            } else {
                global_btn_qualify.addClass("btn-success");
            }
            //--- --- ---//
            Swal.fire('Listo!', data.message, 'success');
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Al parecer no hay formularios disponibles :(', 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
//--- COMENTARIOS ---//
//--- --- ---//
$(document).on('click', '.btn-nw-comments', function(e) {
    //--- --- ---//
    global_btn_qualify = $(this);
    var id_learning_map = $('#slct_learning_map').val();
    var id_student = $(this).closest('tr').attr('id');
    var no_installment = $('#slct_installment').val();
    newsCommentsMPA(id_learning_map, id_student, no_installment, global_btn_qualify);
    //--- --- ---//
});
//--- --- ---//
//--- --- ---//
//--- --- ---//
function newsCommentsMPA(id_learning_map, id_student, no_installment) {
    //--- --- ---//
    table = '<div class="form-group">';
    table += '<label class="form-control-label" for="comment1"><b>Comentario 1</b> <span id="txtComm1">(Restan 500 caracteres)</span></label>';
    table += '<textarea class="form-control" id="comment1" rows="3" maxlength="500" onkeyup="countCaracteres(\'txtComm1\', event)"></textarea>';
    table += '</div>';
    //--- --- ---//
    table += '<div class="form-group">';
    table += '<label class="form-control-label" for="comment2"><b>Comentario 2</b> <span id="txtComm2">(Restan 500 caracteres)</span></label>';
    table += '<textarea class="form-control" id="comment2" rows="3" maxlength="500" onkeyup="countCaracteres(\'txtComm2\', event)"></textarea>';
    table += '</div>';
    //--- --- ---//
    $('.body-modal-form-MPA').html(table);
    $('.div-btn-save-mpa').html('<button type="button" class="btn btn-primary" onclick="saveCommentsMPA(' + id_learning_map + ',' + id_student + ',' + no_installment + ')"><i class="fas fa-save"></i>&nbsp&nbspGuardar Comentarios</button>');
    $('#showFormMPA').modal('show');
    //--- --- ---//
}
//--- --- ---//
function saveCommentsMPA(id_learning_map, id_student, no_installment) {
    //--- --- ---//
    var comment1 = $('#comment1').val();
    var comment2 = $('#comment2').val();
    //--- --- ---//
    if (comment1 == '' && comment2 == '') {
        Swal.fire('Atención!', 'No ha agregado ningún comentario', 'info');
    } else {
        //--- --- ---//
        loading();
        //--- --- ---//
        var ascc_lm_assgn = $('#slct_learning_map').val();
        //--- --- ---//
        $.ajax({
            url: 'php/controllers/evaluations_cualitatives.php',
            method: 'POST',
            data: {
                mod: 'saveCommentsMPA',
                ascc_lm_assgn: ascc_lm_assgn,
                id_student: id_student,
                no_installment: no_installment,
                comment1: comment1,
                comment2: comment2
            }
        }).done(function(data) {
            var data = JSON.parse(data);
            if (data.response) {
                //--- --- ---//
                global_btn_qualify.removeClass('btn-qualify');
                global_btn_qualify.removeClass('btn-primary');
                //--- --- ---//
                global_btn_qualify.addClass("btn-update-comments");
                global_btn_qualify.addClass("btn-success");
                global_btn_qualify.attr('data-id-comments', data.id_comments);
                global_btn_qualify.html('Editar Comentarios');
                $('.div-btn-save-mpa').html('');
                global_btn_qualify = '';
                //--- --- ---//
                Swal.fire('Listo!', data.message, 'success');
                //--- --- ---//
            } else {
                Swal.fire('Atención!', 'Ocurrió un error al guardar los comentarios, inténtelo nuevamente', 'info');
            }
        }).fail(function(message) {
            Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
        });
        //--- --- ---// 
    }
}
//--- --- ---//
$(document).on('click', '.btn-update-comments', function(e) {
    //--- --- ---//
    global_btn_qualify = $(this);
    var id_comments = $(this).attr('data-id-comments');
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'getCommentsMPAFilled',
            id_comments: id_comments
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            table = '<div class="form-group">';
            table += '<label class="form-control-label" for="comment1"><b>Comentario 1</b> <span id="txtComm1">(Restan ' + (500 - data.comments1.length) + ' caracteres)</span></label>';
            table += '<textarea class="form-control" id="comment1" rows="3" maxlength="500" onkeyup="countCaracteres(\'txtComm1\', event)">' + data.comments1 + '</textarea>';
            table += '</div>';
            //--- --- ---//
            table += '<div class="form-group">';
            table += '<label class="form-control-label" for="comment2"><b>Comentario 2</b> <span id="txtComm2">(Restan ' + (500 - data.comments2.length) + ' caracteres)</span></label>';
            table += '<textarea class="form-control" id="comment2" rows="3" maxlength="500" onkeyup="countCaracteres(\'txtComm2\', event)">' + data.comments2 + '</textarea>';
            table += '</div>';
            //--- --- ---//
            $('.body-modal-form-MPA').html(table);
            $('.div-btn-save-mpa').html('<button type="button" class="btn btn-primary" onclick="updateCommentsMPA(' + id_comments + ')"><i class="fas fa-save"></i>&nbsp&nbspActualizar comentarios</button>');
            $('#showFormMPA').modal('show');
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Al parecer no hay formularios disponibles :(', 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
});
//--- --- ---//
function updateCommentsMPA(id_comments) {
    //--- --- ---//
    loading();
    //--- --- ---//
    var comment1 = $('#comment1').val();
    var comment2 = $('#comment2').val();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'updateCommentsMPA',
            id_comments: id_comments,
            comment1: comment1,
            comment2: comment2
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (comment1 == '' && comment2 == '') {
                //--- --- ---//
                global_btn_qualify.removeClass('btn-update-comments');
                global_btn_qualify.removeClass('btn-success');
                //--- --- ---//
                global_btn_qualify.addClass("btn-nw-comments");
                global_btn_qualify.addClass("btn-primary");
                global_btn_qualify.html('Agregar Comentarios');
                global_btn_qualify.removeAttr("data-id-comments");
                $('.div-btn-save-mpa').html('');
                //--- --- ---//
            }
            global_btn_qualify = '';
            Swal.fire('Listo!', data.message, 'success');
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Ocurrió un error al actualizar los comentarios, inténtelo nuevamente', 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
function deleteFormMPA(id_historical_learning_maps) {
    Swal.fire({
        title: 'Atención!',
        text: "¿Está seguro de eliminar esta evaluación?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí'
    }).then((result) => {
        if (result.isConfirmed) {
            //--- --- ---//
            loading();
            //--- --- ---//
            $.ajax({
                url: 'php/controllers/evaluations_cualitatives.php',
                method: 'POST',
                data: {
                    mod: 'deleteEvaluationMPA',
                    id_historical_learning_maps: id_historical_learning_maps
                }
            }).done(function(data) {
                var data = JSON.parse(data);
                if (data.response) {
                    //--- --- ---//
                    global_btn_qualify.removeClass('btn-update-mpa');
                    global_btn_qualify.removeClass('btn-success');
                    global_btn_qualify.removeClass('btn-warning');
                    //--- --- ---//
                    global_btn_qualify.addClass("btn-qualify");
                    global_btn_qualify.addClass("btn-primary");
                    global_btn_qualify.removeAttr("data-id-historical-map");
                    global_btn_qualify.html('Calificar');
                    $('.div-btn-save-mpa').html('');
                    global_btn_qualify = '';
                    $('#showFormMPA').modal('toggle');
                    //--- --- ---//
                    Swal.fire('Listo!', data.message, 'success');
                    //--- --- ---//
                } else {
                    Swal.fire('Atención!', 'Al parecer no hay formularios disponibles :(', 'info');
                }
            }).fail(function(message) {
                Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
            });
            //--- --- ---//
        }
    })
}
//--- --- ---//
function countCaracteres(id, e) {
    var count = 500 - e.target.value.length;
    $('#' + id).html('(Restan ' + count + ' caracteres)');
}