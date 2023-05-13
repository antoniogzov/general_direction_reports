function downloadReportPrimaryBangueoloHebrew(data, installment, group_code) {
  //console.log(data);
  var table1 = "";
  var table = "";
  //--- --- ---//
  window.jsPDF = window.jspdf.jsPDF;
  var doc = new jsPDF({
    orientation: "p",
    format: "letter",
  });

  doc.addFileToVFS("js/vendor/jsPDF/fonts/VarelaRound-Regular.ttf");
  doc.addFont(
    "js/vendor/jsPDF/fonts/VarelaRound-Regular.ttf",
    "VarelaRound-Regular",
    "normal"
  );
  //--- --- ---//
  doc.setFontSize(11);
  doc.setFont("VarelaRound-Regular"); // set font
  //--- --- ---//

  doc.autoTable({
    theme: "plain",
    startY: 5,
    tableWidth: 190,
    margin: {
      left: 12,
    },
    headStyles: {
      halign: "left",
      valign: "middle",
      font: "VarelaRound-Regular",
      fillColor: [255, 255, 255],
      textColor: [0, 0, 0],
      fontSize: 12,
    },
    head: [
      [
        {
          content:
            "COMENTARIOS FINALES | GRUPO: " +
            group_code +
            " | ENTREGA: " +
            installment,
          styles: { halign: "center" },
        },
      ],
    ],
  });
  doc.addImage(getLogoBangueoloFemalesYKTBase64(), "png", 8, 5, 20, 20);
  //--- --- ---//
  //--- MDA GENERAL ---//
  var positionYfinal = 30;
  if (data.final_comments[0].comments.length > 0) {
    var header = [
      [
        {
          content: "N° de Alumno",
          styles: {
            halign: "center",
          },
        },
        {
          content: "CÓDIGO ALUMNO",
          styles: {
            halign: "center",
          },
        },
        {
          content: "NOMBRE",
          styles: {
            halign: "center",
          },
        },
        {
          content: "COMENTARIO",
          styles: {
            halign: "center",
          },
        },
      ],
    ];

    //--- LISTA PREGUNTAS ---//
    var body_mda = [];

    for (var i = 0; i < data.final_comments[0].comments.length; i++) {
      //--- --- --- ---//

      //--- --- --- ---//
      var colorvar = [238, 238, 238];
      if (i == 0 || i % 2 == 0) {
        colorvar = [255, 255, 255];
      }
      var mda = [
        {
          content: i + 1,
          styles: {
            halign: "center",
            fontSize: 12,
            fillColor:colorvar,
          },
        },
        {
          content: data.students[i].student_code,
          styles: {
            halign: "center",
            fontSize: 11,
            fillColor:colorvar,
          },
        },
        {
          content: data.students[i].student_name,
          styles: {
            halign: "center",
            fontSize: 10,
            fillColor:colorvar,
          },
        },
        {
          content: data.final_comments[0].comments[i].comments1.trim(),
          styles: {
            halign: "center",
            fontSize: 12,
            fillColor:colorvar,
          },
        },
      ];
      //--- --- ---//
      body_mda.push(mda);
      //--- --- ---//
      //--- --- --- ---//

      //--- --- ---//
    }
    doc.autoTable({
      startY: positionYfinal,
      margin: {
        left: 22,
      },

      columnStyles: {
        0: { cellWidth: 15 },
        1: { cellWidth: 20 },
        2: { cellWidth: 50 },
        3: { cellWidth: 90 },
        // etc
      },
      headStyles: {
        fillColor: [241, 211, 245],
        textColor: [0, 0, 0],
        lineWidth: 0.1,
        lineColor: [0, 0, 0],
        fontSize: 9,
        halign: "center",
        valign: "middle",
      },
      bodyStyles: {
        fontSize: 7,
        halign: "center",
        lineWidth: 0.1,
        lineColor: [0, 0, 0],
        halign: "center",
        valign: "middle",
      },
      head: header,
      body: body_mda,
      theme: "grid",
    });
    //--- --- ---//
    positionYfinal = doc.lastAutoTable.finalY + 15;
    //--- --- ---//
  }
  //--
  //--- --- ---//
  doc.setFontSize(7);
  let date = new Date();
  let output =
    String(date.getDate()).padStart(2, "0") +
    "/" +
    String(date.getMonth() + 1).padStart(2, "0") +
    "/" +
    date.getFullYear();

  //--- --- ---//
  doc.save("COMENTARIOS FINALES | ENTREGA: " + installment + ".pdf");
  Swal.close();
  /* var string = doc.output("datauristring");
    var embed = "<embed width='100%' height='100%' src='" + string + "'/>";
    var x = window.open();
    x.document.open();
    x.document.write(embed);
    x.document.close();
     */ //--- --- ---//
}
