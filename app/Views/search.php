<?= $this->extend("includes/navbar-layout") ?>
<?= $this->section("content") ?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mt-5">
            <?php
            if (isset($error)) :
            ?>
                <h2 class="text-danger text-center"><?= $error ?></h2>
            <?php
            else :
            ?>
                <div id="confessions" class="p-3">
                    <h1 class="text-center mb-5">Results found: <?= $count ?></h1>
                    <?php 
                    foreach ($fetchAll as $confess) : ?>
                        <?php
                        $approvals = empty($confess["approvals"]) ? 0 : $confess["approvals"];
                        $disapprovals = empty($confess["disapprovals"]) ? 0 : $confess["disapprovals"];
                        ?>
                        <div class="col-sm-10 m-auto">
                            <div class="my-confession p-3">
                                <div class="confession-id-and-time d-flex justify-content-between">
                                    <p class="text-muted">#<?php echo $confess["id"] ?></p>
                                    <p class="text-muted"><?php echo date("d M Y", strtotime($confess["created_at"])) ?></p>
                                </div>
                                <div class="mytext">
                                    <p><?php echo $confess["confession_text"] ?></p>
                                </div>
                                <div class="confession-data">
                                    <div class="values d-flex justify-content-around p-2">
                                        <p><?= $approvals ?></p>
                                        <p><?= $disapprovals ?></p>
                                        <p><?= $confess["comments"] ?></p>
                                    </div>
                                    <form method="post" class="actions d-flex justify-content-around p-2">
                                        <input type="button" value="Approve" class="action p-2 approve-<?php echo $confess["id"]; ?>" data-confess-id="<?php echo $confess["id"] ?>">
                                        <input type="button" value="Disaprove" class="action p-2 disaprove-<?php echo $confess["id"]; ?>" data-confess-id="<?php echo $confess["id"] ?>">
                                        <a href="/single_confess?confess_id=<?php echo $confess["id"] ?>">
                                            <button type="button" class="p-2">
                                                <i class="fas fa-comment size"></i>
                                            </button>
                                        </a>
                                    </form>
                                </div>
                            </div>
                            <br><br>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php
            endif;
            ?>
        </div>
    </div>
</div>
<?php
    require_once "assets/js/ajax/approve_disapprove.php";
?>
<?= $this->endSection() ?>