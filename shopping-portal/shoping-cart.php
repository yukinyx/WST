<?php
include "check_ath.php";
?>
<?php include "lib/functions.php";
$pdo = get_connection();
?>
<?php include "./template/top.php"; ?>
<style>
    /* --- MOBILE BOTTOM NAV --- */
    .mobile-bottom-nav {
        display: none; position: fixed; bottom: 0; left: 0; width: 100%;
        background: #ffffff; box-shadow: 0 -2px 10px rgba(0,0,0,0.1); z-index: 99999;
        justify-content: space-around; padding: 10px 0; border-top: 1px solid #e1e1e1;
    }
    .mobile-bottom-nav .nav-item {
        display: flex; flex-direction: column; align-items: center; text-decoration: none;
        color: #1c1c1c; font-size: 10px; font-weight: 600; text-transform: uppercase; width: 20%;
    }
    .mobile-bottom-nav .nav-item i { font-size: 18px; margin-bottom: 4px; color: #666; transition: 0.3s; }
    .mobile-bottom-nav .nav-item.active i, .mobile-bottom-nav .nav-item:hover i { color: #7fad39; }

    /* --- MOBILE TOP STICKY BAR --- */
    .mobile-sticky-top-bar {
        display: none; align-items: center; justify-content: space-between;
        padding: 10px 15px; background: #fff; width: 100%;
    }
    .mobile-sticky-top-bar .search-wrapper { flex-grow: 1; margin-right: 15px; }
    .mobile-sticky-top-bar form { display: flex; width: 100%; position: relative; }
    .mobile-sticky-top-bar input {
        width: 100%; border: 1px solid #e1e1e1; padding: 8px 15px; padding-right: 40px;
        border-radius: 20px; font-size: 14px; background: #f5f5f5; outline: none;
    }
    .mobile-sticky-top-bar button {
        position: absolute; right: 0; top: 0; height: 100%; width: 40px;
        background: transparent; border: none; color: #1c1c1c; border-radius: 0 20px 20px 0;
    }
    .mobile-sticky-top-bar .icons-wrapper { display: flex; align-items: center; gap: 15px; }
    .mobile-sticky-top-bar .icons-wrapper a { position: relative; color: #1c1c1c; font-size: 20px; }
    .mobile-sticky-top-bar .qty-badge {
        position: absolute; top: -5px; right: -8px; background: #7fad39;
        color: #fff; font-size: 9px; height: 15px; width: 15px;
        line-height: 15px; text-align: center; border-radius: 50%; font-weight: bold;
    }

    /* --- STICKY & RESPONSIVE --- */
    .sticky { position: fixed; top: 0; width: 100%; background: #ffffff; z-index: 9990; box-shadow: 0 5px 10px rgba(0,0,0,0.1); animation: fadeInDown 0.5s; }
    @media (max-width: 991px) { .hero__search__phone { display: none !important; } }
    @media (max-width: 767px) {
        .header__logo { text-align: center; margin-bottom: 10px; }
        .header__cart { text-align: center; padding: 10px 0; }
        .humberger__open { left: 15px; top: 25px; }
        .mobile-bottom-nav { display: flex; }
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
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="./index.php">
                            <img id="headerLogo" src="img/logo.png" alt="logo" class="img-fluid" style="max-width: 180px; height: auto; transition: all 0.3s;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu">
                        <ul>
                            <li><a href="./index.php">Home</a></li>
                            <li><a href="./shop-grid.php">Shop</a></li>
                            <li class="active"><a href="./shoping-cart.php">Shopping Cart</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i> <span><?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : ''; ?></span></a></li>
                            <li><a href="./profile.php"><i class="fa fa-user"></i></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span><?php if (isset($_SESSION["total"])) echo "$".number_format($_SESSION["total"], 2); ?></span></div>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>

        <div class="mobile-sticky-top-bar">
            <div class="search-wrapper">
                <form action="shop-grid.php" method="GET">
                    <input type="text" name="search" placeholder="Search products...">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <div class="icons-wrapper">
                <a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i><span class="qty-badge"><?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : ''; ?></span></a>
                <a href="contact.php"><i class="fa fa-envelope"></i></a>
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
                            if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
                                if (isset($_SESSION['username']) && $_SESSION['username'] !== '') {
                                    $emailStmt = $pdo->prepare("SELECT email FROM user WHERE customerName = ? LIMIT 1");
                                    $emailStmt->execute([$_SESSION['username']]);
                                    $emailRow = $emailStmt->fetch();
                                    if ($emailRow && isset($emailRow['email'])) {
                                        $_SESSION['email'] = $emailRow['email'];
                                    } else {
                                        header('Location: login_form.php');
                                        exit;
                                    }
                                } else {
                                    header('Location: login_form.php');
                                    exit;
                                }
                            }
                            $hasQtyCol = false;
                            try {
                                $colCheck = $pdo->query("SHOW COLUMNS FROM shopping_cart LIKE 'quantity'");
                                if ($colCheck && $colCheck->rowCount() > 0) $hasQtyCol = true;
                            } catch (Exception $e) { $hasQtyCol = false; }

                            if ($hasQtyCol && isset($_POST['update_cart']) && isset($_POST['qty']) && is_array($_POST['qty'])) {
                                foreach ($_POST['qty'] as $pname => $q) {
                                    $qval = is_numeric($q) && $q > 0 ? (int)$q : 1;
                                    $updateStmt = $pdo->prepare("UPDATE shopping_cart SET quantity = ? WHERE product_name = ? AND user_email = ?");
                                    $updateStmt->execute([$qval, $pname, $_SESSION['email']]);
                                }
                                echo '<script>window.location.href=window.location.href;</script>';
                                exit;
                            }

                            if ($hasQtyCol) {
                                $res = $pdo->prepare ("SELECT product_name, quantity FROM shopping_cart WHERE user_email = ?");
                            } else {
                                $res = $pdo->prepare ("SELECT product_name FROM shopping_cart WHERE user_email = ?");
                            }
                            $res->execute([$_SESSION["email"]]);

                            echo '<form method="post" action="">';
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
                                        <div class="line-total" style="margin-top:6px; font-weight:600;">$<?php echo number_format(((is_numeric($com["product_price"]) ? (float)$com["product_price"] : 0) * $cartQty), 2); ?></div>
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty">
                                                <input type="number" class="cart-qty" name="qty[<?php echo htmlspecialchars($com['product_name'], ENT_QUOTES, 'UTF-8'); ?>]" value="<?php echo $cartQty; ?>" min="1">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <button type="submit" name="delete_cart" value="<?php echo htmlspecialchars($com["product_name"], ENT_QUOTES, 'UTF-8'); ?>" class="icon_close"></button>
                                    </td>
                                </tr>
                            </tbody>
                                    <?php
                                    $lineTotal = (is_numeric($com["product_price"]) ? (float)$com["product_price"] : 0) * $cartQty;
                                    $total = $lineTotal + $total;
                                }
                            }
                            echo '<div class="shoping__cart__btns">';
                            echo '<button type="submit" name="update_cart" class="primary-btn cart-btn cart-btn-right">Update Cart</button>';
                            echo '</div>';
                            echo '</form>';
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="index.php" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <ul>
                            <li>Subtotal <span id="cart-subtotal">$<?php echo number_format($total, 2); ?></span></li>
                            <li>Total <span id="cart-total">$<?php echo number_format($total, 2); ?></span></li>
                        </ul>
                        <form method="post" action="addorder.php">
                        <button name="order" class="primary-btn">CHECKOUT</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mobile-bottom-nav">
        <a href="./index.php" class="nav-item">
            <i class="fa fa-home"></i>
            <span>Home</span>
        </a>
        <a href="./shop-grid.php" class="nav-item">
            <i class="fa fa-shopping-bag"></i>
            <span>Shop</span>
        </a>
        <a href="./shoping-cart.php" class="nav-item active">
            <i class="fa fa-shopping-cart"></i>
            <span>Cart</span>
        </a>
        <a href="./contact.php" class="nav-item">
            <i class="fa fa-envelope"></i>
            <span>Contact</span>
        </a>
        <a href="./profile.php" class="nav-item">
            <i class="fa fa-user"></i>
            <span>Profile</span>
        </a>
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
    // Live update cart totals when quantity inputs change
    (function(){
        function formatMoney(n){ return '$' + Number(n).toFixed(2); }
        function recalcCart(){
            var subtotal = 0;
            document.querySelectorAll('tr').forEach(function(row){
                var priceEl = row.querySelector('.unit-price');
                var qtyEl = row.querySelector('.cart-qty');
                var lineEl = row.querySelector('.line-total');
                if(priceEl && qtyEl && lineEl){
                    var unit = parseFloat(priceEl.getAttribute('data-price')) || 0;
                    var qty = parseInt(qtyEl.value) || 1;
                    var line = unit * qty;
                    lineEl.textContent = formatMoney(line);
                    subtotal += line;
                }
            });
            var subtotalEl = document.getElementById('cart-subtotal');
            var totalEl = document.getElementById('cart-total');
            if(subtotalEl) subtotalEl.textContent = formatMoney(subtotal);
            if(totalEl) totalEl.textContent = formatMoney(subtotal);
        }
        document.addEventListener('input', function(e){
            if(e.target && e.target.classList && e.target.classList.contains('cart-qty')){ recalcCart(); }
        });
        document.addEventListener('click', function(e){
            var el = e.target;
            if (el && el.classList && el.classList.contains('qtybtn')) { setTimeout(recalcCart, 30); }
        });
        window.addEventListener('load', function(){ recalcCart(); });
    })();

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
    if(isset($_POST["delete_cart"])) {
        $product_to_delete = $_POST["delete_cart"];
        $stmt= $pdo->prepare ("DELETE FROM shopping_cart WHERE product_name = ? AND user_email = ?");
        $stmt->execute([$product_to_delete, $_SESSION['email']]);
        ?>
        <script type="text/javascript">
            setTimeout(function () { window.location.href=window.location.href; },1000);
        </script>
<?php
    }
    $_SESSION["total"]=$total;
?>
