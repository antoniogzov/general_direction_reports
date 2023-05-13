//getGroups2();
//--- --- ---///
$(document).on("change", "#id_academic_area", function() {
    loading();
    $("#tableTeachers").empty();
    $("#total_teachers").empty();
    var id_academic_area = $(this).val();
    getAcademicLevels(id_academic_area);
});
$(document).on("click", ".btn_teacher_assignments", function() {
    loading();
    var id_teacher = $(this).attr("id-teacher");
    var id_academic_level = $("#academic_level").val();
    var id_academic_area = $("#id_academic_area").val();
    $.ajax({
        url: "php/controllers/academic_mesh.php",
        method: "POST",
        data: {
            mod: "getTeacherAssignments",
            id_teacher: id_teacher,
            id_academic_level: id_academic_level,
            id_academic_area: id_academic_area,
        },
    }).done(function(data) {
        // console.log(data);
        Swal.close();
        var data = JSON.parse(data);
        if (data.response) {
            var html_sweet_alert = "<h2>" + data.data[0].teacher_name + "</h2>";
            html_sweet_alert += '<div style="height: 500px; overflow: auto;"><table class="table align-items-center table-flush" id="tablaAsignaturas">';
            html_sweet_alert += '<thead class="thead-light">';
            html_sweet_alert += "<tr>";
            html_sweet_alert += "<th onclick='sortTable(0)'>Materia</th>";
            html_sweet_alert += "<th onclick='sortTable(0)'>Grupo</th>";
            html_sweet_alert += "</tr>";
            html_sweet_alert += "</thead>";
            html_sweet_alert += "<tbody class='list'>";
            for (var i = 0; i < data.data.length; i++) {
                html_sweet_alert += "<tr id='" + data.data[i].id_assignment + "'>";
                html_sweet_alert += "<td>" + data.data[i].name_subject + "</td>";
                html_sweet_alert += "<td>" + data.data[i].group_code + "</td>";
                html_sweet_alert += "</tr>";
            }
            html_sweet_alert += "</tbody>";
            html_sweet_alert += "</table></div>";
            Swal.fire({
                title: "Asignaturas",
                html: html_sweet_alert,
                showCancelButton: false,
                customClass: "swal-wide",
                confirmButtonText: "Aceptar",
            });
        } else {
            VanillaToasts.create({
                title: "Error",
                text: data.message,
                type: "error",
                timeout: 1200,
                positionClass: "topRight",
            });
        }
        //swal.close();
        //--- --- ---//
    }).fail(function(message) {
        VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
        });
    });
});
$(document).on("change", "#academic_level", function() {
    loading();
    var id_academic_level = $(this).val();
    var id_academic_area = $("#id_academic_area").val();
    var url = window.location.search;
    const urlParams = new URLSearchParams(url);
    if (urlParams.has("submodule")) {
        //--- --- ---//
        const submodule = urlParams.get("submodule");
        window.location.search = "submodule=" + submodule + "&id_academic_area=" + id_academic_area + "&id_academic_level=" + id_academic_level;
        //--- --- ---//
    }
});

function getAcademicLevels(id_academic_area) {
    $.ajax({
        url: "php/controllers/academic_mesh.php",
        method: "POST",
        data: {
            mod: "getAcademicLevelsByAcademicArea",
            id_academic_area: id_academic_area,
        },
    }).done(function(data) {
        // console.log(data);
        Swal.close();
        var data = JSON.parse(data);
        if (data.response) {
            for (var i = 0; i < data.data.length; i++) {
                var options = '<option selected value="" disabled>Elija una opción</option>';
                if (data.response) {
                    for (var i = 0; i < data.data.length; i++) {
                        options += '<option value="' + data.data[i].id_academic_level + '">' + data.data[i].academic_level.toUpperCase() + "</option>";
                    }
                }
            }
            $("#academic_level").html(options);
        } else {
            VanillaToasts.create({
                title: "Error",
                text: data.message,
                type: "error",
                timeout: 1200,
                positionClass: "topRight",
            });
        }
        swal.close();
        //--- --- ---//
    }).fail(function(message) {
        VanillaToasts.create({
            title: "Error",
            text: "Ocurrió un error, intentelo nuevamente",
            type: "error",
            timeout: 1200,
            positionClass: "topRight",
        });
    });
}

function sortTable(n) {
    var table,
        rows,
        switching,
        i,
        x,
        y,
        shouldSwitch,
        dir,
        switchcount = 0;
    table = document.getElementById("tablaAsignaturas");
    switching = true;
    // Set the sorting direction to ascending:
    dir = "asc";
    /* Make a loop that will continue until
      no switching has been done: */
    while (switching) {
        // Start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /* Loop through all table rows (except the
          first, which contains table headers): */
        for (i = 1; i < rows.length - 1; i++) {
            // Start by saying there should be no switching:
            shouldSwitch = false;
            /* Get the two elements you want to compare,
              one from current row and one from the next: */
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];
            /* Check if the two rows should switch place,
              based on the direction, asc or desc: */
            if (dir == "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    // If so, mark as a switch and break the loop:
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            /* If a switch has been marked, make the switch
              and mark that a switch has been done: */
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            // Each time a switch is done, increase this count by 1:
            switchcount++;
        } else {
            /* If no switching has been done AND the direction is "asc",
              set the direction to "desc" and run the while loop again. */
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
$(document).on("change", ".id_acasdemic_level", function() {
    var id_level_combination = $(".id_academic_level option:selected").attr("id");
    console.log(id_level_combination);
    loading();
    $("#div_tabla").empty();
    getPeriodsByAcademicLevel(id_level_combination);
});
//--- --- ---//
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