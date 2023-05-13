<?php if (isset($catalogueSubinex)) : ?>
                                        <?php if (count($catalogueSubinex) == 5) : ?>
                                            <style>
                                                .level-2-wrapper {
                                                    position: relative;
                                                    display: grid;
                                                    grid-template-columns: repeat(5, 1fr);
                                                }

                                                .level-4-wrapper::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: -20px;
                                                    left: -9px;
                                                    width: 2px;
                                                    height: calc(100% + 20px);
                                                    background: var(--black);
                                                }

                                                .level-4::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: 50%;
                                                    left: 0%;
                                                    transform: translate(-100%, -50%);
                                                    width: 9px;
                                                    height: 2px;
                                                    background: var(--black);
                                                }
                                            </style>
                                        <?php endif; ?>
                                        <?php if (count($catalogueSubinex) == 4) : ?>
                                            <style>
                                                .level-2-wrapper {
                                                    position: relative;
                                                    display: grid;
                                                    grid-template-columns: repeat(4, 1fr);
                                                }

                                                .level-4-wrapper::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: -20px;
                                                    left: -11px;
                                                    width: 2px;
                                                    height: calc(100% + 20px);
                                                    background: var(--black);
                                                }

                                                .level-4::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: 50%;
                                                    left: 0%;
                                                    transform: translate(-100%, -50%);
                                                    width: 11px;
                                                    height: 2px;
                                                    background: var(--black);
                                                }
                                            </style>
                                        <?php endif; ?>
                                        <?php if (count($catalogueSubinex) == 3) : ?>
                                            <style>
                                                .level-2-wrapper {
                                                    position: relative;
                                                    display: grid;
                                                    grid-template-columns: repeat(3, 1fr);
                                                }

                                                .level-4-wrapper::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: -20px;
                                                    left: -14px;
                                                    width: 2px;
                                                    height: calc(100% + 20px);
                                                    background: var(--black);
                                                }

                                                .level-4::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: 50%;
                                                    left: 0%;
                                                    transform: translate(-100%, -50%);
                                                    width: 14px;
                                                    height: 2px;
                                                    background: var(--black);
                                                }
                                            </style>
                                        <?php endif; ?>
                                        
                                        <?php if (count($catalogueSubinex) < 3) : ?>
                                            <style>
                                                .level-2-wrapper {
                                                    position: relative;
                                                    display: grid;
                                                    grid-template-columns: repeat(3, 1fr);
                                                }

                                                .level-4-wrapper::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: -20px;
                                                    left: -14px;
                                                    width: 2px;
                                                    height: calc(100% + 20px);
                                                    background: var(--black);
                                                }

                                                .level-4::before {
                                                    content: "";
                                                    position: absolute;
                                                    top: 50%;
                                                    left: 0%;
                                                    transform: translate(-100%, -50%);
                                                    width: 14px;
                                                    height: 2px;
                                                    background: var(--black);
                                                }
                                            </style>
                                        <?php endif; ?>
                                    <?php endif; ?>