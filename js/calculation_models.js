$(document).on('click', '.btn_apply', function() {
    //--- --- ---//
    let idModel = $(this).attr('id');
    let nameFunction = $(this).attr('data-name-function');
    let id_assignment = $(this).attr('data-id-assignment');
    getInfoModel(idModel, nameFunction, id_assignment);
    //--- --- ---//
});

function getInfoModel(idModel, nameFunction, id_assignment) {
    //--- --- ---//
    loading();
    $.ajax({
        url: "php/controllers/models_calc.php",
        method: "POST",
        data: {
            mod: 'getInfoModel',
            operation_model_id: idModel,
            id_assignment: id_assignment
        },
    }).done(function(data) {
        data = JSON.parse(data);
        if (data.response) {
            //--- --- ---//
            let name_model = data.name_model;
            let subject = data.subject;
            //--- --- ---//
            Swal.fire({
                title: 'Atención!',
                html: "¿Desea aplicar la siguiente configuración? <br/> Modelo: <b>" + name_model + "</b><br/>Asignatura: <b>" + subject + "</b>",
                icon: 'warning',
                confirmButtonColor: '#65E374',
                showCancelButton: true,
                confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    applyModel(idModel, nameFunction, id_assignment);
                }
            })
        }
        //--- --- ---//
    }).fail(function(message) {
        Swal.fire("Error", "Hubo un error al intentar conectarse con la base de datos :(", "error");
    });
    //--- --- ---//
}

function applyModel(idModel, nameFunction, id_assignment) {
    //--- --- ---//
    loading();
    $.ajax({
        url: "php/controllers/models_calc.php",
        method: "POST",
        data: {
            mod: nameFunction,
            operation_model_id: idModel,
            id_assignment: id_assignment
        },
    }).done(function(data) {
        data = JSON.parse(data);
        var title = 'Listo!';
        if (data.response) {
            title = 'Atención';
        }
        //--- --- ---//
        Swal.fire({
            title: title,
            html: data.message,
            icon: data.icon,
            showCancelButton: false
        }).then((result) => {
            location.reload();
        })
        //--- --- ---//
    }).fail(function(message) {
        Swal.fire("Error", "Hubo un error al intentar conectarse con la base de datos :(", "error");
    });
    //--- --- ---//
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