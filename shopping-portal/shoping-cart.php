<?php
include "check_ath.php";
include "lib/functions.php";
$pdo = get_connection();

// 1. Handle Cart Update AND Checkout in one go (if Checkout clicked)
$hasQtyCol = false;
try {
    $colCheck = $pdo->query("SHOW COLUMNS FROM shopping_cart LIKE 'quantity'");
    if ($colCheck && $colCheck->rowCount() > 0) $hasQtyCol = true;
} catch (Exception $e) { $hasQtyCol = false; }

// Check if user clicked "Update Cart" OR "Checkout"
if ($hasQtyCol && (isset($_POST['update_cart']) || isset($_POST['checkout_trigger'])) && isset($_POST['qty']) && is_array($_POST['qty'])) {
    foreach ($_POST['qty'] as $pname => $q) {
        $qval = is_numeric($q) && $q > 0 ? (int)$q : 1;
        $updateStmt = $pdo->prepare("UPDATE shopping_cart SET quantity = ? WHERE product_name = ? AND user_email = ?");
        $updateStmt->execute([$qval, $pname, $_SESSION['email']]);
    }
    
    // If it was the Checkout button, redirect to addorder logic
    if (isset($_POST['checkout_trigger'])) {
        // Use a mock form submission to addorder.php via JS or direct include logic, 
        // but simplest is to redirect to a page that handles order creation.
        // However, addorder.php expects $_POST['order'].
        // We can auto-submit a form using JS or change addorder.php.
        // To adhere to strict requirements without rewriting addorder.php logic entirely:
        // We construct a form on the fly and submit it.
        echo '<body onload="document.getElementById(\'hiddenCheckoutForm\').submit();">';
        echo '<form id="hiddenCheckoutForm" action="addorder.php" method="post">';
        echo '<input type="hidden" name="order" value="1">';
        echo '</form></body>';
        exit;
    } else {
        // Just update
        echo '<script>window.location.href=window.location.href;</script>';
        exit;
    }
}

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
    @keyframes fadeInDown { from { opacity: 0; transform: translate3d(0, -100%, 0); } to { opacity: 1; transform: none; } }
</style>
<body>
      <!-- Header Section Begin -->
    <header class="header" id="myHeader">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-2 col-md-1">
    <div class="header__logo" >
        <a href="./index.php">
            <img id="headerLogo" src="img/logo.png" alt="logo" class="img-fluid" style="max-width: 180px; height: auto; transition: all 0.3s;">
        </a>
    </div>
</div>
<div class="col-lg-8 col-md-8 text-center">
    <nav class="header__menu">
        <ul>
            <li><a href="./index.php">Home</a></li>
            <li><a href="./shop-grid.php" >Shop</a></li>
            <li class="active"><a href="./shoping-cart.php" >Shopping Cart</a></li>
            <li><a href="./contact.php" >Contact</a></li>
        </ul>
    </nav>
