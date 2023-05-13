let slcts = document.querySelectorAll('.slct-enroll');
//slct_enroll.addEventListener('change', enrollGroup);
slcts.forEach(slct_enroll => {
    slct_enroll.addEventListener('change', enrollGroup);
});
//--- --- ---//
function enrollGroup(e) {
    let slct = e.target;
    let id_group = slct.value;
    let id_student = slct.closest('td').closest('tr').getAttribute('id');
    let text = slct.options[slct.selectedIndex].text;
    Swal.fire({
        title: 'Atención!',
        text: `El alumno seleccionado será asignado al grupo ${text}, desea continuar?`,
        confirmButtonText: 'Sí',
        icon: 'question',
        showCancelButton: true
    }).then((result) => {
        if (result.isConfirmed) {
            enrollDB(id_student, id_group, slct);
        } else {
            e.target.value = 0;
        }
    })
}
//--- --- ---//
function enrollDB(id_student, id_group, slct) {
    const data = new FormData();
    data.append('mod', 'updateGroupStudent');
    data.append('id_student', id_student);
    data.append('id_group', id_group);
    fetch('php/controllers/students.php', {
        method: 'POST',
        body: data
    }).then(function(response) {
        if (response.ok) {
            return response.json()
        } else {
            console.log(response);
            Swal.fire('Error', 'Ocurrió un error al intentar conectarse a la base de datos :[', 'error');
            throw new "Error en la llamada Ajax";
        }
    }).then(function(data) {
        if (data.response) {
            slct.closest('td').closest('tr').style.backgroundColor = '#AAE792';
            Swal.fire('Listo!', data.message, 'success');
        }
    }).catch(function(err) {
        Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
        console.log(err);
    });
}
//--- --- ---//