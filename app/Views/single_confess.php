<?= $this->extend("includes/navbar-layout") ?>
<?= $this->section("content") ?>
<?php
$approvals = empty($single_confession["approvals"]) ? 0 : $single_confession["approvals"];
$disapprovals = empty($single_confession["disapprovals"]) ? 0 : $single_confession["disapprovals"];
?>
<main id="main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-5">
                <div id="confessions" class="p-3">
                    <div class="col-sm-10 m-auto">
                        <div class="my-confession p-3">
                            <div class="confession-id-and-time d-flex justify-content-between">
                                <p class="text-muted">#<?= $single_confession["id"] ?></p>
                                <p class="text-muted"><?= date("d M Y", strtotime($single_confession["created_at"])) ?></p>
                            </div>
                            <div class="mytext">
                                <p><?= $single_confession["confession_text"] ?></p>
                            </div>
                            <div class="confession-data">
                                <div class="values d-flex justify-content-around p-2">
                                    <p><?= $approvals ?></p>
                                    <p><?= $disapprovals ?></p>
                                    <p><?= $single_confession['comments'] ?></p>
                                </div>
                                <form method="post" class="actions d-flex justify-content-around p-2">
                                    <input type="button" value="Approve" class="action p-2  approve-<?php echo $single_confession["id"]; ?>" data-confess-id="<?= $single_confession["id"] ?>">
                                    <input type="button" value="Disaprove" class="action p-2 disaprove-<?php echo $single_confession["id"]; ?>" data-confess-id="<?= $single_confession["id"] ?>">
                                    <button type="button" class="p-2" id="comment">
                                        <i class="fas fa-comment size"></i>
                                    </button>
                                </form>
                                <br>
                                <form method="post">
                                    <div class="form-group">
                                        <input type="text" name="username" class="form-control confession-textarea" id="username" placeholder="Username"><br>
                                        <textarea name="comment_text" class="form-control confession-textarea" id="comment_text" rows="5" placeholder="Comment here.." required></textarea>
                                    </div>
                                    <div class="form-group d-flex justify-content-center">
                                        <input type="submit" value="Post comment" class="post-confess mt-2 text-center" id="post_comment" data-confess-id="<?= $single_confession["id"] ?>">
                                    </div>
                                </form>

                                <div class="comment-data"></div>
                            </div>

                            <div class="comment-section mt-5">
                                <p class="text-center">Comments</p>
                                <div class="all-comments mb-5">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
require_once "assets/js/ajax/approve_disapprove.php";
require_once "assets/js/ajax/comments.php";
?>
<?= $this->endSection() ?>