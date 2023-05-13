(function () {
  var $body = document.body,
    $menu_trigger = $body.getElementsByClassName("menu-trigger")[0];
  if (typeof $menu_trigger !== "undefined") {
    $menu_trigger.addEventListener("click", function () {
      $body.className = $body.className == "menu-active" ? "" : "menu-active";
    });
  }
}.call(this));

function getCriteriaDetails(
  id_grade_period,
  id_student,
  id_assignment,
  promedio,
  grade_period_calc
) {
  loading();
  if (id_grade_period != "-") {
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getCriteriaDetails",
        id_grade_period: id_grade_period,
        id_student: id_student,
        id_assignment: id_assignment,
        promedio: promedio,
        grade_period_calc: grade_period_calc,
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        // console.log(info);
        Swal.fire({
          title: "<h2>DESGLOSE DE CALIFICACIÓN</h2>",
          icon: "info",
          html: info.data,
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
        });
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  } else {
    Swal.fire({
      title: "<strong>DESGLOSE DE CALIFICACIÓN</strong>",
      icon: "info",
      html: "<h3>Aún no se asigna calificación para este periodo</h3>",
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: "Aceptar",
    });
  }
}

function getCriteriaDetailsStudents(
  id_student,
  id_assignment,
  id_period_calendar,
  grade_period,
  id_group
) {
  loading();
  if (grade_period != "-") {
    $.ajax({
      url: "php/controllers/students.php",
      method: "POST",
      data: {
        mod: "getCriteriaDetailsArchive",
        id_student: id_student,
        id_assignment: id_assignment,
        id_period_calendar: id_period_calendar,
        id_group:id_group
      },
    })
      .done(function (info) {
        info = $.parseJSON(info);
        // console.log(info);
        Swal.fire({
          title: "<h2>DESGLOSE DE CALIFICACIÓN</h2>",
          icon: "info",
          html: info.data,
          showCloseButton: true,
          focusConfirm: false,
          confirmButtonText: "Aceptar",
        });
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  } else {
    Swal.fire({
      title: "<strong>DESGLOSE DE CALIFICACIÓN</strong>",
      icon: "info",
      html: "<h3>Aún no se asigna calificación para este periodo</h3>",
      showCloseButton: true,
      focusConfirm: false,
      confirmButtonText: "Aceptar",
    });
  }
}
$(document).ready(function () {
  var general_student_attendance_percentage = $(
    "#general_student_attendance_percentage"
  ).text();
  $("#main_general_student_attendance_percentage").text(
    general_student_attendance_percentage
  );
  $(document).on("click", ".saveInterview", function () {
    loading();
    var id_student = $(this).attr("data-id-student");
    var terapeuta = $("#terapeuta").val();
    var tipo_intervencion = $("#tipo_intervencion").val();
    if (
      tipo_intervencion == "" ||
      tipo_intervencion == null ||
      tipo_intervencion == undefined
    ) {
      var txt_tipo_intervencion = "Sin info.";
    } else {
      var txt_tipo_intervencion = $(
        "#tipo_intervencion option:selected"
      ).text();
    }
    var referido_por = $("#referido_por").val();
    if (
      referido_por == "" ||
      referido_por == null ||
      referido_por == undefined
    ) {
      var txt_referido_por = "Sin info.";
    } else {
      var txt_referido_por = $("#referido_por option:selected").text();
    }
    var motivo = $("#motivo").val();
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var causa_cocluyo = $("#causa_cocluyo").val();
    var txt_causa_cocluyo = $("#causa_cocluyo option:selected").text();

    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth();
    var yyy = date.getFullYear();
    var fecha_reg = yyy + "-" + mes + "-" + dia;

    if (
      terapeuta == "" ||
      tipo_intervencion == "" ||
      motivo == "" ||
      fecha_inicio == ""
    ) {
      Swal.fire({
        title: "Atención",
        text: "Ingrese los campos son obligatorios",
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    } else {
      $.ajax({
        url: "php/controllers/psychopedagogy_controller.php",
        method: "POST",
        data: {
          mod: "saveInterview",
          id_student: id_student,
          terapeuta: terapeuta,
          tipo_intervencion: tipo_intervencion,
          referido_por: referido_por,
          motivo: motivo,
          fecha_inicio: fecha_inicio,
          fecha_fin: fecha_fin,
          causa_cocluyo: causa_cocluyo,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          if (data.response) {
            id = data.id;
            $("#addNewInterview").find("input,textarea,select").val("");
            $("#addNewInterview input[type='checkbox']")
              .prop("checked", false)
              .change();
            swal.close();
            $("#addNewInterview").modal("hide");
            // console.log(data.data);
            Swal.fire({
              title: "Atención",
              text: "Se ha guardado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            });
            var name_colaborator = $("#name_colab").text();
            var_html = "";
            var_html += "<tr id='tr" + id + "'>";
            var_html += "<td>" + terapeuta + "</td>";
            var_html += "<td>" + txt_tipo_intervencion + "</td>";
            var_html += "<td>" + txt_referido_por + "</td>";
            var_html += "<td>" + fecha_reg + "</td>";
            var_html +=
              '<td><a title="Ficha completa" href="#" data-toggle="modal" data-target="#infoInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-warning infoInterview"><i class="fa-solid fa-info"></i></a><a href="#" title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-success seeTrackingInterview"><i class="fa-regular fa-comment-dots"></i></a> <a title="Compartir" href="#" data-toggle="modal" data-target="#shareInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-info shareTrackingInterview"><i class="fa-solid fa-share-nodes"></i></a> <a title="Editar" href="#" data-toggle="modal" data-target="#editInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-primary editInterview"><i class="fa-solid fa-edit"></i></a><a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-danger deleteInterview"><i class="fa-regular fa-trash-alt"></i></a><td>';
            var_html += "</tr>";
            $(".trEmptyResults").remove();
            $("#tStudents").append(var_html);
          } else {
            Swal.close();
            VanillaToasts.create({
              title: "Error",
              text: data.message,
              type: "error",
              timeout: 3000,
              positionClass: "topRight",
            });
          }
          //--- --- ---//

          //--- --- ---//
        })
        .fail(function (message) {
          VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
          });
        });
    }
  });
  $(document).on("click", ".seeTrackingInterview", function () {
    loading();
    $(".trackingInterview").empty();
    var id_card = $(this).attr("data-id-card");
    console.log(id_card);
    $(".commentaryTracingInterview").attr("data-id-card", id_card);

    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "getInterviewTracking",
        id_card: id_card,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.response) {
          for (var i = 0; i < data.data.length; i++) {
            var_chat_meessae = data.data[i].chat_message;
            if (var_chat_meessae.includes("\n")) {
              var_chat_meessae = var_chat_meessae.replace(/\n/g, "<br>");
            }
            var_html = "";
            //
            var_html +=
              '<div class="timeline-block" id="divTracking' +
              data.data[i].id_terapeutic_cards_chat +
              '">';

            var_html += '<span class="timeline-step badge-success">';
            var_html += '<i class="fa-solid fa-address-card"></i>';
            var_html += "</span>";
            var_html += '<div class="timeline-content">';
            var_html +=
              '<small class="text-muted font-weight-bold">' +
              data.data[i].teacher_name +
              " | " +
              data.data[i].datelog +
              "</small>";
            var_html +=
              '<h5 class=" mt-3 mb-0" id="textComentary' +
              data.data[i].id_terapeutic_cards_chat +
              '">' +
              var_chat_meessae +
              "</h5>";
            var_html +=
              '<div class="mt-3" id="divTrackingCommentary' +
              data.data[i].id_terapeutic_cards_chat +
              '"> ';
            var_html +=
              '<a style="margin: 10px 3px 30px 5px;" target="_blank" data-id="' +
              data.data[i].id_terapeutic_cards_chat +
              '" data-commentary="' +
              var_chat_meessae +
              '"  class="editCommentTracking"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';
            if (
              data.data[i].route_archive != null ||
              data.data[i].route_archive == ""
            ) {
              if (data.data[i].archive_type == "pdf") {
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;" href="' +
                  data.data[i].route_archive +
                  '" target="_blank" class="btnDoc' +
                  data.id_terapeutic_cards_chat +
                  '"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
              } else {
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;" href="' +
                  data.data[i].route_archive +
                  '" target="_blank" class="btnDoc' +
                  data.id_terapeutic_cards_chat +
                  '"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
              }
            } else {
              btn_doc =
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                data.data[i].id_terapeutic_cards_chat +
                '" class="btnAddTrackingArchive" id="btnAddArciveChat' +
                data.data[i].id_terapeutic_cards_chat +
                '"><span class="btn-inner--icon"><i id="iconAddArchive' +
                data.data[i].id_terapeutic_cards_chat +
                '"  class="fa-solid fa-folder-plus"></i></span></a>';
              var_html += btn_doc;
            }
            var_html +=
              '<a style="margin: 10px 3px 30px 5px;" data-id="' +
              data.data[i].id_terapeutic_cards_chat +
              '" class="btnDeleteTrackingCommitInterview"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a>';
            var_html += "</div>";
            var_html += "</div>";
            var_html += "</div>";
            $(".trackingInterview").append(var_html);
            Swal.close();
          }
        } else {
          Swal.close();
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });
  $(document).on("change", "#archiveTtrtacking", function () {
    var archiveTtrtacking = document.querySelector("#archiveTtrtacking");
    if (archiveTtrtacking.files.length > 0) {
      var file = archiveTtrtacking.files[0];
      var name = file.name;
      var ext = name.split(".").pop().toLowerCase();
      if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg", "pdf"]) == -1) {
        Swal.fire({
          title: "Atención",
          text: "Solo se permiten archivos de imagen o PDF",
          icon: "warning",
          confirmButtonText: "Aceptar",
        });
        $("#archiveTtrtacking").val("");
        $("#lblArchivo").hide();
        return false;
      } else {
        $("#lblArchivo").show();
        $("#lblArchivo").text("Archivo:" + name);
      }
    }
  });
  $(document).on("change", ".archiveTtrtackingChat", function () {
    var id = $(this).attr("data-id");
    var archiveTtrtacking = document.querySelector(
      "#archiveTtrtackingChat" + id
    );
    if (archiveTtrtacking.files.length > 0) {
      var file = archiveTtrtacking.files[0];
      var name = file.name;
      var ext = name.split(".").pop().toLowerCase();
      if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg", "pdf"]) == -1) {
        Swal.fire({
          title: "Atención",
          text: "Solo se permiten archivos de imagen o PDF",
          icon: "warning",
          confirmButtonText: "Aceptar",
        });
        $("#archiveTtrtackingChat" + id).val("");
        $("#lblArchivoParents" + id).html("Seleccionar archivo");
        return false;
      } else {
        $("#lblArchivoParents" + id).html(name);
      }
    }
  });
  $(document).on("change", "#archiveTtrtackingParents", function () {
    var archiveTtrtacking = document.querySelector("#archiveTtrtackingParents");
    if (archiveTtrtacking.files.length > 0) {
      var file = archiveTtrtacking.files[0];
      var name = file.name;
      var ext = name.split(".").pop().toLowerCase();
      if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg", "pdf"]) == -1) {
        Swal.fire({
          title: "Atención",
          text: "Solo se permiten archivos de imagen o PDF",
          icon: "warning",
          confirmButtonText: "Aceptar",
        });
        $("#archiveTtrtackingParents").val("");
        $("#lblArchivoParents").html("");
        $("#lblArchivoParents").hide();
        return false;
      } else {
        $("#lblArchivoParents").html(name);
        $("#lblArchivoParents").show();
      }
    }
  });

  $(document).on("click", ".commentaryTracingInterview", function () {
    loading();
    if (
      $("#comentario_seguimientos").val() == "" ||
      $("#comentario_seguimientos").val() == null
    ) {
      Swal.fire({
        title: "Atención",
        text: "Debe ingresar un comentario",
        icon: "warning",
        confirmButtonText: "Aceptar",
        timer: 2000,
      }).then((result) => {
        /* $("#comentario_seguimientos").focus(); */
      });

      /* $( "#target" ).focus(); */
    } else {
      const archive_tracking = document.querySelector("#archiveTtrtacking");
      var id_card = $(this).attr("data-id-card");
      if (archive_tracking.files.length > 0) {
        saveTccArchive(id_card);
      } else {
        var id_card = $(this).attr("data-id-card");
        var id_teacher_tracking = $("#id_teacher_tracking").val();
        var comentario_seguimientos = $("#comentario_seguimientos").val();
        $("#comentario_seguimientos").val("");
        var teacher_name_registered_tracking = $(
          "#teacher_name_registered_tracking"
        ).val();

        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          method: "POST",
          data: {
            mod: "saveInterviewTracking",
            id_card: id_card,
            id_teacher_tracking: id_teacher_tracking,
            comentario_seguimientos: comentario_seguimientos,
          },
        })
          .done(function (data) {
            var time_now = new Date();

            var data = JSON.parse(data);
            if (data.response) {
              btn_doc =
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                data.id +
                '" class="btnAddTrackingArchive btnDoc' +
                data.id +
                '" id="btnAddArciveChat' +
                data.id +
                '"><span class="btn-inner--icon"><i id="iconAddArchive' +
                data.id +
                '" class="fa-solid fa-folder-plus"></i></span></a>';
              var_html = "";
              var_html +=
                '<div class="timeline-block" id="divTracking' + data.id + '">';

              var_html += '<span class="timeline-step badge-success">';
              var_html += '<i class="fa-solid fa-address-card"></i>';
              var_html += "</span>";
              var_html += '<div class="timeline-content">';
              var_html +=
                '<small class="text-muted font-weight-bold">' +
                teacher_name_registered_tracking +
                " | (Justo ahora)</small>";
              var_chat_meessae = comentario_seguimientos;
              if (
                var_chat_meessae.includes("\n") == true ||
                var_chat_meessae.includes("\r") == true
              ) {
                var_chat_meessae = var_chat_meessae.replace(/\n/g, "<br>");
              }
              var_html +=
                '<h5 class=" mt-3 mb-0" id="textComentary' +
                data.id +
                '"><pre>' +
                comentario_seguimientos +
                "</pre></h5>";
              var_html +=
                '<div class="mt-3" id="divTrackingCommentary' + data.id + '">';
              var_html +=
                '<a style="margin: 10px 3px 30px 5px;" style="margin: 10px 3px 30px 5px;" target="_blank" data-id="' +
                data.id +
                '" data-commentary="' +
                comentario_seguimientos +
                '" class="editCommentTracking"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';

              var_html += btn_doc;
              var_html +=
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                data.id +
                '" class="btnDeleteTrackingCommitInterview"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a><span class="badge badge-pill badge-success">';
              var_html += "</div>";
              var_html += "</div>";
              var_html += "</div>";
              $(".trackingInterview").append(var_html);
              Swal.close();
            } else {
              Swal.close();
              VanillaToasts.create({
                title: "Error",
                text: data.message,
                type: "error",
                timeout: 3000,
                positionClass: "topRight",
              });
              Swal.close();
            }
            //--- --- ---//

            //--- --- ---//
          })
          .fail(function (message) {
            VanillaToasts.create({
              title: "Error",
              text: "Ocurrió un error, intentelo nuevamente",
              type: "error",
              timeout: 1200,
              positionClass: "topRight",
            });
          });
      }
    }
  });
  $(document).on("click", ".btnAddTrackingArchive", function () {
    id_terapeutic_cards_chat = $(this).attr("data-id");
    $(this).removeClass("btn-default");
    $(this).addClass("btn-success");
    $(this).removeClass("btnAddTrackingArchive");
    $(this).addClass("btnAddTrackingArchiveSelected");
    var_html = "";
    var_html +=
      '<div class="modal-footer divAddArchiveChat' +
      id_terapeutic_cards_chat +
      '" id="">';
    var_html += '<div class="custom-file">';
    var_html +=
      '<input type="file" accept="application/pdf, image/png, image/jpg, image/jpeg" data-id="' +
      id_terapeutic_cards_chat +
      '" class="archiveTtrtackingChat" id="archiveTtrtackingChat' +
      id_terapeutic_cards_chat +
      '" lang="es">';
    var_html +=
      '<label class="custom-file-label" id="lblArchiveTrackingChat' +
      id_terapeutic_cards_chat +
      '" for="archiveTtrtackingChat' +
      id_terapeutic_cards_chat +
      '">Seleccionar un archivo</label>';
    var_html +=
      '</div> <button type="button" data-id="' +
      id_terapeutic_cards_chat +
      '" class="btn btn-primary btnAddArchiveTrackingChat" data-id="' +
      id_terapeutic_cards_chat +
      '"><i class="fa-solid fa-upload"></i></button>';
    var_html += "</div>";
    $("#divTracking" + id_terapeutic_cards_chat).append(var_html);
  });

  $(document).on("click", ".btnAddTrackingArchiveSelected", function () {
    id_terapeutic_cards_chat = $(this).attr("data-id");
    $(this).removeClass("btn-success");
    $(this).addClass("btn-default");
    $(this).removeClass("btnAddTrackingArchiveSelected");
    $(this).addClass("btnAddTrackingArchive");
    $(".divAddArchiveChat" + id_terapeutic_cards_chat).remove();
  });
  $(document).on("click", ".btnAddArchiveTrackingChat", function () {
    loading();
    id_terapeutic_cards_chat = $(this).attr("data-id");

    const archive_tracking = document.querySelector(
      "#archiveTtrtackingChat" + id_terapeutic_cards_chat
    );
    if (archive_tracking.files.length > 0) {
      var id_archive = "NULL";
      let formData = new FormData();
      formData.append("archive_tracking", archive_tracking.files[0]);
      formData.append("mod", "saveTccArchiveChat");
      formData.append("id_chat", id_terapeutic_cards_chat);
      fetch("php/controllers/psychopedagogy_controller.php", {
        method: "POST",
        body: formData,
      })
        .then((respuesta) => respuesta.json())
        .then((decodificado) => {
          id_archive = decodificado.id;
          var ext = decodificado.extension_img;
          var ruta = decodificado.ruta_sql_img;

          if (ext != "pdf") {
            btn_icon = "fa-image";
            btn_class = "btn-info";
          } else {
            btn_icon = "fa-file-pdf";
            btn_class = "btn-danger";
          }

          $("#btnAddArciveChat" + id_terapeutic_cards_chat).removeClass(
            "btn-success"
          );
          $("#btnAddArciveChat" + id_terapeutic_cards_chat).removeClass(
            "btnAddTrackingArchive"
          );
          $("#btnAddArciveChat" + id_terapeutic_cards_chat).removeClass(
            "btnAddTrackingArchiveSelected"
          );

          $("#iconAddArchive" + id_terapeutic_cards_chat).removeClass(
            "fa-folder-plus"
          );

          $("#iconAddArchive" + id_terapeutic_cards_chat).addClass(btn_icon);

          $("#btnAddArciveChat" + id_terapeutic_cards_chat).addClass(btn_class);
          $("#btnAddArciveChat" + id_terapeutic_cards_chat).attr("href", ruta);
          $("#btnAddArciveChat" + id_terapeutic_cards_chat).attr(
            "target",
            "_blank"
          );
          $(".divAddArchiveChat" + id_terapeutic_cards_chat).remove();

          Swal.fire({
            title: "Archivo agregado",
            text: "El archivo se agregó correctamente",
            icon: "success",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            timer: 1500,
          });
        });
    }
  });

  function saveTccArchive(id_card) {
    const archive_tracking = document.querySelector("#archiveTtrtacking");
    if (archive_tracking.files.length > 0) {
      var id_archive = "NULL";
      let formData = new FormData();
      formData.append("archive_tracking", archive_tracking.files[0]);
      formData.append("mod", "saveTccArchive");
      fetch("php/controllers/psychopedagogy_controller.php", {
        method: "POST",
        body: formData,
      })
        .then((respuesta) => respuesta.json())
        .then((decodificado) => {
          id_archive = decodificado.id;
          var ext = decodificado.extension_img;
          var ruta = decodificado.ruta_sql_img;

          var id_teacher_tracking = $("#id_teacher_tracking").val();
          var comentario_seguimientos = $("#comentario_seguimientos").val();
          $("#comentario_seguimientos").val("");
          var teacher_name_registered_tracking = $(
            "#teacher_name_registered_tracking"
          ).val();

          $.ajax({
            url: "php/controllers/psychopedagogy_controller.php",
            method: "POST",
            data: {
              mod: "saveInterviewTrackingArchive",
              id_card: id_card,
              id_teacher_tracking: id_teacher_tracking,
              comentario_seguimientos: comentario_seguimientos,
              id_archive: id_archive,
            },
          })
            .done(function (data) {
              var time_now = new Date();

              var data = JSON.parse(data);
              if (data.response) {
                if (ext != "pdf") {
                  btn_doc =
                    '<a style="margin: 10px 3px 30px 5px;"  href="' +
                    ruta +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id +
                    '"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
                } else {
                  btn_doc =
                    '<a  style="margin: 10px 3px 30px 5px;" href="' +
                    ruta +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id +
                    '"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
                }
                var_html = "";
                var_html +=
                  '<div class="timeline-block" id="divTracking' +
                  data.id +
                  '">';

                var_html += '<span class="timeline-step badge-success">';
                var_html += '<i class="fa-solid fa-address-card"></i>';
                var_html += "</span>";
                var_html += '<div class="timeline-content">';
                var_html +=
                  '<small class="text-muted font-weight-bold">' +
                  teacher_name_registered_tracking +
                  " | (Justo ahora)</small>";
                var_html +=
                  '<h5 class=" mt-3 mb-0" id="textComentary' +
                  data.id +
                  '">' +
                  comentario_seguimientos +
                  "</h5>";
                var_html +=
                  '<div class="mt-3" id="divTrackingCommentary' +
                  data.id +
                  '">';
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;"  target="_blank" data-id="' +
                  data.id +
                  '" data-commentary="' +
                  comentario_seguimientos +
                  '" type="button" class="editCommentTracking"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';
                var_html += btn_doc;
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;"  data-id="' +
                  data.id +
                  '" class="btnDeleteTrackingCommitInterview"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a><span class="badge badge-pill badge-success">';
                var_html += "</div>";
                var_html += "</div>";
                var_html += "</div>";
                $(".trackingInterview").append(var_html);
                $("#archiveTtrtacking").val("");
                $("#lblArchivo").hide();

                $("#btnAddArchiveTr").removeClass("addArchive2");
                $("#btnAddArchiveTr").addClass("addArchive");
                $("#btnAddArchiveTr").addClass("btn-secondary");
                $("#btnAddArchiveTr").removeClass("btn-info");
                $(".divAddArchive").hide();
                Swal.close();
              } else {
                Swal.close();
                VanillaToasts.create({
                  title: "Error",
                  text: data.message,
                  type: "error",
                  timeout: 3000,
                  positionClass: "topRight",
                });
                Swal.close();
              }
              //--- --- ---//

              //--- --- ---//
            })
            .fail(function (message) {
              VanillaToasts.create({
                title: "Error",
                text: "Ocurrió un error, intentelo nuevamente",
                type: "error",
                timeout: 1200,
                positionClass: "topRight",
              });
            });
        });
    }
  }
  function saveTccArchiveParents(id_parents_tracking) {
    const archive_tracking_parents = document.querySelector(
      "#archiveTtrtackingParents"
    );
    if (archive_tracking_parents.files.length > 0) {
      var id_archive = "NULL";
      let formData = new FormData();
      formData.append(
        "archive_tracking_parents",
        archive_tracking_parents.files[0]
      );
      formData.append("mod", "saveTccArchiveParents");
      fetch("php/controllers/psychopedagogy_controller.php", {
        method: "POST",
        body: formData,
      })
        .then((respuesta) => respuesta.json())
        .then((decodificado) => {
          id_archive = decodificado.id;
          var ext = decodificado.extension_img;
          var ruta = decodificado.ruta_sql_img;

          var id_teacher_tracking_parents = $(
            "#id_teacher_tracking_parents"
          ).val();
          var comentario_seguimientos_padres = $(
            "#comentario_seguimientos_padres"
          ).val();
          $("#comentario_seguimientos_padres").val("");
          var teacher_name_registered_tracking = $(
            "#teacher_name_registered_tracking"
          ).val();

          $.ajax({
            url: "php/controllers/psychopedagogy_controller.php",
            method: "POST",
            data: {
              mod: "saveInterviewTrackingArchiveParents",
              id_parents_tracking: id_parents_tracking,
              id_teacher_tracking_parents: id_teacher_tracking_parents,
              comentario_seguimientos_padres: comentario_seguimientos_padres,
              id_archive: id_archive,
            },
          })
            .done(function (data) {
              var time_now = new Date();

              var data = JSON.parse(data);
              if (data.response) {
                if (ext != "pdf") {
                  btn_doc =
                    '<a style="margin: 10px 3px 30px 5px;"  href="' +
                    ruta +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id +
                    '"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
                } else {
                  btn_doc =
                    '<a  style="margin: 10px 3px 30px 5px;" href="' +
                    ruta +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id +
                    '"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
                }
                var_html = "";
                var_html +=
                  '<div class="timeline-block" id="divTracking' +
                  data.id +
                  '">';

                var_html += '<span class="timeline-step badge-success">';
                var_html += '<i class="fa-solid fa-address-card"></i>';
                var_html += "</span>";
                var_html += '<div class="timeline-content">';
                var_html +=
                  '<small class="text-muted font-weight-bold">' +
                  teacher_name_registered_tracking +
                  " | (Justo ahora)</small>";
                var_html +=
                  '<h5 class=" mt-3 mb-0" id="textComentary' +
                  data.id +
                  '">' +
                  comentario_seguimientos_padres +
                  "</h5>";
                var_html +=
                  '<div class="mt-3" id="divTrackingCommentary' +
                  data.id +
                  '">';
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;"  target="_blank" data-id="' +
                  data.id +
                  '" data-commentary="' +
                  comentario_seguimientos_padres +
                  '" type="button" class="editCommentTracking"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';
                var_html += btn_doc;
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;"  data-id="' +
                  data.id +
                  '" class="btnDeleteTrackingCommitInterview"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a><span class="badge badge-pill badge-success">';
                var_html += "</div>";
                var_html += "</div>";
                var_html += "</div>";
                $(".trackingWithParents").append(var_html);
                $("#archiveTtrtackingParents").val("");
                $("#lblArchivoParents").hide();

                $("#btnAddArchiveTr").removeClass("addArchive2");
                $("#btnAddArchiveTr").addClass("addArchive");
                $("#btnAddArchiveTr").addClass("btn-secondary");
                $("#btnAddArchiveTr").removeClass("btn-info");

                Swal.close();
              } else {
                Swal.close();
                VanillaToasts.create({
                  title: "Error",
                  text: data.message,
                  type: "error",
                  timeout: 3000,
                  positionClass: "topRight",
                });
                Swal.close();
              }
              //--- --- ---//

              //--- --- ---//
            })
            .fail(function (message) {
              VanillaToasts.create({
                title: "Error",
                text: "Ocurrió un error, intentelo nuevamente",
                type: "error",
                timeout: 1200,
                positionClass: "topRight",
              });
            });
        });
    }
  }

  $(document).on("click", ".btnDeleteTrackingCommitInterview", function () {
    loading();
    var id_commentary = $(this).attr("data-id");
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "¡Sí, bórralo!",
    }).then((result) => {
      if (result.value) {
        loading();
        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          method: "POST",
          data: {
            mod: "deleteInterviewTrackingComment",
            id_commentary: id_commentary,
          },
        })
          .done(function (data) {
            Swal.close();
            var data = JSON.parse(data);
            if (data.response) {
              $("#divTracking" + id_commentary).remove();
            } else {
              Swal.close();
              VanillaToasts.create({
                title: "Error",
                text: data.message,
                type: "error",
                timeout: 3000,
                positionClass: "topRight",
              });
            }
            //--- --- ---//

            //--- --- ---//
          })
          .fail(function (message) {
            VanillaToasts.create({
              title: "Error",
              text: "Ocurrió un error, intentelo nuevamente",
              type: "error",
              timeout: 1200,
              positionClass: "topRight",
            });
          });
      }
    });
  });

  $(document).on("click", ".deleteInterview", function () {
    Swal.fire({
      title: "¿Está seguro de eliminar este registro?",
      text: "No podrá revertir esta acción",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        loading();
        var id_card = $(this).attr("data-id-card");
        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          method: "POST",
          data: {
            mod: "deleteInterview",
            id_card: id_card,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              Swal.close();
              VanillaToasts.create({
                title: "Éxito",
                text: "Registro eliminado",
                type: "success",
                timeout: 3000,
                positionClass: "topRight",
              });
              $("#tr" + id_card).remove();
            } else {
              Swal.close();
              VanillaToasts.create({
                title: "Error",
                text: data.message,
                type: "error",
                timeout: 3000,
                positionClass: "topRight",
              });
            }
            //--- --- ---//

            //--- --- ---//
          })
          .fail(function (message) {
            VanillaToasts.create({
              title: "Error",
              text: "Ocurrió un error, intentelo nuevamente",
              type: "error",
              timeout: 1200,
              positionClass: "topRight",
            });
          });
      }
    });
  });

  $(document).on("click", ".btnBreakdownIncidents", function () {
    var id_student = $(this).attr("id-student");
    $(".menuStudent").hide();

    if (id_student != null) {
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "breakdownincidents",
          id_student: id_student,
        },
      })
        .done(function (json) {
          //        //console.log(json);
          var json = JSON.parse(json);
          if (json != "") {
            //--- --- ---//
            if (json.response) {
              //--- --- ---//
              $("#cuerpo_desglose_incidencias").empty();
              $("#cuerpo_desglose_incidencias").html(json.html_sweet_alert);
              //--- --- ---//
              /*  Swal.fire({
                title: "Faltas anticipadas",
                icon: "info",
                html: json.html_sweet_alert,
                showCancelButton: false,
                width: "800px",
              }).then((result) => {
                //window.location.reload();
              }); */
              //--- --- ---/
            } else {
              swal(
                "Atención!",
                "No se pudieron guardar los datos :( intentelo nuevamente",
                "error"
              );
            }
            //--- --- ---//
          } else {
            swal(
              "Atención!",
              "Ocurrió un error al registrar la justificación",
              "error"
            );
          }
        })
        .fail(function () {
          swal(
            "Error!",
            "Error al intentar conectarse con la Base de Datos :/",
            "error"
          );
        });
    } else {
      Swal.fire({
        icon: "error",
        title: "Debe completar todos los campos requeridos",
      });
    }
  });
  $(document).on("click", ".cerrarModalInc", function () {
    $(".menuStudent").show();
  });

  $(document).on("click", ".addTrackingIncidentComment", function () {
    var id_student_incidents_log = $(this).attr("id");
    $(".commentaryTracingIncidents").attr("id", id_student_incidents_log);
    loading();

    $.ajax({
      url: "php/controllers/justifyController.php",
      method: "POST",
      data: {
        mod: "getCommentaryTracingIncidents",
        id_student_incidents_log: id_student_incidents_log,
      },
    })
      .done(function (json) {
        Swal.close();
        //console.log(json);
        var json = JSON.parse(json);
        if (json != "") {
          //--- --- ---//
          if (json.response) {
            //--- --- ---//
            $("#div_timeline_incidents").empty();
            //--- --- ---/
            $("#div_timeline_incidents").append(json.html);
            student_name_code = json.student_name_code;
            $("#header_lbl_tracking_incidents").text(student_name_code);
          } else {
            student_name_code = json.student_name_code;
            $("#header_lbl_tracking_incidents").text(student_name_code);
          }

          //--- --- ---//
        } else {
        }
      })
      .fail(function () {});
  });
  $(document).on("click", ".deleteIncident", function () {
    var id_student_incidents_log = $(this).attr("id");

    Swal.fire({
      title: "¿Estás seguro?",
      text: "Este proceso no se podrá revertir!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, borrarlo!",
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: "php/controllers/justifyController.php",
          method: "POST",
          data: {
            mod: "deleteIncident",
            id_student_incidents_log: id_student_incidents_log,
          },
        })
          .done(function (json) {
            //console.log(json);
            var json = JSON.parse(json);
            if (json != "") {
              //--- --- ---//
              if (json.response) {
                $("#" + id_student_incidents_log)
                  .closest("tr")
                  .remove();
                //--- --- ---//
                Swal.fire({
                  title: "Éxito!!",
                  icon: "info",
                  text: "Se eliminó correctamente la justificación!!",
                  showCancelButton: false,
                  timer: 3000,
                }).then((result) => {
                  if (
                    $("#tablaDesgloseIncidencias").find("tbody tr").length == 0
                  ) {
                    window.location.reload();
                  }
                });
                //--- --- ---/
              } else {
                swal(
                  "Atención!",
                  "No se pudieron guardar los datos :( intentelo nuevamente",
                  "error"
                );
              }
              //--- --- ---//
            } else {
              swal(
                "Atención!",
                "Ocurrió un error al registrar la justificación",
                "error"
              );
            }
          })
          .fail(function () {
            swal(
              "Error!",
              "Error al intentar conectarse con la Base de Datos :/",
              "error"
            );
          });
      }
    });
  });
  $(document).on("click", ".commentaryTracingIncidents", function () {
    var id_student_incidents_log = $(this).attr("id");
    var id_teacher_tracking = $("#id_teacher_tracking").val();
    const days = [
      "Domingo",
      "Lunes",
      "Martes",
      "Miercoles",
      "Jueves",
      "Viernes",
      "Sabado",
    ];

    const d = new Date();
    let day = days[d.getDay()];
    let date = d.getDate();
    let month = d.getMonth() + 1;
    let year = d.getFullYear();
    let hour = d.getHours();
    let minutes = d.getMinutes();

    var today_datetime =
      day +
      ", " +
      date +
      " de " +
      month +
      " de " +
      year +
      " a las " +
      hour +
      ":" +
      minutes;
    var txt_commentary = $("#comentario_seguimiento").val();
    var teacher_name = $("#teacher_name_registered_tracking").val();
    var html_timeline = "";
    if (txt_commentary != "") {
      $.ajax({
        url: "php/controllers/justifyController.php",
        method: "POST",
        data: {
          mod: "saveCommentaryTracingIncident",
          txt_commentary: txt_commentary,
          id_student_incidents_log: id_student_incidents_log,
          id_teacher_tracking: id_teacher_tracking,
        },
      })
        .done(function (json) {
          Swal.close();
          //console.log(json);
          var json = JSON.parse(json);
          if (json != "") {
            //--- --- ---//
            if (json.response) {
              //--- --- ---//
              VanillaToasts.create({
                title: "Éxito!!",
                text: "Se actualizó correctamente la justificación",
                timeout: 1200,
                positionClass: "topRight",
              });
              html_timeline +=
                '<div class="timeline-block" id="div' + json.last_id + '">';
              html_timeline += '<span class="timeline-step badge-success">';
              html_timeline += '<i class="ni ni-email-83"></i>';
              html_timeline += "</span>";
              html_timeline += '<div class="timeline-content">';
              html_timeline +=
                '<small class="text-muted font-weight-bold">' +
                today_datetime +
                "</small>";
              html_timeline +=
                '<h5 class=" mt-3 mb-0">' + txt_commentary + "</h5>";
              html_timeline +=
                '<p class=" text-sm mt-1 mb-0">' + teacher_name + "</p>";
              html_timeline += '<div class="mt-3">';
              html_timeline +=
                '<button type="button" id="' +
                json.last_id +
                '" class="btn btn-dribbble btn-icon-only rounded-circle btnDeleteTrackingCommit">';
              html_timeline +=
                '<span class="btn-inner--icon"><i class="ni ni-basket"></i></span>';
              html_timeline += "</button>";
              html_timeline += "</div>";
              html_timeline += "</div>";
              html_timeline += "</div>";
              //--- --- ---/
              $("#div_timeline_incidents").append(html_timeline);
            } else {
              Swal.fire({
                title: "Error",
                icon: "error",
                html: json.fechas_sobrepuestas,
                showCancelButton: false,
              });
            }
            //--- --- ---//
          } else {
            swal(
              "Atención!",
              "Ocurrió un error al registrar la justificación",
              "error"
            );
          }
        })
        .fail(function () {
          swal(
            "Error!",
            "Error al intentar conectarse con la Base de Datos :/",
            "error"
          );
        });
    }

    $("#comentario_seguimiento").val("");
  });
  $(document).on("click", ".btnDeleteTrackingCommit", function () {
    var id_absences_excuse = $(this).attr("id");
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "¡Sí, eliminar!",
    }).then((result) => {
      if (result.value) {
        loading();
        $.ajax({
          url: "php/controllers/justifyController.php",
          method: "POST",
          data: {
            mod: "deleteCommentaryTracing",
            id_absences_excuse: id_absences_excuse,
          },
        })
          .done(function (json) {
            Swal.close();
            //console.log(json);
            var json = JSON.parse(json);
            if (json != "") {
              //--- --- ---//
              if (json.response) {
                //--- --- ---//
                $("#div" + id_absences_excuse).remove();
                //--- --- ---//
              } else {
                Swal.fire({
                  title: "Error",
                  icon: "error",
                  html: "Ocurrió un error al intentar eliminar el comentario!!",
                  showCancelButton: false,
                });
              }
              //--- --- ---//
            } else {
              swal(
                "Atención!",
                "Ocurrió un error al consultar la información",
                "error"
              );
            }
          })
          .fail(function () {
            swal(
              "Error!",
              "Error al intentar conectarse con la Base de Datos :/",
              "error"
            );
          });
      }
    });

    $("#comentario_seguimiento").val("");
  });
  $(document).on("click", ".btnDeleteTrackingCommitIncident", function () {
    var id_incident_tracking = $(this).attr("id");
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "¡Sí, eliminar!",
    }).then((result) => {
      if (result.value) {
        loading();
        $.ajax({
          url: "php/controllers/justifyController.php",
          method: "POST",
          data: {
            mod: "deleteCommentaryTracingIncident",
            id_incident_tracking: id_incident_tracking,
          },
        })
          .done(function (json) {
            Swal.close();
            //console.log(json);
            var json = JSON.parse(json);
            if (json != "") {
              //--- --- ---//
              if (json.response) {
                //--- --- ---//
                $("#div" + id_incident_tracking).remove();
                //--- --- ---//
              } else {
                Swal.fire({
                  title: "Error",
                  icon: "error",
                  html: "Ocurrió un error al intentar eliminar el comentario!!",
                  showCancelButton: false,
                });
              }
              //--- --- ---//
            } else {
              swal(
                "Atención!",
                "Ocurrió un error al consultar la información",
                "error"
              );
            }
          })
          .fail(function () {
            swal(
              "Error!",
              "Error al intentar conectarse con la Base de Datos :/",
              "error"
            );
          });
      }
    });

    $("#comentario_seguimiento").val("");
  });
  $(document).on("click", ".editInterview", function () {
    loading();
    var id_card = $(this).attr("data-id-card");
    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "getInterviewDetails",
        id_card: id_card,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        if (data.response) {
          Swal.close();
          console.log(data);
          if (
            data.data[0].who_reffered == "1" ||
            data.data[0].who_reffered == 2
          ) {
            if (data.data[0].who_reffered == "1") {
              $("#edit_referido_por").val("Referido por el colegio");
            } else {
              $("#edit_referido_por").val("Referido por los padres");
            }
          } else {
            $("#edit_referido_por").val(data.data[0].who_reffered);
          }
          $("#edit_terapeuta").val(data.data[0].name_of_proffessional);
          $("#edit_tipo_intervencion").val(data.data[0].kind_of_interview);
          //$("#edit_referido_por").val();
          $("#edit_motivo").val(data.data[0].reason_why_reffered);
          $("#edit_fecha_inicio").val(data.data[0].start_date);
          if (data.data[0].end_date != "0000-00-00") {
            $("#edit_fecha_fin").val(data.data[0].end_date);
          }
          $("#edit_causa_cocluyo").val(data.data[0].cause_why_conclused);

          $(".updateInterview").attr("data-id-card", id_card);
        } else {
          Swal.close();
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 3000,
            positionClass: "topRight",
          });
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
    var id_student = $(this).attr("data-id-student");

    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth();
    var yyy = date.getFullYear();
    var fecha_reg = yyy + "-" + mes + "-" + dia;
  });

  $(document).on("click", ".updateInterview", function () {
    loading();
    var id_card = $(this).attr("data-id-card");
    var terapeuta = $("#edit_terapeuta").val();
    var tipo_intervencion = $("#edit_tipo_intervencion").val();
    var txt_tipo_intervencion = $(
      "#edit_tipo_intervencion option:selected"
    ).text();
    var referido_por = $("#edit_referido_por").val();
    var txt_referido_por = $("#edit_referido_por option:selected").text();
    var motivo = $("#edit_motivo").val();
    var fecha_inicio = $("#edit_fecha_inicio").val();
    var fecha_fin = $("#edit_fecha_fin").val();
    var causa_cocluyo = $("#edit_causa_cocluyo").val();

    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth();
    var yyy = date.getFullYear();
    var fecha_reg = yyy + "-" + mes + "-" + dia;

    if (
      terapeuta == "" ||
      tipo_intervencion == "" ||
      motivo == "" ||
      fecha_inicio == ""
    ) {
      Swal.fire({
        title: "Atención",
        text: "Ingrese los campos son obligatorios",
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    } else {
      $.ajax({
        url: "php/controllers/psychopedagogy_controller.php",
        method: "POST",
        data: {
          mod: "updateInterview",
          id_card: id_card,
          terapeuta: terapeuta,
          tipo_intervencion: tipo_intervencion,
          referido_por: referido_por,
          motivo: motivo,
          fecha_inicio: fecha_inicio,
          fecha_fin: fecha_fin,
          causa_cocluyo: causa_cocluyo,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          if (data.response) {
            $("#editInterview").find("input,textarea,select").val("");
            $("#editInterview input[type='checkbox']")
              .prop("checked", false)
              .change();
            swal.close();
            $("#editInterview").modal("hide");
            // console.log(data.data);
            Swal.fire({
              title: "Atención",
              text: "Se ha guardado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            });
            //$("#tr" + id_card).remove();
            var name_colaborator = $("#name_colab").text();
            var_html = "";
            var_html += "<td>" + terapeuta + "</td>";
            var_html += "<td>" + txt_tipo_intervencion + "</td>";
            var_html += "<td>" + referido_por + "</td>";
            var_html += "<td>" + fecha_inicio + "</td>";
            var_html +=
              '<td><a title="Ficha completa" href="#" data-toggle="modal" data-target="#infoInterview" data-id-card="' +
              id_card +
              '" class="btn btn-sm btn-warning infoInterview"><i class="fa-solid fa-info"></i></a><a href="#" title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoInterview" data-id-card="' +
              id_card +
              '" class="btn btn-sm btn-success seeTrackingInterview"><i class="fa-regular fa-comment-dots"></i></a> <a title="Compartir" href="#" data-toggle="modal" data-target="#shareInterview" data-id-card="' +
              id_card +
              '" class="btn btn-sm btn-info shareTrackingInterview"><i class="fa-solid fa-share-nodes"></i></a><a title="Editar" href="#" data-toggle="modal" data-target="#editInterview" data-id-card="' +
              id_card +
              '" class="btn btn-sm btn-primary editInterview"><i class="fa-solid fa-edit"></i></a><a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteInterview" data-id-card="' +
              id_card +
              '" class="btn btn-sm btn-danger deleteInterview"><i class="fa-regular fa-trash-alt"></i></a><td>';
            $("#tr" + id_card).html(var_html);
            /* $(".trEmptyResults").remove();
            $("#tStudents").append(var_html); */
          } else {
            Swal.close();
            VanillaToasts.create({
              title: "Error",
              text: data.message,
              type: "error",
              timeout: 3000,
              positionClass: "topRight",
            });
          }
          //--- --- ---//

          //--- --- ---//
        })
        .fail(function (message) {
          VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
          });
        });
    }
  });
  $(document).on("click", ".addArchive", function () {
    $(".divAddArchive").show();
    $(this).removeClass("btn-secondary");
    $(this).addClass("btn-info");
    $(this).removeClass("addArchive");
    $(this).addClass("addArchive2");
  });
  $(document).on("click", ".shareTrackingInterview", function () {
    var id_card = $(this).attr("data-id-card");
    $(".sendInterviewMails").attr("data-id-card", id_card);
    var reason_why_reffered = $("#tr" + id_card).attr(
      "data-reason_why_reffered"
    );
    var fecha_registro_format = $("#tr" + id_card).attr(
      "data-fecha_registro_format"
    );
    var start_date = $("#tr" + id_card).attr("data-start_date");
    var end_date = $("#tr" + id_card).attr("data-end_date");
    var cause_why_conclused = $("#tr" + id_card).attr(
      "data-cause_why_conclused"
    );
    var colaborador_registro = $("#tr" + id_card).attr(
      "data-colaborador_registro"
    );
    var terapeuta = $("#tr" + id_card)
      .find("td:eq(0)")
      .text();
    var tipo_intervencion = $("#tr" + id_card)
      .find("td:eq(1)")
      .text();
    var referido_por = $("#tr" + id_card)
      .find("td:eq(2)")
      .text();
    var motivo = reason_why_reffered;
    var fecha_reg = fecha_registro_format;
    var fecha_inicio = start_date;
    var fecha_fin = end_date;
    var causa_cocluyo = cause_why_conclused;
    var name_colaborator = colaborador_registro;

    /* console.log(tipo_intervencion);
    console.log(referido_por);
    console.log(motivo);
    console.log(fecha_reg);
    console.log(fecha_inicio);
    console.log(fecha_fin);
    console.log(causa_cocluyo);
    console.log(name_colaborator); */

    var html = "";
    html += '<div class="row">';
    html += '<div class="col-md-6">';
    html += '<p class="lead"><strong>Terapeuta: </strong>' + terapeuta + "</p>";
    html +=
      '<p class="lead"><strong>Tipo de intervención: </strong>' +
      tipo_intervencion +
      "</p>";
    html +=
      '<p class="lead"><strong>Referido por: </strong>' + referido_por + "</p>";
    html += '<p class="lead"><strong>Motivo: </strong>' + motivo + "</p>";
    html +=
      '<p class="lead"><strong>Fecha de registro: </strong>' +
      fecha_reg +
      "</p>";
    html += "</div>";
    html += '<div class="col-md-6">';
    html +=
      '<p class="lead"><strong>Fecha de inicio: </strong>' +
      fecha_inicio +
      "</p>";
    html +=
      '<p class="lead"><strong>Fecha de fin: </strong>' + fecha_fin + "</p>";
    html +=
      '<p class="lead"><strong>Causa de concluyo: </strong>' +
      causa_cocluyo +
      "</p>";
    html +=
      '<p class="lead"><strong>Registrado por: </strong>' +
      name_colaborator +
      "</p>";
    html += "</div>";
    html += "</div><br><br>";
    html +=
      "<h3>A continuación seleccione los colaboradores con quienes compartirá la información y el seguimiento de esta intervención: </h3>";
    $("#bodyShareInterview").empty();
    $("#bodyShareInterview").prepend(html);
  });
  $(document).on("click", ".infoInterview", function () {
    var id_card = $(this).attr("data-id-card");
    var reason_why_reffered = $("#tr" + id_card).attr(
      "data-reason_why_reffered"
    );
    var fecha_registro_format = $("#tr" + id_card).attr(
      "data-fecha_registro_format"
    );
    var start_date = $("#tr" + id_card).attr("data-start_date");
    var end_date = $("#tr" + id_card).attr("data-end_date");
    var cause_why_conclused = $("#tr" + id_card).attr(
      "data-cause_why_conclused"
    );
    var colaborador_registro = $("#tr" + id_card).attr(
      "data-colaborador_registro"
    );
    var terapeuta = $("#tr" + id_card)
      .find("td:eq(0)")
      .text();
    var tipo_intervencion = $("#tr" + id_card)
      .find("td:eq(1)")
      .text();
    var referido_por = $("#tr" + id_card)
      .find("td:eq(2)")
      .text();
    var motivo = reason_why_reffered;
    var fecha_reg = fecha_registro_format;
    var fecha_inicio = start_date;
    var fecha_fin = end_date;
    var causa_cocluyo = cause_why_conclused;
    var name_colaborator = colaborador_registro;

    /* console.log(tipo_intervencion);
    console.log(referido_por);
    console.log(motivo);
    console.log(fecha_reg);
    console.log(fecha_inicio);
    console.log(fecha_fin);
    console.log(causa_cocluyo);
    console.log(name_colaborator); */

    var html = "";
    html += '<div class="row">';
    html += '<div class="col-md-6">';
    html += '<p class="lead"><strong>Terapeuta: </strong>' + terapeuta + "</p>";
    html +=
      '<p class="lead"><strong>Tipo de intervención: </strong>' +
      tipo_intervencion +
      "</p>";
    html +=
      '<p class="lead"><strong>Referido por: </strong>' + referido_por + "</p>";
    html += '<p class="lead"><strong>Motivo: </strong>' + motivo + "</p>";
    html +=
      '<p class="lead"><strong>Fecha de registro: </strong>' +
      fecha_reg +
      "</p>";
    html += "</div>";
    html += '<div class="col-md-6">';
    html +=
      '<p class="lead"><strong>Fecha de inicio: </strong>' +
      fecha_inicio +
      "</p>";
    html +=
      '<p class="lead"><strong>Fecha de fin: </strong>' + fecha_fin + "</p>";
    html +=
      '<p class="lead"><strong>Causa de concluyo: </strong>' +
      causa_cocluyo +
      "</p>";
    html +=
      '<p class="lead"><strong>Registrado por: </strong>' +
      name_colaborator +
      "</p>";
    html += "</div>";
    html += "</div><br><br>";
    $("#bodyinfoInterview2").empty();
    $("#bodyinfoInterview2").prepend(html);
  });

  $(document).on("click", ".sendInterviewMails", function () {
    var id_card = $(this).attr("data-id-card");
    var colabs = $("#colaboradores_mails").val();

    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "sendInterviewMails",
        id_card: id_card,
        colabs: colabs,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.response) {
        } else {
          Swal.close();
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 3000,
            positionClass: "topRight",
          });
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });
  $("#modalDesgloseIncidencias").on("hidden.bs.modal", function () {
    $(".menuStudent").show();
  });
  $(document).on("click", ".savePaternsTracking", function () {
    loading();
    var id_student = $(this).attr("data-id-student");
    var motivo = $("#motivo").val();
    var responsable_seguimiento = $("#responsable_seguimiento").val();
    var tipo_seguimiento = $("#tipo_seguimiento").val();
    var seguimiento_a = $('input[name="seguimiento_a"]:checked').val();
    if (
      tipo_seguimiento == "" ||
      tipo_seguimiento == null ||
      tipo_seguimiento == undefined
    ) {
      var txt_tipo_seguimiento = "Sin info.";
    } else {
      var txt_tipo_seguimiento = $("#tipo_seguimiento option:selected").text();
    }

    var fecha_contacto = $("#fecha_contacto").val();
    var descripcion = $("#descripcion").val();
    var acuerdos = $("#acuerdos").val();

    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth();
    var yyy = date.getFullYear();
    var fecha_reg = yyy + "-" + mes + "-" + dia;

    if (
      motivo == "" ||
      tipo_seguimiento == "" ||
      responsable_seguimiento == "" ||
      fecha_contacto == ""
    ) {
      Swal.fire({
        title: "Atención",
        text: "Ingrese los campos son obligatorios",
        icon: "error",
        confirmButtonText: "Aceptar",
      });
    } else {
      $.ajax({
        url: "php/controllers/psychopedagogy_controller.php",
        method: "POST",
        data: {
          mod: "saveParentsTracking",
          motivo: motivo,
          tipo_seguimiento: tipo_seguimiento,
          responsable_seguimiento: responsable_seguimiento,
          fecha_contacto: fecha_contacto,
          descripcion: descripcion,
          acuerdos: acuerdos,
          id_student: id_student,
          seguimiento_a: seguimiento_a,
        },
      })
        .done(function (data) {
          var data = JSON.parse(data);
          if (data.response) {
            id = data.id;
            $("#newParentTracking").find("input,textarea,select").val("");
            $("#newParentTracking input[type='checkbox']")
              .prop("checked", false)
              .change();
            swal.close();
            $("#newParentTracking").modal("hide");
            // console.log(data.data);
            Swal.fire({
              title: "Atención",
              text: "Se ha guardado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
            });
            str_acuerdos = acuerdos.substring(0, 20);
            str_descripcion = descripcion.substring(0, 20);
            var name_colaborator = $("#name_colab").text();
            var_html = "";
            var_html += "<tr id='tr" + id + "'>";
            var_html += "<td>" + seguimiento_a + "</td>";
            var_html += "<td>" + motivo + "</td>";
            var_html +=
              '<td class="td-agreements" data-agreements="' +
              acuerdos +
              '" style="white-space: normal; !important;">' +
              str_acuerdos +
              "</td>";
            var_html += "<td>" + responsable_seguimiento + "</td>";
            var_html += "<td>" + txt_tipo_seguimiento + "</td>";
            var_html += "<td>" + fecha_contacto + "</td>";
            var_html +=
              '<td class="td-description" data-descripcion="' +
              descripcion +
              '" style="white-space: normal; !important;">' +
              str_descripcion +
              "</td>";
            var_html += "<td>";
            var_html +=
              '              <a title="Editar" href="#" data-toggle="modal" data-target="#editParentTracking" data-id-tracking="' +
              id +
              '" class="btn btn-sm btn-primary editParentTracking"><i class="fa-solid fa-edit"></i></a>';
            var_html +=
              '<a title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoPadresChat" data-id-parents-tracking="' +
              id +
              '" class="btn btn-sm btn-success seeTrackingParents"><i class="fa-regular fa-comment-dots"></i></a>';
            var_html +=
              '              <a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteParentTracking" data-id-tracking="' +
              id +
              '" class="btn btn-sm btn-danger deleteParentTracking"><i class="fa-regular fa-trash-alt"></i></a>';
            var_html += "</td>";
            /*  var_html +=
              '<td><a title="Ficha completa" href="#" data-toggle="modal" data-target="#infoInterview" data-id-card="<?= $card->id_therapeutic_cards ?>" class="btn btn-sm btn-warning infoInterview"><i class="fa-solid fa-info"></i></a><a href="#" title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-success seeTrackingInterview"><i class="fa-regular fa-comment-dots"></i></a> <a title="Compartir" href="#" data-toggle="modal" data-target="#shareInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-info shareTrackingInterview"><i class="fa-solid fa-share-nodes"></i></a> <a title="Editar" href="#" data-toggle="modal" data-target="#editInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-primary editInterview"><i class="fa-solid fa-edit"></i></a><a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteInterview" data-id-card="' +
              id +
              '" class="btn btn-sm btn-danger deleteInterview"><i class="fa-regular fa-trash-alt"></i></a><td>';
            var_html += "</tr>"; */
            $(".trEmptyResults").remove();
            $("#tStudents tbody").prepend(var_html);
          } else {
            Swal.close();
            VanillaToasts.create({
              title: "Error",
              text: data.message,
              type: "error",
              timeout: 3000,
              positionClass: "topRight",
            });
          }
          //--- --- ---//

          //--- --- ---//
        })
        .fail(function (message) {
          VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
          });
        });
    }
  });

  $(document).on("click", ".addArchive2", function () {
    $(".divAddArchive").hide();
    $(this).addClass("btn-secondary");
    $(this).removeClass("btn-info");
    $(this).addClass("addArchive");
    $(this).removeClass("addArchive2");
  });
  $(document).on("click", ".td-agreements", function () {
    agreements = $(this).attr("data-agreements");
    if (agreements.includes("\n")) {
      agreements = agreements.replace(/\n/g, "<br>");
    }
    Swal.fire({
      title: "Acuerdos",
      html: agreements,
      icon: "info",
      confirmButtonText: "Aceptar",
    });
  });

  $(document).on("click", ".td-description", function () {
    description = $(this).attr("data-descripcion");
    if (description.includes("\n")) {
      description = description.replace(/\n/g, "<br>");
    }

    Swal.fire({
      title: "Descripción",
      html: description,
      icon: "info",
      confirmButtonText: "Aceptar",
    });
  });

  $(document).on("click", ".deleteParentTracking", function () {
    Swal.fire({
      title: "¿Está seguro de eliminar este registro?",
      text: "No podrá revertir esta acción",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, eliminar",
      cancelButtonText: "Cancelar",
    }).then((result) => {
      if (result.value) {
        loading();
        var id_tracking = $(this).attr("data-id-tracking");
        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          method: "POST",
          data: {
            mod: "deleteParentTracking",
            id_tracking: id_tracking,
          },
        })
          .done(function (data) {
            var data = JSON.parse(data);
            if (data.response) {
              Swal.close();
              VanillaToasts.create({
                title: "Éxito",
                text: "Registro eliminado",
                type: "success",
                timeout: 3000,
                positionClass: "topRight",
              });
              $("#tr" + id_tracking).remove();
            } else {
              Swal.close();
              VanillaToasts.create({
                title: "Error",
                text: data.message,
                type: "error",
                timeout: 3000,
                positionClass: "topRight",
              });
            }
            //--- --- ---//

            //--- --- ---//
          })
          .fail(function (message) {
            VanillaToasts.create({
              title: "Error",
              text: "Ocurrió un error, intentelo nuevamente",
              type: "error",
              timeout: 1200,
              positionClass: "topRight",
            });
          });
      }
    });
  });

  $(document).on("click", ".editParentTracking", function () {
    loading();
    var id_parents_tracking = $(this).attr("data-id-tracking");
    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "getParetTrackingDetails",
        id_parents_tracking: id_parents_tracking,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        if (data.response) {
          Swal.close();
          console.log(data);
          /* 
          edit_motivo
          edit_responsable_seguimiento
          edit_tipo_seguimiento
          edit_fecha_contacto
          edit_descripcion
          edit_acuerdos 
          */
          var descripcion = data.data[0].descripcion;
          if (descripcion.includes("\n")) {
            descripcion = descripcion.replace(/\n/g, "<br>");
          }
          var agreements = data.data[0].agreements;
          if (agreements.includes("\n")) {
            agreements = agreements.replace(/\n/g, "<br>");
          }
          $("#edit_motivo").val(data.data[0].reason);
          $("#edit_responsable_seguimiento").val(
            data.data[0].monitoring_manager
          );
          $("#edit_tipo_seguimiento").val(data.data[0].id_tracking_type); // select
          $("#edit_fecha_contacto").val(data.data[0].contact_date);
          $("#edit_descripcion").val(descripcion);
          $("#edit_acuerdos").val(agreements);

          $(".updateParentTracking").attr(
            "data-id-tracking",
            id_parents_tracking
          );
          if (data.data[0].tracing_to == "PADRES") {
            $("#edit_seg_alumnos").prop("checked", false);
            $("#edit_seg_padres").prop("checked", true);
          } else {
            $("#edit_seg_padres").prop("checked", false);
            $("#edit_seg_alumnos").prop("checked", true);
          }
        } else {
          Swal.close();
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 3000,
            positionClass: "topRight",
          });
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
    var id_student = $(this).attr("data-id-student");

    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth();
    var yyy = date.getFullYear();
    var fecha_reg = yyy + "-" + mes + "-" + dia;
  });

  $(document).on("click", ".updateParentTracking", function () {
    loading();
    var id_parents_tracking = $(this).attr("data-id-tracking");
    var reason = $("#edit_motivo").val();
    var monitoring_manager = $("#edit_responsable_seguimiento").val();
    var id_tracking_type = $("#edit_tipo_seguimiento").val(); // select
    var str_id_tracking_type = $(
      "#edit_tipo_seguimiento option:selected"
    ).text();
    var contact_date = $("#edit_fecha_contacto").val();
    var descripcion = $("#edit_descripcion").val();
    var str_descripcion = descripcion.substring(0, 20);
    var agreements = $("#edit_acuerdos").val();
    var str_agreements = agreements.substring(0, 20);
    var seguimiento_a = $('input[name="edit_seguimiento_a"]:checked').val();

    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "updateParetTracking",
        id_parents_tracking: id_parents_tracking,
        reason: reason,
        monitoring_manager: monitoring_manager,
        id_tracking_type: id_tracking_type,
        contact_date: contact_date,
        descripcion: descripcion,
        agreements: agreements,
        seguimiento_a: seguimiento_a,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        if (data.response) {
          console.log(data);
          /* 
          edit_motivo
          edit_responsable_seguimiento
          edit_tipo_seguimiento
          edit_fecha_contacto
          edit_descripcion
          edit_acuerdos 
          */
          var_html = "";
          var_html += "<td>" + seguimiento_a + "</td>";
          var_html += "<td>" + reason + "</td>";
          var_html +=
            '<td class="td-agreements" data-agreements="' +
            agreements +
            '" style="white-space: normal; !important;">' +
            str_agreements +
            "</td>";
          var_html += "<td>" + monitoring_manager + "</td>";
          var_html += "<td>" + str_id_tracking_type + "</td>";
          var_html += "<td>" + contact_date + "</td>";
          var_html +=
            '<td class="td-description" data-descripcion="' +
            descripcion +
            '" style="white-space: normal; !important;">' +
            str_descripcion +
            "</td>";
          var_html += "<td>";
          var_html +=
            '              <a title="Editar" href="#" data-toggle="modal" data-target="#editParentTracking" data-id-tracking="' +
            id_parents_tracking +
            '" class="btn btn-sm btn-primary editParentTracking"><i class="fa-solid fa-edit"></i></a>';
          var_html +=
            '<a title="Seguimiento" href="#" data-toggle="modal" data-target="#seguimientoPadresChat" data-id-parents-tracking="' +
            id_parents_tracking +
            '" class="btn btn-sm btn-success seeTrackingParents"><i class="fa-regular fa-comment-dots"></i></a>';
          var_html +=
            '              <a title="Eliminar" href="#" data-toggle="modal" data-target="#deleteParentTracking" data-id-tracking="' +
            id_parents_tracking +
            '" class="btn btn-sm btn-danger deleteParentTracking"><i class="fa-regular fa-trash-alt"></i></a>';
          var_html += "</td>";
          $("#tr" + id_parents_tracking).html(var_html);
          Swal.close();
        } else {
          Swal.close();
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 3000,
            positionClass: "topRight",
          });
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
    var id_student = $(this).attr("data-id-student");

    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth();
    var yyy = date.getFullYear();
    var fecha_reg = yyy + "-" + mes + "-" + dia;
  });

  $(document).on("change", "#studentDocument", function () {
    var studentDocument = document.querySelector("#studentDocument");
    if (studentDocument.files.length > 0) {
      var file = studentDocument.files[0];
      var name = file.name;
      var ext = name.split(".").pop().toLowerCase();
      if (jQuery.inArray(ext, ["gif", "png", "jpg", "jpeg", "pdf"]) == -1) {
        Swal.fire({
          title: "Atención",
          text: "Solo se permiten archivos de imagen o PDF",
          icon: "warning",
          confirmButtonText: "Aceptar",
        });
        $("#studentDocument").val("");
        $("#lblStudentDocument").hide();
        return false;
      } else {
        $("#lblStudentDocument").show();
        $("#lblStudentDocument").text("Archivo:" + name);
      }
    }
  });

  $(document).on("click", ".saveStudentDocument", function () {
    loading();
    if (
      $("#descripcion_documento_alumno").val() == "" ||
      $("#descripcion_documento_alumno").val() == null
    ) {
      Swal.fire({
        title: "Atención",
        text: "Debe ingresar una descripción",
        icon: "warning",
        confirmButtonText: "Aceptar",
        timer: 2000,
      }).then((result) => {
        /* $("#descripcion_documento_alumno").focus(); */
      });

      /* $( "#target" ).focus(); */
    } else {
      const student_document = document.querySelector("#studentDocument");
      var id_student = $(this).attr("data-id-student");
      if (student_document.files.length > 0) {
        saveStudentDocument(id_student);
      } else {
        Swal.fire({
          title: "Atención",
          text: "Debe seleccionar un archivo",
          icon: "warning",
          confirmButtonText: "Aceptar",
          timer: 2000,
        }).then((result) => {
          $("#studentDocument").focus();
        });
      }
    }
  });

  function saveStudentDocument(id_student) {
    loading();
    const student_document = document.querySelector("#studentDocument");
    const description_document = $("#descripcion_documento_alumno").val();
    if (student_document.files.length > 0) {
      var id_archive = "NULL";
      let formData = new FormData();
      formData.append("student_document", student_document.files[0]);
      formData.append("id_student", id_student);
      formData.append("description_document", description_document);
      formData.append("mod", "saveStudentDocument");
      fetch("php/controllers/psychopedagogy_controller.php", {
        method: "POST",
        body: formData,
      })
        .then((respuesta) => respuesta.json())
        .then((decodificado) => {
          console.log(decodificado);
          id_archive = decodificado.id;
          var ext = decodificado.extension_img;
          var ruta = decodificado.ruta_sql_img;
          var description_document = decodificado.description_document;

          html = "";
          if ($("#table_documents").length) {
            html += '<tr id="tr' + id_archive + '">';
            html += "<td>" + description_document + "</td>";
            html += "<td>";
            html +=
              '<a href="' +
              ruta +
              '" target="_blank" class="btn btn-sm btn-primary"><i class="fa-solid fa-eye"></i></a>';
            html +=
              '<a href="#" data-toggle="modal" data-target="#deleteStudentDocument" data-id-archive="' +
              id_archive +
              '" class="btn btn-sm btn-danger deleteStudentDocument"><i class="fa-regular fa-trash-alt"></i></a>';
            html += "</td>";
            html += "</tr>";
            $("#table_documents").append(html);
          } else {
            html +=
              '<table class="table table-bordered table-striped" id="table_documents">';
            html += "<thead>";
            html += "<tr>";
            html += "<th>Descripción</th>";
            html += "<th>Acciones</th>";
            html += "</tr>";
            html += "</thead>";
            html += "<tbody>";
            html += '<tr id="tr' + id_archive + '">';
            html += "<td>" + description_document + "</td>";
            html += "<td>";
            html +=
              '<a href="' +
              ruta +
              '" target="_blank" class="btn btn-sm btn-primary"><i class="fa-solid fa-eye"></i></a>';
            html +=
              '<a href="#" data-toggle="modal" data-target="#deleteStudentDocument" data-id-archive="' +
              id_archive +
              '" class="btn btn-sm btn-danger deleteStudentDocument"><i class="fa-regular fa-trash-alt"></i></a>';
            html += "</td>";
            html += "</tr>";
            html += "</tbody>";
            html += "</table>";
            $("#divTableDocuments").append(html);
          }
          $("#descripcion_documento_alumno").val("");
          $("#studentDocument").val("");
          $("#lblStudentDocument").hide();
          Swal.fire({
            title: "Éxito",
            text: "Archivo guardado correctamente",
            icon: "success",
            confirmButtonText: "Aceptar",
            timer: 2000,
          });

          /* 
          con
          
          if (ext != "pdf") {
            btn_doc =
              '<a href="' +
              ruta +
              '" target="_blank" type="button" class="btn btn-info btn-icon-info rounded-circle"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
          } else {
            btn_doc =
              '<a href="' +
              ruta +
              '" target="_blank" type="button" class="btn btn-danger btn-icon-danger rounded-circle"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
          }
          var id_teacher_tracking = $("#id_teacher_tracking").val();
          var comentario_seguimientos = $("#comentario_seguimientos").val();
          $("#comentario_seguimientos").val("");
          var teacher_name_registered_tracking = $(
            "#teacher_name_registered_tracking"
          ).val(); */
        });
    }
  }
  $(document).on("click", ".deleteStudentDocument", function () {
    var id_archive = $(this).attr("data-id-archive");
    Swal.fire({
      title: "¿Está seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, bórralo!",
    }).then((result) => {
      loading();
      if (result.value) {
        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          type: "POST",
          data: {
            mod: "deleteStudentDocument",
            id_archive: id_archive,
          },
          success: function (response) {
            console.log(response);
            $("#tr" + id_archive).remove();
            Swal.fire({
              title: "Éxito",
              text: "Archivo eliminado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
              timer: 2000,
            });
          },
        });
      }
    });
    $("#id_archive").val(id_archive);
  });

  $(document).on("click", ".getStudentsDocuments", function () {
    loading();
    var id_student = $(this).attr("data-id-student");
    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "getStudentsDocuments",
        id_student: id_student,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.response) {
          $("#divTableDocuments").html("");
          html = "";
          html +=
            '<table class="table table-bordered table-striped" id="table_documents">';
          html += "<thead>";
          html += "<tr>";
          html += "<th>Descripción</th>";
          html += "<th>Acciones</th>";
          html += "</tr>";
          html += "</thead>";
          html += "<tbody>";
          for (let i = 0; i < data.data.length; i++) {
            const element = data.data[i];
            var ext = element.extension_img;
            var ruta = element.archive_route;
            var description_document = element.deocument_description;
            var id_archive = element.id_student_documents;

            html += '<tr id="tr' + id_archive + '">';
            html += "<td>" + description_document + "</td>";
            html += "<td>";
            html +=
              '<a href="' +
              ruta +
              '" target="_blank" class="btn btn-sm btn-primary"><i class="fa-solid fa-eye"></i></a>';
            html +=
              '<a href="#" data-toggle="modal" data-target="#deleteStudentDocument" data-id-archive="' +
              id_archive +
              '" class="btn btn-sm btn-danger deleteStudentDocument"><i class="fa-regular fa-trash-alt"></i></a>';
            html += "</td>";
            html += "</tr>";
          }
          html += "</tbody>";
          html += "</table>";
          $("#divTableDocuments").append(html);
          Swal.close();
        } else {
          Swal.close();
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 3000,
            positionClass: "topRight",
          });
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });
  $(document).on("click", ".editCommentTracking", function () {
    var id_commentary = $(this).attr("data-id");
    var commentary = $(this).attr("data-commentary");
    commentary = commentary.replace(/<br>/g, "\n");

    var html = "";
    html += '<div class="form-group row">';
    html += '<label class="col-sm-3 col-form-label">Comentario</label>';
    html += '<div class="col-sm-9">';
    html +=
      '<textarea class="form-control" id="edit_commentary_tracking' +
      id_commentary +
      '" rows="3">' +
      commentary +
      "</textarea>";
    html +=
      "<br><button class='btn btn-primary btn-sm btnUpdateCommentary' id='btnUpdateCommentary' data-id='" +
      id_commentary +
      "'>Guardar</button>";
    html +=
      "<button class='btn btn-danger btn-sm btnCancelUpdateCommentary' data-commentary='" +
      commentary +
      "' data-id='" +
      id_commentary +
      "'>Cancelar</button>";
    html += "</div>";
    html += "</div>";
    $("#divTrackingCommentary" + id_commentary).html(html);
    /* Swal.fire({
      title: "¿Está seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, bórralo!",
    }).then((result) => {
      loading();
      if (result.value) {
        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          type: "POST",
          data: {
            mod: "deleteStudentDocument",
            id_archive: id_archive,
          },
          success: function (response) {
            console.log(response);
            $("#tr" + id_archive).remove();
            Swal.fire({
              title: "Éxito",
              text: "Archivo eliminado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
              timer: 2000,
            });
          },
        });
      }
    }); */
    /* $("#id_archive").val(id_archive); */
  });
  $(document).on("click", ".btnCancelUpdateCommentary", function () {
    var id_commentary = $(this).attr("data-id");
    var commentary = $(this).attr("data-commentary");
    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "getDocInfoCommentaryTracking",
        id_commentary: id_commentary,
      },
    })
      .done(function (data) {
        var time_now = new Date();
        var btn_archive = "";
        var data = JSON.parse(data);
        if (data.response) {
          var extension = data.data[0].archive_type;
          var route = data.data[0].route_archive;
          if (data.data[0].archive_type == "pdf") {
            btn_archive +=
              '<a  style="margin: 10px 3px 30px 5px;" href="' +
              data.data[0].route_archive +
              '" target="_blank" class="btnDoc' +
              data.id_terapeutic_cards_chat +
              '"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
          } else {
            btn_archive +=
              '<a  style="margin: 10px 3px 30px 5px;" href="' +
              data.data[0].route_archive +
              '" target="_blank" class="btnDoc' +
              data.id_terapeutic_cards_chat +
              '"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
          }
        } else {
          btn_doc =
            '<a  style="margin: 10px 3px 30px 5px;" data-id="' +
            id_commentary +
            '" class="btnAddTrackingArchive" id="btnAddArciveChat' +
            id_commentary +
            '"><span class="btn-inner--icon"><i id="iconAddArchive' +
            id_commentary +
            '"  class="fa-solid fa-folder-plus"></i></span></a>';
          btn_archive += btn_doc;
        }
        //--- --- ---//
        var html = "";
        html +=
          '<div class="mt-3" id="divTrackingCommentary' + id_commentary + '">';
        html += btn_archive;
        html +=
          '<a style="margin: 10px 3px 30px 5px;"  target="_blank" data-id="' +
          id_commentary +
          '" data-commentary="' +
          commentary +
          '" class="editCommentTracking"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';
        html +=
          '<a style="margin: 10px 3px 30px 5px;"  data-id="' +
          id_commentary +
          '" class="btnDeleteTrackingCommitInterview"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a><span class="badge badge-pill badge-success">';
        html += "</div>";
        $("#divTrackingCommentary" + id_commentary).html(html);
        $("#textComentary" + id_commentary).html(commentary);
        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });

    /* Swal.fire({
      title: "¿Está seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, bórralo!",
    }).then((result) => {
      loading();
      if (result.value) {
        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          type: "POST",
          data: {
            mod: "deleteStudentDocument",
            id_archive: id_archive,
          },
          success: function (response) {
            console.log(response);
            $("#tr" + id_archive).remove();
            Swal.fire({
              title: "Éxito",
              text: "Archivo eliminado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
              timer: 2000,
            });
          },
        });
      }
    }); */
    /* $("#id_archive").val(id_archive); */
  });
  $(document).on("click", ".btnUpdateCommentary", function () {
    var id_commentary = $(this).attr("data-id");
    var commentary = $("#edit_commentary_tracking" + id_commentary).val();
    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "updateCommentaryTracking",
        id_commentary: id_commentary,
        commentary: commentary,
      },
    })
      .done(function (data) {
        var time_now = new Date();

        var data = JSON.parse(data);
        if (data.response) {
          $.ajax({
            url: "php/controllers/psychopedagogy_controller.php",
            method: "POST",
            data: {
              mod: "getDocInfoCommentaryTracking",
              id_commentary: id_commentary,
            },
          })
            .done(function (data) {
              var time_now = new Date();
              var btn_archive = "";
              var data = JSON.parse(data);
              console.log(data);
              if (data.response == true) {
                var extension = data.data[0].archive_type;
                var route = data.data[0].route_archive;
                if (data.data[0].archive_type == "pdf") {
                  btn_archive +=
                    '<a style="margin: 10px 3px 30px 5px;" href="' +
                    data.data[0].route_archive +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id_terapeutic_cards_chat +
                    '"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
                } else {
                  btn_archive +=
                    '<a style="margin: 10px 3px 30px 5px;" href="' +
                    data.data[0].route_archive +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id_terapeutic_cards_chat +
                    '"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
                }
              } else {
                btn_doc =
                  '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                  id_commentary +
                  '" class="btnAddTrackingArchive" id="btnAddArciveChat' +
                  id_commentary +
                  '"><span class="btn-inner--icon"><i id="iconAddArchive' +
                  id_commentary +
                  '"  class="fa-solid fa-folder-plus"></i></span></a>';
                btn_archive += btn_doc;
              }
              //--- --- ---//
              var html = "";
              html +=
                '<div class="mt-3" id="divTrackingCommentary' +
                id_commentary +
                '">';
              html += btn_archive;
              html +=
                '<a style="margin: 10px 3px 30px 5px;" target="_blank" data-id="' +
                id_commentary +
                '" data-commentary="' +
                commentary +
                '" class="editCommentTracking"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';
              html +=
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                id_commentary +
                '" class="btnDeleteTrackingCommitInterview"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a><span class="badge badge-pill badge-success">';
              html += "</div>";
              $("#divTrackingCommentary" + id_commentary).html(html);
              $("#textComentary" + id_commentary).html(commentary);
              //--- --- ---//
            })
            .fail(function (message) {
              VanillaToasts.create({
                title: "Error",
                text: "Ocurrió un error, intentelo nuevamente",
                type: "error",
                timeout: 1200,
                positionClass: "topRight",
              });
            });
        } else {
          Swal.close();
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 3000,
            positionClass: "topRight",
          });
          Swal.close();
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
    /* Swal.fire({
      title: "¿Está seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, bórralo!",
    }).then((result) => {
      loading();
      if (result.value) {
        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          type: "POST",
          data: {
            mod: "deleteStudentDocument",
            id_archive: id_archive,
          },
          success: function (response) {
            console.log(response);
            $("#tr" + id_archive).remove();
            Swal.fire({
              title: "Éxito",
              text: "Archivo eliminado correctamente",
              icon: "success",
              confirmButtonText: "Aceptar",
              timer: 2000,
            });
          },
        });
      }
    }); */
    /* $("#id_archive").val(id_archive); */
  });

  //// SEGUIMIENTO A PADRES DE FAMILIA //
  $(document).on("click", ".seeTrackingParents", function () {
    loading();
    $(".trackingWithParents").empty();
    var id_parents_tracking = $(this).attr("data-id-parents-tracking");
    console.log(id_parents_tracking);
    $(".saveComentaryParents").attr(
      "data-id-parents-tracking",
      id_parents_tracking
    );

    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "getTrackingParents",
        id_parents_tracking: id_parents_tracking,
      },
    })
      .done(function (data) {
        var data = JSON.parse(data);
        console.log(data);
        if (data.response) {
          for (var i = 0; i < data.data.length; i++) {
            var_chat_meessae = data.data[i].chat_message;
            if (var_chat_meessae.includes("\n")) {
              var_chat_meessae = var_chat_meessae.replace(/\n/g, "<br>");
            }
            var_html = "";
            //
            var_html +=
              '<div class="timeline-block" id="divTracking' +
              data.data[i].id_parents_tracking_chat +
              '">';

            var_html += '<span class="timeline-step badge-success">';
            var_html += '<i class="fa-solid fa-address-card"></i>';
            var_html += "</span>";
            var_html += '<div class="timeline-content">';
            var_html +=
              '<small class="text-muted font-weight-bold">' +
              data.data[i].teacher_name +
              " | " +
              data.data[i].datelog +
              "</small>";
            var_html +=
              '<h5 class=" mt-3 mb-0" id="textComentary' +
              data.data[i].id_parents_tracking_chat +
              '">' +
              var_chat_meessae +
              "</h5>";
            var_html +=
              '<div class="mt-3" id="divTrackingCommentary' +
              data.data[i].id_parents_tracking_chat +
              '"> ';
            var_html +=
              '<a style="margin: 10px 3px 30px 5px;" target="_blank" data-id="' +
              data.data[i].id_parents_tracking_chat +
              '" data-commentary="' +
              var_chat_meessae +
              '"  class="editCommentTrackingParents"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';
            if (
              data.data[i].route_archive != null ||
              data.data[i].route_archive == ""
            ) {
              if (data.data[i].archive_type == "pdf") {
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;" href="' +
                  data.data[i].route_archive +
                  '" target="_blank" class="btnDoc' +
                  data.id_parents_tracking_chat +
                  '"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
              } else {
                var_html +=
                  '<a style="margin: 10px 3px 30px 5px;" href="' +
                  data.data[i].route_archive +
                  '" target="_blank" class="btnDoc' +
                  data.id_parents_tracking_chat +
                  '"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
              }
            } else {
              btn_doc =
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                data.data[i].id_parents_tracking_chat +
                '" class="btnAddTrackingArchiveParents" id="btnAddArciveChat' +
                data.data[i].id_parents_tracking_chat +
                '"><span class="btn-inner--icon"><i id="iconAddArchive' +
                data.data[i].id_parents_tracking_chat +
                '"  class="fa-solid fa-folder-plus"></i></span></a>';
              var_html += btn_doc;
            }
            var_html +=
              '<a style="margin: 10px 3px 30px 5px;" data-id="' +
              data.data[i].id_parents_tracking_chat +
              '" class="btnDeleteTrackingCommitInterviewParents"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a>';
            var_html += "</div>";
            var_html += "</div>";
            var_html += "</div>";
            $(".trackingWithParents").append(var_html);
            Swal.close();
          }
        } else {
          Swal.close();
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });

  $(document).on("click", ".saveComentaryParents", function () {
    loading();
    if (
      $("#comentario_seguimientos_padres").val() == "" ||
      $("#comentario_seguimientos_padres").val() == null
    ) {
      Swal.fire({
        title: "Atención",
        text: "Debe ingresar un comentario",
        icon: "warning",
        confirmButtonText: "Aceptar",
        timer: 2000,
      }).then((result) => {
        /* $("#comentario_seguimientos").focus(); */
      });

      /* $( "#target" ).focus(); */
    } else {
      const archive_tracking_parents = document.querySelector(
        "#archiveTtrtackingParents"
      );
      var id_parents_tracking = $(this).attr("data-id-parents-tracking");
      if (archive_tracking_parents.files.length > 0) {
        saveTccArchiveParents(id_parents_tracking);
      } else {
        var id_parents_tracking = $(this).attr("data-id-parents-tracking");
        var id_teacher_tracking = $("#id_teacher_tracking_parents").val();
        var comentario_seguimientos_padres = $(
          "#comentario_seguimientos_padres"
        ).val();
        $("#comentario_seguimientos_padres").val("");
        var teacher_name_registered_tracking = $(
          "#teacher_name_registered_tracking"
        ).val();

        $.ajax({
          url: "php/controllers/psychopedagogy_controller.php",
          method: "POST",
          data: {
            mod: "saveInterviewTrackingParents",
            id_parents_tracking: id_parents_tracking,
            id_teacher_tracking: id_teacher_tracking,
            comentario_seguimientos: comentario_seguimientos_padres,
          },
        })
          .done(function (data) {
            var time_now = new Date();

            var data = JSON.parse(data);
            if (data.response) {
              btn_doc =
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                data.id +
                '" class="btnAddTrackingArchive btnDoc' +
                data.id +
                '" id="btnAddArciveChat' +
                data.id +
                '"><span class="btn-inner--icon"><i id="iconAddArchive' +
                data.id +
                '" class="fa-solid fa-folder-plus"></i></span></a>';
              var_html = "";
              var_html +=
                '<div class="timeline-block" id="divTracking' + data.id + '">';

              var_html += '<span class="timeline-step badge-success">';
              var_html += '<i class="fa-solid fa-address-card"></i>';
              var_html += "</span>";
              var_html += '<div class="timeline-content">';
              var_html +=
                '<small class="text-muted font-weight-bold">' +
                teacher_name_registered_tracking +
                " | (Justo ahora)</small>";
              var_chat_meessae = comentario_seguimientos_padres;
              if (
                var_chat_meessae.includes("\n") == true ||
                var_chat_meessae.includes("\r") == true
              ) {
                var_chat_meessae = var_chat_meessae.replace(/\n/g, "<br>");
              }
              var_html +=
                '<h5 class=" mt-3 mb-0" id="textComentary' +
                data.id +
                '"><pre>' +
                comentario_seguimientos_padres +
                "</pre></h5>";
              var_html +=
                '<div class="mt-3" id="divTrackingCommentary' + data.id + '">';
              var_html +=
                '<a style="margin: 10px 3px 30px 5px;" style="margin: 10px 3px 30px 5px;" target="_blank" data-id="' +
                data.id +
                '" data-commentary="' +
                comentario_seguimientos_padres +
                '" class="editCommentTracking"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';

              var_html += btn_doc;
              var_html +=
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                data.id +
                '" class="btnDeleteTrackingCommitInterviewParents"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a><span class="badge badge-pill badge-success">';
              var_html += "</div>";
              var_html += "</div>";
              var_html += "</div>";
              $(".trackingWithParents").append(var_html);
              Swal.close();
            } else {
              Swal.close();
              VanillaToasts.create({
                title: "Error",
                text: data.message,
                type: "error",
                timeout: 3000,
                positionClass: "topRight",
              });
              Swal.close();
            }
            //--- --- ---//

            //--- --- ---//
          })
          .fail(function (message) {
            VanillaToasts.create({
              title: "Error",
              text: "Ocurrió un error, intentelo nuevamente",
              type: "error",
              timeout: 1200,
              positionClass: "topRight",
            });
          });
      }
    }
  });

  $(document).on("click", ".editCommentTrackingParents", function () {
    var id_commentary = $(this).attr("data-id");
    var commentary = $(this).attr("data-commentary");
    commentary = commentary.replace(/<br>/g, "\n");

    var html = "";
    html += '<div class="form-group row">';
    html += '<label class="col-sm-3 col-form-label">Comentario</label>';
    html += '<div class="col-sm-9">';
    html +=
      '<textarea class="form-control" id="edit_commentary_tracking_parents' +
      id_commentary +
      '" rows="3">' +
      commentary +
      "</textarea>";
    html +=
      "<br><button class='btn btn-primary btn-sm btnUpdateCommentaryParents' id='btnUpdateCommentaryParents' data-id='" +
      id_commentary +
      "'>Guardar</button>";
    html +=
      "<button class='btn btn-danger btn-sm btnCancelUpdateCommentary' data-commentary='" +
      commentary +
      "' data-id='" +
      id_commentary +
      "'>Cancelar</button>";
    html += "</div>";
    html += "</div>";
    $("#divTrackingCommentary" + id_commentary).html(html);
  });

  $(document).on("click", ".btnUpdateCommentaryParents", function () {
    var id_commentary = $(this).attr("data-id");
    var commentary = $(
      "#edit_commentary_tracking_parents" + id_commentary
    ).val();
    $.ajax({
      url: "php/controllers/psychopedagogy_controller.php",
      method: "POST",
      data: {
        mod: "updateCommentaryTrackingParents",
        id_commentary: id_commentary,
        commentary: commentary,
      },
    })
      .done(function (data) {
        var time_now = new Date();

        var data = JSON.parse(data);
        if (data.response) {
          $.ajax({
            url: "php/controllers/psychopedagogy_controller.php",
            method: "POST",
            data: {
              mod: "getDocInfoCommentaryTracking",
              id_commentary: id_commentary,
            },
          })
            .done(function (data) {
              var time_now = new Date();
              var btn_archive = "";
              var data = JSON.parse(data);
              console.log(data);
              if (data.response == true) {
                var extension = data.data[0].archive_type;
                var route = data.data[0].route_archive;
                if (data.data[0].archive_type == "pdf") {
                  btn_archive +=
                    '<a style="margin: 10px 3px 30px 5px;" href="' +
                    data.data[0].route_archive +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id_parents_tracking_chat +
                    '"><span class="btn-inner--icon"><i class="fa-regular fa-file-pdf"></i></span></a>';
                } else {
                  btn_archive +=
                    '<a style="margin: 10px 3px 30px 5px;" href="' +
                    data.data[0].route_archive +
                    '" target="_blank" type="button" class="btnDoc' +
                    data.id_parents_tracking_chat +
                    '"><span class="btn-inner--icon"><i class="fa-solid fa-image"></i></i></span></a>';
                }
              } else {
                btn_doc =
                  '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                  id_commentary +
                  '" class="btnAddTrackingArchive" id="btnAddArciveChat' +
                  id_commentary +
                  '"><span class="btn-inner--icon"><i id="iconAddArchive' +
                  id_commentary +
                  '"  class="fa-solid fa-folder-plus"></i></span></a>';
                btn_archive += btn_doc;
              }
              //--- --- ---//
              var html = "";
              html +=
                '<div class="mt-3" id="divTrackingCommentary' +
                id_commentary +
                '">';
              html += btn_archive;
              html +=
                '<a style="margin: 10px 3px 30px 5px;" target="_blank" data-id="' +
                id_commentary +
                '" data-commentary="' +
                commentary +
                '" class="editCommentTrackingParents"><span class="btn-inner--icon"><i class="fa-regular fa-pen-to-square"></i></span></a>';
              html +=
                '<a style="margin: 10px 3px 30px 5px;" data-id="' +
                id_commentary +
                '" class="btnDeleteTrackingCommitInterviewParents"><span class="btn-inner--icon"><i class="fa-solid fa-trash-can"></i></span></a><span class="badge badge-pill badge-success">';
              html += "</div>";
              $("#divTrackingCommentary" + id_commentary).html(html);
              $("#textComentary" + id_commentary).html(commentary);
              //--- --- ---//
            })
            .fail(function (message) {
              VanillaToasts.create({
                title: "Error",
                text: "Ocurrió un error, intentelo nuevamente",
                type: "error",
                timeout: 1200,
                positionClass: "topRight",
              });
            });
        } else {
          Swal.close();
          VanillaToasts.create({
            title: "Error",
            text: data.message,
            type: "error",
            timeout: 3000,
            positionClass: "topRight",
          });
          Swal.close();
        }
        //--- --- ---//

        //--- --- ---//
      })
      .fail(function (message) {
        VanillaToasts.create({
          title: "Error",
          text: "Ocurrió un error, intentelo nuevamente",
          type: "error",
          timeout: 1200,
          positionClass: "topRight",
        });
      });
  });

  $(document).on(
    "click",
    ".btnDeleteTrackingCommitInterviewParents",
    function () {
      loading();
      var id_commentary = $(this).attr("data-id");
      Swal.fire({
        title: "¿Estás seguro?",
        text: "¡No podrás revertir esto!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "¡Sí, bórralo!",
      }).then((result) => {
        if (result.value) {
          loading();
          $.ajax({
            url: "php/controllers/psychopedagogy_controller.php",
            method: "POST",
            data: {
              mod: "deleteInterviewTrackingCommentParents",
              id_commentary: id_commentary,
            },
          })
            .done(function (data) {
              Swal.close();
              var data = JSON.parse(data);
              if (data.response) {
                $("#divTracking" + id_commentary).remove();
              } else {
                Swal.close();
                VanillaToasts.create({
                  title: "Error",
                  text: data.message,
                  type: "error",
                  timeout: 3000,
                  positionClass: "topRight",
                });
              }
              //--- --- ---//

              //--- --- ---//
            })
            .fail(function (message) {
              VanillaToasts.create({
                title: "Error",
                text: "Ocurrió un error, intentelo nuevamente",
                type: "error",
                timeout: 1200,
                positionClass: "topRight",
              });
            });
        }
      });
    }
  );

  $(document).on("click", ".btnAddTrackingArchiveParents", function () {
    id_parents_tracking_chat = $(this).attr("data-id");
    $(this).removeClass("btn-default");
    $(this).addClass("btn-success");
    $(this).removeClass("btnAddTrackingArchiveParents");
    $(this).addClass("btnAddTrackingArchiveParentsSelected");
    var_html = "";
    var_html +=
      '<div class="modal-footer divAddArchiveChat' +
      id_parents_tracking_chat +
      '" id="">';
    var_html += '<div class="custom-file">';
    var_html +=
      '<input type="file" accept="application/pdf, image/png, image/jpg, image/jpeg" data-id="' +
      id_parents_tracking_chat +
      '" class="archiveTtrtackingChat" id="archiveTtrtackingChat' +
      id_parents_tracking_chat +
      '" lang="es">';
    var_html +=
      '<label class="custom-file-label" id="lblArchiveTrackingChat' +
      id_parents_tracking_chat +
      '" for="archiveTtrtackingChat' +
      id_parents_tracking_chat +
      '">Seleccionar un archivo</label>';
    var_html +=
      '</div> <button type="button" data-id="' +
      id_parents_tracking_chat +
      '" class="btn btn-primary btnAddArchiveTrackingChatParents" data-id="' +
      id_parents_tracking_chat +
      '"><i class="fa-solid fa-upload"></i></button>';
    var_html += "</div>";
    $("#divTracking" + id_parents_tracking_chat).append(var_html);
  });
  $(document).on("click", ".btnAddArchiveTrackingChatParents", function () {
    loading();
    id_parents_tracking_chat = $(this).attr("data-id");

    const archive_tracking = document.querySelector(
      "#archiveTtrtackingChat" + id_parents_tracking_chat
    );
    if (archive_tracking.files.length > 0) {
      var id_archive = "NULL";
      let formData = new FormData();
      formData.append("archive_tracking", archive_tracking.files[0]);
      formData.append("mod", "saveTccArchiveChatParents");
      formData.append("id_chat", id_parents_tracking_chat);
      fetch("php/controllers/psychopedagogy_controller.php", {
        method: "POST",
        body: formData,
      })
        .then((respuesta) => respuesta.json())
        .then((decodificado) => {
          id_archive = decodificado.id;
          var ext = decodificado.extension_img;
          var ruta = decodificado.ruta_sql_img;

          if (ext != "pdf") {
            btn_icon = "fa-image";
            btn_class = "btn-info";
          } else {
            btn_icon = "fa-file-pdf";
            btn_class = "btn-danger";
          }

          $("#btnAddArciveChat" + id_parents_tracking_chat).removeClass(
            "btn-success"
          );
          $("#btnAddArciveChat" + id_parents_tracking_chat).removeClass(
            "btnAddTrackingArchiveParents"
          );
          $("#btnAddArciveChat" + id_parents_tracking_chat).removeClass(
            "btnAddTrackingArchiveParentsSelected"
          );

          $("#iconAddArchive" + id_parents_tracking_chat).removeClass(
            "fa-folder-plus"
          );

          $("#iconAddArchive" + id_parents_tracking_chat).addClass(btn_icon);

          $("#btnAddArciveChat" + id_parents_tracking_chat).addClass(btn_class);
          $("#btnAddArciveChat" + id_parents_tracking_chat).attr("href", ruta);
          $("#btnAddArciveChat" + id_parents_tracking_chat).attr(
            "target",
            "_blank"
          );
          $(".divAddArchiveChat" + id_parents_tracking_chat).remove();

          Swal.fire({
            title: "Archivo agregado",
            text: "El archivo se agregó correctamente",
            icon: "success",
            showCancelButton: false,
            confirmButtonColor: "#3085d6",
            timer: 1500,
          });
        });
    }
  });

  /*  $("#colaboradores_mails").select2({
    dropdownParent: $("#shareInterview"),
  }); */
  /* $.fn.modal.Constructor.prototype._enforceFocus = function() {}; */
});
