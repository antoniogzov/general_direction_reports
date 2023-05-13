<!-- Modal -->
<div class="modal fade" id="medicalInfo" tabindex="-1" role="dialog" aria-labelledby="medicalInfoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="medicalInfoLabel"><strong>INFORMACIÓN GENERAL DE:</strong> <?= mb_strtoupper($listStudent->name_student) ?> </h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name" colspan="100%">
                                    <h5 class="modal-title" id="medicalInfoLabel"><strong>INFORMACIÓN MÉDICA DE:</strong> <?= mb_strtoupper($listStudent->name_student) ?> (<?= $edad ?> años | GRUPO: <?= mb_strtoupper($listStudent->group_code) ?>) </h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php if (!empty($getMedicalInfo)) : ?>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>ALTURA: </strong><?= mb_strtoupper($getMedicalInfo->student_height) ?></td>
                                    <td style="font-size: 15px;"> <strong>PESO: </strong><?= mb_strtoupper($getMedicalInfo->student_weight) ?></td>
                                    <td style="font-size: 15px;"> <strong>TIPO DE SANGRE: </strong><?= $getMedicalInfo->blood_type ?> </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"><strong>GATEÓ A LA EDAD DE: </strong> <?= mb_strtoupper($getMedicalInfo->gateo) ?></td>
                                    <td style="font-size: 15px;"><strong>CAMINÓ A LA EDAD DE: </strong> <?= mb_strtoupper($getMedicalInfo->camino) ?></td>
                                    <td style="font-size: 15px;"><strong>ALGÚN PADECIMIENTO IMPORTANTE: </strong> <?php $resultado = $getMedicalInfo->student_padeciment == 'true' ? ('SI') : 'NO';
                                                                                                                    echo $resultado; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿TOMA ALGÚN MEDICAMENTO? </strong><?php $resultado = $getMedicalInfo->student_medicine == 'true' ? ('SI') : 'NO';
                                                                                                            echo $resultado;  ?></td>
                                    <td style="font-size: 15px;"><strong>ALERGIAS: </strong> <?= mb_strtoupper($getMedicalInfo->allergy_something) ?></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>ALTURA: </strong>Sin información</td>
                                    <td style="font-size: 15px;"> <strong>PESO: </strong>Sin información</td>
                                    <td style="font-size: 15px;"> <strong>TIPO DE SANGRE: </strong>Sin información</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"><strong>GATEÓ A LA EDAD DE: </strong>Sin información</td>
                                    <td style="font-size: 15px;"><strong>CAMINÓ A LA EDAD DE: </strong>Sin información</td>
                                    <td style="font-size: 15px;"><strong>ALGÚN PADECIMIENTO IMPORTANTE: </strong>Sin información</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿TOMA ALGÚN MEDICAMENTO? </strong>Sin información</td>
                                    <td style="font-size: 15px;"><strong>ALERGIAS: </strong>Sin información</td>
                                </tr>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name" colspan="100%">
                                    <h5 class="modal-title" id="medicalInfoLabel"><strong>INFORMACIÓN MÉDICA ADICIONAL</strong></h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php if (!empty($getMedicalInfo)) : ?>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿TIENE ESQUEMA DE VACUNACIÓN COMPLETO? </strong> <?php $resultado = $getMedicalInfo->student_vacuns == 'true' ? ('SI') : 'NO';
                                                                                                                            echo $resultado; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿HA SUFRIDO ALGÚN ACCIDENTE O CAÍDAS SERIAS? </strong><?php $resultado = $getMedicalInfo->student_fall == 'true' ? ('SI') : 'NO';
                                                                                                                                echo $resultado; ?></td>
                                    <td style="font-size: 15px;"> <strong>¿HA ESTADO HOSPITALIZADO O A TENIDO ALGUNA CIRUGÍA? </strong><?= mb_strtoupper($getMedicalInfo->student_hospitalice) ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>ALGUNA ENFERMEDAD SEVERA? </strong><?php $resultado = $getMedicalInfo->student_sick == 'true' ? ('SI') : 'NO';
                                                                                                                echo $resultado; ?></td>
                                    <td style="font-size: 15px;"> <strong>¿ALGUNA CONDICIÓN QUE DESEE INFORMAR? </strong><?php $resultado = $getMedicalInfo->student_condition == 'true' ? ('SI') : 'NO';
                                                                                                                            echo $resultado; ?></td>

                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿PADECE ENFERMEDADES DEL CORAZÓN?</strong><?php $resultado = $getMedicalInfo->student_heart == 'true' ? ('SI') : 'NO';
                                                                                                                    echo $resultado; ?></td>
                                    <td style="font-size: 15px;"> <strong>¿PADECE ASMA/BRONQUITIS/RINITIS? </strong><?php $resultado = $getMedicalInfo->student_respiratory_disease == 'true' ? ('SI') : 'NO';
                                                                                                                    echo $resultado; ?></td>

                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿ESTÁ EN TRATAMIENTO? </strong><?php $resultado = $getMedicalInfo->student_tratamient == 'true' ? ('SI') : 'NO';
                                                                                                            echo $resultado; ?></td>
                                    <td style="font-size: 15px;"> <strong>¿ALGÚN IMPEDIMENTO PARA REALIZAR DEPORTE? </strong><?php $resultado = $getMedicalInfo->student_sport == 'true' ? ('SI') : 'NO';
                                                                                                                                echo $resultado; ?></td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿ES ATENDIDO POR ALGÚN PEDIATRA? </strong> <?php $resultado = $getMedicalInfo->trusted_doctor_name == 'true' ? ('SI') : 'NO';
                                                                                                                        echo $resultado; ?></td>
                                    <td style="font-size: 15px;"> <strong>¿PADECE PROBLEMAS DE AGUDEZA VISUAL? </strong> <?php $resultado = $getMedicalInfo->student_vision_problems == 'true' ? ('SI') : 'NO';
                                                                                                                            echo $resultado; ?></td>
                                </tr>
                            <?php else : ?>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿TIENE ESQUEMA DE VACUNACIÓN COMPLETO? </strong>Sin información</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿HA SUFRIDO ALGÚN ACCIDENTE O CAÍDAS SERIAS? </strong>Sin información</td>
                                    <td style="font-size: 15px;"> <strong>¿HA ESTADO HOSPITALIZADO O A TENIDO ALGUNA CIRUGÍA? </strong>Sin información</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>ALGUNA ENFERMEDAD SEVERA? </strong>Sin información</td>
                                    <td style="font-size: 15px;"> <strong>¿ALGUNA CONDICIÓN QUE DESEE INFORMAR? </strong>Sin información</td>

                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿PADECE ENFERMEDADES DEL CORAZÓN?</strong>Sin información</td>
                                    <td style="font-size: 15px;"> <strong>¿PADECE ASMA/BRONQUITIS/RINITIS? </strong>Sin información</td>

                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿ESTÁ EN TRATAMIENTO? </strong>Sin información</td>
                                    <td style="font-size: 15px;"> <strong>¿ALGÚN IMPEDIMENTO PARA REALIZAR DEPORTE? </strong>Sin información</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>¿ES ATENDIDO POR ALGÚN PEDIATRA? </strong>Sin información</td>
                                    <td style="font-size: 15px;"> <strong>¿PADECE PROBLEMAS DE AGUDEZA VISUAL? </strong>Sin información</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <br>

                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name" colspan="100%">
                                    <h5 class="modal-title" id="medicalInfoLabel"><strong>INFORMACIÓN DEL PEDIATRA</strong></h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php if (!empty($getMedicalInfo)) : ?>
                                <tr>
                                    <td style="font-size: 15px;"> <strong>NOMBRE DEL PEDIATRA: </strong><?= mb_strtoupper($getMedicalInfo->trusted_doctor_name) ?></td>
                                    <td style="font-size: 15px;"> <strong>TELÉFONO: </strong><?= mb_strtoupper($getMedicalInfo->cell_phone_trusted_doctor) ?></td>
                                    <td style="font-size: 15px;"> <strong>DIRECCIÓN DEL CONSULTORIO: </strong> </td>
                                </tr> <?php else : ?>
                                <td style="font-size: 15px;"> <strong>NOMBRE DEL PEDIATRA: </strong>Sin información</td>
                                <td style="font-size: 15px;"> <strong>TELÉFONO: </strong>Sin información</td>
                                <td style="font-size: 15px;"> <strong>DIRECCIÓN DEL CONSULTORIO: </strong>Sin información</td>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <br>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>