
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
                            <li class="active"><a href="./shop-grid.php">Shop</a></li>
                            <li><a href="./shoping-cart.php">Shopping Cart</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>

                            <li><a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i> <span>3</span></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span></span></div>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>
    </header>
    <!-- Header Section End -->

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
                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    <input type="number" name="quantity" value="1" min="1">
                                </div>
                            </div>
                        </div>
                        <form method="post" action="">
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


</body>

</html>
<?php
if(isset($_POST["add_to_cart"])) {
    $count =0;
    $x_value=$_POST["the_id"];
    $res = $pdo->prepare("SELECT * FROM shopping_cart where product_name=? and user_email=? ");
    $res ->execute([$x_value,$_SESSION["email"]]);
    $count =$res->rowCount();
    if($count >0) {
        // If item already exists in cart, update its quantity to the posted value (if DB supports quantity)
        $qty = isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? (int)$_POST['quantity'] : 1;
        $hasQtyCol = false;
        try {
            $colCheck = $pdo->query("SHOW COLUMNS FROM shopping_cart LIKE 'quantity'");
            if ($colCheck && $colCheck->rowCount() > 0) {
                $hasQtyCol = true;
            }
        } catch (Exception $e) {
            $hasQtyCol = false;
        }

        if ($hasQtyCol) {
    // CHANGE: Use 'quantity = quantity + ?' to add to existing amount
    $update = $pdo->prepare("UPDATE shopping_cart SET quantity = quantity + ? WHERE product_name = ? AND user_email = ?");
    $update->execute([$qty, $x_value, $_SESSION['email']]);
    ?>
            <script type="text/javascript">
                document.getElementById("error").style.display="none";
                document.getElementById("success").style.display="block";
                setTimeout(function () { window.location.href=window.location.href; },1000);
            </script>
            <?php
        } else {
            // Quantity not supported in DB - keep previous behavior (show error)
            ?>
            <script type="text/javascript">
                document.getElementById("success").style.display="none";
                document.getElementById("error").style.display="block";
                setTimeout(function () { window.location.href=window.location.href; },1000);
            </script>
            <?php
        }
    } else {
        $product_name = $_POST["the_id"];
        // Get quantity from POST (fallback to 1)
        $qty = isset($_POST['quantity']) && is_numeric($_POST['quantity']) && $_POST['quantity'] > 0 ? (int)$_POST['quantity'] : 1;

        // If the shopping_cart table has a quantity column we'll insert it, otherwise fall back to previous behaviour.
        $hasQtyCol = false;
        try {
            $colCheck = $pdo->query("SHOW COLUMNS FROM shopping_cart LIKE 'quantity'");
            if ($colCheck && $colCheck->rowCount() > 0) {
                $hasQtyCol = true;
            }
        } catch (Exception $e) {
            $hasQtyCol = false;
        }

        if ($hasQtyCol) {
            $stmt = $pdo->prepare("INSERT INTO shopping_cart (product_name,user_email,quantity) VALUES (?,?,?)");
            $stmt->execute([$product_name, $_SESSION["email"], $qty]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO shopping_cart (product_name,user_email) value(?,?) ");
            $stmt->execute([$product_name, $_SESSION["email"]]);
        }
        ?>
        <script type="text/javascript">

            document.getElementById("success").style.display="block";
            document.getElementById("error").style.display="none";
            setTimeout(function () {

                window.location.href=window.location.href;

            },1000);
        </script>
            <?php

    }
        ?>




<?php
}?>