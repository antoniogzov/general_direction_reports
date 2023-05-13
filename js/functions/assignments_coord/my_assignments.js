/*var tf = new TableFilter('tableSubjectsCoordinator', {
    base_path: '../general/js/vendor/tablefilter/tablefilter/',
    col_2: 'select',
    auto_filter: {
        delay: 100
    }
});
tf.init();*/

/*let table = new DataTable('#tableSubjectsCoordinator', {
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL'
        }, 'print'
    ],
    ordering: false,
    paging: false,
    info: false,
});*/

$('#tableSubjectsCoordinator tfoot th').each(function () {
    var title = $(this).text();
    $(this).html('<input type="text" class="form-control" placeholder="Search ' + title + '" />');
});

let table = new DataTable('#tableSubjectsCoordinator', {
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', {
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'LEGAL'
        }, 'print'
    ],
    initComplete: function () {
        // Apply the search
        this.api()
            .columns()
            .every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
    },
    //ordering: false,
    //paging: false,
    info: false,
    "columns": [
        { "width": "8%" },
        { "width": "29%" },
        { "width": "12%" },
        { "width": "12%" },
        { "width": "25%" },
      ]
});

table.buttons().container().appendTo($('.col-sm-6:eq(0)', table.table().container()));
table.columns.adjust().draw();