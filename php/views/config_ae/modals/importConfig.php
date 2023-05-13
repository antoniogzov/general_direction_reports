<div class="modal fade" id="importConfig" tabindex="-1" role="dialog" aria-labelledby="importConfigLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importConfigLabel">Importar configuración existente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="accordion-1">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 ml-auto">
                                <div class="accordion my-3" id="accordionExample">
                                    <?php foreach ($catalogue_exist as $catalogue) : ?>
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link w-100 text-primary text-left" type="button" data-toggle="collapse" data-target="#collapse<?=$catalogue->id_expected_learning_index; ?>" aria-expanded="false" aria-controls="collapse<?=$catalogue->id_expected_learning_index; ?>">
                                                        <?= mb_strtoupper($catalogue->index_description) ?> |<span class="badge badge-secondary"><?= mb_strtoupper($catalogue->nombre_colaborador) ?></span> 
                                                    </button>
                                                </h5>
                                            </div>

                                            <div id="collapse<?=$catalogue->id_expected_learning_index; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body opacity-8">
                                                    <div class="container">
                                                        <h1 class="level-1 rectangle"><?= mb_strtoupper($catalogue->index_description) ?> <small class="text-muted"><?= mb_strtoupper($catalogue->nombre_colaborador) ?></small></h1>
                                                        <ol class="level-2-wrapper">
                                                            <?php
                                                            $catalogueSubinex = $expected_learnings->getSubindexCatalog($catalogue->id_expected_learning_index);

                                                            foreach ($catalogueSubinex as $subindex) : ?>
                                                                <li>
                                                                    <h2 class="level-2 rectangle">Periodo <?= $subindex->no_period ?></h2>
                                                                    <ol class="level-4-wrapper">
                                                                        <?php
                                                                        $catalogueSubinexCatalogItems = $expected_learnings->getSubindexCatalogItems($subindex->id_expected_learning_subindex);
                                                                        foreach ($catalogueSubinexCatalogItems as $items) : ?>
                                                                            <li>
                                                                                <h4 class="level-4 rectangle"><?= $items->short_description ?></h4>
                                                                            </li>
                                                                        <?php endforeach; ?>
                                                                    </ol>
                                                                </li>

                                                            <?php endforeach; ?>
                                                        </ol>
                                                    </div>

                                                </div>
                                                <button type="button" data-id-expected-learning-index="<?=$catalogue->id_expected_learning_index; ?>" class="btn btn-primary btnImportConfig">Importar</button>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
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
        .level-1::before{
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