</div>
<div class="col-lg-2 col-md-2">
    <div class="header__cart">
                        <ul>
                            <!-- Normal view Cart -->
                            <li><a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i></a><div class="header__cart__price" style= margin-left:.5em;>: <span><?php if (isset($_SESSION["total"])) echo "$".number_format($_SESSION["total"], 2); ?></span></div></li>
                            
                            <!-- Profile Icon (Desktop/Normal View) -->
                            <li>
                                <a href="./profile.php">
                                    <?php if(isset($_SESSION['email'])): ?>
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
        </div>
    </header>
    
    <section class="breadcrumb-section set-bg" data-setbg="img/batstateu-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Shopping Cart</h2>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="shoping-cart spad">
        <div class="container">
            <!-- Open form here to wrap both table and checkout button -->
            <form method="post" action="">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <?php
                            $total=0;
                            if (!isset($_SESSION['email'])) { header('Location: login_form.php'); exit; }

                            if ($hasQtyCol) {
                                $res = $pdo->prepare ("SELECT product_name, quantity FROM shopping_cart WHERE user_email = ?");
                            } else {
                                $res = $pdo->prepare ("SELECT product_name FROM shopping_cart WHERE user_email = ?");
                            }
                            $res->execute([$_SESSION["email"]]);

                            while($row = $res->fetch()){
                                $cartProduct = $row['product_name'];
                                $cartQty = isset($row['quantity']) ? (int)$row['quantity'] : 1;
                                $rest=$pdo->prepare ("SELECT * FROM product WHERE product_name = ?");
                                $rest->execute([$cartProduct]);
                                while($com=$rest->fetch()){
                                    $loc="../admin_portal/admin/";
                            ?>
                            <tbody>
                                <tr>
                                    <td class="shoping__cart__item">
                                        <img src="<?php echo htmlspecialchars($loc . $com["image_file_name"], ENT_QUOTES, 'UTF-8'); ?>" width="100px">
                                        <h5><?php echo htmlspecialchars($com["product_name"], ENT_QUOTES, 'UTF-8'); ?></h5>
                                    </td>
                                    <td class="shoping__cart__price">
                                        <span class="unit-price" data-price="<?php echo htmlspecialchars($com["product_price"], ENT_QUOTES, 'UTF-8'); ?>">$<?php echo htmlspecialchars($com["product_price"], ENT_QUOTES, 'UTF-8'); ?></span>
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <input type="number" class="cart-qty" name="qty[<?php echo htmlspecialchars($com['product_name'], ENT_QUOTES, 'UTF-8'); ?>]" value="<?php echo $cartQty; ?>" min="1">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <!-- Use a different button type or name so it doesn't submit main form? 
                                             Actually, separate delete handling below handles this via $_POST['delete_cart'] 
                                             We should close the main form before this if we want independent delete, 
                                             BUT nested forms are bad. 
                                             We can keep one form and use button names to distinguish actions. -->
                                        <button type="submit" name="delete_cart" value="<?php echo htmlspecialchars($com["product_name"], ENT_QUOTES, 'UTF-8'); ?>" class="icon_close" formnovalidate></button>
                                    </td>
                                </tr>
                            </tbody>
                                    <?php
                                    $lineTotal = (is_numeric($com["product_price"]) ? (float)$com["product_price"] : 0) * $cartQty;
                                    $total = $lineTotal + $total;
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <div class="shoping__cart__btns">
                        <a href="shop-grid.php" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6"></div>
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            <li>Subtotal <span id="cart-subtotal">$<?php echo number_format($total, 2); ?></span></li>
                            <li>Total <span id="cart-total">$<?php echo number_format($total, 2); ?></span></li>
                        </ul>
                        <!-- Checkout Button now acts as Update AND Proceed -->
                        <button type="submit" name="checkout_trigger" class="primary-btn">CHECKOUT</button>
                    </div>
                </div>
            </div>
            </form> <!-- End Main Form -->
        </div>
    </section>
    
    <!-- Mobile Bottom Nav & Footer ... -->
    <div class="mobile-bottom-nav">
         <!-- ... links ... -->
         <a href="./index.php" class="nav-item"><i class="fa fa-home"></i><span>Home</span></a>
         <a href="./shop-grid.php" class="nav-item"><i class="fa fa-shopping-bag"></i><span>Shop</span></a>
         <a href="./shoping-cart.php" class="nav-item active"><i class="fa fa-shopping-cart"></i><span>Cart</span></a>
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
    // ... Sticky Header Script ... 
    // ... Recalc Script ...
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
    if(isset($_POST["delete_cart"])) {
        $product_to_delete = $_POST["delete_cart"];
        $stmt= $pdo->prepare ("DELETE FROM shopping_cart WHERE product_name = ? AND user_email = ?");
        $stmt->execute([$product_to_delete, $_SESSION['email']]);
        echo '<script>window.location.href=window.location.href;</script>';
    }
    $_SESSION["total"]=$total;
?>