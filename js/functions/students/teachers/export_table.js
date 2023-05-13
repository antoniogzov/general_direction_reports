var txt_group = $('#id_group option:selected').text();
$('#tStudents').DataTable({
    colReorder: false,
    "pageLength": 50,
    dom: 'Bfrtip',
    lengthMenu: [
        [40, 25, 50, -1],
        ['10 rows', '25 rows', '50 rows', 'Show all']
    ],
    buttons: [{
            extend: 'excel',
            text: 'Excel',
            className: 'exportExcel',
            exportOptions: {
                modifier: {
                    page: 'all'
                }
            }
        },
        {
            extend: 'csv',
            text: 'CSV',
            className: 'exportExcel',
            exportOptions: {
                modifier: {
                    page: 'all'
                }
            }
        },
        {
            extend: 'pdfHtml5',
            text: 'PDF',
            className: 'exportExcel',
            orientation: 'landscape',
            pageSize: 'LEGAL',
            exportOptions: {
                modifier: {
                    page: 'all'
                }
            }
        }
    ],
    "bSort" : false,
});