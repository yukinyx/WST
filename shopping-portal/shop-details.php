<?php
session_start();
if(isset( $_SESSION["username"]) &&  $_SESSION["username"] != "" ) {
    if(isset($_GET["product"])){
        $_SESSION["product_name"]=$_GET["product"];
    }else{
        header('location:index.php');
    }
} else {
    header('location:login.php');
}
?>

<?php include "lib/functions.php";
$pdo = get_connection();

// Fetch Header Image
$profile_img_header = 'img/logo.png';
if(isset($_SESSION['email'])) {
    $uStmt = $pdo->prepare("SELECT IMG_URL FROM user WHERE email = ?");
    $uStmt->execute([$_SESSION['email']]);
    $uRow = $uStmt->fetch();
    if($uRow && !empty($uRow['IMG_URL'])) $profile_img_header = $uRow['IMG_URL'];
    else $profile_img_header = "img/default-user.png";
}
?>
<?php include "./template/top.php"; ?>
<style>
    /* Mobile & Sticky Styles */
    .mobile-bottom-nav { display: none; position: fixed; bottom: 0; left: 0; width: 100%; background: #fff; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); z-index: 99999; justify-content: space-around; padding: 10px 0; border-top: 1px solid #e1e1e1; }
    .mobile-bottom-nav .nav-item { display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #1c1c1c; font-size: 10px; font-weight: 600; width: 20%; }
    .mobile-bottom-nav .nav-item i { font-size: 18px; margin-bottom: 4px; color: #666; }
    .mobile-bottom-nav .nav-item.active i { color: #7fad39; }
    .mobile-sticky-top-bar { display: none; align-items: center; justify-content: space-between; padding: 10px 15px; background: #fff; width: 100%; }
    .sticky { position: fixed; top: 0; width: 100%; background: #ffffff; z-index: 9990; box-shadow: 0 5px 10px rgba(0,0,0,0.1); animation: fadeInDown 0.5s; }
    .header-profile-img { width: 20px; height: 20px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd; margin-right: 0; vertical-align: middle; }
    @media (max-width: 767px) {
        .header__logo { text-align: center; margin-bottom: 10px; }
        .header__cart { text-align: center; padding: 10px 0; }
        .mobile-bottom-nav { display: flex; }
        body { padding-bottom: 70px; }
        .header.sticky { padding: 0; }
        .header.sticky .header__logo, .header.sticky .header__menu, .header.sticky .header__cart, .header.sticky .humberger__open, .header.sticky .container { display: none !important; }
        .header.sticky .mobile-sticky-top-bar { display: flex !important; }
    }
</style>
<body>
        <header class="header" id="myHeader">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="./index.php">
                            <img id="headerLogo" src="img/logo.png" alt="logo" class="img-fluid" style="max-width: 180px; height: auto; transition: all 0.3s;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li><a href="./index.php">Home</a></li>
                            <li class="active"><a href="./shop-grid.php">Shop</a></li>
                            <li><a href="./shoping-cart.php">Shopping Cart</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i> <span></span></a></li>
                            <li>
                                <a href="./profile.php">
                                    <?php if(strpos($profile_img_header, 'default-user') === false && strpos($profile_img_header, 'logo.png') === false): ?>
                                        <img src="<?php echo $profile_img_header; ?>" class="header-profile-img" alt="Profile">
                                    <?php else: ?>
                                        <i class="fa fa-user"></i>
                                    <?php endif; ?>
                                </a>
                            </li>
                        </ul>
                        
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
        <!-- Mobile Top Bar -->
        <div class="mobile-sticky-top-bar">
             <!-- Content same as other pages -->
        </div>
    </header>

    <!-- Hero Section Begin -->
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Categories</span>
                        </div>
                        <ul>
                            <?php
                            $c=0;
                            $res=$pdo->query("SELECT category_name FROM category");
                            while($row = $res->fetch()){

                                ?>
                                <li id="echo $c;"><a href="#"><?php echo $row["category_name"]; ?></a></li>
                                <?php $c=$c+1; } ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="#">
                                <input type="text" placeholder="What do yo u need?">
                                <button type="submit" class="site-btn">SEARCH</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="hero__search__phone__text">
                                <h5>69696969696</h5>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->
        <div id="error" class='alert alert-danger text-center' style="display: none">
            This product is already exist in shopping cart !
        </div>

        <div id="success" class='alert alert-success text-center' style="display: none">
            product added successfully !
        </div>
    <!-- Breadcrumb Section Begin -->
        <?php

        $res=$pdo->prepare("SELECT * FROM product where product_name =?");
        $res->execute([$_SESSION["product_name"]]);
        $c=0;
        while($row = $res->fetch()){
        $loc="../admin_portal/admin/"


        ?>
    <section class="breadcrumb-section set-bg" data-setbg="img/batstateu-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    
                </div>
            </div>
        </div>
    </section>

    <!-- Breadcrumb Section End -->

    <!-- Product Details Section Begin -->
    <section class="product-details spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img class="product__details__pic__item--large"
                                src="<?php echo $loc.$row["image_file_name"]; ?>" alt="">
                        </div>
                        <div class="product__details__pic__slider owl-carousel">
                            <img data-imgbigurl="img/product/details/product-details-2.jpg"
                                src="img/product/details/thumb-1.jpg" alt="">
                            <img data-imgbigurl="img/product/details/product-details-3.jpg"
                                src="img/product/details/thumb-2.jpg" alt="">
                            <img data-imgbigurl="img/product/details/product-details-5.jpg"
                                src="img/product/details/thumb-3.jpg" alt="">
                            <img data-imgbigurl="img/product/details/product-details-4.jpg"
                                src="img/product/details/thumb-4.jpg" alt="">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="product__details__text">
                        <h3><?php echo $row["product_name"]; ?></h3>
                        <div class="product__details__price">$<?php echo $row["product_price"]; ?></div>
                        
                        <form method="post" action="">
                            <div class="product__details__quantity">
                                <div class="quantity">
                                    <div class="pro-qty">
                                        <input type="number" name="quantity" value="1" min="1">
                                    </div>
                                </div>
                            </div>
                            <input type = "hidden" name = "the_id" value = "<?php echo $row["product_name"]; ?>" />
                            <button name="add_to_cart" class="primary-btn">ADD TO CART</button>
                        </form>
                        <ul>
                            <li><b>Availability</b> <span><?php echo $row["quantity"]; ?> In Stock</span></li>
                            <li><b>Weight</b> <span>0.5 kg</span></li>
                        </ul>
                    </div>
                </div>
                <?php  } ?>
            </div>
        </div>
    </section>
    <!-- Product Details Section End -->

    <div class="mobile-bottom-nav">
        <a href="./index.php" class="nav-item"><i class="fa fa-home"></i><span>Home</span></a>
        <a href="./shop-grid.php" class="nav-item active"><i class="fa fa-shopping-bag"></i><span>Shop</span></a>
        <a href="./shoping-cart.php" class="nav-item"><i class="fa fa-shopping-cart"></i><span>Cart</span></a>
        <a href="./contact.php" class="nav-item"><i class="fa fa-envelope"></i><span>Contact</span></a>
        <a href="./profile.php" class="nav-item"><i class="fa fa-user"></i><span>Profile</span></a>
    </div>

	<?php include_once("./template/footer.php"); ?>
    <!-- Js Plugins -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <script>
    // Sticky Header Logic
    window.onscroll = function() {myStickyFunction()};
    var header = document.getElementById("myHeader");
    var logo = document.getElementById("headerLogo");
    var sticky = header.offsetTop;
    function myStickyFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
            if(window.innerWidth > 767) { logo.style.maxWidth = "120px"; }
        } else {
            header.classList.remove("sticky");
            logo.style.maxWidth = "180px";
        }
    }
    </script>
</body>

</html>
<?php
if(isset($_POST["add_to_cart"])) {
    $count =0;
    $x_value=$_POST["the_id"];
    
    // 1. Capture Quantity Correctly
    $qty = isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? (int)$_POST['quantity'] : 1;

    $res = $pdo->prepare("SELECT * FROM shopping_cart where product_name=? and user_email=? ");
    $res ->execute([$x_value,$_SESSION["email"]]);
    $count =$res->rowCount();
    
    // Check if DB supports Quantity column
    $hasQtyCol = false;
    try {
        $colCheck = $pdo->query("SHOW COLUMNS FROM shopping_cart LIKE 'quantity'");
        if ($colCheck && $colCheck->rowCount() > 0) $hasQtyCol = true;
    } catch (Exception $e) { $hasQtyCol = false; }

    if($count > 0) {
        // UPDATE logic
        if ($hasQtyCol) {
            // Add new quantity to existing quantity
            $update = $pdo->prepare("UPDATE shopping_cart SET quantity = quantity + ? WHERE product_name = ? AND user_email = ?");
            $update->execute([$qty, $x_value, $_SESSION['email']]);
            ?>
            <script>
                document.getElementById("error").style.display="none";
                document.getElementById("success").style.display="block";
                setTimeout(function () { window.location.href=window.location.href; },1000);
            </script>
            <?php
        } else {
            ?>
            <script>
                document.getElementById("success").style.display="none";
                document.getElementById("error").style.display="block";
                setTimeout(function () { window.location.href=window.location.href; },1000);
            </script>
            <?php
        }
    } else {
        // INSERT Logic
        if ($hasQtyCol) {
            // Use the captured quantity here
            $stmt = $pdo->prepare("INSERT INTO shopping_cart (product_name,user_email,quantity) VALUES (?,?,?)");
            $stmt->execute([$x_value, $_SESSION["email"], $qty]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO shopping_cart (product_name,user_email) value(?,?) ");
            $stmt->execute([$x_value, $_SESSION["email"]]);
        }
        ?>
        <script>
            document.getElementById("success").style.display="block";
            document.getElementById("error").style.display="none";
            setTimeout(function () { window.location.href=window.location.href; },1000);
        </script>
        <?php
    }
}?>