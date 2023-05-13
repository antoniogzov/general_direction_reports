//loading();
$(document).ready(function() {
    $("#slct-assg").select2({
        dropdownParent: $("#new-event")
    });
});
//--- --- ---//
//--- --- ---//
var calendar;
getAssg();
//--- --- ---//
//--- --- ---//
var btnAddEvntCal = document.querySelector('.new-event--add');
btnAddEvntCal.addEventListener('click', function(event) {
    event.preventDefault();
    addEventCalendar(event);
});
//--- --- ---//
var btnUpdateEvntCal = document.querySelector('.update-event');
btnUpdateEvntCal.addEventListener('click', function(event) {
    event.preventDefault();
    updateEventCalendar(event, 0);
});
//--- --- ---//
var btnDeleteEvntCal = document.querySelector('.delete-event');
btnDeleteEvntCal.addEventListener('click', function(event) {
    event.preventDefault();
    updateEventCalendar(event, 1);
});
//--- --- ---//
let txtAreas = document.querySelectorAll('textarea');
for (i of txtAreas) {
    i.addEventListener('keyup', function(event) {
        var text = event.target.value;
        event.target.value = checkTextLength(text);
        var counter = event.target.closest('.form-group').querySelector('p').innerHTML = 'Palabras: ' + text.length + '/300';
    });
}
//--- --- ---//
var formNewComment = document.querySelector('.new-event--form');
formNewComment.addEventListener("submit", function(event) {
    event.preventDefault();
    var pagebutton = document.querySelector('.new-event--add');
    pagebutton.click();
}, true);
//--- --- ---//
//--- --- ---//
function getAssg() {
    const data = new FormData();
    data.append('func', 'getAllAssgCoordinator');
    fetch('php/controllers/work_diary.php', {
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
        if (data.length > 0) {
            for (var i = 0; i < data.length; i++) {
                document.getElementById("slct-assg").add(new Option(data[i].name_subject + ' / ' + data[i].group_code, data[i].id_assignment));
            }
        }
        getCommnetsDB();
    }).catch(function(err) {
        Swal.fire('Atención!', 'Ocurrió un error al intentar obtener su información, intento nuevamente porfavor', 'info');
        console.log(err);
    });
}
//--- --- ---//
function getCommnetsDB() {
    const data = new FormData();
    data.append('func', 'getAllCommentsRegistered');
    fetch('php/controllers/work_diary.php', {
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
        createCalendar(data);
    }).catch(function(err) {
        Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
        console.log(err);
    });
}
//--- --- ---//
function addEventCalendar(event) {
    var eventTitle = document.querySelector('.new-event--title').value;
    var evidence_attached = document.querySelector('.new-event--evidence-attached').value;
    let id_assignment = document.querySelector('#slct-assg').value;
    console.log(id_assignment);
    //--- --- ---//
    if (eventTitle != '' && id_assignment != '') {
        btnLoading(event);
        let date = document.querySelector('.new-event--start').value;
        addEventCalendarDB(id_assignment, eventTitle, date, evidence_attached);
    } else {
        //--- --- ---//
        Swal.fire('Atención!', 'Elija una asignatura e ingrese un comentario por favor', 'info');
        $('.new-event--title').closest('.form-group').addClass('has-danger');
        $('.new-event--title').focus();
        //--- --- ---//
    }
}
//--- --- ---//
function updateEventCalendar(event, dlte) {
    let eventId = document.querySelector('.edit-event--id').value;
    if (eventId != '') {
        let infoEvent = calendar.getEventById(eventId);
        if (dlte) {
            Swal.fire({
                icon: 'question',
                title: 'Atención!',
                text: 'Se eliminará el comentaro seleccionado, ¿desea continuar?',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                confirmButtonColor: '#F50F4A'
            }).then((result) => {
                if (result.isConfirmed) {
                    btnLoading(event);
                    updateEventCalendarDB('', '', eventId, '', infoEvent, dlte);
                }
            })
        } else {
            let newTitle = document.querySelector('.edit-event--title').value;
            let newEvidence = document.querySelector('.edit-event--evidence-attached').value;
            newTitle = newTitle.trim();
            if (newTitle != '' && eventId != '') {
                let eventDate = infoEvent.startStr;
                btnLoading(event);
                updateEventCalendarDB(newTitle, newEvidence, eventId, eventDate, infoEvent, dlte);
            } else {
                //--- --- ---//
                $('.edit-event--title').closest('.form-group').addClass('has-danger');
                $('.edit-event--title').focus();
                //--- --- ---//
            }
        }
    } else {
        Swal.fire('Atención!', 'Ocurrió un error, intento nuevamente porfavor', 'info');
    }
}
//--- --- ---//
function updateEventCalendarDB(eventTitle, newEvidence, eventId, eventDate, event, dlte) {
    const data = new FormData();
    data.append('func', 'updateCommentPE');
    data.append('eventId', eventId);
    data.append('eventTitle', eventTitle);
    data.append('newEvidence', newEvidence);
    data.append('dlte', dlte);
    fetch('php/controllers/work_diary.php', {
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
            event.remove();
            if (!dlte) {
                calendar.addEvent({
                    id: eventId,
                    title: eventTitle,
                    start: eventDate,
                    allDay: true,
                    //color: 'purple'
                    evidence: newEvidence,
                    name_subject: data.name_subject,
                    teacher_name: data.teacher_name
                });
            }
            //--- --- ---//
            $('.edit-event--form')[0].reset();
            $('.edit-event--title').closest('.form-group').removeClass('has-danger');
            $('#edit-event').modal('hide');
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
        }
    }).catch(function(err) {
        Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
        console.log(err);
    });
}
//--- --- ---//
function addEventCalendarDB(id_assignment, eventTitle, eventDate, evidence_attached) {
    const data = new FormData();
    data.append('func', 'addCommentPE');
    data.append('id_assignment', id_assignment);
    data.append('eventTitle', eventTitle);
    data.append('eventDate', eventDate);
    data.append('evidence_attached', evidence_attached);
    fetch('php/controllers/work_diary.php', {
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
            calendar.addEvent({
                id: data.idEvent,
                title: eventTitle,
                start: eventDate,
                allDay: true,
                color: 'purple',
                name_subject: data.name_subject,
                teacher_name: data.teacher_name,
                evidence: evidence_attached
            });
            //--- --- ---//
            $('.new-event--form')[0].reset();
            $('.new-event--title').closest('.form-group').removeClass('has-danger');
            $('#new-event').modal('hide');
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
        }
    })
    /*.catch(function(err) {
            Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
            console.log(err);
        })*/
    ;
}
//--- --- ---//
function createCalendar(data) {
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        //aspectRatio: 8,
        contentHeight: "auto",
        timeZone: 'local',
        locale: 'es',
        showNonCurrentDates: false,
        hiddenDays: [0, 6],
        navLinks: true, // can click day/week names to navigate views
        selectable: true,
        selectMirror: true,
        businessHours: true, // display business hours
        dayMaxEvents: true, // allow "more" link when too many events
        eventColor: '#F4819E',
        events: data,
        eventDidMount: function(info) {
            console.log('Hereeeee 1');
            var $elem = $(info.el);
            $elem.attr('title', info.event.extendedProps.name_subject + ' | ' + info.event.extendedProps.teacher_name) // set title attribute
                .data('toggle', 'tooltip') // add data-toggle = tooltip
                .tooltip();
        },
        select: function(arg) {
            console.log(arg);
            var isoDate = moment(arg).toISOString();
            document.querySelector('.word-counter-new').innerHTML = 'Palabras: 0/300';
            $('#new-event').modal('show');
            $('.new-event--title').val('');
            $('.new-event--start').val(arg.startStr);
            //$('.new-event--end').val(isoDate);
            /*if (title) {
                calendar.addEvent({
                    title: title,
                    start: arg.start,
                    end: arg.end,
                    allDay: arg.allDay
                })
            }*/
        },
        eventClick: function(arg) {
            console.log('Hereeeee 2');
            console.log(arg);
            $('#edit-event').modal('show');
            $('.edit-event--id').val(arg.event.id);
            $('.edit-event--title').val(arg.event.title);
            document.querySelector('.assignment-name-edit').value = '';
            document.querySelector('.assignment-name-edit').value = arg.event.extendedProps.name_subject;
            document.querySelector('.word-counter-edit').innerHTML = 'Palabras: ' + arg.event.title.length + '/300';
            $('.edit-event--evidence-attached').val(arg.event.extendedProps.evidence);
        },
    });
    calendar.render();
    swal.close();
    /*window.setTimeout(function() {
        calendar.render();
    }, 1000);*/
}
//--- --- ---//
function checkTextLength(text) {
    var str = text;
    if (text.length > 299) {
        str = text.slice(0, 299);
    }
    return str;
}
//--- --- ---//
function loading() {
    Swal.fire({
        text: 'Procesando petición',
        html: '<img src="images/loading_iteach.gif" width="300" height="300">',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCloseButton: false,
        showCancelButton: false,
        showConfirmButton: false,
    })
}
//--- --- ---//
function btnLoading(event) {
    event.target.classList.add('spin');
    event.target.disabled = true;
    window.setTimeout(function() {
        // when asyncronous action is done, remove the spinner
        // re-enable button/fieldset
        event.target.classList.remove('spin');
        event.target.disabled = false;
    }, 4000);
}