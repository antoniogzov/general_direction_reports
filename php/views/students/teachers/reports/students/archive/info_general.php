<div class="container">
    <div class="text-center">
        <?php
        $ruta_avatar = "../control_escolar/students_archives/".$StudentInfo[0]->student_code.".jpg";
        if (file_exists ($ruta_avatar)) : ?>
        
            <img src="<?=$ruta_avatar?>" class="rounded" height="180" width="auto">
        <?php else : ?>
            <img src="images/user.png" class="rounded" height="180" width="auto">
        <?php endif; ?>
    </div>
    <h2>Código y nombre de Alumno:</h2>
    <?php foreach ($StudentInfo as $student_info) :
        $fch = explode("-", $student_info->birthdate);
        $tfecha = $fch[2] . "-" . $fch[1] . "-" . $fch[0];


        $dias = explode("-", $tfecha, 3);
        $dias = mktime(0, 0, 0, $dias[1], $dias[0], $dias[2]);
        $edad = (int)((time() - $dias) / 31556926);

    ?>
        <h4><?= ucfirst($student_info->student_code) ?> | <?= ucfirst($student_info->name_student) ?></h2>
        <?php endforeach ?>
        <hr>
        <hr>
        <br>
        <div class="row">
            <div class="col">
                <h2><i class="fas fa-birthday-cake"></i> Edad:</h2>
                <h4> <?= $edad ?> años</h2>
                    <h2><i class="fas fa-envelope-open-text"></i> Correo prinicipal de contacto:</h2>
                    <h4> <?= $student_info->mail ?> </h2>

                        <h2><i class="fas fa-phone-square"></i> Teléfono prinicipal de contacto:</h2>
                        <h4><?= $student_info->cell_phone ?> </h2>

            </div>
            <div class="col">
                <h2>Nombre del padre:</h2>
                <h4><?= $student_info->father_name ?> </h2>

                    <h2><i class="far fa-envelope"></i> Correo del padre:</h2>
                    <h4><?= $student_info->father_mail ?> </h2>

                        <h2><i class="fas fa-phone"></i> Teléfono del padre:</h2>
                        <h4><?= $student_info->father_cell_phone ?> </h2>


            </div>

            <div class="col">
                <h2>Nombre de la madre:</h2>
                <h4><?= $student_info->mother_name ?> </h2>

                    <h2><i class="far fa-envelope"></i> Correo de la madre:</h2>
                    <h4><?= $student_info->mother_mail ?> </h2>

                        <h2><i class="fas fa-phone"></i> Teléfono de la madre:</h2>
                        <h4><?= $student_info->mother_cell_phone ?> </h2>
            </div>
        </div>
        <br>
        <h2><i class="fas fa-house-user"></i> Dirección</h2>
        <?php foreach ($StudentInfo as $student_info) : ?>
            <h4><?= $student_info->direction ?></h2>
            <?php endforeach ?>
            <br>
</div>