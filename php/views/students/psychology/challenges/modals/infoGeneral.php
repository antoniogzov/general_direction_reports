<!-- Modal -->
<div class="modal fade" id="infoGeneral" tabindex="-1" role="dialog" aria-labelledby="infoGeneralLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="infoGeneralLabel"><strong>INFORMACIÓN GENERAL DE:</strong> <?= mb_strtoupper($listStudent->name_student) ?> </h5> -->
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
                                    <h5 class="modal-title" id="infoGeneralLabel"><strong>INFORMACIÓN GENERAL DE:</strong> <?= mb_strtoupper($listStudent->name_student) ?> </h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <tr>
                                <td style="font-size: 15px;"> <strong>EDAD: </strong><?= $edad ?> AÑOS</td>
                                <td style="font-size: 15px;"> <strong>FECHA DE NACIMIENTO: </strong><?= $fecha_formato ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;"> <strong>CORREO DE CONTACTO: </strong><?= mb_strtolower($listStudent->mail) ?></td>
                                <td style="font-size: 15px;"> <strong>TELÉFONO DE CONTACTO: </strong><?= $listStudent->cell_phone ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;" colspan="2"><strong>NOMBRE DEL PADRE: </strong> <?= mb_strtoupper($listStudent->father_name) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;"> <strong>CORREO DEL PADRE: </strong><?= mb_strtolower($listStudent->father_mail) ?></td>
                                <td style="font-size: 15px;"> <strong>TELÉFONO DEL PADRE: </strong><?= $listStudent->father_cell_phone ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;" colspan="2"><strong>NOMBRE DE LA MADRE: </strong> <?= mb_strtoupper($listStudent->mother_name) ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;"> <strong>CORREO DE LA MADRE: </strong><?= mb_strtolower($listStudent->mother_mail) ?></td>
                                <td style="font-size: 15px;"> <strong>TELÉFONO DE LA MADRE: </strong><?= $listStudent->mother_cell_phone ?></td>
                            </tr>
                            <tr>
                                <td style="font-size: 15px;"> <strong>DIRECCIÓN: </strong><?= mb_strtoupper($listStudent->direction) ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name" colspan="100%">
                                    <h5 class="modal-title" id="infoGeneralLabel"><strong>INFORMACIÓN ADICIONAL</h5>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="list">
                            <?php
                            $getAditionalInfo = $psychopedagogy->getAditionalInfo($listStudent->id_student);

                            $emergency_phone = '-';
                            $emergency_name_contact = '-';
                            $sec_relationship = '-';
                            if (!empty($getAditionalInfo)) {
                                foreach ($getAditionalInfo as $aditionalInfo) :
                                    $emergency_phone = $aditionalInfo->emergency_phone;
                                    $emergency_name_contact = $aditionalInfo->emergency_name_contact;
                                    $sec_relationship = $aditionalInfo->sec_relationship;
                            ?>
                                    <tr>
                                        <td style="font-size: 15px;"> <strong>TELÉFONO DE EMERGENCIA: </strong><br><?= $emergency_phone ?></td>
                                        <td style="font-size: 15px;"> <strong>NOMBRE DE CONTACTO: </strong><br><?= mb_strtoupper($emergency_name_contact) ?></td>
                                        <td style="font-size: 15px;"> <strong>PARENTESCO: </strong><br><?= mb_strtoupper($sec_relationship) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="font-size: 15px;"><strong>DIRECCIÓN: </strong><br><?= mb_strtoupper($aditionalInfo->emergency_address) ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <hr style=" border-top: 1px dashed;">
                                        </td>
                                    </tr>
                            <?php endforeach;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" data-sort="name" colspan="100%">
                                    <h5 class="modal-title" id="infoGeneralLabel"><strong>GRUPOS</h5>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php foreach ($StudentGroups as $groups) : ?>
                                <tr>
                                    <td colspan="100%" style="font-size: 15px;"> <strong>GRUPO: </strong><?= $groups->group_code ?></td>
                                </tr>
                            <?php endforeach; ?>
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