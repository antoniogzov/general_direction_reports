//--- --- ---//
swal.close();
//--- --- ---//
$(document).on('change', '#slct_learning_map', function(e) {
    //--- --- ---//
    var lmp_id = $(this).val();
    $('#slct_installment').val('');
    $('#slct_group').html('');
    //--- --- ---//
    $('.div_content_mda').html('');
    $('.slct_option_student').html('<option selected value="" disabled>Elija una opción</option>');
    //--- --- ---//
    getGroupsMDA(lmp_id);
    //--- --- ---//
});
//--- --- ---//
$(document).on('change', '#slct_group', function(e) {
    //--- --- ---//
    var lmp_id = $('#slct_learning_map').val();
    var group_id = $('#slct_group').val();
    var installment = $('#slct_installment').val();
    //--- --- ---//
    $('.div_content_mda').html('');
    $('.slct_option_student').html('<option selected value="" disabled>Elija una opción</option>');
    //--- --- ---//
    if (lmp_id != '' && group_id != '' && installment != '' && lmp_id != null && group_id != null && installment != null) {
        getStudentByGroup(group_id);
    }
    //--- --- ---//
});
//--- --- ---//
$(document).on('change', '#slct_installment', function(e) {
    //--- --- ---//
    var lmp_id = $('#slct_learning_map').val();
    var group_id = $('#slct_group').val();
    var installment = $('#slct_installment').val();
    //--- --- ---//
    $('.div_content_mda').html('');
    $('.slct_option_student').html('<option selected value="" disabled>Elija una opción</option>');
    //--- --- ---//
    if (lmp_id != '' && group_id != '' && installment != '' && lmp_id != null && group_id != null && installment != null) {
        getStudentByGroup(group_id);
    }
    //--- --- ---//
});
//--- --- ---//
$(document).on('change', '#slct_option_student', function(e) {
    //--- --- ---//
    var lmp_id = $('#slct_learning_map').val();
    var group_id = $('#slct_group').val();
    var installment = $('#slct_installment').val();
    var id_student = $(this).val();
    //--- --- ---//
    $('.div_content_mda').html('');
    //--- --- ---//
    if (lmp_id != '' && group_id != '' && installment != '' && id_student != '' && lmp_id != null && group_id != null && installment != null && id_student != null) {
        getReportMDA(lmp_id, group_id, installment, id_student);
    }
    //--- --- ---//
});
//--- --- ---//
//--- COMENTARIOS DIRECTOR ---//
//--- --- ---//
$(document).on('keydown paste', '.td-comment1, .td-comment2, .td-comment-director', function() {
    //Just for info, you can remove this line
    console.log('Total chars:' + $(this).text().length);
    //You can add delete key event code as well over here for windows users.
    if ($(this).text().length >= 500 && event.keyCode != 8) {
        event.preventDefault();
    }
});
//--- --- ---//
$(document).on('keyup', '.td-comment1, .td-comment2, .td-comment-director', function() {
    //Just for info, you can remove this line
    var string = $(this).text();
    console.log('Total chars:' + $(this).text().length);
    //You can add delete key event code as well over here for windows users.
    if ($(this).text().length >= 500) {
        string = string.substring(0, 500);
        $(this).text(string);
    }
});
//--- --- ---//
$(document).on('click', '.btn-update', function() {
    //--- --- ---//
    var tr_active = $(this).closest('td').closest('tr');
    //--- --- ---//
    var colunm = $(this).attr('data-class-column');
    var class_col_text = $(this).attr('data-class-td-new-text');
    let txt_update = tr_active.find('.' + class_col_text).text();
    var id_comments = tr_active.find('.' + class_col_text).attr('data-id-comments');
    Swal.fire({
        title: 'Atención!',
        text: "Está a punto de actualizar un comentario, ¿desea continuar?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí'
    }).then((result) => {
        if (result.isConfirmed) {
            updateComments(id_comments, colunm, txt_update);
        }
    })
    //--- --- ---//
});
//--- --- ---//
$(document).on('click', '.btn-new', function() {
    //--- --- ---//
    var tr_active = $(this).closest('td').closest('tr');
    //--- --- ---//
    var colunm = $(this).attr('data-class-column');
    var class_col_text = $(this).attr('data-class-td-new-text');
    let txt_update = tr_active.find('.' + class_col_text).text();
    var id_student = tr_active.find('.' + class_col_text).attr('data-id-student');
    var ascc_lm_assgn = tr_active.find('.' + class_col_text).attr('data-id-lm-assgn');
    //--- --- ---//
    /*console.log(colunm);
    console.log(class_col_text);
    console.log(txt_update);
    console.log(id_student);
    console.log(ascc_lm_assgn);*/
    Swal.fire({
        title: 'Atención!',
        text: "Está a punto de actualizar un comentario, ¿desea continuar?",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí'
    }).then((result) => {
        if (result.isConfirmed) {
            AddComments(id_student, ascc_lm_assgn, colunm, txt_update);
        }
    })
    //--- --- ---//
});
//--- --- ---//
var specialElementHandlers = {
    '#editor': function(element, renderer) {
        return true;
    }
};
$(document).on('click', '.btn-dowload-report', function() {
    //--- --- ---//
    const name_topic = $(this).attr('data-name-group');
    const student_code = $(this).attr('data-student-code');
    //--- --- ---//
    var HTML_Width = $("#div-report").width();
    var HTML_Height = $("#div-report").height();
    var top_left_margin = 15;
    var PDF_Width = HTML_Width;
    var PDF_Height = (PDF_Width * 1.4);
    var canvas_image_width = HTML_Width;
    var canvas_image_height = HTML_Height;
    //--- --- ---//
    /*console.log('HTML_Width: ' + HTML_Width);
    console.log('HTML_Height: ' + HTML_Height);
    //--- --- ---//
    console.log('PDF_Width: ' + PDF_Width);
    console.log('PDF_Height: ' + PDF_Height);
    console.log('canvas_image_width: ' + canvas_image_width);
    console.log('canvas_image_height: ' + canvas_image_height);*/
    //--- --- ---//
    var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;
    html2canvas($("#div-report")[0]).then(function(canvas) {
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF('l', 'px', [PDF_Width, PDF_Height]);
        //var pdf = new jsPDF('landscape');
        pdf.addImage(imgData, 'JPG', top_left_margin, top_left_margin, canvas_image_width, canvas_image_height);
        for (var i = 1; i <= totalPDFPages; i++) {
            pdf.addPage('l', PDF_Width, PDF_Height);
            pdf.addImage(imgData, 'JPG', top_left_margin, -(PDF_Height * i) + (top_left_margin * 4), canvas_image_width, canvas_image_height);
        }
        pdf.save(student_code + '_' + name_topic + '.pdf');
    });
    //--- --- ---//
});
//--- --- ---//
function getStudentByGroup(group_id) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'getStudentByGroup',
            group_id: group_id
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            var students = '<option selected value="" disabled>Elija una opción</option><option value="0">TODO GRUPO</option>';
            if (data.students.length > 0) {
                for (var i = 0; i < data.students.length; i++) {
                    students += '<option value="' + data.students[i].id_student + '">' + data.students[i].student_name + '</option>';
                }
            }
            //--- --- ---//
            $('#slct_option_student').html(students);
            $("#slct_option_student").val('0').change();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire('Atención!', data.message, 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
function getGroupsMDA(lmp_id) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'getGroupsMDA',
            lmp_id: lmp_id
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            var options = '<option selected value="" disabled>Elija una opción</option>';
            if (data.groups.length > 0) {
                for (var i = 0; i < data.groups.length; i++) {
                    options += '<option value="' + data.groups[i].id_group + '" data-toggle="tooltip" data-placement="top" title="' + data.groups[i].string_group + '">' + data.groups[i].group_code + '</option>';
                }
            }
            //--- --- ---//
            $('#slct_group').html(options);
            //--- --- ---//
            $('[data-toggle="tooltip"]').tooltip();
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire('Atención!', data.message, 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
function getReportMDA(lmp_id, group_id, installment, id_student) {
    //--- --- ---//
    loading();
    //--- --- ---//
    if (parseInt(id_student) == 0) {
        mod = 'getReportMDAGeneral';
    } else {
        mod = 'getReportMDAStudent'
    }
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: mod,
            lmp_id: lmp_id,
            group_id: group_id,
            installment: installment,
            id_student: id_student
        }
    }).done(function(data) {
        //console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (parseInt(id_student) == 0) {
                generateReportGroupMDAGeneral(data);
            } else {
                generateReportGroupMDAByStudent(data);
            }
            //--- --- ---//
        } else {
            Swal.fire('Atención!', data.message, 'info');
        }
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
function generateReportGroupMDAByStudent(data) {
    //--- --- ---//
    var all_data = '';
    var comments_director = '';
    //--- --- ---//
    if (data.topics.length > 0) {
        for (var i = 0; i < data.topics.length; i++) {
            //--- --- ---//
            all_data += '<button type="button" class="btn btn-outline-danger btn-dowload-report" data-student-code = "' + data.students.student_code + '" data-name-group="' + data.topics[i].topic.name_question_group + '">Descargar PDF&nbsp;&nbsp;<i class="fas fa-file-pdf fa-lg"></i></button><br/><br/>';
            all_data += '<div class="card" id="div-report">';
            all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.topics[i].topic.name_question_group + '</b></h3><br/>';
            all_data += '<h4 class="mb-0">Código: ' + data.students.student_code + '</h4>';
            all_data += '<h4 class="mb-0">Nombre: ' + data.students.student_name + '</h4>';
            all_data += '<h4 class="mb-0">Grupo: ' + data.students.group_code + '</h4>';
            all_data += '</div>';
            //--- COMENTARIOS FINALES ---//
            if (data.final_comments.length > 0) {
                //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                    if (data.topics[i].data.assgs[a].assg.id_subject == '309') {
                        for (var d = 0; d < data.final_comments.length; d++) {
                            for (var x = 0; x < data.final_comments[d].comments.length; x++) {
                                if (data.final_comments[d].comments[x].ascc_lm_assgn == data.final_comments[d].assg.ascc_lm_assgn && data.topics[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments[d].comments[x].ascc_lm_assgn) {
                                    //--- --- ---//
                                    var director_name = '<blockquote class="blockquote">';
                                    director_name += '<footer class="blockquote-footer"><cite title="Source Title">' + data.final_comments[d].comments[x].director_name + '</cite></footer>';
                                    director_name += '</blockquote>';
                                    comments_director += '<h4>COMENTARIO DEL DIRECTOR: </h4><h3>' + data.final_comments[d].comments[x].directors_comment + '</h3>' + director_name + '<br/><br/>';
                                    //--- --- ---//
                                }
                            }
                        }
                    }
                    //--- --- ---//
                }
            }
            //--- --- ---//
            var table = '<div class="table-responsive">';
            table += '<table class="table" style="table-layout:fixed;">';
            table += '<thead>';
            table += '<tr>';
            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
            for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                //--- ASIGNATURA ---//
                table += '<th class="text-center" scope="col" style="width:' + (data.topics[i].data.questions_evaluations.evaluations.length * 35) + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.topics[i].data.questions_evaluations.evaluations.length + '"><b>' + data.topics[i].data.assgs[a].assg.name_subject + '</b><br/>' + data.topics[i].data.assgs[a].assg.teacher_name + '</th>';
                //--- --- ---//
            }
            table += '</tr>';
            //--- CATALOGO DE EVALUACIONES ---//
            table += '<tr>';
            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
            for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                for (var b = 0; b < data.topics[i].data.questions_evaluations.evaluations.length; b++) {
                    table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.topics[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.topics[i].data.questions_evaluations.evaluations[b].symbol + '</th>';
                }
            }
            //--- --- ---//
            table += '</tr>';
            //--- --- ---//
            table += '</thead>';
            table += '<tbody class="list">';
            //--- LISTA PREGUNTAS ---//
            for (var f = 0; f < data.topics[i].data.questions_evaluations.questions.length; f++) {
                //--- --- ---//
                var answer_null_to_student = '';
                var answer_filled_to_student = '';
                //--- --- ---//
                table += '<tr>';
                table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.topics[i].data.questions_evaluations.questions[f].question + '</td>';
                //--- RESPUESTAS ---//
                for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                    //--- --- ---//
                    var answer_null_to_student = '';
                    var answer_filled_to_student = '';
                    var answer_to_question = false;
                    //--- --- ---//
                    for (var b = 0; b < data.topics[i].data.questions_evaluations.evaluations.length; b++) {
                        //--- --- ---/
                        var answer_find = false;
                        //--- --- ---//
                        var style_border = 'dotted';
                        if ((b + 1) >= data.topics[i].data.questions_evaluations.evaluations.length) {
                            style_border = 'solid';
                        }
                        //--- --- ---//
                        //--- RESPUESTAS ---//
                        for (var d = 0; d < data.topics[i].data.assgs[a].answers.length; d++) {
                            //--- --- ---//
                            if (data.topics[i].data.assgs[a].assg.ascc_lm_assgn == data.topics[i].data.assgs[a].answers[d].ascc_lm_assgn && data.topics[i].topic.assc_mpa_id == data.topics[i].data.assgs[a].answers[d].assc_mpa_id && data.topics[i].data.questions_evaluations.questions[f].id_question_bank == data.topics[i].data.assgs[a].answers[d].id_question_bank && data.topics[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.topics[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                answer_find = true;
                                answer_to_question = true;
                                answer_filled_to_student += '<td style="border-right: 1px ' + style_border + '; padding: 3px; background-color:' + data.topics[i].data.questions_evaluations.evaluations[b].colorHTML + '" ></td>';
                            }
                            //--- --- ---//
                        }
                        //--- --- ---//
                        if (!answer_find) {
                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + '; padding: 3px;"></td>';
                            answer_null_to_student += '<td style="border-right: 1px ' + style_border + '; padding: 3px; background-color: #DCDCDC"></td>';
                        }
                        //--- --- ---//
                    }
                    //--- --- ---//
                    if (answer_to_question) {
                        table += answer_filled_to_student;
                    } else {
                        table += answer_null_to_student;
                    }
                    //--- --- ---//
                }
                //--- --- ---//
                table += '</tr>';
                //--- --- ---//
            }
            //--- --- ---//
            //--- COMENTARIOS FINALES ---//
            if (data.final_comments.length > 0) {
                //--- --- ---//
                table += '<tr>';
                table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                    var find_comment = false;
                    for (var d = 0; d < data.final_comments.length; d++) {
                        for (var x = 0; x < data.final_comments[d].comments.length; x++) {
                            if (data.final_comments[d].comments[x].ascc_lm_assgn == data.final_comments[d].assg.ascc_lm_assgn && data.topics[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments[d].comments[x].ascc_lm_assgn) {
                                //--- --- ---//
                                find_comment = true;
                                table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.topics[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments[d].comments[x].comments1 + '</td>';
                                //--- --- ---//
                            }
                        }
                    }
                    //--- --- ---//
                    if (!find_comment) {
                        table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.topics[i].data.questions_evaluations.evaluations.length + '"></td>';
                    }
                    //--- --- ---//
                }
                table += '</tr>';
            }
            //--- --- ---//
            //--- --- ---//
            table += '</tbody>';
            table += '</table>';
            table += '</div>';
            //--- --- ---//
            all_data += '<div class="card-body" style="background-color: white">';
            all_data += comments_director;
            all_data += table;
            all_data += '</div>';
            all_data += '</div>';
            all_data += '</div>';
        }
        //--- ---- ---//
        //--- ---- ---//
        //--- ---- ---//
        //--- ---- ---//
        //--- ---- ---//
    }
    //--- --- ---//
    $('.div_content_mda').html(all_data);
    //--- --- ---//
    $('[data-toggle="tooltip"]').tooltip();
    swal.close();
    //--- --- ---//
}
//--- --- ---//
function generateReportGroupMDAGeneral(data) {
    //--- --- ---//
    var all_data = '';
    //--- --- ---//
    if (data.topics.length > 0) {
        for (var i = 0; i < data.topics.length; i++) {
            //--- --- ---//
            all_data += '<div class="card">';
            all_data += '<div class="card-header border-0"><h3 class="mb-0">' + data.topics[i].topic.name_question_group + '</h3></div>';
            //--- --- ---//
            var table = '<div class="table-responsive">';
            table += '<table class="table" style="table-layout:fixed;">';
            table += '<thead>';
            table += '<tr>';
            table += '<th style="border-right: 1px solid; padding: 3px; width: 250px;"></th>';
            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
            for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                //--- ASIGNATURA ---//
                table += '<th class="text-center" scope="col" style="width:' + ((data.topics[i].data.questions_evaluations.evaluations.length * data.topics[i].data.questions_evaluations.questions.length) * 35) + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + (data.topics[i].data.questions_evaluations.evaluations.length * data.topics[i].data.questions_evaluations.questions.length) + '">' + data.topics[i].data.assgs[a].assg.name_subject + '<br/>' + data.topics[i].data.assgs[a].assg.teacher_name + '</th>';
                //--- --- ---//
            }
            table += '</tr>';
            //--- PREGUNTAS ---//
            table += '<tr>';
            table += '<th style="border-right: 1px solid; padding: 3px; width: 250px;"></th>';
            for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                for (var f = 0; f < data.topics[i].data.questions_evaluations.questions.length; f++) {
                    table += '<th class="text-center" scope="col" style="width:' + (35 * data.topics[i].data.questions_evaluations.evaluations.length) + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; padding-right: 1px; padding-left: 1px; letter-spacing: 0;" colspan="' + data.topics[i].data.questions_evaluations.evaluations.length + '">' + data.topics[i].data.questions_evaluations.questions[f].question + '</th>';
                }
            }
            table += '</tr>';
            //--- CATALOGO DE EVALUACIONES ---//
            table += '<tr>';
            table += '<th style="border-right: 1px solid; padding: 3px; width: 250px;"></th>';
            for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                for (var f = 0; f < data.topics[i].data.questions_evaluations.questions.length; f++) {
                    for (var b = 0; b < data.topics[i].data.questions_evaluations.evaluations.length; b++) {
                        table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.topics[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.topics[i].data.questions_evaluations.evaluations[b].symbol + '</th>';
                    }
                }
            }
            //--- --- ---//
            table += '</tr>';
            //--- --- ---//
            table += '</thead>';
            table += '<tbody class="list">';
            //--- LISTA ALUMNOS ---//
            for (var e = 0; e < data.students.length; e++) {
                table += '<tr>';
                table += '<td style="border-right: 1px solid; padding: 3px; width: 250px;">' + data.students[e].student_name + '</td>';
                //--- RESPUESTAS ---//
                for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
                    for (var f = 0; f < data.topics[i].data.questions_evaluations.questions.length; f++) {
                        var answer_null_to_student = '';
                        var answer_filled_to_student = '';
                        var answer_to_question = false;
                        for (var b = 0; b < data.topics[i].data.questions_evaluations.evaluations.length; b++) {
                            //--- --- ---/
                            var answer_find = false;
                            //--- --- ---//
                            var style_border = 'dotted';
                            if ((b + 1) >= data.topics[i].data.questions_evaluations.evaluations.length) {
                                style_border = 'solid';
                            }
                            //--- --- ---//
                            //--- RESPUESTAS ---//
                            for (var d = 0; d < data.topics[i].data.assgs[a].answers.length; d++) {
                                //--- --- ---//
                                if (data.topics[i].data.assgs[a].assg.ascc_lm_assgn == data.topics[i].data.assgs[a].answers[d].ascc_lm_assgn && data.topics[i].topic.assc_mpa_id == data.topics[i].data.assgs[a].answers[d].assc_mpa_id && data.topics[i].data.questions_evaluations.questions[f].id_question_bank == data.topics[i].data.assgs[a].answers[d].id_question_bank && data.topics[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.topics[i].data.assgs[a].answers[d].id_evaluation_bank && data.students[e].id_student == data.topics[i].data.assgs[a].answers[d].id_student) {
                                    answer_find = true;
                                    answer_to_question = true;
                                    answer_filled_to_student += '<td style="border-right: 1px ' + style_border + '; padding: 3px; background-color:' + data.topics[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.students[e].student_name + '"></td>';
                                }
                                //--- --- ---//
                            }
                            //--- --- ---//
                            if (!answer_find) {
                                answer_filled_to_student += '<td style="border-right: 1px ' + style_border + '; padding: 3px;" data-toggle="tooltip" data-placement="top" title="' + data.students[e].student_name + '"></td>';
                                answer_null_to_student += '<td style="border-right: 1px ' + style_border + '; padding: 3px; background-color: #DCDCDC" data-toggle="tooltip" data-placement="top" title="' + data.students[e].student_name + '"></td>';
                            }
                            //--- --- ---//
                        }
                        //--- --- ---//
                        if (answer_to_question) {
                            table += answer_filled_to_student;
                        } else {
                            table += answer_null_to_student;
                        }
                        //--- --- ---//
                    }
                }
                //--- --- ---//
                table += '</tr>';
                //--- --- ---//
            }
            //--- --- ---//
            table += '</tbody>';
            table += '</table>';
            table += '</div>';
            //--- --- ---//
            all_data += '<div class="card-body">';
            all_data += table;
            all_data += '</div>';
            all_data += '</div>';
            all_data += '</div>';
        }
        //--- ---- ---//
        //--- ---- ---//
        //--- ---- ---//
        //--- ---- ---//
        //--- ---- ---//
        //--- COMENTARIOS FINALES ---//
        if (data.final_comments.length > 0) {
            //--- --- ---//
            all_data += '<div class="card">';
            all_data += '<div class="card-header border-0"><h3 class="mb-0">COMENTARIOS FINALES</h3></div>';
            //--- --- ---//
            var table = '<div class="" style="overflow-x:scroll; overflow-y:visible; margin-left:250px;">';
            table += '<table class="table" style="table-layout:fixed; width: 100%;">';
            table += '<thead>';
            table += '<tr>';
            table += '<th style="border-right: 1px solid; padding: 3px; width: 250px; position:absolute; left:0;"></th>';
            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
            for (var i = 0; i < data.final_comments.length; i++) {
                //--- ASIGNATURA ---//
                table += '<th class="text-center" scope="col" style="width:1380px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="6">' + data.final_comments[i].assg.name_subject + '<br/>' + data.final_comments[i].assg.teacher_name + '</th>';
                //--- --- ---//
            }
            table += '</tr>';
            //--- CABECERA COMENTARIOS ---//
            table += '<tr>';
            table += '<th style="border-right: 1px solid; padding: 3px; width: 250px; position:absolute; left:0;"></th>';
            for (var i = 0; i < data.final_comments.length; i++) {
                table += '<th class="text-center" scope="col" style="width: 400px; white-space: normal; vertical-align: middle; border-right: 1px dotted; padding-right: 1px; padding-left: 1px; letter-spacing: 0;">COMENTARIO 1</th>';
                table += '<th class="text-center" scope="col" style="width: 60px; white-space: normal; vertical-align: middle; border-right: 1px dotted; padding-right: 1px; padding-left: 1px; letter-spacing: 0;"></th>';
                table += '<th class="text-center" scope="col" style="width: 400px; white-space: normal; vertical-align: middle; border-right: 1px dotted; padding-right: 1px; padding-left: 1px; letter-spacing: 0;">COMENTARIO 2</th>';
                table += '<th class="text-center" scope="col" style="width: 60px; white-space: normal; vertical-align: middle; border-right: 1px dotted; padding-right: 1px; padding-left: 1px; letter-spacing: 0;"></th>';
                table += '<th class="text-center" scope="col" style="width: 400px; white-space: normal; vertical-align: middle; border-right: 1px dotted; padding-right: 1px; padding-left: 1px; letter-spacing: 0;">COMENTARIO DIRECTOR</th>';
                table += '<th class="text-center" scope="col" style="width: 60px; white-space: normal; vertical-align: middle; border-right: 1px dotted; padding-right: 1px; padding-left: 1px; letter-spacing: 0;"></th>';
            }
            //--- --- ---//
            table += '</tr>';
            //--- --- ---//
            table += '</thead>';
            table += '<tbody class="list">';
            //--- LISTA ALUMNOS ---//
            var cont = 1;
            for (var e = 0; e < data.students.length; e++) {
                //--- --- ---//
                table += '<tr>';
                table += '<td style="border-right: 1px solid; padding: 3px; width: 250px; position:absolute; left:0; ">' + data.students[e].student_name + '</td>';
                //--- --- ---//
                //--- RECORREMOS TODOS LOS COMENTARIOS ---//
                for (var i = 0; i < data.final_comments.length; i++) {
                    var answer_find = false;
                    for (var x = 0; x < data.final_comments[i].comments.length; x++) {
                        if (data.final_comments[i].comments[x].id_student == data.students[e].id_student && data.final_comments[i].comments[x].ascc_lm_assgn == data.final_comments[i].assg.ascc_lm_assgn) {
                            //--- --- ---//
                            answer_find = true;
                            //--- --- ---//
                            table += '<td style="border-right: 1px dotted; padding: 3px; width: 400px; white-space: normal" class="comments1' + cont + '" data-id-comments="' + data.final_comments[i].comments[x].id_comments + '" contenteditable="true">' + data.final_comments[i].comments[x].comments1 + '</td>';
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 60px; vertical-align: middle""><button style="width: 50px;" type="button" class="btn btn-primary btn-update" data-class-column="comments1" data-class-td-new-text="comments1' + cont + '"><i class="fas fa-sync-alt"></i></button></td>';
                            //--- --- ---//
                            table += '<td style="border-right: 1px dotted; padding: 3px; width: 400px; white-space: normal" class="comments2' + cont + '" data-id-comments="' + data.final_comments[i].comments[x].id_comments + '" contenteditable="true">' + data.final_comments[i].comments[x].comments2 + '</td>';
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 60px; vertical-align: middle"><button style="width: 50px;" type="button" class="btn btn-primary btn-update" data-class-column="comments2" data-class-td-new-text="comments2' + cont + '"><i class="fas fa-sync-alt"></i></button></td>';
                            //--- --- ---//
                            table += '<td style="border-right: 1px dotted; padding: 3px; width: 400px; white-space: normal" class="directors_comment' + cont + '" data-id-comments="' + data.final_comments[i].comments[x].id_comments + '" contenteditable="true">' + data.final_comments[i].comments[x].directors_comment + '</td>';
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 60px; vertical-align: middle"><button style="width: 50px;" type="button" class="btn btn-primary btn-update" data-class-column="directors_comment" data-class-td-new-text="directors_comment' + cont + '"><i class="fas fa-sync-alt"></i></button></td>';
                            //--- --- ---//
                        }
                    }
                    //--- --- ---//
                    if (!answer_find) {
                        //--- --- ---//
                        table += '<td style="border-right: 1px dotted; padding: 3px; width: 400px; white-space: normal" class="comments1' + cont + '" data-id-student="' + data.students[e].id_student + '" data-id-lm-assgn="' + data.final_comments[i].assg.ascc_lm_assgn + '" contenteditable="true"></td>';
                        table += '<td style="border-right: 1px solid; padding: 3px; width: 60px; vertical-align: middle""><button style="width: 50px;" type="button" class="btn btn-primary btn-new" data-class-column="comments1" data-class-td-new-text="comments1' + cont + '"><i class="fas fa-sync-alt"></i></button></td>';
                        //--- --- ---//
                        table += '<td style="border-right: 1px dotted; padding: 3px; width: 400px; white-space: normal" class="comments2" data-id-student="' + data.students[e].id_student + '" data-id-lm-assgn="' + data.final_comments[i].assg.ascc_lm_assgn + '" contenteditable="true"></td>';
                        table += '<td style="border-right: 1px solid; padding: 3px; width: 60px; vertical-align: middle"><button style="width: 50px;" type="button" class="btn btn-primary btn-new" data-class-column="comments2" data-class-td-new-text="comments2' + cont + '"><i class="fas fa-sync-alt"></i></button></td>';
                        //--- --- ---//
                        table += '<td style="border-right: 1px dotted; padding: 3px; width: 400px; white-space: normal" class="directors_comment' + cont + '" data-id-student="' + data.students[e].id_student + '" data-id-lm-assgn="' + data.final_comments[i].assg.ascc_lm_assgn + '" contenteditable="true"></td>';
                        table += '<td style="border-right: 1px solid; padding: 3px; width: 60px; vertical-align: middle"><button style="width: 50px;" type="button" class="btn btn-primary btn-new" data-class-column="directors_comment" data-class-td-new-text="directors_comment' + cont + '"><i class="fas fa-sync-alt"></i></button></td>';
                        //--- --- ---//
                    }
                    //--- --- ---//
                    cont++;
                    //--- --- ---//
                }
                //--- --- ---//
                table += '</tr>';
                //--- --- ---//
            }
            //--- --- ---//
            table += '</tbody>';
            table += '</table>';
            table += '</div>';
            //--- --- ---//
            all_data += '<div class="card-body">';
            all_data += '<div style="position:relative">';
            all_data += table;
            all_data += '</div>';
            all_data += '</div>';
            all_data += '</div>';
            all_data += '</div>';
            //--- --- ---//
        }
    }
    //--- --- ---//
    $('.div_content_mda').html(all_data);
    //--- --- ---//
    $('[data-toggle="tooltip"]').tooltip();
    swal.close();
    //--- --- ---//
}
//--- --- ---//
function AddComments(id_student, ascc_lm_assgn, colum, text) {
    //--- --- ---//
    //loading();
    var installment = $('#slct_installment').val();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'addDirectorsComments',
            id_student: id_student,
            ascc_lm_assgn: ascc_lm_assgn,
            installment: installment,
            colum: colum,
            text: text
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        swal.close();
        if (data.response) {
            typeToast = 'success';
            //--- --- ---//
        } else {
            typeToast = 'error';
        }
        VanillaToasts.create({
            title: 'Notificación',
            text: data.message,
            type: typeToast,
            timeout: 1200,
            positionClass: 'topRight'
        });
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
//--- --- ---//
function updateComments(id_comments, colum, text) {
    //--- --- ---//
    //loading();
    //--- --- ---//
    $.ajax({
        url: 'php/controllers/evaluations_cualitatives.php',
        method: 'POST',
        data: {
            mod: 'updateDirectorsComments',
            id_comments: id_comments,
            colum: colum,
            text: text
        }
    }).done(function(data) {
        var data = JSON.parse(data);
        swal.close();
        if (data.response) {
            typeToast = 'success';
            //--- --- ---//
        } else {
            typeToast = 'error';
        }
        VanillaToasts.create({
            title: 'Notificación',
            text: data.message,
            type: typeToast,
            timeout: 1200,
            positionClass: 'topRight'
        });
    }).fail(function(message) {
        Swal.fire('Error!', 'Error al intentar conectarse con la Base de Datos :/', 'error');
    });
    //--- --- ---//
}
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