swal.close();

$(function () {
  $("#slct_learning_map").val("15").change();
  $("#slct_learning_map").closest(".col-md-4").hide();
});
$(document).on("change", "#slct_learning_map", function (e) {
  var lmp_id = $(this).val();
  $("#slct_installment").val("");
  $("#slct_group").html("");

  $(".div_content_mda").html("");
  $(".slct_option_student").html(
    '<option selected value="" disabled>Elija una opción</option>'
  );

  getGroupsMDA(lmp_id);
});

$(document).on("change", "#slct_group", function (e) {
  var lmp_id = $("#slct_learning_map").val();
  var group_id = $("#slct_group").val();
  var installment = $("#slct_installment").val();

  $(".div_content_mda").html("");
  $(".slct_option_student").html(
    '<option selected value="" disabled>Elija una opción</option>'
  );

  if (
    lmp_id != "" &&
    group_id != "" &&
    installment != "" &&
    lmp_id != null &&
    group_id != null &&
    installment != null
  ) {
    getStudentByGroup(group_id);
  }
});

$(document).on("change", "#slct_installment", function (e) {
  var lmp_id = $("#slct_learning_map").val();
  var group_id = $("#slct_group").val();
  var installment = $("#slct_installment").val();

  $(".div_content_mda").html("");
  $(".slct_option_student").html(
    '<option selected value="" disabled>Elija una opción</option>'
  );

  if (
    lmp_id != "" &&
    group_id != "" &&
    installment != "" &&
    lmp_id != null &&
    group_id != null &&
    installment != null
  ) {
    getStudentByGroup(group_id);
  }
});

$(document).on("change", "#slct_option_student", function (e) {
  var lmp_id = $("#slct_learning_map").val();
  var group_id = $("#slct_group").val();
  var installment = $("#slct_installment").val();
  var id_student = $(this).val();

  $(".div_content_mda").html("");

  if (
    lmp_id != "" &&
    group_id != "" &&
    installment != "" &&
    id_student != "" &&
    lmp_id != null &&
    group_id != null &&
    installment != null &&
    id_student != null
  ) {
    getReportMDA(lmp_id, group_id, installment, id_student);
    getReportMDAHebrew(group_id, installment, id_student);
  }
});

//--- COMENTARIOS DIRECTOR ---//

$(document).on(
  "keydown paste",
  ".td-comment1, .td-comment2, .td-comment-director",
  function () {
    //Just for info, you can remove this line
    console.log("Total chars:" + $(this).text().length);
    //You can add delete key event code as well over here for windows users.
    if ($(this).text().length >= 500 && event.keyCode != 8) {
      event.preventDefault();
    }
  }
);

$(document).on(
  "keyup",
  ".td-comment1, .td-comment2, .td-comment-director",
  function () {
    //Just for info, you can remove this line
    var string = $(this).text();
    console.log("Total chars:" + $(this).text().length);
    //You can add delete key event code as well over here for windows users.
    if ($(this).text().length >= 500) {
      string = string.substring(0, 500);
      $(this).text(string);
    }
  }
);

$(document).on("click", ".btn-update", function () {
  var tr_active = $(this).closest("td").closest("tr");

  var colunm = $(this).attr("data-class-column");
  var class_col_text = $(this).attr("data-class-td-new-text");
  let txt_update = tr_active.find("." + class_col_text).text();
  var id_comments = tr_active
    .find("." + class_col_text)
    .attr("data-id-comments");
  Swal.fire({
    title: "Atención!",
    text: "Está a punto de actualizar un comentario, ¿desea continuar?",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      updateComments(id_comments, colunm, txt_update);
    }
  });
});
$(document).on("click", "#print_tables", function () {
  let id_table = $(this).attr("data-table_to_print");
  let group_name = $("#slct_group").children(":selected").prop("textContent");
  let mda_name = $(this).siblings()[0].textContent.trim();
  let dom_element = document.getElementById(id_table);
  var wb = XLSX.utils.table_to_book(dom_element);
  XLSX.writeFile(wb, `${group_name}-${mda_name}.xlsx`);
});

