//--- MDA BANGUEOLO HEBREO SECUNDARIA MUJERES ---//
function reportHighSchoolBangueoloMDAhebrew(id_group, id_student, installment) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportHighSchoolBangueoloMDAhebrew",
            id_group: id_group,
            id_student: id_student,
            installment: installment,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (data.results_evc_normal.length > 0 || data.results_evc_mejanejet.length > 0) {
                //--- --- ---//
                var table = "";
                var table1 = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div class="card-header border-0">';
                all_data += '<h4 class="mb-0">Entrega: ' + installment + "</h4>";
                all_data += '<h4 class="mb-0">Código: ' + data.students.student_code + "</h4>";
                all_data += '<h4 class="mb-0">Nombre: ' + data.students.student_name.toUpperCase() + "</h4>";
                all_data += '<h4 class="mb-0">Grupo: ' + data.students.group_code + "</h4>";
                //--- --- ---//
                var comments_director = "";
                //--- COMENTARIO DIRECTOR ---//
                if (data.comment_director.length != "") {
                    //--- --- ---//
                    for (var d = 0; d < data.comment_director.length; d++) {
                        //--- --- ---//
                        if (data.comment_director[d].comment != "") {
                            comments_director += '<div class="">';
                            comments_director += "<br/><br/><h4>COMENTARIO DEL DIRECTOR: </h4><h3>" + data.comment_director[d].comment + "</h3>";
                            comments_director += '<blockquote class="blockquote">';
                            comments_director += '<footer class="blockquote-footer"><cite title="Source Title">' + data.comment_director[d].director_name + "</cite></footer>";
                            comments_director += "</blockquote>";
                            comments_director += "</div>";
                            comments_director += "<br/><br/>";
                        }
                        //--- --- ---//
                    }
                }
                all_data += comments_director + "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal.length > 0) {
                    for (var i = 0; i < data.results_evc_normal.length; i++) {
                        if (data.results_evc_normal[i].topic.id_learning_map != 17) {
                            table += '<div class="table-responsive">';
                            table += '<table class="table" style="table-layout:fixed;">';
                            table += "<thead>";
                            table += "<tr>";
                            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                //--- ASIGNATURA ---//
                                table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                                //--- --- ---//
                            }
                            table += "</tr>";
                            //--- CATALOGO DE EVALUACIONES ---//
                            table += "<tr>";
                            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                    table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                                }
                            }
                            //--- --- ---//
                            table += "</tr>";
                            //--- --- ---//
                            table += "</thead>";
                            table += '<tbody class="list">';
                            //--- LISTA PREGUNTAS ---//
                            for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                //--- --- ---//
                                table += "<tr>";
                                table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                                //--- RESPUESTAS ---//
                                for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                    //--- --- ---//
                                    var answer_null_to_student = "";
                                    var answer_filled_to_student = "";
                                    var answer_to_question = false;
                                    //--- --- ---//
                                    for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                        //--- --- ---/
                                        var answer_find = false;
                                        //--- --- ---//
                                        var style_border = "dotted";
                                        if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                            style_border = "solid";
                                        }
                                        //--- --- ---//
                                        //--- RESPUESTAS ---//
                                        for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                            //--- --- ---//
                                            if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                                answer_find = true;
                                                answer_to_question = true;
                                                answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                                table += "</tr>";
                                //--- --- ---//
                            }
                            //--- --- ---//
                            //--- COMENTARIOS FINALES ---//
                            if (data.final_comments_evc_normal.length > 0) {
                                //--- --- ---//
                                table += "<tr>";
                                table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                                //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                                for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                    var find_comment = false;
                                    for (var d = 0; d < data.final_comments_evc_normal.length; d++) {
                                        for (var x = 0; x < data.final_comments_evc_normal[d].comments.length; x++) {
                                            if (data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn == data.final_comments_evc_normal[d].assg.ascc_lm_assgn && data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn) {
                                                //--- --- ---//
                                                find_comment = true;
                                                table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_evc_normal[d].comments[x].comments1 + "</td>";
                                                //--- --- ---//
                                            }
                                        }
                                    }
                                    //--- --- ---//
                                    if (!find_comment) {
                                        table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"></td>';
                                    }
                                    //--- --- ---//
                                }
                                table += "</tr>";
                            }
                            //--- --- ---//
                            //--- --- ---//
                            table += "</tbody>";
                            table += "</table>";
                            table += "</div>";
                        }
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- MDA MEJANEJET ---//
                if (data.results_evc_mejanejet.length > 0) {
                    for (var i = 0; i < data.results_evc_mejanejet.length; i++) {
                        table1 += '<div class="table-responsive">';
                        table1 += '<table class="table" style="table-layout:fixed;">';
                        table1 += "<thead>";
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table1 += '<th class="text-center" scope="col" style="width:' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_mejanejet[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_mejanejet[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table1 += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                table1 += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table1 += "</tr>";
                        //--- --- ---//
                        table1 += "</thead>";
                        table1 += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_mejanejet[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_mejanejet[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_mejanejet[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_mejanejet[i].topic.assc_mpa_id == data.results_evc_mejanejet[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].colorHTML + '" ></td>';
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
                                    table1 += answer_filled_to_student;
                                } else {
                                    table1 += answer_null_to_student;
                                }
                                //--- --- ---//
                            }
                            //--- --- ---//
                            table1 += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        //--- COMENTARIOS FINALES ---//
                        if (data.final_comments_mejanejet.length > 0) {
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                var find_comment = false;
                                for (var d = 0; d < data.final_comments_mejanejet.length; d++) {
                                    for (var x = 0; x < data.final_comments_mejanejet[d].comments.length; x++) {
                                        if (data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn == data.final_comments_mejanejet[d].assg.ascc_lm_assgn && data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn) {
                                            //--- --- ---//
                                            find_comment = true;
                                            table1 += '<td class="text-center" scope="col" style="font-weight: bold; white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_mejanejet[d].comments[x].comments1 + "</td>";
                                            //--- --- ---//
                                        }
                                    }
                                }
                                //--- --- ---//
                                if (!find_comment) {
                                    table1 += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"></td>';
                                }
                                //--- --- ---//
                            }
                            table1 += "</tr>";
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table1 += "</tbody>";
                        table1 += "</table>";
                        table1 += "</div>";
                        //--- --- ---//
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table + "<br/><br/>" + table1;
                all_data += "</div>";
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
}
//--- MDA INTERLOMAS HEBREO SECUNDARIA MUJERES ---//
function reportHighSchoolInterlomasMDAhebrew(id_group, id_student, installment) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportHighSchoolInterlomasMDAhebrew",
            id_group: id_group,
            id_student: id_student,
            installment: installment,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (data.results_evc_normal.length > 0 || data.results_evc_mejanejet.length > 0) {
                //--- --- ---//
                var table = "";
                var table1 = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div class="card-header border-0">';
                all_data += '<h4 class="mb-0">Entrega: ' + installment + "</h4>";
                all_data += '<h4 class="mb-0">Código: ' + data.students.student_code + "</h4>";
                all_data += '<h4 class="mb-0">Nombre: ' + data.students.student_name.toUpperCase() + "</h4>";
                all_data += '<h4 class="mb-0">Grupo: ' + data.students.group_code + "</h4>";
                //--- --- ---//
                var comments_director = "";
                //--- COMENTARIO DIRECTOR ---//
                if (data.comment_director.length != "") {
                    //--- --- ---//
                    for (var d = 0; d < data.comment_director.length; d++) {
                        //--- --- ---//
                        if (data.comment_director[d].comment != "") {
                            comments_director += '<div class="">';
                            comments_director += "<br/><br/><h4>COMENTARIO DEL DIRECTOR: </h4><h3>" + data.comment_director[d].comment + "</h3>";
                            comments_director += '<blockquote class="blockquote">';
                            comments_director += '<footer class="blockquote-footer"><cite title="Source Title">' + data.comment_director[d].director_name + "</cite></footer>";
                            comments_director += "</blockquote>";
                            comments_director += "</div>";
                            comments_director += "<br/><br/>";
                        }
                        //--- --- ---//
                    }
                }
                all_data += comments_director + "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal.length > 0) {
                    for (var i = 0; i < data.results_evc_normal.length; i++) {
                        table += '<div class="table-responsive">';
                        table += '<table class="table" style="table-layout:fixed;">';
                        table += "<thead>";
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table += "</tr>";
                        //--- --- ---//
                        table += "</thead>";
                        table += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                            table += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        //--- COMENTARIOS FINALES ---//
                        if (data.final_comments_evc_normal.length > 0) {
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                var find_comment = false;
                                for (var d = 0; d < data.final_comments_evc_normal.length; d++) {
                                    for (var x = 0; x < data.final_comments_evc_normal[d].comments.length; x++) {
                                        if (data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn == data.final_comments_evc_normal[d].assg.ascc_lm_assgn && data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn) {
                                            //--- --- ---//
                                            find_comment = true;
                                            table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_evc_normal[d].comments[x].comments1 + "</td>";
                                            //--- --- ---//
                                        }
                                    }
                                }
                                //--- --- ---//
                                if (!find_comment) {
                                    table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"></td>';
                                }
                                //--- --- ---//
                            }
                            table += "</tr>";
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- MDA MEJANEJET ---//
                if (data.results_evc_mejanejet.length > 0) {
                    for (var i = 0; i < data.results_evc_mejanejet.length; i++) {
                        table1 += '<div class="table-responsive">';
                        table1 += '<table class="table" style="table-layout:fixed;">';
                        table1 += "<thead>";
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table1 += '<th class="text-center" scope="col" style="width:' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_mejanejet[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_mejanejet[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table1 += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                table1 += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table1 += "</tr>";
                        //--- --- ---//
                        table1 += "</thead>";
                        table1 += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_mejanejet[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_mejanejet[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_mejanejet[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_mejanejet[i].topic.assc_mpa_id == data.results_evc_mejanejet[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].colorHTML + '" ></td>';
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
                                    table1 += answer_filled_to_student;
                                } else {
                                    table1 += answer_null_to_student;
                                }
                                //--- --- ---//
                            }
                            //--- --- ---//
                            table1 += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        //--- COMENTARIOS FINALES ---//
                        if (data.final_comments_mejanejet.length > 0) {
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                var find_comment = false;
                                for (var d = 0; d < data.final_comments_mejanejet.length; d++) {
                                    for (var x = 0; x < data.final_comments_mejanejet[d].comments.length; x++) {
                                        if (data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn == data.final_comments_mejanejet[d].assg.ascc_lm_assgn && data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn) {
                                            //--- --- ---//
                                            find_comment = true;
                                            table1 += '<td class="text-center" scope="col" style="font-weight: bold; white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_mejanejet[d].comments[x].comments1 + "</td>";
                                            //--- --- ---//
                                        }
                                    }
                                }
                                //--- --- ---//
                                if (!find_comment) {
                                    table1 += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"></td>';
                                }
                                //--- --- ---//
                            }
                            table1 += "</tr>";
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table1 += "</tbody>";
                        table1 += "</table>";
                        table1 += "</div>";
                        //--- --- ---//
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table + "<br/><br/>" + table1;
                all_data += "</div>";
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
}
//--- MDA BANGUEOLO HEBREO PREPARATORIA MUJERES ---//
function reportPreparatoryBangueoloMDAhebrew(id_group, id_student, installment) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportPreparatoryBangueoloMDAhebrew",
            id_group: id_group,
            id_student: id_student,
            installment: installment,
        },
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (data.results_evc_normal.length > 0 || data.results_evc_mejanejet.length > 0) {
                //--- --- ---//
                var table = "";
                var table1 = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div class="card-header border-0">';
                all_data += '<h4 class="mb-0">Entrega: ' + installment + "</h4>";
                all_data += '<h4 class="mb-0">Código: ' + data.students.student_code + "</h4>";
                all_data += '<h4 class="mb-0">Nombre: ' + data.students.student_name.toUpperCase() + "</h4>";
                all_data += '<h4 class="mb-0">Grupo: ' + data.students.group_code + "</h4>";
                //--- --- ---//
                var comments_director = "";
                //--- COMENTARIO DIRECTOR ---//
                if (data.comment_director.length != "") {
                    //--- --- ---//
                    for (var d = 0; d < data.comment_director.length; d++) {
                        //--- --- ---//
                        if (data.comment_director[d].comment != "") {
                            comments_director += '<div class="">';
                            comments_director += "<br/><br/><h4>COMENTARIO DEL DIRECTOR: </h4><h3>" + data.comment_director[d].comment + "</h3>";
                            comments_director += '<blockquote class="blockquote">';
                            comments_director += '<footer class="blockquote-footer"><cite title="Source Title">' + data.comment_director[d].director_name + "</cite></footer>";
                            comments_director += "</blockquote>";
                            comments_director += "</div>";
                            comments_director += "<br/><br/>";
                        }
                        //--- --- ---//
                    }
                }
                all_data += comments_director + "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal.length > 0) {
                    for (var i = 0; i < data.results_evc_normal.length; i++) {
                        table += '<div class="table-responsive">';
                        table += '<table class="table" style="table-layout:fixed;">';
                        table += "<thead>";
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table += "</tr>";
                        //--- --- ---//
                        table += "</thead>";
                        table += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                            table += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        //--- COMENTARIOS FINALES ---//
                        if (data.final_comments_evc_normal.length > 0) {
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                var find_comment = false;
                                for (var d = 0; d < data.final_comments_evc_normal.length; d++) {
                                    for (var x = 0; x < data.final_comments_evc_normal[d].comments.length; x++) {
                                        if (data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn == data.final_comments_evc_normal[d].assg.ascc_lm_assgn && data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn) {
                                            //--- --- ---//
                                            find_comment = true;
                                            table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_evc_normal[d].comments[x].comments1 + "</td>";
                                            //--- --- ---//
                                        }
                                    }
                                }
                                //--- --- ---//
                                if (!find_comment) {
                                    table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"></td>';
                                }
                                //--- --- ---//
                            }
                            table += "</tr>";
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- MDA MEJANEJET ---//
                if (data.results_evc_mejanejet.length > 0) {
                    for (var i = 0; i < data.results_evc_mejanejet.length; i++) {
                        table1 += '<div class="table-responsive">';
                        table1 += '<table class="table" style="table-layout:fixed;">';
                        table1 += "<thead>";
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table1 += '<th class="text-center" scope="col" style="width:' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_mejanejet[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_mejanejet[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table1 += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                table1 += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table1 += "</tr>";
                        //--- --- ---//
                        table1 += "</thead>";
                        table1 += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_mejanejet[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_mejanejet[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_mejanejet[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_mejanejet[i].topic.assc_mpa_id == data.results_evc_mejanejet[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].colorHTML + '" ></td>';
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
                                    table1 += answer_filled_to_student;
                                } else {
                                    table1 += answer_null_to_student;
                                }
                                //--- --- ---//
                            }
                            //--- --- ---//
                            table1 += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        //--- COMENTARIOS FINALES ---//
                        if (data.final_comments_mejanejet.length > 0) {
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                var find_comment = false;
                                for (var d = 0; d < data.final_comments_mejanejet.length; d++) {
                                    for (var x = 0; x < data.final_comments_mejanejet[d].comments.length; x++) {
                                        if (data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn == data.final_comments_mejanejet[d].assg.ascc_lm_assgn && data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn) {
                                            //--- --- ---//
                                            find_comment = true;
                                            table1 += '<td class="text-center" scope="col" style="font-weight: bold; white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_mejanejet[d].comments[x].comments1 + "</td>";
                                            //--- --- ---//
                                        }
                                    }
                                }
                                //--- --- ---//
                                if (!find_comment) {
                                    table1 += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"></td>';
                                }
                                //--- --- ---//
                            }
                            table1 += "</tr>";
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table1 += "</tbody>";
                        table1 += "</table>";
                        table1 += "</div>";
                        //--- --- ---//
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table + "<br/><br/>" + table1;
                all_data += "</div>";
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
}
//--- MDA BANGUEOLO ESPAÑOL PREPARATORIA MUJERES ---//
function reportPreparatoryBangueoloMDAspanish(id_group, id_student, installment) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportPreparatoryBangueoloMDAspanish",
            id_group: id_group,
            id_student: id_student,
            installment: installment,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (data.results_evc_normal.length > 0 || data.results_evc_mejanejet.length > 0) {
                //--- --- ---//
                var table = "";
                var table1 = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div class="card-header border-0">';
                all_data += '<h4 class="mb-0">Entrega: ' + installment + "</h4>";
                all_data += '<h4 class="mb-0">Código: ' + data.students.student_code + "</h4>";
                all_data += '<h4 class="mb-0">Nombre: ' + data.students.student_name.toUpperCase() + "</h4>";
                all_data += '<h4 class="mb-0">Grupo: ' + data.students.group_code + "</h4>";
                //--- --- ---//
                var comments_director = "";
                //--- COMENTARIO DIRECTOR ---//
                if (data.comment_director.length != "") {
                    //--- --- ---//
                    for (var d = 0; d < data.comment_director.length; d++) {
                        //--- --- ---//
                        if (data.comment_director[d].comment != "") {
                            comments_director += '<div class="">';
                            comments_director += "<br/><br/><h4>COMENTARIO DEL DIRECTOR: </h4><h3>" + data.comment_director[d].comment + "</h3>";
                            comments_director += '<blockquote class="blockquote">';
                            comments_director += '<footer class="blockquote-footer"><cite title="Source Title">' + data.comment_director[d].director_name + "</cite></footer>";
                            comments_director += "</blockquote>";
                            comments_director += "</div>";
                            comments_director += "<br/><br/>";
                        }
                        //--- --- ---//
                    }
                }
                all_data += comments_director + "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal.length > 0) {
                    for (var i = 0; i < data.results_evc_normal.length; i++) {
                        table += '<div class="table-responsive">';
                        table += '<table class="table" style="table-layout:fixed;">';
                        table += "<thead>";
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table += "</tr>";
                        //--- --- ---//
                        table += "</thead>";
                        table += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                            table += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        //--- COMENTARIOS FINALES ---//
                        if (data.final_comments_evc_normal.length > 0) {
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                var find_comment = false;
                                for (var d = 0; d < data.final_comments_evc_normal.length; d++) {
                                    for (var x = 0; x < data.final_comments_evc_normal[d].comments.length; x++) {
                                        if (data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn == data.final_comments_evc_normal[d].assg.ascc_lm_assgn && data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_evc_normal[d].comments[x].ascc_lm_assgn) {
                                            //--- --- ---//
                                            find_comment = true;
                                            table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_evc_normal[d].comments[x].comments1 + "</td>";
                                            //--- --- ---//
                                        }
                                    }
                                }
                                //--- --- ---//
                                if (!find_comment) {
                                    table += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"></td>';
                                }
                                //--- --- ---//
                            }
                            table += "</tr>";
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- MDA MEJANEJET ---//
                if (data.results_evc_mejanejet.length > 0) {
                    for (var i = 0; i < data.results_evc_mejanejet.length; i++) {
                        table1 += '<div class="table-responsive">';
                        table1 += '<table class="table" style="table-layout:fixed;">';
                        table1 += "<thead>";
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table1 += '<th class="text-center" scope="col" style="width:' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_mejanejet[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_mejanejet[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table1 += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table1 += "<tr>";
                        table1 += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                table1 += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table1 += "</tr>";
                        //--- --- ---//
                        table1 += "</thead>";
                        table1 += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_mejanejet[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_mejanejet[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_mejanejet[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_mejanejet[i].topic.assc_mpa_id == data.results_evc_mejanejet[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_mejanejet[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_mejanejet[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations[b].colorHTML + '" ></td>';
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
                                    table1 += answer_filled_to_student;
                                } else {
                                    table1 += answer_null_to_student;
                                }
                                //--- --- ---//
                            }
                            //--- --- ---//
                            table1 += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        //--- COMENTARIOS FINALES ---//
                        if (data.final_comments_mejanejet.length > 0) {
                            //--- --- ---//
                            table1 += "<tr>";
                            table1 += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></td>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_mejanejet[i].data.assgs.length; a++) {
                                var find_comment = false;
                                for (var d = 0; d < data.final_comments_mejanejet.length; d++) {
                                    for (var x = 0; x < data.final_comments_mejanejet[d].comments.length; x++) {
                                        if (data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn == data.final_comments_mejanejet[d].assg.ascc_lm_assgn && data.results_evc_mejanejet[i].data.assgs[a].assg.ascc_lm_assgn == data.final_comments_mejanejet[d].comments[x].ascc_lm_assgn) {
                                            //--- --- ---//
                                            find_comment = true;
                                            table1 += '<td class="text-center" scope="col" style="font-weight: bold; white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '">' + data.final_comments_mejanejet[d].comments[x].comments1 + "</td>";
                                            //--- --- ---//
                                        }
                                    }
                                }
                                //--- --- ---//
                                if (!find_comment) {
                                    table1 += '<td class="text-center" scope="col" style="white-space: normal; vertical-align: top; letter-spacing: 0;" colspan="' + data.results_evc_mejanejet[i].data.questions_evaluations.evaluations.length + '"></td>';
                                }
                                //--- --- ---//
                            }
                            table1 += "</tr>";
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table1 += "</tbody>";
                        table1 += "</table>";
                        table1 += "</div>";
                        //--- --- ---//
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table + "<br/><br/>" + table1;
                all_data += "</div>";
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
} //--- --- ---//
//--- MDA BANGUEOLO ESPAÑOL SECUNDARIA MUJERES ---//
function reportHighSchoolBangueoloMDASpanish(id_group, id_student, installment) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportHighSchoolBangueoloMDASpanish",
            id_group: id_group,
            installment: installment,
            id_student: id_student,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (data.results_evc_normal.length) {
                //--- --- ---//
                var table = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div class="card-header border-0">';
                all_data += '<h4 class="mb-0">Entrega: ' + installment + "</h4>";
                all_data += '<h4 class="mb-0">Código: ' + data.students.student_code + "</h4>";
                all_data += '<h4 class="mb-0">Nombre: ' + data.students.student_name.toUpperCase() + "</h4>";
                all_data += '<h4 class="mb-0">Grupo: ' + data.students.group_code + "</h4>";
                all_data += "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal.length > 0) {
                    for (var i = 0; i < data.results_evc_normal.length; i++) {
                        table += '<div class="table-responsive mt-5">';
                        table += '<h3 class="mb-0"><b>' + data.results_evc_normal[i].topic.name_question_group + "</b></h3><br/>";
                        table += '<table class="table" style="table-layout:fixed;">';
                        table += "<thead>";
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table += "</tr>";
                        //--- --- ---//
                        table += "</thead>";
                        table += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                            table += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                    }
                    //--- COMENTARIOS FINALES ---//
                    //--- ---- ---//
                    if (data.final_comments_evc_normal.length > 0) {
                        table += '<div class="table-responsive mt-5">';
                        table += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                        table += '<table class="table" style="table-layout:fixed;">';
                        table += "<thead>";
                        table += "<tr>";
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.assgns.length; a++) {
                            //--- ASIGNATURA ---//
                            table += '<th class="text-center" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>' + data.assgns[a].name_subject + "</b><br/>" + data.assgns[a].teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table += "</tr>";
                        table += "</thead>";
                        table += '<tbody class="list">';
                        //--- --- ---//
                        table += "<tr>";
                        //--- --- ---//
                        //--- COMENTARIOS ---//
                        for (var a = 0; a < data.assgns.length; a++) {
                            for (var b = 0; b < data.final_comments_evc_normal.length; b++) {
                                //--- --- ---/
                                if (data.assgns[a].id_assignment == data.final_comments_evc_normal[b].id_assignment) {
                                    if (data.final_comments_evc_normal[b].comments.length > 0) {
                                        table += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal[b].comments[0].comments1 + " </td>";
                                    } else {
                                        table += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                                    }
                                }
                                //--- --- ---//
                            }
                        }
                        //--- --- ---//
                        table += "</tr>";
                        //--- --- ---//
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table;
                all_data += "</div>";
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
}

function reportHighSchoolBangueoloMDASpanish(id_group, id_student, installment) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportHighSchoolBangueoloMDASpanish",
            id_group: id_group,
            installment: installment,
            id_student: id_student,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (data.results_evc_normal.length) {
                //--- --- ---//
                var table = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div class="card-header border-0">';
                all_data += '<h4 class="mb-0">Entrega: ' + installment + "</h4>";
                all_data += '<h4 class="mb-0">Código: ' + data.students.student_code + "</h4>";
                all_data += '<h4 class="mb-0">Nombre: ' + data.students.student_name.toUpperCase() + "</h4>";
                all_data += '<h4 class="mb-0">Grupo: ' + data.students.group_code + "</h4>";
                all_data += "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal.length > 0) {
                    for (var i = 0; i < data.results_evc_normal.length; i++) {
                        table += '<div class="table-responsive mt-5">';
                        table += '<h3 class="mb-0"><b>' + data.results_evc_normal[i].topic.name_question_group + "</b></h3><br/>";
                        table += '<table class="table" style="table-layout:fixed;">';
                        table += "<thead>";
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            //--- ASIGNATURA ---//
                            table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table += "</tr>";
                        //--- CATALOGO DE EVALUACIONES ---//
                        table += "<tr>";
                        table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                        for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                            for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                            }
                        }
                        //--- --- ---//
                        table += "</tr>";
                        //--- --- ---//
                        table += "</thead>";
                        table += '<tbody class="list">';
                        //--- LISTA PREGUNTAS ---//
                        for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                            //--- --- ---//
                            var answer_null_to_student = "";
                            var answer_filled_to_student = "";
                            //--- --- ---//
                            table += "<tr>";
                            table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                            //--- RESPUESTAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                var answer_to_question = false;
                                //--- --- ---//
                                for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                    //--- --- ---/
                                    var answer_find = false;
                                    //--- --- ---//
                                    var style_border = "dotted";
                                    if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                        style_border = "solid";
                                    }
                                    //--- --- ---//
                                    //--- RESPUESTAS ---//
                                    for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                        //--- --- ---//
                                        if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                            answer_find = true;
                                            answer_to_question = true;
                                            answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                            table += "</tr>";
                            //--- --- ---//
                        }
                        //--- --- ---//
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                    }
                    //--- COMENTARIOS FINALES ---//
                    //--- ---- ---//
                    if (data.final_comments_evc_normal.length > 0) {
                        table += '<div class="table-responsive mt-5">';
                        table += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                        table += '<table class="table" style="table-layout:fixed;">';
                        table += "<thead>";
                        table += "<tr>";
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        for (var a = 0; a < data.assgns.length; a++) {
                            //--- ASIGNATURA ---//
                            table += '<th class="text-center" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>' + data.assgns[a].name_subject + "</b><br/>" + data.assgns[a].teacher_name + "</th>";
                            //--- --- ---//
                        }
                        table += "</tr>";
                        table += "</thead>";
                        table += '<tbody class="list">';
                        //--- --- ---//
                        table += "<tr>";
                        //--- --- ---//
                        //--- COMENTARIOS ---//
                        for (var a = 0; a < data.assgns.length; a++) {
                            for (var b = 0; b < data.final_comments_evc_normal.length; b++) {
                                //--- --- ---/
                                if (data.assgns[a].id_assignment == data.final_comments_evc_normal[b].id_assignment) {
                                    if (data.final_comments_evc_normal[b].comments.length > 0) {
                                        table += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal[b].comments[0].comments1 + " </td>";
                                    } else {
                                        table += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                                    }
                                }
                                //--- --- ---//
                            }
                        }
                        //--- --- ---//
                        table += "</tr>";
                        //--- --- ---//
                        table += "</tbody>";
                        table += "</table>";
                        table += "</div>";
                    }
                    //--- ---- ---//
                    //--- ---- ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table;
                all_data += "</div>";
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
}

function reportPrimaryBangueoloSpanishAndEnglish(id_group, id_student, installment) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportPrimaryBangueoloMDASpanishAndEnglish",
            id_group: id_group,
            installment: installment,
            id_student: id_student,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            if (data.results_evc_normal.length) {
                //--- --- ---//
                var table1 = "";
                var table = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div class="card-header border-0 justify-content-center">';
                all_data += '<h2 style="text-align: center !important;" class="mb-0 text-uppercase" > INFORME CUALITATIVO | Entrega: ' + installment + "</h2>";
                all_data += "<input type='hidden' id='NameDoc' value='" + data.students.student_code + " - INFORME CUALITATIVO EVALUACIÓN ING Y ESP'/>";
                all_data += '<h4 style="text-align: center !important;" class="mb-0 text-uppercase">Alumno: ' + data.students.student_code + " | " + data.students.student_name + "</h4>";
                all_data += '<h4 style="text-align: center !important;" class="mb-0 text-uppercase">Grupo: ' + data.students.group_code + "</h4><br><br>";
                all_data += '<button type="button" class="btn btn-danger generatePDF2"><i class="fa-regular fa-file-pdf"></i></button>';
                all_data += "</div>";
                //--- --- ---//
                all_data += '<div id="questionsSpanish">';
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += '<h1 style="text-align: center !important;" class="text-center">MAPAS DE APRENDIZAJE: EVALUACIÓN DE ESPAÑOL</h3>';
                all_data += "</div>";
                all_data += "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal.length > 0) {
                    for (var i = 0; i < data.results_evc_normal.length; i++) {
                        console.log(data.results_evc_normal_eng);
                        if ((data.results_evc_normal[i].topic.id_learning_map = "21")) {
                            table += '<div class="table-responsive mt-5">';
                            table += '<h3 class="mb-0  text-uppercase"><b>' + data.results_evc_normal[i].topic.name_question_group + "</b></h3><br/>";
                            table += '<table class="table" style="table-layout:fixed;">';
                            table += "<thead>";
                            table += "<tr>";
                            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                //--- ASIGNATURA ---//
                                table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                                //--- --- ---//
                            }
                            table += "</tr>";
                            //--- CATALOGO DE EVALUACIONES ---//
                            table += "<tr>";
                            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                            for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                    table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                                }
                            }
                            //--- --- ---//
                            table += "</tr>";
                            //--- --- ---//
                            table += "</thead>";
                            table += '<tbody class="list">';
                            //--- LISTA PREGUNTAS ---//
                            for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                //--- --- ---//
                                table += "<tr>";
                                table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                                //--- RESPUESTAS ---//
                                for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                    //--- --- ---//
                                    var answer_null_to_student = "";
                                    var answer_filled_to_student = "";
                                    var answer_to_question = false;
                                    //--- --- ---//
                                    for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                        //--- --- ---/
                                        var answer_find = false;
                                        //--- --- ---//
                                        var style_border = "dotted";
                                        if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                            style_border = "solid";
                                        }
                                        //--- --- ---//
                                        //--- RESPUESTAS ---//
                                        for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                            //--- --- ---//
                                            if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                                answer_find = true;
                                                answer_to_question = true;
                                                answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                                table += "</tr>";
                                //--- --- ---//
                            }
                            //--- --- ---//
                            table += "</tbody>";
                            table += "</table>";
                            table += "</div>";
                        }
                    }
                    //--- COMENTARIOS FINALES ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table;
                all_data += "</div>";
                if (data.final_comments_evc_normal.length > 0) {
                    table1 += '<div class="table-responsive mt-5">';
                    table1 += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                    table1 += '<table class="table" style="table-layout:fixed;">';
                    table1 += "<thead>";
                    table1 += "<tr>";
                    //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                    //--- ASIGNATURA ---//
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Fortalezas</b></th>';
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Areas de Oportunidad</b></th>';
                    //--- --- ---//
                    table1 += "</tr>";
                    table1 += "</thead>";
                    table1 += '<tbody class="list">';
                    //--- --- ---//
                    table1 += "<tr>";
                    //--- --- ---//
                    //--- COMENTARIOS ---//
                    //--- --- ---/
                    if (data.final_comments_evc_normal[0].comments.length > 0) {
                        table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal[0].comments[0].comments1 + " </td>";
                        table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal[0].comments[0].comments2 + " </td>";
                    } else {
                        table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                    }
                    //--- --- ---//
                    //--- --- ---//
                    table1 += "</tr>";
                    //--- --- ---//
                    table1 += "</tbody>";
                    table1 += "</table>";
                    table1 += "</div>";
                    all_data += '<div class="card-body" style="background-color: white">';
                    all_data += table1;
                    all_data += "</div>";
                } else {
                    table1 += '<div class="table-responsive mt-5">';
                    table1 += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                    table1 += '<table class="table" style="table-layout:fixed;">';
                    table1 += "<thead>";
                    table1 += "<tr>";
                    //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                    //--- ASIGNATURA ---//
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Fortalezas</b></th>';
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Areas de Oportunidad</b></th>';
                    //--- --- ---//
                    table1 += "</tr>";
                    table1 += "</thead>";
                    table1 += '<tbody class="list">';
                    //--- --- ---//
                    table1 += "<tr>";
                    //--- --- ---//
                    //--- COMENTARIOS ---//
                    //--- --- ---/
                    table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                    ('<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>');
                    //--- --- ---//
                    //--- --- ---//
                    table1 += "</tr>";
                    //--- --- ---//
                    table1 += "</tbody>";
                    table1 += "</table>";
                    table1 += "</div>";
                    all_data += '<div class="card-body" style="background-color: white">';
                    all_data += table1;
                    all_data += "</div>";
                }
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            if (data.results_evc_normal_eng.length) {
                //--- --- ---//
                var table = "";
                //--- --- ---//
                var all_data = '<div class="card" id="div-report">';
                //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                all_data += '<div id="questionsSpanish">';
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += '<h1 class="text-center">MAPAS DE APRENDIZAJE: EVALUACIÓN DE INGLÉS</h3>';
                all_data += "</div>";
                all_data += "</div>";
                //--- --- ---//
                //--- MDA GENERAL ---//
                if (data.results_evc_normal_eng.length > 0) {
                    for (var i = 0; i < data.results_evc_normal_eng.length; i++) {
                        if (
                            (data.results_evc_normal_eng[i].topic.id_learning_map = "21")) {
                            table += '<div class="table-responsive mt-5">';
                            table += '<h3 class="mb-0  text-uppercase"><b>' + data.results_evc_normal_eng[i].topic.name_question_group + "</b></h3><br/>";
                            table += '<table class="table" style="table-layout:fixed;">';
                            table += "<thead>";
                            table += "<tr>";
                            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                            //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                            for (var a = 0; a < data.results_evc_normal_eng[i].data.assgs.length; a++) {
                                //--- ASIGNATURA ---//
                                table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal_eng[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal_eng[i].data.questions_evaluations.evaluations.length + '"><b>' + data.results_evc_normal_eng[i].data.assgs[a].assg.name_subject + "</b><br/>" + data.results_evc_normal_eng[i].data.assgs[a].assg.teacher_name + "</th>";
                                //--- --- ---//
                            }
                            table += "</tr>";
                            //--- CATALOGO DE EVALUACIONES ---//
                            table += "<tr>";
                            table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                            for (var a = 0; a < data.results_evc_normal_eng[i].data.assgs.length; a++) {
                                for (var b = 0; b < data.results_evc_normal_eng[i].data.questions_evaluations.evaluations.length; b++) {
                                    table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal_eng[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal_eng[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                                }
                            }
                            //--- --- ---//
                            table += "</tr>";
                            //--- --- ---//
                            table += "</thead>";
                            table += '<tbody class="list">';
                            //--- LISTA PREGUNTAS ---//
                            for (var f = 0; f < data.results_evc_normal_eng[i].data.questions_evaluations.questions.length; f++) {
                                //--- --- ---//
                                var answer_null_to_student = "";
                                var answer_filled_to_student = "";
                                //--- --- ---//
                                table += "<tr>";
                                table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal_eng[i].data.questions_evaluations.questions[f].question + "</td>";
                                //--- RESPUESTAS ---//
                                for (var a = 0; a < data.results_evc_normal_eng[i].data.assgs.length; a++) {
                                    //--- --- ---//
                                    var answer_null_to_student = "";
                                    var answer_filled_to_student = "";
                                    var answer_to_question = false;
                                    //--- --- ---//
                                    for (var b = 0; b < data.results_evc_normal_eng[i].data.questions_evaluations.evaluations.length; b++) {
                                        //--- --- ---/
                                        var answer_find = false;
                                        //--- --- ---//
                                        var style_border = "dotted";
                                        if (b + 1 >= data.results_evc_normal_eng[i].data.questions_evaluations.evaluations.length) {
                                            style_border = "solid";
                                        }
                                        //--- --- ---//
                                        //--- RESPUESTAS ---//
                                        for (var d = 0; d < data.results_evc_normal_eng[i].data.assgs[a].answers.length; d++) {
                                            //--- --- ---//
                                            if (data.results_evc_normal_eng[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal_eng[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal_eng[i].topic.assc_mpa_id == data.results_evc_normal_eng[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal_eng[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal_eng[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal_eng[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal_eng[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                                answer_find = true;
                                                answer_to_question = true;
                                                answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal_eng[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal_eng[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                                table += "</tr>";
                                //--- --- ---//
                            }
                            //--- --- ---//
                            table += "</tbody>";
                            table += "</table>";
                            table += "</div>";
                        }
                    }
                    //--- COMENTARIOS FINALES ---//
                    //--- ---- ---//
                }
                //--- --- --//
                //--- --- ---//
                all_data += '<div class="card-body" style="background-color: white">';
                all_data += table;
                all_data += "</div>";
                table1 = "";
                if (data.final_comments_evc_normal_eng.length > 0) {
                    table1 += '<div class="table-responsive mt-5">';
                    table1 += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                    table1 += '<table class="table" style="table-layout:fixed;">';
                    table1 += "<thead>";
                    table1 += "<tr>";
                    //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                    //--- ASIGNATURA ---//
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Fortalezas</b></th>';
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Areas de Oportunidad</b></th>';
                    //--- --- ---//
                    table1 += "</tr>";
                    table1 += "</thead>";
                    table1 += '<tbody class="list">';
                    //--- --- ---//
                    table1 += "<tr>";
                    //--- --- ---//
                    //--- COMENTARIOS ---//
                    //--- --- ---/
                    console.log(data.final_comments_evc_normal_eng);
                    if (data.final_comments_evc_normal_eng[0].comments.length > 0) {
                        table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal_eng[0].comments[0].comments1 + " </td>";
                        table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal_eng[0].comments[0].comments2 + " </td>";
                    } else {
                        table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                    }
                    //--- --- ---//
                    //--- --- ---//
                    table1 += "</tr>";
                    //--- --- ---//
                    table1 += "</tbody>";
                    table1 += "</table>";
                    table1 += "</div>";
                    all_data += '<div class="card-body" style="background-color: white">';
                    all_data += table1;
                    all_data += "</div>";
                } else {
                    table1 += '<div class="table-responsive mt-5">';
                    table1 += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                    table1 += '<table class="table" style="table-layout:fixed;">';
                    table1 += "<thead>";
                    table1 += "<tr>";
                    //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                    //--- ASIGNATURA ---//
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Fortalezas</b></th>';
                    table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Areas de Oportunidad</b></th>';
                    //--- --- ---//
                    table1 += "</tr>";
                    table1 += "</thead>";
                    table1 += '<tbody class="list">';
                    //--- --- ---//
                    table1 += "<tr>";
                    //--- --- ---//
                    //--- COMENTARIOS ---//
                    //--- --- ---/
                    table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                    ('<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>');
                    //--- --- ---//
                    //--- --- ---//
                    table1 += "</tr>";
                    //--- --- ---//
                    table1 += "</tbody>";
                    table1 += "</table>";
                    table1 += "</div>";
                    all_data += '<div class="card-body" style="background-color: white">';
                    all_data += table1;
                    all_data += "</div>";
                }
                //--- --- ---//
                all_data += "</div>";
                //--- --- ---//
                //--- --- ---//
            }
            $(".div_content_mda").append(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
}

function reportPrimaryBangueoloHebrew(id_group, id_student, installment, show_download) {
    //--- --- ---//
    loading();
    //--- --- ---//
    $.ajax({
        url: "php/controllers/reports_evaluations_cualitatives.php",
        method: "POST",
        data: {
            mod: "reportPrimaryBangueoloMDAHebrew",
            id_group: id_group,
            installment: installment,
            id_student: id_student,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            console.log(data);
            //--- --- ---//
            if (data.results_evc_normal.length) {
                //--- --- ---//
                if (show_download == 'download') {
                    downloadReportPrimaryBangueoloHebrew(data, installment);
                } else {
                    var table1 = "";
                    var table = "";
                    //--- --- ---//
                    var all_data = '<div class="card" id="div-report">';
                    //all_data += '<div class="card-header border-0"><h3 class="mb-0"><b>' + data.results_evc_mejanejet[i].topic.name_question_group + '</b></h3><br/>';
                    all_data += '<div class="card-header border-0 justify-content-center">';
                    all_data += '<h2 style="text-align: center !important;" class="mb-0 text-uppercase"> INFORME CUALITATIVO | Entrega: ' + installment + "</h2>";
                    all_data += "<input type='hidden' id='NameDoc' value='" + data.students.student_code + " - INFORME CUALITATIVO EVALUACIÓN HEBREO'/>";
                    all_data += '<h4 style="text-align: center !important;" class="mb-0 text-uppercase">Alumno: ' + data.students.student_code + " | " + data.students.student_name + "</h4>";
                    all_data += '<h4 style="text-align: center !important;" class="mb-0 text-uppercase">Grupo: ' + data.students.group_code + "</h4><br><br>";
                    all_data += '<button type="button" class="btn btn-danger" onclick="reportPrimaryBangueoloHebrew(' + id_group + ', ' + id_student + ', ' + installment + ', \'download\')"><i class="fa-regular fa-file-pdf"></i></button>';
                    all_data += "</div>";
                    //--- --- ---//
                    all_data += '<div id="questionsSpanish">';
                    all_data += '<div class="card-body" style="background-color: white">';
                    all_data += '<h1 style="text-align: center !important;" class="text-center">MAPAS DE APRENDIZAJE: EVALUACIÓN DE HEBREO</h3>';
                    all_data += "</div>";
                    all_data += "</div>";
                    //--- --- ---//
                    //--- MDA GENERAL ---//
                    if (data.results_evc_normal.length > 0) {
                        for (var i = 0; i < data.results_evc_normal.length; i++) {
                            console.log(data.results_evc_normal_eng);
                            if ((data.results_evc_normal[i].topic.id_learning_map = "21")) {
                                table += '<div class="table-responsive mt-5">';
                                table += '<h3 class="mb-0  text-uppercase"><b>' + data.results_evc_normal[i].topic.name_question_group + "</b></h3><br/>";
                                table += '<table class="table" style="table-layout:fixed;">';
                                table += "<thead>";
                                table += "<tr>";
                                table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                                //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                                for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                    //--- ASIGNATURA ---//
                                    table += '<th class="text-center" scope="col" style="width:' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length * 35 + 'px; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0;" colspan="' + data.results_evc_normal[i].data.questions_evaluations.evaluations.length + '"><b></b>' + data.results_evc_normal[i].data.assgs[a].assg.teacher_name + "</th>";
                                    //--- --- ---//
                                }
                                table += "</tr>";
                                //--- CATALOGO DE EVALUACIONES ---//
                                table += "<tr>";
                                table += '<th style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
                                for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                    for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                        table += '<th class="text-center" style="width: 35px; border: 1px solid black; border-collapse: collapse; font-size: x-small; padding-right: 1px; padding-left: 1px;" scope="col" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].evaluation + '">' + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].symbol + "</th>";
                                    }
                                }
                                //--- --- ---//
                                table += "</tr>";
                                //--- --- ---//
                                table += "</thead>";
                                table += '<tbody class="list">';
                                //--- LISTA PREGUNTAS ---//
                                for (var f = 0; f < data.results_evc_normal[i].data.questions_evaluations.questions.length; f++) {
                                    //--- --- ---//
                                    var answer_null_to_student = "";
                                    var answer_filled_to_student = "";
                                    //--- --- ---//
                                    table += "<tr>";
                                    table += '<td style="border-right: 1px solid; padding: 3px; width: 400px; white-space: normal;">' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + "</td>";
                                    //--- RESPUESTAS ---//
                                    for (var a = 0; a < data.results_evc_normal[i].data.assgs.length; a++) {
                                        //--- --- ---//
                                        var answer_null_to_student = "";
                                        var answer_filled_to_student = "";
                                        var answer_to_question = false;
                                        //--- --- ---//
                                        for (var b = 0; b < data.results_evc_normal[i].data.questions_evaluations.evaluations.length; b++) {
                                            //--- --- ---/
                                            var answer_find = false;
                                            //--- --- ---//
                                            var style_border = "dotted";
                                            if (b + 1 >= data.results_evc_normal[i].data.questions_evaluations.evaluations.length) {
                                                style_border = "solid";
                                            }
                                            //--- --- ---//
                                            //--- RESPUESTAS ---//
                                            for (var d = 0; d < data.results_evc_normal[i].data.assgs[a].answers.length; d++) {
                                                //--- --- ---//
                                                if (data.results_evc_normal[i].data.assgs[a].assg.ascc_lm_assgn == data.results_evc_normal[i].data.assgs[a].answers[d].ascc_lm_assgn && data.results_evc_normal[i].topic.assc_mpa_id == data.results_evc_normal[i].data.assgs[a].answers[d].assc_mpa_id && data.results_evc_normal[i].data.questions_evaluations.questions[f].id_question_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_question_bank && data.results_evc_normal[i].data.questions_evaluations.evaluations[b].id_evaluation_bank == data.results_evc_normal[i].data.assgs[a].answers[d].id_evaluation_bank) {
                                                    answer_find = true;
                                                    answer_to_question = true;
                                                    answer_filled_to_student += '<td style="border-right: 1px ' + style_border + "; padding: 3px; background-color:" + data.results_evc_normal[i].data.questions_evaluations.evaluations[b].colorHTML + '" data-toggle="tooltip" data-placement="top" title="' + data.results_evc_normal[i].data.questions_evaluations.questions[f].question + '"></td>';
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
                                    table += "</tr>";
                                    //--- --- ---//
                                }
                                //--- --- ---//
                                table += "</tbody>";
                                table += "</table>";
                                table += "</div>";
                            }
                        }
                        //--- COMENTARIOS FINALES ---//
                        //--- ---- ---//
                    }
                    //--- --- --//
                    //--- --- ---//
                    all_data += '<div class="card-body" style="background-color: white">';
                    all_data += table;
                    all_data += "</div>";
                    if (data.final_comments_evc_normal.length > 0) {
                        table1 += '<div class="table-responsive mt-5">';
                        table1 += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                        table1 += '<table class="table" style="table-layout:fixed;">';
                        table1 += "<thead>";
                        table1 += "<tr>";
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        //--- ASIGNATURA ---//
                        table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Fortalezas</b></th>';
                        table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Areas de Oportunidad</b></th>';
                        //--- --- ---//
                        table1 += "</tr>";
                        table1 += "</thead>";
                        table1 += '<tbody class="list">';
                        //--- --- ---//
                        table1 += "<tr>";
                        //--- --- ---//
                        //--- COMENTARIOS ---//
                        //--- --- ---/
                        if (data.final_comments_evc_normal[0].comments.length > 0) {
                            table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal[0].comments[0].comments1 + " </td>";
                            table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"> ' + data.final_comments_evc_normal[0].comments[0].comments2 + " </td>";
                        } else {
                            table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                        }
                        //--- --- ---//
                        //--- --- ---//
                        table1 += "</tr>";
                        //--- --- ---//
                        table1 += "</tbody>";
                        table1 += "</table>";
                        table1 += "</div>";
                        all_data += '<div class="card-body" style="background-color: white">';
                        all_data += table1;
                        all_data += "</div>";
                    } else {
                        table1 += '<div class="table-responsive mt-5">';
                        table1 += '<h3 class="mb-0"><b>COMENTARIOS</b></h3><br/>';
                        table1 += '<table class="table" style="table-layout:fixed;">';
                        table1 += "<thead>";
                        table1 += "<tr>";
                        //--- RECORREMOS TODAS LA ASIGNATURAS ---//
                        //--- ASIGNATURA ---//
                        table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Fortalezas</b></th>';
                        table1 += '<th class="text-center text-uppercase" scope="col" style="width:250px !important; white-space: normal; vertical-align: middle; border-right: 1px dotted; letter-spacing: 0; font-size: small""><b>Areas de Oportunidad</b></th>';
                        //--- --- ---//
                        table1 += "</tr>";
                        table1 += "</thead>";
                        table1 += '<tbody class="list">';
                        //--- --- ---//
                        table1 += "<tr>";
                        //--- --- ---//
                        //--- COMENTARIOS ---//
                        //--- --- ---/
                        table1 += '<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>';
                        ('<td style="border-right: 1px dotted; padding: 8px; text-align: justify; white-space: break-spaces; font-size: small"></td>');
                        //--- --- ---//
                        //--- --- ---//
                        table1 += "</tr>";
                        //--- --- ---//
                        table1 += "</tbody>";
                        table1 += "</table>";
                        table1 += "</div>";
                        all_data += '<div class="card-body" style="background-color: white">';
                        all_data += table1;
                        all_data += "</div>";
                    }
                    //--- --- ---//
                    all_data += "</div>";
                    //--- --- ---//
                    //--- --- ---//
                }
            } else {
                var all_data = '<div class="card" id="div-report"><div class="card-body" style="background-color: white"><h3 class="text-center">NO HAY EVALUACIONES DISPONIBLES</h3></div></div>';
            }
            //--- --- ---//
            $(".div_content_mda").html(all_data);
            $('[data-toggle="tooltip"]').tooltip();
            //--- --- ---//
            swal.close();
            //--- --- ---//
        } else {
            Swal.fire("Atención!", data.message, "info");
        }
    }).fail(function(message) {
        Swal.fire("Error!", "Error al intentar conectarse con la Base de Datos :/", "error");
    });
    //--- --- ---//
}