<?php
$catalogue_exist = $expected_learnings->getExistCatalogCoordinator($no_teacher, $_GET['id_academic_level'], $id_subject);
?>
<div class="card card-table-evaluations">
    <?php if (!empty($catalogue_exist)) : ?>
        <div class="card-body">
            <?php foreach ($catalogue_exist as $catalogue) : ?>
                <h1><?= mb_strtoupper($catalogue->index_description) ?> |<span class="badge badge-secondary"><?= mb_strtoupper($catalogue->nombre_colaborador) ?></span></h1>
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="tStudents">
                        <thead>
                            <tr class="sticky-header">
                                <?php
                                $catalogueSubinex = $expected_learnings->getSubindexCatalog($catalogue->id_expected_learning_index);
                                foreach ($catalogueSubinex as $subindex) :
                                    $get_period_average = $expected_learnings->getPeriodAverage($subindex->id_expected_learning_subindex);
                                    $span = '';
                                    if (!empty($get_period_average)) {
                                        $period_average = $get_period_average[0]->period_average;
                                        $period_average = round($period_average, 1);
                                        if ($period_average > 0) {
                                            $span = '<span class="badge badge-secondary">' . $period_average . '</span>';
                                        }
                                    }
                                ?>

                                <?php endforeach; ?>
                                <th class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">PERIODO</th>
                                <th colspan="100%" class="sticky-cell text-center p-1" style="color:#fff !important; background-color: #191d4d !important; font-size: x-small !important;">CRITERIOS</th>
                            </tr>
                        </thead>
                        <tbody class="list">
                            <?php
                            foreach ($catalogueSubinex as $subindex) : ?>
                                <tr>
                                    <td>
                                        <h1>P. <?= $subindex->no_period ?> <?= $span ?></h1>
                                    </td>
                                    <?php
                                    $catalogueSubinexCatalogItems = $expected_learnings->getSubindexCatalogItems($subindex->id_expected_learning_subindex);
                                    foreach ($catalogueSubinexCatalogItems as $items) : ?>
                                        
                                        <?php
                                        $getExpectedLearningArchive = $expected_learnings->getExpectedLearningArchive($items->id_expected_learning_catalog);
                                        $url_archive = "";
                                        $tooltip_href = "No se encontró evidencia";
                                        $color_style = '';
                                        if (!empty($getExpectedLearningArchive)) {
                                            $color_style = 'style="color: #7eacff !important;"';
                                            $tooltip_href = "Ver evidencia";
                                            $url_archive = 'href="' . $getExpectedLearningArchive[0]->url_archive . '"  target="_blank"';
                                            if ($getExpectedLearningArchive[0]->link_type == 1) {
                                                $url_archive = 'href="' . $getExpectedLearningArchive[0]->url_archive . '"  target="_blank"';
                                            } else {
                                                $url_archive = 'href="' .  '../iTeach' . $getExpectedLearningArchive[0]->url_archive . '" target="_blank"';
                                            }
                                        } ?>

                                        <?php $item_average = $expected_learnings->getCatalogAverage($items->id_expected_learning_catalog);
                                        $average = 0;
                                        if (!empty($item_average)) {
                                            $average = $item_average[0]->learning_average;
                                            $average = round($average, 1);
                                        } else {
                                            $average = "-";
                                        } ?>

                                        <td class="sticky-cell text-center p-1 td-hd-600" <?= $color_style ?>><a <?= $url_archive ?> > <?= $items->short_description ?></a>  <br>
                                            <h3><?= $average ?></h3>
                                        </td>

                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <br>
                <br>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="card-body">
            <div class="table-responsive">
                <h1 class="">No hay aprendizajes esperados para este nivel académico</h1>
            </div>
        </div>
    <?php endif; ?>
</div>
<div class="card card-evaluations-gathering">
    <style>
        :root {
            --level-1: #8dccad;
            --level-2: #f5cc7f;
            --level-3: #7b9fe0;
            --level-4: #f27c8d;
            --black: black;
        }
