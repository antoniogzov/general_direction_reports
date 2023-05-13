
$(document).on('click', '.modify_ev_plan', function () {
    //--- --- ---//
    var id_plan = $(this).attr("id");
    console.log(id_plan);


    $.ajax({
        url: "php/controllers/controllerConfigEvaluationPlan.php",
        method: "POST",
        data: {
            fun: 'getEvaluationConfig',
            id_criterio: id_plan
        },
        dataType: "json",
    }).done(function (data) {
        console.log(data);
        $("#txt_evaluation_nameOG").text("Usted seleccionó: " + data[0].evaluation_name);
        $("#txt_manual_nameOG").text("Usted ingresó: " + data[0].manual_name);;
        $("#txt_percentageOG").text("Usted ingresó: " + data[0].percentage + "%");;
        $("#manual_name").val(data[0].manual_name);
        $("#edit_percentage").val(data[0].percentage);
        $("#affect_final_calification").prop("checked", false);

        if (data[0].id_evaluation_source == 1) {
            $("#div_manual_name_edit").show();
        } else {
            $("#div_manual_name_edit").hide();
        }
        if (data[0].affects_evaluation == 1) {
            $("#affect_final_calification").prop("checked", true);
        }
        var sql_date = data[0].deadline;
        var sDateParts = sql_date.split(" ");
        var fDateParts = sDateParts[0].split("-");
        var shDate = fDateParts[2] + "/" + fDateParts[1] + "/" + fDateParts[0];
        $("#txt_deadline").text("Usted seleccionó: " + shDate);
        //$("#in_deadline").val(shDate);
        var botones = ' <button type="button" class="btn btn-primary btn_update_criteria" id="' + data[0].id_evaluation_plan + '">Guardar</button><button type="button" class="btn btn-secondary" id="cerrar_b_actualizar">Volver</button>';
        $("#buttons").html(botones);
    }).fail(function (error) {
        console.log(error);
    });
    $('#modify_evaluation_plan').modal('show');
    loadEditPercentage();
    //$('.modal-backdrop').hide();
});
$(document).on('click', '#btn_export_subject_config', function () {
    //--- --- ---//
    console.log("ecc");
    $('#export_subject_plan').modal('show');

});
$(document).on('change', '#name_edit_criteria', function () {
    //--- --- ---//
    var criteria_name = $(this).val();
    if (criteria_name == 1) {
        $("#div_manual_name_edit").show();
    } else {
        $("#div_manual_name_edit").hide();
        $("#manual_name").val("");
    }
});

$(document).on('click', '.btn_update_criteria', function () {
    //--- --- ---//
    var id_criterio = $(this).attr('id');
    var criteria_name = $("#name_edit_criteria").val();
    var manual_name = $("#manual_name").val();
    //var eval_type = $("#eval_type").val();
    var eval_type = "0";
    var affect_final_calification = 0;
    var edit_percentage = $("#edit_percentage").val();
    var in_deadline = $("#in_deadline").val();

    var check_afc = $("#affect_final_calification");
    if ($(check_afc).is(":checked")) {
        affect_final_calification = 1;
    }

    console.log(id_criterio);
    console.log(criteria_name);
    console.log(manual_name);
    console.log(eval_type);
    console.log(edit_percentage);
    console.log(affect_final_calification);
    console.log(in_deadline);

    if (id_criterio == null || criteria_name < 1 || (criteria_name == 1 && manual_name == "")) {
        Swal.fire({
            icon: 'error',
            title: "Debe llenar todas los datos obligatorios!!",
            showConfirmButton: false,
            timer: 3500
        });
    } else {
        $.ajax({
            url: "php/controllers/controllerConfigEvaluationPlan.php",
            method: "POST",
            data: {
                fun: 'updateEvaluationConfig',
                id_criterio: id_criterio,
                criteria_name: criteria_name,
                manual_name: manual_name,
                eval_type: eval_type,
                edit_percentage: edit_percentage,
                affect_final_calification: affect_final_calification,
                in_deadline: in_deadline
            },
            dataType: "json",
        }).done(function (data) {
            console.log(data);
            if (data[0].resultado = "correcto") {
                var mensaje = data[0].mensaje;
                Swal.fire({
                    icon: 'success',
                    title: mensaje,
                    showConfirmButton: false,
                    timer: 1500
                });
                setTimeout(function () {
                    window.location.reload(1);
                }, 500);
            }



        }).fail(function (error) {
            console.log(error);
        });
        $('#modify_evaluation_plan').modal('hide');
    }
});

setInputFilter(document.getElementById("edit_percentage"), function (value) {
    disponible = parseInt($('#edit_percentage_asigned').val());
    return /^\d*$/.test(value) && (value == "" || parseInt(value) >= 0 && parseInt(value) <= disponible);
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
    return true;
}

function loadEditPercentage() {
    var periodo = $('#id_period_selected').val();
    var assignment = $('#assignment').val();
    $.ajax({
        url: "php/models/get_percentage.php",
        method: "POST",
        data: {
            periodo: periodo,
            assignment: assignment
        },
        dataType: "json",
        success: function (data) {
            var porcentaje_asignado = porcentaje_asignado = data[0].porcentaje;
            var disponible = parseInt(porcentaje_asignado);
            console.log(disponible);
            var pd = 100-disponible;
            $('#edit_percentage_asigned').val(disponible);
            $('#txt_percentage_asigned_edit').text("Tiene un: " + pd + "% disponible");
        }
    });
}

