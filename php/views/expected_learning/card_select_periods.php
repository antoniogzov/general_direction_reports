<div class="card mb-4">
<!-- Card header -->
    <div class="card-header">
      <h3 class="mb-0">Periodos</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
      <!-- Form groups used in grid -->
      <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-control-label" for="id_period_calendar">* Elija un periodo</label>
                <form method="GET" action="" id="formPeriod">
                    <input type="hidden" name="id_assignment" value="<?=$id_assignment?>">
                    <select class="form-control" name="id_period_calendar" id="id_period_calendar">
                        <option selected value="">Elija una opción</option>
                        <?php foreach ($periods as $period): ?>
                            <?php if ($id_period_calendar == $period->id_period_calendar): ?>
                                <option selected value="<?=$period->id_period_calendar?>"><?=$period->no_period?></option>
                            <?php else: ?>
                                <option value="<?=$period->id_period_calendar?>"><?=$period->no_period?></option>
                            <?php endif;?>
                        <?php endforeach;?>
                    </select>
                </form>
            </div>
        </div>
      </div>
    </div>
</div>