<?php
$no_teacher = $_SESSION['colab'];
//--- --- ---//
?>
<?php if (!isset($_GET['week_range'])) : ?>
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Opciones</h3>
        </div>
        <!-- Card body -->
        <div class="card-body">
            <!-- Form groups used in grid -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label" for="exampleDatepicker">* Seleccione una semana</label>
                        <input class="form-control" id="week_picker_historic" placeholder="Elegir semana..." type="text">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-control-label" for="exampleDatepicker"></label>
                    <div class="form-group">
                        <button type="button" id="btnCheckWeekIncidents" class="btn btn-success" disabled>Consultar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php if (isset($_GET['week_range'])) :
    $txt_week_range = $_GET['week_range'];
    $arr_week_range = explode('-', $txt_week_range);

    $week_range_start = $arr_week_range[0];
    $week_range_end = $arr_week_range[1];

    $arr_week_range_start_date = explode('/', ($week_range_start));
    $arr_week_range_end_date = explode('/', ($week_range_end));

    $day_start = $arr_week_range_start_date[1];
    if (strlen($day_start) == 1) {
        $day_start = '0' . $day_start;
    }
    $day_end = $arr_week_range_end_date[1];
    if (strlen($day_end) == 1) {
        $day_end = '0' . $day_end;
    }

    $month_start = $arr_week_range_start_date[0];
    $month_end = $arr_week_range_end_date[0];

    if (strlen($month_start) == 1) {
        $month_start = '0' . $month_start;
    }
    if (strlen($month_end) == 1) {
        $month_end = '0' . $month_end;
    }
    $year_start = $arr_week_range_start_date[2];
    $year_end = $arr_week_range_end_date[2];

    $week_range_start_date = $year_start . '-' . $month_start . '-' . $day_start;
    $week_range_end_date = $year_end . '-' . $month_end . '-' . $day_end;

    $dias = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
    $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
?>
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Incidencias registradas del <?= $arr_week_range_start_date[1] ?> de <?= $meses[$arr_week_range_start_date[0]] ?> de <?= $arr_week_range_start_date[2] ?> <b>al</b> <?= $arr_week_range_end_date[1] ?> de <?= $meses[$arr_week_range_end_date[0]] ?> de <?= $arr_week_range_end_date[2] ?></h3>

        </div>
        <!-- Card body -->
        <div class="card-body">
            <!-- Form groups used in grid -->
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-control-label" for="exampleDatepicker">* Seleccione otra semana</label>
                        <input class="form-control" id="week_picker_historic" placeholder="Elegir semana..." type="text">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-control-label" for="exampleDatepicker"></label>
                    <div class="form-group">
                        <button type="button" id="btnCheckWeekIncidents" class="btn btn-success" disabled>Consultar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>