$(document).on("click", ".btn-new", function () {
  var tr_active = $(this).closest("td").closest("tr");

  var colunm = $(this).attr("data-class-column");
  var class_col_text = $(this).attr("data-class-td-new-text");
  let txt_update = tr_active.find("." + class_col_text).text();
  var id_student = tr_active.find("." + class_col_text).attr("data-id-student");
  var ascc_lm_assgn = tr_active
    .find("." + class_col_text)
    .attr("data-id-lm-assgn");

  /*console.log(colunm);
    console.log(class_col_text);
    console.log(txt_update);
    console.log(id_student);
    console.log(ascc_lm_assgn);*/
  Swal.fire({
    title: "Atención!",
    text: "Está a punto de actualizar un comentario, ¿desea continuar?",
    icon: "info",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí",
  }).then((result) => {
    if (result.isConfirmed) {
      AddComments(id_student, ascc_lm_assgn, colunm, txt_update);
    }
  });
});

var specialElementHandlers = {
  "#editor": function (element, renderer) {
    return true;
  },
};
$(document).on("click", ".btn-dowload-report", function () {
  const name_topic = $(this).attr("data-name-group");
  const student_code = $(this).attr("data-student-code");

  var HTML_Width = $("#div-report").width();
  var HTML_Height = $("#div-report").height();
  var top_left_margin = 15;
  var PDF_Width = HTML_Width;
  var PDF_Height = PDF_Width * 1.4;
  var canvas_image_width = HTML_Width;
  var canvas_image_height = HTML_Height;

  /*console.log('HTML_Width: ' + HTML_Width);
    console.log('HTML_Height: ' + HTML_Height);
    
    console.log('PDF_Width: ' + PDF_Width);
    console.log('PDF_Height: ' + PDF_Height);
    console.log('canvas_image_width: ' + canvas_image_width);
    console.log('canvas_image_height: ' + canvas_image_height);*/

  var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;
  html2canvas($("#div-report")[0]).then(function (canvas) {
    var imgData = canvas.toDataURL("image/jpeg", 1.0);
    var pdf = new jsPDF("l", "px", [PDF_Width, PDF_Height]);
    //var pdf = new jsPDF('landscape');
    pdf.addImage(
      imgData,
      "JPG",
      top_left_margin,
      top_left_margin,
      canvas_image_width,
      canvas_image_height
    );
    for (var i = 1; i <= totalPDFPages; i++) {
      pdf.addPage("l", PDF_Width, PDF_Height);
      pdf.addImage(
        imgData,
        "JPG",
        top_left_margin,
        -(PDF_Height * i) + top_left_margin * 4,
        canvas_image_width,
        canvas_image_height
      );
    }
    pdf.save(student_code + "_" + name_topic + ".pdf");
  });
});
$(document).on("click", ".btn-dowload-report-eng", function () {
  const name_topic = $(this).attr("data-name-group");
  const student_code = $(this).attr("data-student-code");

  var HTML_Width = $("#div-report-eng").width();
  var HTML_Height = $("#div-report-eng").height();
  var top_left_margin = 15;
  var PDF_Width = HTML_Width;
  var PDF_Height = PDF_Width * 1.4;
  var canvas_image_width = HTML_Width;
  var canvas_image_height = HTML_Height;

  /*console.log('HTML_Width: ' + HTML_Width);
    console.log('HTML_Height: ' + HTML_Height);
    
    console.log('PDF_Width: ' + PDF_Width);
    console.log('PDF_Height: ' + PDF_Height);
    console.log('canvas_image_width: ' + canvas_image_width);
    console.log('canvas_image_height: ' + canvas_image_height);*/

  var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;
  html2canvas($("#div-report-eng")[0]).then(function (canvas) {
    var imgData = canvas.toDataURL("image/jpeg", 1.0);
    var pdf = new jsPDF("l", "px", [PDF_Width, PDF_Height]);
    //var pdf = new jsPDF('landscape');
    pdf.addImage(
      imgData,
      "JPG",
      top_left_margin,
      top_left_margin,
      canvas_image_width,
      canvas_image_height
    );
    for (var i = 1; i <= totalPDFPages; i++) {
      pdf.addPage("l", PDF_Width, PDF_Height);
      pdf.addImage(
        imgData,
        "JPG",
        top_left_margin,
        -(PDF_Height * i) + top_left_margin * 4,
        canvas_image_width,
        canvas_image_height
      );
    }
    pdf.save(student_code + "_" + name_topic + ".pdf");
  });
});
$(document).on("click", ".btn-dowload-report-heb", function () {
  const name_topic = $(this).attr("data-name-group");
  const student_code = $(this).attr("data-student-code");

  var HTML_Width = $("#div-report-heb").width();
  var HTML_Height = $("#div-report-heb").height();
  var top_left_margin = 15;
  var PDF_Width = HTML_Width;
  var PDF_Height = PDF_Width * 1.4;
  var canvas_image_width = HTML_Width;
  var canvas_image_height = HTML_Height;

  /*console.log('HTML_Width: ' + HTML_Width);
    console.log('HTML_Height: ' + HTML_Height);
    
    console.log('PDF_Width: ' + PDF_Width);
    console.log('PDF_Height: ' + PDF_Height);
    console.log('canvas_image_width: ' + canvas_image_width);
    console.log('canvas_image_height: ' + canvas_image_height);*/

  var totalPDFPages = Math.ceil(HTML_Height / PDF_Height) - 1;
  html2canvas($("#div-report-heb")[0]).then(function (canvas) {
    var imgData = canvas.toDataURL("image/jpeg", 1.0);
    var pdf = new jsPDF("l", "px", [PDF_Width, PDF_Height]);
    //var pdf = new jsPDF('landscape');
    pdf.addImage(
      imgData,
      "JPG",
      top_left_margin,
      top_left_margin,
      canvas_image_width,
      canvas_image_height
    );
    for (var i = 1; i <= totalPDFPages; i++) {
      pdf.addPage("l", PDF_Width, PDF_Height);
      pdf.addImage(
        imgData,
        "JPG",
        top_left_margin,
        -(PDF_Height * i) + top_left_margin * 4,
        canvas_image_width,
        canvas_image_height
      );
    }
    pdf.save(student_code + "_" + name_topic + ".pdf");
  });
});

