<!DOCTYPE html>
<html lang="en">

<head>
    <title><?= $title ?></title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">
    <link rel="icon" href="assets/images/favicon.png" type="image/x-icon">
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= noCache("assets/css/style.css") ?>">
    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="<?= noCache("assets/js/js_script.js") ?>" type="text/javascript"></script>
    <script src="<?= noCache("assets/js/jq_script.js") ?>" type="text/javascript"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-E7HKS31D1H"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-E7HKS31D1H');
    </script>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PLMRXRL');</script>
    <!-- End Google Tag Manager -->
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PLMRXRL"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="top-up-button">
        <a href="#header" title="Back to top"><i class="fas fa-3x fa-arrow-alt-circle-up"></i></a>
    </div>
    
    <header id="header">
        <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php"><span class="brand">MYC</span>onfessio.com</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item <?= (basename($_SERVER["PHP_SELF"]) == "index.php" ? "active" : "") ?> mr-2" style="margin-top:2px;">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li id="sortby" class="nav-item mr-2" style="margin-top: 2px;">
                        <a class="nav-link" href="#"><i class="fas fa-sort-amount-down-alt"></i>Sort By</a>
                        <div class="sortby-submenu p-2">
                            <form method="post">
                                <i class="fas fa-random"></i>
                                <input type="submit" value="Random" name="random" id="random" class="sortby-btn"><br>
                                <i class="far fa-calendar-alt"></i>
                                <input type="submit" value="The latest" name="latest" id="latest" class="sortby-btn"><br>
                                <i class="far fa-thumbs-up"></i>
                                <input type="submit" value="Approvals" name="approvals" id="approvals" class="sortby-btn"><br>
                                <i class="far fa-thumbs-down"></i>
                                <input type="submit" value="Disapprovals" name="disapprovals" id="disapprovals" class="sortby-btn"><br>
                                <i class="far fa-calendar-alt"></i>
                                <input type="submit" value="The oldest" name="oldest" id="latest" class="sortby-btn">
                            </form>
                        </div>
                    </li>
                    <li class="nav-item <?= (basename($_SERVER["PHP_SELF"]) == "confess" ? "active" : "") ?>">
                        <a class="nav-link" href="/confess">Leave a confession <img src="assets/images/feather.png" alt="Leave a confession feather"></a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 ml-2" method="post" action="/search">
                    <div class="search-form">
                        <i class="fas fa-search"></i>
                        <input class="form-control mr-sm-2 text" type="search" name="q" placeholder="Enter some text to search" aria-label="Search">
                        <input type="submit" style="display: none;" name="searchBtn" class="form-control btn btn-primary d-none" value="Search">
                    </div>
                </form>
            </div>
        </nav>
    </header>
    <?= $this->renderSection("content") ?>
</body>

</html>