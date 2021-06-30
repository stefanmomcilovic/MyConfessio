<?= $this->extend("includes/main-layout") ?>
<?= $this->section("content") ?>
<main id="main">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 mt-5">
                <div id="confessions" class="p-3">
                    <?php
                    foreach ($all_confessions as $confess) : ?>
                        <?php
                        $approvals = empty($confess["approvals"]) ? 0 : $confess["approvals"];
                        $disapprovals = empty($confess["disapprovals"]) ? 0 : $confess["disapprovals"];
                        ?>
                        <div class="col-sm-10 m-auto">
                            <div class="my-confession p-3">
                                <div class="confession-id-and-time d-flex justify-content-between">
                                    <p class="text-muted">#<?= $confess["id"] ?></p>
                                    <p class="text-muted"><?= date("d M Y", strtotime($confess["created_at"])) ?></p>
                                </div>
                                <div class="mytext">
                                    <p><?= $confess["confession_text"] ?></p>
                                </div>
                                <div class="confession-data">
                                    <div class="values d-flex justify-content-around p-2">
                                        <p><?= $approvals ?></p>
                                        <p><?= $disapprovals ?></p>
                                        <p><?= $confess["comments"] ?></p>
                                    </div>
                                    <form method="post" class="actions d-flex justify-content-around p-2">
                                        <input type="button" value="Approve" class="action p-2 approve-<?= $confess["id"] ?>" data-confess-id="<?= $confess["id"] ?>">
                                        <input type="button" value="Disaprove" class="action p-2 disaprove-<?= $confess["id"] ?>" data-confess-id="<?= $confess["id"] ?>">
                                        <a href="/single_confess?confess_id=<?= $confess["id"] ?>">
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
                    <div class="col-sm-10 d-flex m-auto">
                        <div class="col-sm-5 text-center m-auto">
                            <p><a class="page-link" href="index.php?page=1">First page</a></p>
                        </div>
                        <div class="col-sm-5 text-center m-auto">
                            <p><a class="page-link" href="index.php?page=<?= $total_pages ?>">Last page</a></p>
                        </div>
                    </div>
                    <div class="col-sm-10 d-flex justify-content-center text-center m-auto" id="data-container">
                        <ul class="pagination">
                            <?php
                            $page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
                            for ($i = 1; $i <= $total_pages; $i++) : ?>
                                <li class="page-item"><a class="page-link mr-3 ml-3 <?= $i ?> <?= (isset($page) && $i == $page ? "active-page" : "") ?>" href="index.php?page=<?= $i ?>"><?= $i ?></a></li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript">
    $(document).ready(function() {
        myPagination();

        function myPagination() {
            const params = new URLSearchParams(window.location.search); // Search to find params in url
            var page_total = <?= $total_pages ?>; // Getting total pages that we have
            var prevPageArr = []; // Create previous page array
            var allPages = []; // Create all pages array
            var nextPageArr = []; // Create next page array
            var pagesToShowOnStartIndex = 8; // To show pages on index.php on start

            for (var z = 1; z <= page_total; z++) { // Looping through pages so we can add them in allPages Array
                allPages.push(z); // Adding into allPages array all number of pages that we have
            }

            for (var j = 0; j < allPages.length; j++) { // Looping through all pages array
                if (allPages[j] > pagesToShowOnStartIndex) { // Checking if we have more than defined pagesToShowOnStartIndex 
                    $("." + allPages[j]).hide(); // Hide all other pages in pagination
                }
            }

            var current_page = parseInt(params.get("page")); // Getting the current page

            if (current_page >= 4) { // Checking if current page is 5 or > 5
                prevPageArr = []; // Reset previous page array to 0 length
                nextPageArr = []; // Reset next page array to 0 length
                for (var i = 1; i <= page_total; i++) { // Looping through total pages
                    if (i < current_page) { // Checking if current_page is less than current page
                        prevPageArr.push(i); // Adding into previous pages all less numbers
                        $("." + i).hide(); // And hide them
                    }

                    if (i > current_page) { // Checking if i is greater than current page
                        nextPageArr.push(i); // Adding into nextPageArr if i is greater than current page
                        $("." + i).hide(); // Also hide them
                    }
                }
                var lastPages = allPages.slice(-pagesToShowOnStartIndex); // Generating last pages to show
                var prevFour = ''; // Creating new varibale
                if (current_page > lastPages[0]) { // Checking if current page is greter than first element of last pages array
                    prevFour = lastPages; // then previous pages is equal to last pages
                } else {
                    prevFour = prevPageArr.slice(prevPageArr.length - 2); // Getting last two previous pages from prevPageArr
                }
                var nextFive = nextPageArr.slice(0, 5); // Getting only first five in nextPageArr
                for (var p = 0; p < prevFour.length; p++) { // Looping through previous four pages in pagination
                    $("." + prevFour[p]).show(); // And showed them
                }
                for (var o = 0; o < nextFive.length; o++) { // Looping through next five pages in pagination
                    $("." + nextFive[o]).show(); // And showed them too
                }
                $("." + current_page).show(); // Lastly we show current page what we on
            }
        }
    });
</script>
<?php
    require_once "assets/js/ajax/approve_disapprove.php";
?>
<?= $this->endSection() ?>