function getStudentByGroup(group_id) {
  loading();

  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "getStudentByGroup",
      group_id: group_id,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      if (data.response) {
        var students =
          '<option selected value="" disabled>Elija una opción</option><option value="0">TODO GRUPO</option>';
        if (data.students.length > 0) {
          for (var i = 0; i < data.students.length; i++) {
            students +=
              '<option value="' +
              data.students[i].id_student +
              '">' +
              data.students[i].student_name +
              "</option>";
          }
        }

        $("#slct_option_student").html(students);
        $("#slct_option_student").val("0").change();

        swal.close();
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
}

function getGroupsMDA(lmp_id) {
  loading();

  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "getGroupsMDA",
      lmp_id: lmp_id,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      if (data.response) {
        var options =
          '<option selected value="" disabled>Elija una opción</option>';
        if (data.groups.length > 0) {
          for (var i = 0; i < data.groups.length; i++) {
            options +=
              '<option value="' +
              data.groups[i].id_group +
              '" data-toggle="tooltip" data-placement="top" title="' +
              data.groups[i].string_group +
              '">' +
              data.groups[i].group_code +
              "</option>";
          }
        }

        $("#slct_group").html(options);

        $('[data-toggle="tooltip"]').tooltip();
        swal.close();
      } else {
        Swal.fire("Atención!", data.message, "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
}

function getReportMDA(lmp_id, group_id, installment, id_student) {
  loading();

  if (parseInt(id_student) == 0) {
    mod = "getReportMDAGeneral";
  } else {
    mod = "getReportMDAStudent";
  }

  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: mod,
      lmp_id: lmp_id,
      group_id: group_id,
      installment: installment,
      id_student: id_student,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      console.log(data);
      if (data.response) {
        if (parseInt(id_student) == 0) {
          generateReportGroupMDAGeneral(data);
        } else {
          generateReportGroupMDAByStudent(data);
        }
      } else {
        Swal.fire("Atención!", "se encontraron calificaciones para este periodo", "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
}
function getReportMDAHebrew(group_id, installment, id_student) {
  loading();

  if (parseInt(id_student) == 0) {
    mod = "getReportMDAGeneralHebrew";
  } else {
    mod = "getReportMDAGeneralHebrewStudent";
  }

  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: mod,
      group_id: group_id,
      installment: installment,
      id_student: id_student,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      console.log(data);
      if (data.response) {
        if (parseInt(id_student) == 0) {
          generateReportGroupMDAGeneralHebrew(data);
        } else {
          generateReportGroupMDAByStudentHebrew(data);
        }
      } else {
        Swal.fire("Atención!", "No se encontraron calificaciones para este periodo", "info");
      }
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
}

function generateReportGroupMDAByStudent(data) {
  var all_data = "";
  var comments_director = "";
  if (data.topics.length > 0) {
    for (var i = 0; i < data.topics.length; i++) {
      all_data +=
        '<button type="button" class="btn btn-outline-danger btn-dowload-report" data-student-code = "' +
        data.students.student_code +
        '" data-name-group="' +
        data.topics[i].topic.name_question_group +
        '">Descargar PDF&nbsp;&nbsp;<i class="fas fa-file-pdf fa-lg"></i></button><br/><br/>';
      all_data += '<div class="card" id="div-report">';
      all_data +=
        '<div class="card-header border-0"><h3 class="mb-0"><b>' +
        data.topics[i].topic.name_question_group +
        "</b></h3><br/>";
      all_data +=
        '<h4 class="mb-0 text-uppercase">Código: ' +
        data.students.student_code +
        "</h4>";
      all_data +=
        '<h4 class="mb-0 text-uppercase">Nombre: ' +
        data.students.student_name +
        "</h4>";
      all_data +=
        '<h4 class="mb-0 text-uppercase">Grupo: ' +
        data.students.group_code +
        "</h4>";
      all_data += "</div>";

      var table = '<div class="table-responsive">';
      table += '<table class="table" style="table-layout:fixed;">';
      table += "<thead>";
      table += "<tr>";
      table +=
        '<th style="border: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
      //--- RECORREMOS TODAS LA ASIGNATURAS ---//
      for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
        //--- ASIGNATURA ---//
        table +=
          '<th class="text-center" scope="col" style="width:' +
          data.topics[i].data.questions_evaluations.evaluations.length * 35 +
          'px; white-space: normal; vertical-align: middle; border: 1px solid; letter-spacing: 0;" colspan="' +
          data.topics[i].data.questions_evaluations.evaluations.length +
          '"><b>' +
          data.topics[i].data.assgs[a].assg.name_subject +
          "</b><br/>" +
          data.topics[i].data.assgs[a].assg.teacher_name +
          "</th>";
      }
      table += "</tr>";
      table += "</thead>";
      table += '<tbody class="list">';
      //--- LISTA PREGUNTAS ---//
      for (
        var f = 0;
        f < data.topics[i].data.questions_evaluations.questions.length;
        f++
      ) {
        var answer_null_to_student = "";
        var answer_filled_to_student = "";

        table += "<tr>";
        table +=
          '<td style="border: 1px solid; padding: 3px; width: 400px; white-space: normal;">' +
          data.topics[i].data.questions_evaluations.questions[f].question +
          "</td>";
        //--- RESPUESTAS ---//
        for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
          var answer_null_to_student = "";
          var answer_filled_to_student = "";
          var answer_to_question = false;

          for (
            var b = 0;
            b < data.topics[i].data.questions_evaluations.evaluations.length;
            b++
          ) {
            //--- --- ---/
            var answer_find = false;

            var style_border = "solid";
            //--- RESPUESTAS ---//
            for (
              var d = 0;
              d < data.topics[i].data.assgs[a].answers.length;
              d++
            ) {
              if (
                data.topics[i].data.assgs[a].assg.ascc_lm_assgn ==
                  data.topics[i].data.assgs[a].answers[d].ascc_lm_assgn &&
                data.topics[i].topic.assc_mpa_id ==
                  data.topics[i].data.assgs[a].answers[d].assc_mpa_id &&
                data.topics[i].data.questions_evaluations.questions[f]
                  .id_question_bank ==
                  data.topics[i].data.assgs[a].answers[d].id_question_bank &&
                data.topics[i].data.questions_evaluations.evaluations[b]
                  .id_evaluation_bank ==
                  data.topics[i].data.assgs[a].answers[d].id_evaluation_bank
              ) {
                answer_find = true;
                answer_to_question = true;
                answer_filled_to_student +=
                  '<td colspan="3" class="text-center" style="border: 1px ' +
                  style_border +
                  "; padding: 3px; background-color:" +
                  data.topics[i].data.questions_evaluations.evaluations[b]
                    .colorHTML +
                  '" >' +
                  data.topics[i].data.questions_evaluations.evaluations[b]
                    .symbol +
                  "</td>";
              }
            }
          }

          if (answer_to_question) {
            table += answer_filled_to_student;
          } else {
            table += answer_null_to_student;
          }
        }

        table += "</tr>";
      }

      table += "</tbody>";
      table += "</table>";
      table += "</div>";

      all_data += '<div class="card-body" style="background-color: white">';
      all_data += comments_director;
      all_data += table;
      all_data += "</div>";
      all_data += "</div>";
      all_data += "</div>";
    }
  }

  $(".div_content_mda").html(all_data);

  $('[data-toggle="tooltip"]').tooltip();
  swal.close();
}
function generateReportGroupMDAByStudentHebrew(data) {
  var all_data = "";
  var comments_director = "";
  let group_code = $("#slct_group").children(":selected").prop("textContent");
  if (data.topics.length > 0) {
    
      all_data +=
        '<button type="button" class="btn btn-outline-danger btn-dowload-report-heb" data-student-code = "' +
        data.students[0].student_code +
        '" data-name-group="' +
        "AREAS DESARROLLO PERSONAL Y SOCIAL HEBREO" +
        '">Descargar PDF&nbsp;&nbsp;<i class="fas fa-file-pdf fa-lg"></i></button><br/><br/>';
      all_data += '<div class="card" id="div-report-heb">';
      all_data +=
        '<div class="card-header border-0"><h3 class="mb-0"><b>' +
        "AREAS DESARROLLO PERSONAL Y SOCIAL HEBREO</b></h3><br/>";
      all_data +=
        '<h4 class="mb-0 text-uppercase">Código: ' +
        data.students[0].student_code +
        "</h4>";
      all_data +=
        '<h4 class="mb-0 text-uppercase">Nombre: ' +
        data.students[0].student_name +
        "</h4>";
      all_data +=
        '<h4 class="mb-0 text-uppercase">Grupo: ' + group_code + "</h4>";
      all_data += "</div>";

      var table = '<div class="table-responsive">';
      table += '<table class="table" style="table-layout:fixed;">';
      table += "<thead>";
      table += "<tr>";
      table +=
        '<th style="border: 1px solid; padding: 3px; width: 400px; white-space: normal;"></th>';
      table +=
        '<th class="text-center" scope="col" style="width:' +
        data.topics[0].data.questions_evaluations.evaluations.length * 35 +
        'px; white-space: normal; vertical-align: middle; border: 1px solid; letter-spacing: 0;" colspan="' +
        data.topics[0].data.questions_evaluations.evaluations.length +
        '"">AREAS DESARROLLO PERSONAL Y SOCIAL HEBREO</th>';

        for (var top_st = 0; top_st < data.topics.length; top_st++) {
          style_border = "";
          table += "<tr>";
          table +='<td style="border: 1px solid; padding: 3px; width: 400px; white-space: normal;">'+
          data.topics[top_st].topic.evaluation_name +
          "</td>";
          table += '<td class="text-center" style="border: 1px black solid; padding: 3px; background-color:' +
          data.topics[top_st].data.questions_evaluations.evaluations[0]
            .html_color +
          ' !important;">' +
          data.topics[top_st].data.questions_evaluations.evaluations[0]
            .grade_evaluation_criteria_teacher +
          "</td>";
          table += "</tr>";
        }

      table += "</tbody>";
      table += "</table>";
      table += "</div>";

      all_data += '<div class="card-body">';
      all_data += table;
      all_data += "</div>";
      all_data += "</div>";
      all_data += "</div>";
    }

    $(".div_content_mda").append(all_data);

    $('[data-toggle="tooltip"]').tooltip();
    swal.close();
  }

function generateReportGroupMDAGeneral(data) {
  var all_data = "";
  let group_code = $("#slct_group").children(":selected").prop("textContent");

  if (data.topics.length > 0) {
    for (var i = 0; i < data.topics.length; i++) {
      all_data += '<div class="card">';
      all_data += `<div class="card-header border-0 d-flex justify-content-between"><h3 class="mb-0">
        ${data.topics[i].topic.name_question_group}
        </h3>
        <button class="btn btn-success" id="print_tables" data-table_to_print="tabla_${i}"><i class="fas fa-print"></i></button>
        </div>`;

      var table = '<div class="table-responsive">';
      table += `<table class="table" id="tabla_${i}" style="table-layout:fixed;">`;
      table += `<thead id="tabla_head_${i}">`;
      table += "<tr id='table_subject_teacher'>";
      table +=
        '<th style="border: 1px solid; padding: 3px; width: 250px;"></th>';
      //--- RECORREMOS TODAS LA ASIGNATURAS ---//
      for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
        //--- ASIGNATURA ---//
        table +=
          '<th class="text-center" scope="col" style="width:' +
          data.topics[i].data.questions_evaluations.evaluations.length *
            data.topics[i].data.questions_evaluations.questions.length *
            35 +
          'px; white-space: normal; vertical-align: middle; border: 1px solid; letter-spacing: 0;" colspan="' +
          data.topics[i].data.questions_evaluations.evaluations.length *
            data.topics[i].data.questions_evaluations.questions.length +
          '">' +
          data.topics[i].data.assgs[a].assg.name_subject +
          "<br/>" +
          data.topics[i].data.assgs[a].assg.teacher_name +
          "</th>";
      }
      table += "</tr>";
      //--- PREGUNTAS ---//
      table += "<tr id='table_questions'>";
      table += `<th class="text-center" style="vertical-align: middle; border: 1px solid; padding: 3px; width: 250px;">
      <h5> Alumnos ${group_code} </h5>
      </th>`;
      for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
        for (
          var f = 0;
          f < data.topics[i].data.questions_evaluations.questions.length;
          f++
        ) {
          table +=
            '<th class="text-center" scope="col" style="width:' +
            35 * data.topics[i].data.questions_evaluations.evaluations.length +
            'px; white-space: normal; vertical-align: middle; border: 1px solid; padding-right: 1px; padding-left: 1px; letter-spacing: 0;" colspan="' +
            data.topics[i].data.questions_evaluations.evaluations.length +
            '">' +
            data.topics[i].data.questions_evaluations.questions[f].question +
            "</th>";
        }
      }
      table += "</tr>";
      table += "</thead>";
      table += '<tbody class="list">';
      //--- LISTA ALUMNOS ---//
      for (var e = 0; e < data.students.length; e++) {
        table += `<tr id='id_student_${data.students[e].id_student}'>`;
        table +=
          '<td id="students_name" class="text-uppercase" style="border: 1px solid; padding: 3px; width: 250px;">' +
          data.students[e].student_name +
          "</td>";
        //--- RESPUESTAS ---//
        for (var a = 0; a < data.topics[i].data.assgs.length; a++) {
          for (
            var f = 0;
            f < data.topics[i].data.questions_evaluations.questions.length;
            f++
          ) {
            var answer_null_to_student = "";
            var answer_filled_to_student = "";
            var answer_to_question = false;
            for (
              var b = 0;
              b < data.topics[i].data.questions_evaluations.evaluations.length;
              b++
            ) {
              //--- --- ---/
              var answer_find = false;

              var style_border = "solid";
              if (
                b + 1 >=
                data.topics[i].data.questions_evaluations.evaluations.length
              ) {
                style_border = "solid";
              }

              //--- RESPUESTAS ---//
              for (
                var d = 0;
                d < data.topics[i].data.assgs[a].answers.length;
                d++
              ) {
                if (
                  data.topics[i].data.assgs[a].assg.ascc_lm_assgn ==
                    data.topics[i].data.assgs[a].answers[d].ascc_lm_assgn &&
                  data.topics[i].topic.assc_mpa_id ==
                    data.topics[i].data.assgs[a].answers[d].assc_mpa_id &&
                  data.topics[i].data.questions_evaluations.questions[f]
                    .id_question_bank ==
                    data.topics[i].data.assgs[a].answers[d].id_question_bank &&
                  data.topics[i].data.questions_evaluations.evaluations[b]
                    .id_evaluation_bank ==
                    data.topics[i].data.assgs[a].answers[d]
                      .id_evaluation_bank &&
                  data.students[e].id_student ==
                    data.topics[i].data.assgs[a].answers[d].id_student
                ) {
                  answer_find = true;
                  answer_to_question = true;
                  answer_filled_to_student +=
                    '<td class="text-center" colspan="3" style="border: 1px ' +
                    style_border +
                    "; padding: 3px; background-color:" +
                    data.topics[i].data.questions_evaluations.evaluations[b]
                      .colorHTML +
                    '" data-toggle="tooltip" data-placement="top" title="' +
                    data.students[e].student_name +
                    '">' +
                    data.topics[i].data.questions_evaluations.evaluations[b]
                      .symbol +
                    "</td>";
                }
              }
            }
            if (answer_to_question) {
              table += answer_filled_to_student;
            } else {
              table += answer_null_to_student;
            }
          }
        }
        table += "</tr>";
      }

      table += "</tbody>";
      table += "</table>";
      table += "</div>";

      all_data += '<div class="card-body">';
      all_data += table;
      all_data += "</div>";
      all_data += "</div>";
      all_data += "</div>";
    }
  }

  $(".div_content_mda").html(all_data);

  $('[data-toggle="tooltip"]').tooltip();
  swal.close();
}
function generateReportGroupMDAGeneralHebrew(data) {
  var all_data = "";
  let group_code = $("#slct_group").children(":selected").prop("textContent");

  if (data.topics.length > 0) {
    all_data += '<div class="card">';
    all_data += `<div class="card-header border-0 d-flex justify-content-between"><h3 class="mb-0">AREAS DESARROLLO PERSONAL Y SOCIAL HEBREO</h3>
      <button class="btn btn-success" id="print_tables" data-table_to_print="tablaHeb"><i class="fas fa-print"></i></button>
      </div>`;

    var table = '<div class="table-responsive">';
    table += `<table class="table" id="tablaHeb" style="table-layout:fixed;">`;
    table += `<thead id="tablaHeb_head_${i}">`;

    table += "<tr id='table_subject_teacher'>";
    table += '<th style="border: 1px solid; padding: 3px; width: 250px;"></th>';
    table += "</tr>";
    //--- PREGUNTAS ---//
    table += "<tr id='table_questions'>";
    table += `<th class="text-center" style="vertical-align: middle; border: 1px solid; padding: 3px; width: 250px;">
      <h5> Alumnos ${group_code} </h5>
      </th>`;
    for (var i = 0; i < data.topics.length; i++) {
      table +=
        '<th class="text-center" scope="col" style="width:' +
        35 * data.topics[i].data.questions_evaluations.evaluations.length +
        'px; white-space: normal; vertical-align: middle; border: 1px solid; padding-right: 1px; padding-left: 1px; letter-spacing: 0;">' +
        data.topics[i].topic.evaluation_name +
        "</th>";
    }
    for (
      var st = 0;
      st < data.topics[0].data.questions_evaluations.evaluations.length;
      st++
    ) {
      table += "<tr>";
      table +=
        '<td id="students_name" class="text-uppercase" style="border: 1px black solid; padding: 3px; width: 250px;">' +
        data.topics[0].data.questions_evaluations.evaluations[st].student_name +
        "</td>";
      for (var top_st = 0; top_st < data.topics.length; top_st++) {
        style_border = "";
        table +=
          '<td  class="text-center" style="border: 1px black solid; padding: 3px; background-color:' +
          data.topics[top_st].data.questions_evaluations.evaluations[st]
            .html_color +
          ' !important;" data-toggle="tooltip" data-placement="top" title="' +
          data.topics[0].data.questions_evaluations.evaluations[
            st
          ].student_name.toUpperCase() +
          '">' +
          data.topics[top_st].data.questions_evaluations.evaluations[st]
            .grade_evaluation_criteria_teacher +
          "</td>";
      }
      table += "</tr>";
    }
    table += "</tbody>";
    table += "</table>";
    table += "</div>";

    all_data += '<div class="card-body">';
    all_data += table;
    all_data += "</div>";
    all_data += "</div>";
    all_data += "</div>";
  }

  $(".div_content_mda").append(all_data);

  $('[data-toggle="tooltip"]').tooltip();
  swal.close();
}
function AddComments(id_student, ascc_lm_assgn, colum, text) {
  //loading();
  var installment = $("#slct_installment").val();

  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "addDirectorsComments",
      id_student: id_student,
      ascc_lm_assgn: ascc_lm_assgn,
      installment: installment,
      colum: colum,
      text: text,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      swal.close();
      if (data.response) {
        typeToast = "success";
      } else {
        typeToast = "error";
      }
      VanillaToasts.create({
        title: "Notificación",
        text: data.message,
        type: typeToast,
        timeout: 1200,
        positionClass: "topRight",
      });
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
}

function updateComments(id_comments, colum, text) {
  //loading();

  $.ajax({
    url: "php/controllers/evaluations_cualitatives.php",
    method: "POST",
    data: {
      mod: "updateDirectorsComments",
      id_comments: id_comments,
      colum: colum,
      text: text,
    },
  })
    .done(function (data) {
      var data = JSON.parse(data);
      swal.close();
      if (data.response) {
        typeToast = "success";
      } else {
        typeToast = "error";
      }
      VanillaToasts.create({
        title: "Notificación",
        text: data.message,
        type: typeToast,
        timeout: 1200,
        positionClass: "topRight",
      });
    })
    .fail(function (message) {
      Swal.fire(
        "Error!",
        "Error al intentar conectarse con la Base de Datos :/",
        "error"
      );
    });
}

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
