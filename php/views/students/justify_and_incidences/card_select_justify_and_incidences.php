<div class="card mb-4">
    <div class="card-header">
        <h3 class="mb-0">Opciones</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-control-label" for="type_report">* Acción a realizar</label>
                    <form>
                        <?php if (isset($_GET['type_report'])) : ?>
                            <select class="form-control" name="type_report" id="type_report">

                                <?php if (($_GET['type_report']) == 1) : ?>
                                    <option value="" disabled>Elija una opción</option>
                                    <option selected value="1">Registrar / justificar faltas</option>
                                    <option value="3">Registrar incidencias</option>
                                <?php else : ?>
                                    <option value="" disabled>Elija una opción</option>
                                    <option value="1">Registrar / justificar faltas</option>
                                    <option selected value="3">Registrar incidencias</option>
                                <?php endif; ?>
                            </select>
                        <?php else : ?>
                            <select class="form-control" name="type_report" id="type_report">
                                <option selected value="" disabled>Elija una opción</option>
                                <option value="1">Registrar / justificar faltas</option>
                                <option value="3">Registrar incidencias</option>
                            </select>
                        <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
            </div>
            <div class="col-md-3">
                <label class="form-control-label" for="type_report">* Buscar registros por día</label>
                <div class="input-group mb-3">
                    <?php if (isset($_GET['type_report'])) : ?>
                        <input type="text" class="form-control date-input" id="search_date" placeholder="Buscar por fecha">
                        <div class="input-group-append">
                            <?php if (($_GET['type_report']) == 1) : ?>
                                <button class="btn btn-outline-info search_absences" id="btn_search" type="button"><i class="fas fa-search"></i></button>
                            <?php elseif (($_GET['type_report']) == 3) : ?>
                                <button class="btn btn-outline-info search_incidents" id="btn_search" type="button"><i class="fas fa-search"></i></button>
                            <?php endif; ?>
                        </div>
                    <?php else : ?>
                        <input type="text" class="form-control date-input" id="search_date" disabled placeholder="Buscar por fecha">
                        <div class="input-group-append">
                            <button class="btn btn-outline-info" id="btn_search" disabled type="button"><i class="fas fa-search"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>