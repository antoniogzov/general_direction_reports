<?php
$catalogue_exist = $expected_learnings->getExistCatalogCoordinator($no_teacher, $_GET['id_academic_level'], $id_subject);
?>
<div class="card card-table-evaluations">
    <?php if (!empty($catalogue_exist)) : ?>
        <div class="card-body">
            <div class="table-responsive">
                <div class="accordion-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 ml-auto">
                                <div class="accordion my-3" id="accordionExample">
                                    <?php foreach ($catalogue_exist as $catalogue) : ?>
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link w-100 text-primary text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $catalogue->id_expected_learning_index; ?>" aria-expanded="false" aria-controls="collapse<?= $catalogue->id_expected_learning_index; ?>">
                                                        <?= mb_strtoupper($catalogue->index_description) ?> |<span class="badge badge-secondary"><?= mb_strtoupper($catalogue->nombre_colaborador) ?></span>
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse<?= $catalogue->id_expected_learning_index; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body opacity-8">
                                                    <div class="container">
                                                        <h1 class="level-1 rectangle"><?= mb_strtoupper($catalogue->index_description) ?> <small class="text-muted"><?= mb_strtoupper($catalogue->nombre_colaborador) ?></small></h1>
                                                        <ol class="level-2-wrapper">
                                                            <?php
                                                            $catalogueSubinex = $expected_learnings->getSubindexCatalog($catalogue->id_expected_learning_index);

                                                            foreach ($catalogueSubinex as $subindex) :
                                                                $get_period_average = $expected_learnings->getPeriodAverage($subindex->id_expected_learning_subindex);

                                                                $catalogueSubinexCatalogItems = $expected_learnings->getSubindexCatalogItems($subindex->id_expected_learning_subindex);
                                                                $count_expected_learnings = count($catalogueSubinexCatalogItems);
                                                                $already_learned = 0;
                                                                foreach ($catalogueSubinexCatalogItems as $items) {
                                                                    $item_average = $expected_learnings->getCatalogAverage($items->id_expected_learning_catalog);
                                                                    if (!empty($item_average)) {
                                                                        $average = $item_average[0]->learning_average;
                                                                        $average = round($average, 1);
                                                                        if ($average > 0) {
                                                                            $already_learned++;
                                                                        }
                                                                    }
                                                                }
                                                                if ($already_learned > 0) {
                                                                    $percentage_learnings = ($already_learned / $count_expected_learnings)*100;
                                                                }else{
                                                                    $percentage_learnings = 0;
                                                                }
                                                                $percentage_learnings = number_format($percentage_learnings, 1);
                                                                
                                                            ?>
                                                                <li>
                                                                    <h2 class="level-2 rectangle">Periodo <?= $subindex->no_period ?>
                                                                        <?php
                                                                        if (!empty($get_period_average)) {
                                                                            $period_average = $get_period_average[0]->period_average;
                                                                            $period_average = round($period_average, 1);
                                                                            if ($period_average > 0) {
                                                                                echo '<span class="badge badge-secondary">' . $period_average . '</span>';
                                                                                echo '<p title="Se han cubierto '.$already_learned.' de '.$count_expected_learnings.' aprendizajes esperados"><em>' . $percentage_learnings . '% cubierto</em></p>';
                                                                                
                                                                            }else{
                                                                                echo '<p title="Se han cubierto '.$already_learned.' de '.$count_expected_learnings.' aprendizajes esperados"><em>' . $percentage_learnings . '% cubierto</em></p>';
                                                                            }
                                                                        }else{
                                                                            echo '<p title="Se han cubierto '.$already_learned.' de '.$count_expected_learnings.' aprendizajes esperados"><em>' . $percentage_learnings . '% cubierto</em></p>';
                                                                        }
                                                                        ?>
                                                                    </h2>
                                                                    <ol class="level-4-wrapper btn">
                                                                        <?php
                                                                        $catalogueSubinexCatalogItems = $expected_learnings->getSubindexCatalogItems($subindex->id_expected_learning_subindex);
                                                                        foreach ($catalogueSubinexCatalogItems as $items) :
                                                                            $getExpectedLearningArchive = $expected_learnings->getExpectedLearningArchive($items->id_expected_learning_catalog);
                                                                            $url_archive = "";
                                                                            $tooltip_href = "No se encontró evidencia";
                                                                            $color_style = '';
                                                                            if (!empty($getExpectedLearningArchive)) {
                                                                                $color_style = 'style="background: #7eacff !important;"';
                                                                                $tooltip_href = "Ver evidencia";
                                                                                $url_archive = 'href="' . $getExpectedLearningArchive[0]->url_archive . '"  target="_blank"';
                                                                                if ($getExpectedLearningArchive[0]->link_type == 1) {
                                                                                    $url_archive = 'href="' . $getExpectedLearningArchive[0]->url_archive . '"  target="_blank"';
                                                                                } else {
                                                                                    $url_archive = 'href="' .  '../iTeach' . $getExpectedLearningArchive[0]->url_archive . '" target="_blank"';
                                                                                }
                                                                            }
                                                                        ?>

                                                                            <a <?= $url_archive ?>>

                                                                                <li>

                                                                                    <h4 class="level-4 rectangle" <?= $color_style ?>><?= $items->short_description ?>
                                                                                        <?php $item_average = $expected_learnings->getCatalogAverage($items->id_expected_learning_catalog);
                                                                                        if (!empty($item_average)) {
                                                                                            $average = $item_average[0]->learning_average;
                                                                                            $average = round($average, 1);
                                                                                            if ($average > 0) {
                                                                                                echo '<br><span class="badge badge-secondary">' . $average . '</span>';
                                                                                            }
                                                                                        }
                                                                                        ?>
                                                                                    </h4>
                                                                                </li>
                                                                            </a>
                                                                        <?php endforeach; ?>
                                                                    </ol>
                                                                </li>

                                                            <?php endforeach; ?>
                                                        </ol>
                                                    </div>

                                                </div>
                                                <br>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php include_once 'validationsStyle.php'; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        ol {
            list-style: none;
        }

        body {
            margin: 50px 0 100px;
            text-align: center;
            font-family: "Inter", sans-serif;
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

        .page-footer {
            position: fixed;
            right: 0;
            bottom: 20px;
            display: flex;
            align-items: center;
            padding: 5px;
        }

        .page-footer a {
            margin-left: 4px;
        }
    </style>
</div>