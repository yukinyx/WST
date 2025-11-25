<?php
include "check_ath.php";
?>
<?php include "lib/functions.php";
$pdo = get_connection();
?>
<?php include "./template/top.php"; ?>
<body>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="header__logo">
                        <a href="./index.php">
                            <img src="img/logo.png" alt="logo" class="img-fluid" style="max-width: 180px; height: auto;">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <nav class="header__menu">
                        <ul>
                            <li><a href="./index.php">Home</a></li>
                            <li> <a href="./shop-grid.php">Shop</a></li>
                            <li class="active"><a href="./shoping-cart.php">Shopping Cart</a>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>
                            <li><a href="#"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span>$150.00</span></div>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    
    <section class="breadcrumb-section set-bg" data-setbg="img/batstateu-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Shopping Cart</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.php">Home</a>
                            <span>Shopping Cart</span>
                        </div>
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
                            // Detect whether shopping_cart has a `quantity` column; if not, fall back to quantity=1 behaviour
                            $hasQtyCol = false;
                            try {
                                $colCheck = $pdo->query("SHOW COLUMNS FROM shopping_cart LIKE 'quantity'");
                                if ($colCheck && $colCheck->rowCount() > 0) {
                                    $hasQtyCol = true;
                                }
                            } catch (Exception $e) {
                                $hasQtyCol = false;
                            }

                            // Handle update cart action if posted and the DB supports quantity
                            if ($hasQtyCol && isset($_POST['update_cart']) && isset($_POST['qty']) && is_array($_POST['qty'])) {
                                foreach ($_POST['qty'] as $pname => $q) {
                                    $qval = is_numeric($q) && $q > 0 ? (int)$q : 1;
                                    $updateStmt = $pdo->prepare("UPDATE shopping_cart SET quantity = ? WHERE product_name = ? AND user_email = ?");
                                    $updateStmt->execute([$qval, $pname, $_SESSION['email']]);
                                }
                                // reload the page to reflect updates
                                echo '<script>window.location.href=window.location.href;</script>';
                                exit;
                            }

                            // Fetch products from cart. If DB doesn't have quantity, return product_name only and default qty to 1
                            if ($hasQtyCol) {
                                $res = $pdo->prepare ("SELECT product_name, quantity FROM shopping_cart WHERE user_email = ?");
                            } else {
                                $res = $pdo->prepare ("SELECT product_name FROM shopping_cart WHERE user_email = ?");
                            }
                            $res->execute([$_SESSION["email"]]);

                            // Start form for update quantities
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
                                    // line total = price * qty
                                    $lineTotal = (is_numeric($com["product_price"]) ? (float)$com["product_price"] : 0) * $cartQty;
                                    $total = $lineTotal + $total;

                                }
                            }
                            // Update cart and close form controls
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
        function formatMoney(n){
            return '$' + Number(n).toFixed(2);
        }

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
            if(e.target && e.target.classList && e.target.classList.contains('cart-qty')){
                recalcCart();
            }
        });
        
        document.addEventListener('click', function(e){
            var el = e.target;
            if (el && el.classList && el.classList.contains('qtybtn')) {
                setTimeout(recalcCart, 30);
            }
        });

        window.addEventListener('load', function(){ recalcCart(); });
    })();
    </script>


</body>

</html>

<?php

    if(isset($_POST["delete_cart"])) {
        $product_to_delete = $_POST["delete_cart"];

        // Delete only for this user
        $stmt= $pdo->prepare ("DELETE FROM shopping_cart WHERE product_name = ? AND user_email = ?");
        $stmt->execute([$product_to_delete, $_SESSION['email']]);
        ?>
        <script type="text/javascript">
            setTimeout(function () {
                window.location.href=window.location.href;
            },1000);
        </script>
<?php

    }
    $_SESSION["total"]=$total;
    $DateAndTime = date('m-d-Y h:i a', time());

/*
    $name=$pdo->prepare("select UserId from user where email =?");
    $name->execute([$_SESSION["email"]]);
    $id_name=$name->fetch();
    $count =0;

    /*$res = $pdo->prepare("select 	total_cost from swe2.order where customer_id=? ");

    $res ->execute([$id_name[0]]);
    $count =$res->rowCount();*/


/*

    if (isset($_POST["order"])) {

        $stmt = $pdo->prepare("insert into swe2.order(quantity,data_created,	total_cost,customer_id) values(?,?,?,?)");
        $stmt->execute([1, $DateAndTime, $total, $id_name[0]]);



    }
*/

?>