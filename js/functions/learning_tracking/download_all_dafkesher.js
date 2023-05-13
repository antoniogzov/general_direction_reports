//--- --- ---//
function getDataDafKesherAllStudentsDownload() {
    loading();
    const data = new FormData();
    data.append('func', 'getAllDataStudentsDafKesher');
    data.append('initialDate', initialDateS);
    data.append('finalDate', finalDateS);
    fetch('php/controllers/learning_tracking.php', {
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
            //--- --- ---//
            var logo_url = "images/logo_dafkesher.jpg";
            getImgFromUrl(logo_url, function(img) {
                creteDafKesherAllStudents(img, data);
            });
            //--- --- ---//
        } else {
            Swal.fire('Atención!', 'No se encontraron comentarios para generar informes', 'info');
        }
    })
    /*.catch(function(err) {
                Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
                console.log(err);
            })*/
    ;
}
//--- --- ---//
function getDataDafKesherAllStudentsSend() {
    Swal.fire({
    title: 'Atención!',
    text: 'Está a punto de envíar el Dafkesher de todos los alumnos, ¿Desea continuar? (Esto puede demorar algunos minutos)',
    icon: 'info',
    showCancelButton: true,
    confirmButtonText: 'Sí',
    }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            const data = new FormData();
            data.append('func', 'getAllDataStudentsDafKesher');
            data.append('initialDate', initialDateS);
            data.append('finalDate', finalDateS);
            fetch('php/controllers/learning_tracking.php', {
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
                    //--- --- ---//
                    var logo_url = "images/logo_dafkesher.jpg";
                    getImgFromUrl(logo_url, function(img) {
                        loading();
                        creteDafKesherAllStudentsToSend(img, data);
                    });
                    //--- --- ---//
                } else {
                    Swal.fire('Atención!', 'No se encontraron comentarios para generar informes', 'info');
                }
            })
            /*.catch(function(err) {
                        Swal.fire('Atención!', 'Ocurrió un error al intentar guardar su comentario, intento nuevamente porfavor', 'info');
                        console.log(err);
                    })*/
            ;
        }
    });
}
//--- --- ---//
async function creteDafKesherAllStudents(imgLogo, data) {
    var zip = new JSZip();
    for (var i = 0; i < data.length; i++) {
        //--- --- ---//
        var body_table = [];
        for (var e = 0; e < data[i].comments.length; e++) {
            body_table.push([fixLetter(data[i].comments[e].name_subject) + '\n' + data[i].comments[e].nombre_hebreo, {
                content: fixLetter(data[i].comments[e].comments),
                rowSpan: 2,
                styles: {
                    halign: 'left'
                }
            }]);
            body_table.push([{
                content: "ID ASSG: " + data[i].comments[e].id_assignment,
                styles: {
                    halign: 'left',
                    fontSize: 5
                }
            }]);
        }
        //--- --- ---//
        window.jsPDF = window.jspdf.jsPDF;
        var doc = new jsPDF({
            orientation: 'p',
            format: 'letter',
        });
        //--- --- ---//
        const pdfWidth = doc.internal.pageSize.getWidth() - 19;
        doc.addImage(imgLogo, "jpg", 11, 5, pdfWidth, 30);
        //--- --- ---//
        doc.setFontSize(11);
        doc.text(15, 52, "Alumno: " + data[i].student_name.toUpperCase());
        doc.text(15, 59, "Grado: " + data[i].degree + " Yeshivá Ketaná");
        doc.text(15, 66, "Fecha: " + getDateString(initialDateS, finalDateS));
        //--- --- ---//
        doc.addFileToVFS('js/vendor/jsPDF/ShlomoSemiStam-normal.ttf', getFontShlomoSemiStam());
        doc.addFont('js/vendor/jsPDF/ShlomoSemiStam-normal.ttf', 'ShlomoSemiStam-normal', 'normal');
        //--- --- ---//
        doc.autoTable({
            startY: 71,
            headStyles: {
                fillColor: [216, 69, 40],
                textColor: [255, 255, 255],
                lineWidth: 0.1,
                lineColor: [0, 0, 0],
                fontSize: 11,
                fontStyle: 'bold',
                valign: "middle",
            },
            bodyStyles: {
                font: "ShlomoSemiStam-normal",
                align: 'right',
                fontSize: 10,
                lineWidth: 0.1,
                lineColor: [0, 0, 0],
            },
            head: [
                [{
                    content: "Materia",
                    styles: {
                        halign: "center",
                    },
                }, {
                    content: "Detalle de lo estudiado",
                    styles: {
                        halign: "center",
                    },
                }, ]
            ],
            body: body_table,
        });
        //--- --- ---//
        //--- --- ---//
        doc.setFontSize(9);
        doc.text(15, 240, 'COMENTARIOS:');
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.05);
        doc.line(42, 241, 195, 241);
        doc.line(15, 249, 195, 249);
        doc.line(15, 257, 195, 257);
        //--- --- ---//
        doc.setFontSize(8);
        const date = new Date();
        let current_date = date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
        doc.text(16, 273, "Fecha de emisión " + current_date + "  |  " + data[i].student_code);
        //--- --- ---//
        /*var string = doc.output('datauristring');
        var embed = "<embed width='100%' height='100%' src='" + string + "'/>"
        var x = window.open();
        x.document.open();
        x.document.write(embed);
        x.document.close();*/
        zip.file(data[i].student_code + '-' + data[i].student_name + '.pdf', doc.output('blob'));
        await timer(2000);
        //--- --- ---//
    }
    zip.generateAsync({
        type: 'blob'
    }).then(function(content) {
        saveAs(content, 'Informe.zip');
    });
    swal.close();
}
//--- --- ---//
async function creteDafKesherAllStudentsToSend(imgLogo, data) {
    let files_to_send = [];
    for (var i = 0; i < 3; i++) {
        //--- --- ---//
        var body_table = [];
        for (var e = 0; e < data[i].comments.length; e++) {
            body_table.push([fixLetter(data[i].comments[e].name_subject) + '\n' + data[i].comments[e].nombre_hebreo, {
                content: fixLetter(data[i].comments[e].comments),
                rowSpan: 2,
                styles: {
                    halign: 'left'
                }
            }]);
            body_table.push([{
                content: "ID ASSG: " + data[i].comments[e].id_assignment,
                styles: {
                    halign: 'left',
                    fontSize: 5
                }
            }]);
        }
        //--- --- ---//
        window.jsPDF = window.jspdf.jsPDF;
        var doc = new jsPDF({
            orientation: 'p',
            format: 'letter',
        });
        //--- --- ---//
        const pdfWidth = doc.internal.pageSize.getWidth() - 19;
        doc.addImage(imgLogo, "jpg", 11, 5, pdfWidth, 30);
        //--- --- ---//
        doc.setFontSize(11);
        doc.text(15, 52, "Alumno: " + data[i].student_name.toUpperCase());
        doc.text(15, 59, "Grado: " + data[i].degree + " Yeshivá Ketaná");
        doc.text(15, 66, "Fecha: " + getDateString(initialDateS, finalDateS));
        //--- --- ---//
        doc.addFileToVFS('js/vendor/jsPDF/ShlomoSemiStam-normal.ttf', getFontShlomoSemiStam());
        doc.addFont('js/vendor/jsPDF/ShlomoSemiStam-normal.ttf', 'ShlomoSemiStam-normal', 'normal');
        //--- --- ---//
        doc.autoTable({
            startY: 71,
            headStyles: {
                fillColor: [216, 69, 40],
                textColor: [255, 255, 255],
                lineWidth: 0.1,
                lineColor: [0, 0, 0],
                fontSize: 11,
                fontStyle: 'bold',
                valign: "middle",
            },
            bodyStyles: {
                font: "ShlomoSemiStam-normal",
                align: 'right',
                fontSize: 10,
                lineWidth: 0.1,
                lineColor: [0, 0, 0],
            },
            head: [
                [{
                    content: "Materia",
                    styles: {
                        halign: "center",
                    },
                }, {
                    content: "Detalle de lo estudiado",
                    styles: {
                        halign: "center",
                    },
                }, ]
            ],
            body: body_table,
        });
        //--- --- ---//
        //--- --- ---//
        doc.setFontSize(9);
        doc.text(15, 240, 'COMENTARIOS:');
        doc.setDrawColor(0, 0, 0);
        doc.setLineWidth(0.05);
        doc.line(42, 241, 195, 241);
        doc.line(15, 249, 195, 249);
        doc.line(15, 257, 195, 257);
        //--- --- ---//
        doc.setFontSize(8);
        const date = new Date();
        let current_date = date.getDate() + "/" + (date.getMonth() + 1) + "/" + date.getFullYear();
        doc.text(16, 273, "Fecha de emisión " + current_date + "  |  " + data[i].student_code);
        //--- --- ---//
        var string64 = doc.output('datauristring');
        /*var embed = "<embed width='100%' height='100%' src='" + string + "'/>"
        var x = window.open();
        x.document.open();
        x.document.write(embed);
        x.document.close();*/
        let obj_file = {
            id_student: data[i].id_student,
            file64: string64
        };
        files_to_send.push(obj_file);
        await timer(2000);
        //--- --- ---//
    }
    sendPDFMailer(files_to_send);
}
//--- --- ---//
function timer(ms) {
    return new Promise((res) => setTimeout(res, ms));
}