.footer{
    background-color: rgba(0, 0, 0, 0);
}
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        ol {
            list-style: none;
        }

       

        .container {
            max-width: 1000px;
            padding: 0 10px;
            margin: 0 auto;
        }

        .rectangle {
            position: relative;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .level-1 {
            width: 50%;
            margin: 0 auto 40px;
            background: var(--level-1);
        }

        .level-1::before {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: var(--black);
        }



        .level-2-wrapper::before {
            content: "";
            position: absolute;
            top: -20px;
            left: 0%;
            width: 100%;
            height: 2px;
            background: var(--black);
        }

        .level-2-wrapper::after {
            display: none;
            content: "";
            position: absolute;
            left: -20px;
            bottom: -20px;
            width: calc(100% + 20px);
            height: 2px;
            background: var(--black);
        }

        .level-2-wrapper li {
            position: relative;
        }

        .level-2-wrapper>li::before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: var(--black);
        }

        .level-2 {
            width: 70%;
            margin: 0 auto 40px;
            background: var(--level-2);
        }

        .level-2::before {
            content: "";
            position: absolute;
            top: 100%;
            left: 1%;
            transform: translateX(-50%);
            width: 2px;
            height: 20px;
            background: var(--black);
        }

        .level-2::after {
            display: none;
            content: "";
            position: absolute;
            top: 50%;
            left: 0%;
            transform: translate(-100%, -50%);
            width: 20px;
            height: 2px;
            background: var(--black);
        }

        .level-3-wrapper {
            position: relative;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            grid-column-gap: 20px;
            width: 90%;
            margin: 0 auto;
        }

        .level-3-wrapper::before {
            content: "";
            position: absolute;
            top: -20px;
            left: calc(25% - 5px);
            width: calc(50% + 10px);
            height: 2px;
            background: var(--black);
        }

        .level-3-wrapper>li::before {
            content: "";
            position: absolute;
            top: 0;
            left: 50%;
            transform: translate(-50%, -100%);
            width: 2px;
            height: 20px;
            background: var(--black);
        }

        .level-3 {
            margin-bottom: 20px;
            background: var(--level-3);
        }

        .level-4-wrapper {
            position: relative;
            width: 80%;
            margin-left: auto;
        }



        .level-4-wrapper li+li {
            margin-top: 20px;
        }

        .level-4 {
            font-weight: normal;
            background: var(--level-4);
        }

        @media screen and (max-width: 700px) {
            .rectangle {
                padding: 20px 10px;
            }

            .level-1,
            .level-2 {
                width: 70%;
            }

            .level-1 {
                margin-bottom: 20px;
            }

            .level-1::before,
            .level-2-wrapper>li::before {
                display: none;
            }

            .level-2-wrapper,
            .level-2-wrapper::after,
            .level-2::after {
                display: block;
            }

            .level-2-wrapper {
                position: relative;
                display: grid;
                grid-template-columns: repeat(1, 1fr);
            }

            .level-4-wrapper::before {
                content: "";
                position: absolute;
                top: -20px;
                left: -15px;
                width: 2px;
                height: calc(100% + 20px);
                background: var(--black);
            }

            .level-1::before {
                left: -20px;
                width: 2px;
                height: 100%;
            }

            .level-4::before {
                content: "";
                position: absolute;
                top: 50%;
                left: 0%;
                transform: translate(-100%, -50%);
                width: 13px;
                height: 2px;
                background: var(--black);
            }

            .level-2-wrapper {
                width: 90%;
                margin-left: 10%;
            }

            .level-2-wrapper::before {
                left: 30px;
                width: 2px;
                height: calc(100% + 40px);
            }

            .level-2-wrapper>li:not(:first-child) {
                margin-top: 50px;
            }
        }

      
    </style>
</div>