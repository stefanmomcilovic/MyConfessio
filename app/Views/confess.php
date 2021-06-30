<?= $this->extend("includes/navbar-layout") ?>
<?= $this->section("content") ?>
<main id="main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-5">
                <div class="col-sm-10 m-auto">
                    <div class="my-confession p-3">
                        <h1 class="text-center">Leave a confession</h1>
                        <?php
                        if (isset($validation)) :
                        ?>
                            <div class="errors text-center">
                                <?php foreach ($validation->getErrors() as $error) : ?>
                                    <p class="text-danger"><?= $error ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php
                        endif;

                        if(session()->get("success")):
                        ?>
                            <div class="success text-center">
                                <p class="text-success"><?= session()->get("success") ?></p>
                            </div>
                        <?php
                            endif;
                        ?>
                        <form action="/confess" method="post" class="text-center">
                            <div class="form-group">
                                <textarea name="confession_text" class="form-control confession-textarea" placeholder="Confess here" rows="10" required><?php if(isset($_POST["confession_text"])){ echo $_POST["confession_text"];}?></textarea>
                                <p class="text-muted d-flex justify-content-end">Character counter: <span class="char-count ml-1">0</span></p>
                                <input type="submit" value="Post a confess" class="post-confess">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?= $this->endSection() ?>