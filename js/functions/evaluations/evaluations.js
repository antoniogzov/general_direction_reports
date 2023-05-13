//--- --- ---//
var value_before;
var validate_enter_key = false;
var name_before_gathering = "";
swal.close();
//--- --- ---//
var editable = $("#input_editable").val();
if (editable == "0") {
    var grade_closing_date = $("#grade_closing_date").val();
    Swal.fire({
        title: "Ya no puede editar esta evaluación",
        icon: "info",
        text: "La evaluación ya ha sido cerrada el día " + grade_closing_date,
        icon: "info",
    });
}
/*if ($("#tStudents").length > 0) {
    //--- --- ---//
    var tf = new TableFilter("tStudents", {
        base_path: "../general/js/vendor/tablefilter/tablefilter/",
        alternate_rows: true,
        rows_counter: true,
        btn_reset: true,
        loader: true,
        status_bar: true,
        responsive: true,
        extensions: [{
            name: "sort",
        }, ],
    });
    //--- --- ---//
    tf.init();
    //--- --- ---//
}*/
//--- --- ---//
function recalculateAverages(id_assignment, id_period_calendar) {
    loading_msg('Recalculando promedios generales');
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "recalculateGeneralAverages",
            id_assignment: id_assignment,
            id_period_calendar: id_period_calendar
        },
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            Swal.fire({
                icon: 'success',
                title: 'Listo!',
                text: 'Se hizo el recálculo de promedios generales',
                showCancelButton: false,
                confirmButtonText: 'OK',
                denyButtonText: `Don't save`,
            }).then((result) => {
                location.reload();
            });
            //--- --- ---//
        } else {
            //--- --- ---//
            Swal.fire('Atención!', 'Ocurrió un error al intentar recalcular los promedios con modelo dinámico, inténtelo nuevamente porfavor', 'info');
            //--- --- ---//
        }
    }).fail(function(message) {
        Swal.fire('Error', 'Ocurrió un error al intentar conectarse con la base de datos :(', 'error');
    });
}
//--- --- ---//
function recalculateAverageModelDynamic(id_assignment, id_period_calendar) {
    loading_msg('Recalculando promedios Modelo Dinámico');
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "recalculateDynamicModelAverages",
            id_assignment: id_assignment,
            id_period_calendar: id_period_calendar
        },
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            Swal.fire({
                icon: 'success',
                title: 'Listo!',
                text: 'Se hizo el recálculo de promedios basado en el modelo dinámico correspondiente',
                showCancelButton: false,
                confirmButtonText: 'OK',
                denyButtonText: `Don't save`,
            }).then((result) => {
                location.reload();
            });
            //--- --- ---//
        } else {
            //--- --- ---//
            Swal.fire('Atención!', 'Ocurrió un error al intentar recalcular los promedios con modelo dinámico, inténtelo nuevamente porfavor', 'info');
            //--- --- ---//
        }
    }).fail(function(message) {
        Swal.fire('Error', 'Ocurrió un error al intentar conectarse con la base de datos :(', 'error');
    });
}
//--- --- ---//
$(document).on("click", ".btn-show-mi", function() {
    let name_img = $(this).attr("data-link-img");
    Swal.fire({
        html: '<img src="images/models_calc/' + name_img + '" width="500" height="500">',
    });
});
//--- --- ---//
$("#id_period_calendar").change(function() {
    $("#formPeriod").submit();
});
//--- EXTRAORDINARIOS ---//
$(document).on("focusin", ".td-grade-extra", function() {
    //--- --- ---//
    var grade = $(this).text().trim();
    value_before = grade;
    //--- --- ---//
});
//--- --- ---//
$(document).on("focusout", ".td-grade-extra", function() {
    //--- --- ---//
    var id_extraordinary_exams = $(this).attr("id");
    var grade = $(this).text().trim();
    let td = $(this);
    if (grade == "") {
        td.css('backgroundColor', '#FF7575');
    }
    if (value_before != grade) {
        //--- --- ---//
        if (grade == "") {
            grade = null;
        }
        //--- --- ---//
        saveGradeExtraExam(id_extraordinary_exams, grade, td);
        //--- --- ---//
    }
    //--- --- ---//
});
//--- --- ---//
//--- EVALUACIONES NORMALES ---//
$(document).on("keypress", ".td-grade-evaluation", function(evt) {
    //--- --- ---//
    const td = $(this);
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode == 13) {
        evt.preventDefault();
        $(evt.target).parent().next().children().eq($(evt.target).parent().find(td).index()).focus();
        validate_enter_key = true;
    }
    //--- --- ---//
});
//--- --- ---//
$(document).on("focusin", ".td-grade-evaluation", function() {
    //--- --- ---//
    var grade = $(this).text().trim();
    value_before = grade;
    //--- --- ---//
});
//--- --- ---//
$(document).on("focusout", ".td-grade-evaluation", function() {
    //--- --- ---//
    var tr = $(this).closest("tr");
    let td = $(this);
    var id_grade_evaluation_criteria = $(this).attr("id");
    var grade = $(this).text().trim();
    var is_averaged = parseInt($(this).attr("data-is-averaged"));
    if (grade == "") {
        td.css('backgroundColor', '#FF7575');
    }
    if (value_before != grade) {
        //--- --- ---//
        if (grade == "") {
            grade = null;
        }
        //--- --- ---//
        saveGrade(id_grade_evaluation_criteria, grade, tr, td, is_averaged);
        //--- --- ---//
    }
    //--- --- ---//
});
//--- ELEGIR UNA OPCIÓN DEL CRITERIO SIMBÓLICO ---//
$(document).on("change", ".slct-opt-scale", function() {
    var id_grade_evaluation_criteria = $(this).attr("id");
    var grade = $(this).val().trim();
    var is_averaged = parseInt($(this).attr("data-is-averaged"));
    const tr = $(this).closest("tr");
    let td = $(this).closest("td");
    //--- --- ---//
    if (grade == "") {
        grade = null;
    }
    //--- --- ---//
    saveGrade(id_grade_evaluation_criteria, grade, tr, td, is_averaged);
    //--- --- ---//
});
//--- GATHERING ---//
$(document).on("keypress", ".td-grade-gathering", function(evt) {
    //--- --- ---//
    /*if (charCode == 13) {
          evt.preventDefault();
          let position = td.index();
          $(`.table-gathering td:eq(${position+1})`).focus();
          validate_enter_key = true;
      }*/
    const td = $(this);
    var charCode = evt.which ? evt.which : evt.keyCode;
    if (charCode == 13) {
        evt.preventDefault();
        $(evt.target).parent().next().children().eq($(evt.target).parent().find(td).index()).focus();
        validate_enter_key = true;
    }
    //--- --- ---//
});
$(document).on("focusin", ".td-grade-gathering", function() {
    /*  Swal.update({
        title: "Heeeeeeeyyyyy",
      }); */
    //--- --- ---//
    var grade = $(this).text().trim();
    value_before = grade;
    //--- --- ---//
});
//--- --- ---//
$(document).on("focusout", ".td-grade-gathering", function(e) {
    //--- --- ---//
    //$("#modalCriterios").modal("show");
    var trStudent = "tr-" + $(this).closest("tr").attr("id");
    let td = $(this);
    var id_grade_gathering = $(this).attr("id");
    var grade = $(this).text().trim();
    var is_averaged = $(this).attr("data-is-averaged");
    var id_evaluation_plan = $(this).attr("data-is-id-evp");
    if (grade == "") {
        td.css('backgroundColor', '#FF7575');
    }
    if (value_before != grade) {
        //--- --- ---//
        if (grade == "") {
            grade = null;
        }
        //--- --- ---//
        saveGradeGathering(id_grade_gathering, grade, trStudent, td, is_averaged, id_evaluation_plan);
        //--- --- ---//
    }
    //--- --- ---//
    value_before = "";
});
//--- --- ---//
$(document).on("focusout", ".th-gathering", function() {
    //--- --- ---//
    var id_conf_grade_gathering = $(this).attr("id");
    var new_title = $(this).text().trim().toLowerCase();
    if (new_title != name_before_gathering) {
        if (new_title == "") {
            $(this).text(name_before_gathering);
        } else {
            //--- --- ---//
            saveTitleGathering(id_conf_grade_gathering, new_title);
            //--- --- ---//
        }
    }
    //--- --- ---//
    name_before_gathering = "";
    $(this).attr("contenteditable", false);
    $(this).css("background-color", "white");
    //--- --- ---//
});
//--- --- ---//
$(document).on("click", ".closeCriteriaModal", function() {
    //--- --- ---//
    $("#modalCriterios").modal("hide");
    //--- --- ---//
});
//--- --- ---//
$(document).on("click", ".btnGetStudentQualifications", function() {
    loading();
    var id_assignment = $(this).attr("data-id-assignment");
    var id_student = $(this).attr("data-id-student");
    var id_period_calendar = $(this).attr("data-id-period-calendar");
    var student_code = $(this).attr("data-student-code");
    var name_student = $(this).attr("data-name-student");
    var group_and_subject = $("#info_header_label").text();
    console.log(id_assignment);
    console.log(id_student);
    console.log(id_period_calendar);
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "getAveragesPeriods",
            id_assignment: id_assignment,
            id_student: id_student,
            id_period_calendar: id_period_calendar,
            student_code: student_code,
            name_student: name_student,
            group_and_subject: group_and_subject,
        },
    }).done(function(data) {
        data = JSON.parse(data);
        if (data.response) {
            Swal.fire({
                icon: "info",
                html: data.html,
            });
        } else {
            console.log(data);
            typeToast = "error";
        }
    }).fail(function(message) {
        Swal.close();
        VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            icon: "error",
            timeout: 1200,
            positionClass: "topRight",
        });
    });
});
//--- --- ---//
function setContentEditable(e) {
    e.style.backgroundColor = "#EFB9FF";
    e.setAttribute("contenteditable", true);
    e.focus();
    name_before_gathering = e.innerText.trim().toLowerCase();
}
//--- --- ---//
//--- --- ---//
function clk_td_gathering(element, id_evaluation_plan, is_averaged, classification, evaluation_scale, editable) {
    getGathering(id_evaluation_plan, is_averaged, classification, evaluation_scale, editable);
}
//--- --- ---//
function saveGradeGathering(id_grade_gathering, grade, trStudent, td, is_averaged, id_evaluation_plan) {
    //--- --- ---//
    td.css("background-color", "#DADDFF");
    //--- --- ---//
    document.activeElement.blur();
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "saveGradeGathering",
            id_grade_gathering: id_grade_gathering,
            grade: grade,
            is_averaged: is_averaged,
        },
    }).done(function(data) {
        data = JSON.parse(data);
        var textToast = data.message;
        var typeToast = "";
        if (data.response) {
            typeToast = "success";
            //--- --- ---//
            $("#" + trStudent).find(".td-grade-dynCalc").html(data.grade_period_calc);
            $("#" + trStudent).find(".td-grade-period").html(data.grade_period);
            $("#" + trStudent).find(".td-gathering-ev-" + id_evaluation_plan).html(data.grade_evaluation_criteria_teacher);
            //--- --- ---//
            if (grade == "") {
                td.css('backgroundColor', '#FF7575');
            } else {
                td.css("background-color", "#CBFFD1");
            }
            //--- --- ---//
        } else {
            console.log(data);
            typeToast = "error";
        }
        VanillaToasts.create({
            title: "Notificación",
            text: textToast,
            icon: typeToast,
            timeout: 1200,
            positionClass: "topRight",
        });
    }).fail(function(message) {
        VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            icon: "error",
            timeout: 1200,
            positionClass: "topRight",
        });
    });
}
//--- --- ---//
$("#modalCriterios").on("hidden.bs.modal", function() {
    $(".GatheringActive").removeClass("GatheringActive");
});
//--- --- ---//
function saveTitleGathering(id_conf_grade_gathering, new_title) {
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "saveTitleGathering",
            id_conf_grade_gathering: id_conf_grade_gathering,
            new_title: new_title,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        var textToast = data.message;
        var typeToast = "";
        if (data.response) {
            typeToast = "success";
        } else {
            typeToast = "error";
        }
        VanillaToasts.create({
            title: "Notificación",
            text: textToast,
            icon: typeToast,
            timeout: 1200,
            positionClass: "topRight",
        });
    }).fail(function(message) {
        VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            icon: "error",
            timeout: 1200,
            positionClass: "topRight",
        });
    });
}
//--- --- ---//
function evaluateKeyGathering(e, element) {
    if (e.keyCode === 13 || e.which === 13) {
        e.preventDefault();
        element.blur();
    }
}
//--- --- ---//
function getGathering(id_evaluation_plan, is_averaged, classification, evaluation_scale, editable) {
    //--- --- ---//
    loading();
    var td_prop = "";
    if (editable == 1) {
        td_prop = 'contenteditable="true"';
    }
    //--- --- ---//
    $(".card-evaluations-gathering").html("");
    //--- --- ---//
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "getGatheringEvaluation",
            id_evaluation_plan: id_evaluation_plan,
        },
    }).done(function(data) {
        var data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            var confGathering = data.confGathering;
            var studentGathering = data.studentsGathering;
            let students = data.students;
            var infoHeader = "Criterio: " + data.name_criteria.toUpperCase();
            //--- --- ---//
            let body_td = "";
            table = '<div class="sticky-table sticky-ltr-cells">';
            table += '<table class="table align-items-center table-flush">';
            table += '<thead class="thead-light">';
            table += '<tr class="sticky-header">';
            table += '<th class="sticky-cell p-1 td-hd-600" style="width: 10px !important; font-size: x-small !important;">Cód.</th>';
            table += '<th class="sticky-cell text-center p-1" style="font-size: x-small !important;">Nombre</th>';
            //--- CABECERAS ---//
            for (var i = 0; i < confGathering.length; i++) {
                table += ' <th scope="col" class="text-center th-gathering" ondblclick="setContentEditable(this)" id="' + confGathering[i].id_conf_grade_gathering + '" onkeypress="evaluateKeyGathering(event, this)">' + confGathering[i].name_item + "</th>";
            }
            table += "</tr>";
            table += "</thead>";
            //--- --- ---//
            for (let s = 0; s < students.length; s++) {
                body_td += '<tr id="' + students[s].id_student + '">';
                body_td += '<td class="sticky-cell text-center">' + students[s].student_code + '</td>';
                body_td += '<td class="sticky-cell text-center">' + students[s].name_student.toUpperCase() + '</td>';
                for (var i = 0; i < confGathering.length; i++) {
                    for (var e = 0; e < studentGathering.length; e++) {
                        if (confGathering[i].id_conf_grade_gathering == studentGathering[e].id_conf_grade_gathering && studentGathering[e].id_student == students[s].id_student) {
                            let gradeGat = "";
                            studentGathering[e].grade_item != null ? (gradeGat = studentGathering[e].grade_item) : (gradeGat = "");
                            body_td += '<td class="text-center td-grade-gathering" data-is-id-evp = "' + confGathering[i].id_evaluation_plan + '" data-id_grade_evaluation_criteria = "' + studentGathering[e].id_grades_evaluation_criteria + '" data-is-averaged="' + is_averaged + '" id="' + studentGathering[e].id_grade_gathering + '" ' + td_prop + " onkeyup=\"evaluate_character('" + classification + "', '" + evaluation_scale + "', this, event)\">" + gradeGat + "</td>";
                        }
                    }
                }
                body_td += "</tr>";
            }
            //--- --- ---//
            table += '<tbody class="list">';
            table += body_td;
            table += "</tbody>";
            table += "</table>";
            table += "</div>";
            //--- --- ---//
            Swal.close();
            $("#criteria_modal_title").html(infoHeader);
            $("#cuerpo_modal").html(table);
            $("#modalCriterios").modal("show");
            setTimeout(() => {
                $(".table-gathering td:eq(0)").focus();
            }, "1500");
        }
        /*
            Swal.fire({
              //title: '<strong>HTML <u>example</u></strong>',
              //icon: 'info',
              html: '<h3 class="mb-0">' + infoHeader + "</h3><br/><br/>" + table,
              showCloseButton: true,
              showCancelButton: false,
              showConfirmButton: false,
              allowOutsideClick: false,
            }).then((result) => {
              if (
                result.dismiss === Swal.DismissReason.close ||
                result.dismiss === Swal.DismissReason.esc ||
                result.dismiss === Swal.DismissReason.backdrop
              ) {
                tr = $(".GatheringActive").closest("tr");
                td = $(".GatheringActive");
                td.removeClass("GatheringActive");
              }
            });
          } else {
            Swal.fire("Error", data.message, "error");
            $(".card-table-evaluations").show("slow");
          } */
    }).fail(function(message) {
        Swal.fire("Error", "Hubo un error al intentar conectarse con la base de datos :(", "error");
        $(".card-table-evaluations").show("slow");
    });
}
//--- --- ---//
function saveGrade(id_grade_evaluation_criteria, grade, tr, td, is_averaged) {
    //--- --- ---//
    td.css("background-color", "#DADDFF");
    //--- --- ---//
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "saveGradeCriteria",
            id_grade_evaluation_criteria: id_grade_evaluation_criteria,
            grade: grade,
            is_averaged: is_averaged,
        },
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
        var textToast = data.message;
        var typeToast = "";
        if (data.response) {
            typeToast = "success";
            if (is_averaged) {
                if (tr != null) {
                    tr.find(".td-grade-period").html(data.grade_period);
                }
            }
            //--- --- ---//
            if (grade == "") {
                td.css('backgroundColor', '#FF7575');
            } else {
                td.css("background-color", "#CBFFD1");
            }
            //--- --- ---//
            tr.find(".td-grade-dynCalc").html(data.grade_period_calc);
            //--- --- ---//
        } else {
            //--- --- ---//
            td.css("background-color", "#FECCCC");
            //--- --- ---//
            typeToast = "error";
        }
        VanillaToasts.create({
            title: "Notificación",
            text: textToast,
            icon: typeToast,
            timeout: 1200,
            positionClass: "topRight",
        });
    }).fail(function(message) {
        //--- --- ---//
        td.css("background-color", "#FECCCC");
        //--- --- ---//
        VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            icon: "error",
            timeout: 1200,
            positionClass: "topRight",
        });
    });
}
//--- --- ---//
function saveGradeExtraExam(id_extraordinary_exams, grade, td) {
    //--- --- ---//
    td.css("background-color", "#DADDFF");
    //--- --- ---//
    $.ajax({
        url: "php/controllers/evaluations.php",
        method: "POST",
        data: {
            mod: "saveGradeExtraExam",
            id_extraordinary_exams: id_extraordinary_exams,
            grade: grade,
        },
    }).done(function(data) {
        console.log(data);
        var data = JSON.parse(data);
        var textToast = data.message;
        var typeToast = "";
        if (data.response) {
            typeToast = "success";
            //--- --- ---//
            if (grade == "") {
                td.css('backgroundColor', '#FF7575');
            } else {
                td.css("background-color", "#CBFFD1");
            }
            //--- --- ---//
        } else {
            //--- --- ---//
            td.css("background-color", "#FECCCC");
            //--- --- ---//
            typeToast = "error";
        }
        VanillaToasts.create({
            title: "Notificación",
            text: textToast,
            icon: typeToast,
            timeout: 1200,
            positionClass: "topRight",
        });
    }).fail(function(message) {
        VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            icon: "error",
            timeout: 1200,
            positionClass: "topRight",
        });
    });
}
//--- --- ---//
function evaluate_character(classification, evaluation_scale, td, evt) {
    switch (classification) {
        case "rank":
            //--- --- ---//
            if (!validate_enter_key) {
                var caracter = document.getElementById("" + td.id).innerHTML;
                if (caracter == "<br>") {
                    caracter = "";
                }
                var values = evaluation_scale.split("-");
                //--- --- ---//
                console.log("caracter: " + caracter);
                if (caracter != "") {
                    caracter = parseFloat(caracter);
                    //--- Sólo admitimos números ---//
                    var charCode = evt.which ? evt.which : evt.keyCode;
                    console.log("charCode: " + charCode);
                    if (charCode == 229 || charCode == 8 || charCode == 9 || charCode == 13 || charCode == 46 || charCode == 110 || charCode == 190 || (charCode >= 35 && charCode <= 40) || (charCode >= 48 && charCode <= 57) || (charCode >= 96 && charCode <= 105)) {
                        if (caracter < values[0] || caracter > values[1] || isNaN(caracter)) {
                            document.getElementById("" + td.id).innerHTML = value_before;
                        }
                    } else {
                        document.getElementById("" + td.id).innerHTML = value_before;
                    }
                }
            }
            validate_enter_key = false;
            //--- --- ---//
            break;
            /*case 'group':
                                //--- --- ---//
                                var caracter = document.getElementById('' + td.id).innerHTML;
                                var values = evaluation_scale.split(",");
                                //--- --- ---//
                                if (caracter != '') {
                                    var keypressed = evt.which
                                    //--- Admitir solon caracteres alfanuméricos ---//
                                    if ((keypressed >= 65 && keypressed <= 90) || (keypressed >= 48 && keypressed <= 57) || (keypressed >= 96 && keypressed <= 105)) {
                                        var contn = false;
                                        for (var a = 0; a < values.length; a++) {
                                            if (caracter == values[a]) {
                                                contn = true;
                                            }
                                        }
                                        //--- --- ---//
                                        if (!contn) {
                                            document.getElementById('' + td.id).innerHTML = value_before;
                                        }
                                        //--- --- ---//
                                    } else {
                                        document.getElementById('' + td.id).innerHTML = value_before;
                                    }
                                }
                                //--- --- ---//
                                break;*/
    }
}
//--- --- ---//
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
function loading_msg(msg) {
    Swal.fire({
        html: '<img src="images/loading_iteach.gif" width="300" height="300">',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false,
        showCancelButton: false,
        showConfirmButton: false,
        footer: '<b>' + msg + '<b/>'
    });
}
/* $('#modalCriterios').modal({
    backdrop: 'static',
    keyboard: false
}) */