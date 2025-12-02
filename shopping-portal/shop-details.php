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
    /* -------------------------------------------
       RESPONSIVE & STICKY BEHAVIOR (Matched with index.php)
    ------------------------------------------- */
    .header-profile-img { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; border: 1px solid #ddd; }
    
    .mobile-bottom-nav { display: none; position: fixed; bottom: 0; left: 0; width: 100%; background: #ffffff; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); z-index: 99999; justify-content: space-around; padding: 10px 0; border-top: 1px solid #e1e1e1; }
    .mobile-bottom-nav .nav-item { display: flex; flex-direction: column; align-items: center; text-decoration: none; color: #1c1c1c; font-size: 10px; font-weight: 600; text-transform: uppercase; width: 20%; }
    .mobile-bottom-nav .nav-item i { font-size: 18px; margin-bottom: 4px; color: #666; transition: 0.3s; }
    .mobile-bottom-nav .nav-item.active i, .mobile-bottom-nav .nav-item:hover i { color: #9c340bff; }

    .mobile-sticky-top-bar { display: none; align-items: center; justify-content: space-between; padding: 10px 15px; background: #fff; width: 100%; }
    .mobile-sticky-top-bar .search-wrapper { flex-grow: 1; margin-right: 15px; }
    .mobile-sticky-top-bar form { display: flex; width: 100%; position: relative; }
    .mobile-sticky-top-bar input { width: 100%; border: 1px solid #e1e1e1; padding: 8px 15px; padding-right: 40px; border-radius: 20px; font-size: 14px; background: #f5f5f5; outline: none; }
    .mobile-sticky-top-bar button { position: absolute; right: 0; top: 0; height: 100%; width: 40px; background: transparent; border: none; color: #1c1c1c; border-radius: 0 20px 20px 0; }
    .mobile-sticky-top-bar .icons-wrapper { display: flex; align-items: center; gap: 15px; }
    .mobile-sticky-top-bar .icons-wrapper a { position: relative; color: #1c1c1c; font-size: 20px; }

    /* Default Sticky styles for Desktop */
    .sticky { position: fixed; top: 0; width: 100%; background: #ffffff; z-index: 9990; box-shadow: 0 5px 10px rgba(0,0,0,0.1); animation: fadeInDown 0.5s; }

    @media (max-width: 767px) {
        .header__logo { text-align: center; margin-bottom: 10px; }
        .header__cart { text-align: center; padding: 10px 0; }
        .mobile-bottom-nav { display: flex; }
        .header__top,
        .header__menu, 
        .header__cart { 
            display: none !important; 
        }

        .breadcrumb-section .breadcrumb__text h2 {
            font-size: 20px;
        }

        .breadcrumb-section {
            padding: 20px 0 20px 0;
        }
        body { padding-bottom: 70px; }
        .header.sticky { padding: 0; }
        .header.sticky .header__logo, .header.sticky .header__menu, .header.sticky .header__cart, .header.sticky .humberger__open, .header.sticky .container { display: none !important; }
        .header.sticky .mobile-sticky-top-bar { display: flex !important; }
    }
    
    @keyframes fadeInDown { from { opacity: 0; transform: translate3d(0, -100%, 0); } to { opacity: 1; transform: none; } }
</style>
<body>
        <header class="header" id="myHeader">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-1">
                    <div class="header__logo">
                        <a href="./index.php">
                            <img id="headerLogo" src="img/logo.png" alt="logo" class="img-fluid" style="max-width: 180px; height: auto; transition: all 0.3s;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-8 col-md-8 text-center">
                    <nav class="header__menu">
                        <ul>
                            <li><a href="./index.php">Home</a></li>
                            <li class="active"><a href="./shop-grid.php">Shop</a></li>
                            <li><a href="./shoping-cart.php">Shopping Cart</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="header__cart">
                        <ul>
                            <li><a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i></a><div class="header__cart__price" style="margin-left:.5em;">: <span><?php if (isset($_SESSION["total"])) echo "$".number_format($_SESSION["total"], 2); ?></span></div></li>
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
    </header>

    <div id="error" class='alert alert-danger text-center' style="display: none">
            This product is already exist in shopping cart !
        </div>

        <div id="success" class='alert alert-success text-center' style="display: none">
            product added successfully !
        </div>
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
                    <div class="breadcrumb__text">
                        <h2>Add to Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $row['quantity']; ?>">
                                    </div>
                                </div>
                            </div>
                            <input type = "hidden" name = "the_id" value = "<?php echo $row["product_name"]; ?>" />
                            <button name="add_to_cart" class="primary-btn">ADD TO CART</button>
                        </form>
                        <ul>
                            <li><b>Availability</b> <span><?php echo $row["quantity"]; ?> In Stock</span></li>
                            
                        </ul>
                    </div>
                </div>
                <?php  } ?>
            </div>
        </div>
    </section>
    <div class="mobile-bottom-nav">
        <a href="./index.php" class="nav-item"><i class="fa fa-home"></i><span>Home</span></a>
        <a href="./shop-grid.php" class="nav-item active"><i class="fa fa-shopping-bag"></i><span>Shop</span></a>
        <a href="./shoping-cart.php" class="nav-item"><i class="fa fa-shopping-cart"></i><span>Cart</span></a>
        <a href="./contact.php" class="nav-item"><i class="fa fa-envelope"></i><span>Contact</span></a>
        <a href="./profile.php" class="nav-item"><i class="fa fa-user"></i><span>Profile</span></a>
    </div>

	<?php include_once("./template/footer.php"); ?>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/jquery.slicknav.js"></script>
    <script src="js/mixitup.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>

    <script>
    // Sticky Header Logic (Smoother match)
    window.onscroll = function() {myStickyFunction()};
    var header = document.getElementById("myHeader");
    var logo = document.getElementById("headerLogo");
    var sticky = header.offsetTop;
    function myStickyFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
            if(window.innerWidth > 767) { 
                logo.style.maxWidth = "120px"; 
            }
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
    $x_value = $_POST["the_id"];
    
    // Capture Quantity
    $qty = isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? (int)$_POST['quantity'] : 1;

    // [MODIFICATION]: Fetch Product Stock Limit
    $stockStmt = $pdo->prepare("SELECT quantity FROM product WHERE product_name = ?");
    $stockStmt->execute([$x_value]);
    $stockRow = $stockStmt->fetch();
    $maxStock = $stockRow ? (int)$stockRow['quantity'] : 0;

    // Check if item exists in cart
    $res = $pdo->prepare("SELECT * FROM shopping_cart where product_name=? and user_email=? ");
    $res->execute([$x_value, $_SESSION["email"]]);
    $existingItem = $res->fetch();
    $count = $res->rowCount();

    // Calculate current quantity in cart
    $currentCartQty = ($existingItem && isset($existingItem['quantity'])) ? (int)$existingItem['quantity'] : 0;
    
    // Determine how much we can actually add
    $totalProposed = $currentCartQty + $qty;
    $qtyToAdd = $qty;

    if ($totalProposed > $maxStock) {
        $qtyToAdd = $maxStock - $currentCartQty;
        // If cart already has max stock, we add 0
        if ($qtyToAdd < 0) $qtyToAdd = 0;
        
        // Optional: Trigger an alert that max stock is reached (handled via JS below if you wish)
        echo "<script>alert('Cannot add full quantity. Stock limit is $maxStock.');</script>";
    }

    if ($qtyToAdd > 0) {
        // Check DB for Quantity Column existence
        $hasQtyCol = false;
        try {
            $colCheck = $pdo->query("SHOW COLUMNS FROM shopping_cart LIKE 'quantity'");
            if ($colCheck && $colCheck->rowCount() > 0) $hasQtyCol = true;
        } catch (Exception $e) { $hasQtyCol = false; }

        if($count > 0) {
            // UPDATE existing item
            if ($hasQtyCol) {
                $update = $pdo->prepare("UPDATE shopping_cart SET quantity = quantity + ? WHERE product_name = ? AND user_email = ?");
                $update->execute([$qtyToAdd, $x_value, $_SESSION['email']]);
            }
        } else {
            // INSERT new item
            if ($hasQtyCol) {
                $stmt = $pdo->prepare("INSERT INTO shopping_cart (product_name,user_email,quantity) VALUES (?,?,?)");
                $stmt->execute([$x_value, $_SESSION["email"], $qtyToAdd]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO shopping_cart (product_name,user_email) value(?,?) ");
                $stmt->execute([$x_value, $_SESSION["email"]]);
            }
        }
    }
    
    // Recalculate Cart Total
    $newTotal = 0;
    $tStmt = $pdo->prepare("SELECT p.product_price, sc.quantity FROM shopping_cart sc JOIN product p ON sc.product_name = p.product_name WHERE sc.user_email = ?");
    $tStmt->execute([$_SESSION['email']]);
    while($row = $tStmt->fetch()) {
        $q = isset($row['quantity']) ? $row['quantity'] : 1;
        $newTotal += ($row['product_price'] * $q);
    }
    $_SESSION['total'] = $newTotal;

    ?>
    <script>
        document.getElementById("success").style.display="block";
        setTimeout(function () { window.location.href=window.location.href; }, 1000);
    </script>
    <?php
}?>
    