swal.close();
//--- --- ---//
if ($('#tGradesStudents').length > 0) {
    var tf = new TableFilter('tGradesStudents', {
        base_path: '../general/js/vendor/tablefilter/tablefilter/',
        alternate_rows: true,
        rows_counter: true,
        btn_reset: true,
        loader: true,
        status_bar: true,
        responsive: true,
        extensions: [{
            name: 'sort'
        }]
    });
    tf.init();
}
//--- --- ---//