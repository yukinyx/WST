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
                            <li class="active"><a href="./index.php">Home</a></li>
                            <li><a href="./shop-grid.php">Shop</a></li>
                            <li><a href="./shoping-cart.php">Shopping Cart</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3">
                    <div class="header__cart">
                        <ul>

                            <li><a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i> <span></span></a></li>
                        </ul>
                        <div class="header__cart__price">item: <span><?php if (isset($_SESSION["total"]))  echo $_SESSION["total"]; ?></span></div>
                    </div>
                </div>
            </div>
            
        </div>
    </header>
   
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories" id="categoriesOverlay">
                        <div class="hero__categories__all" onclick="toggleCategories()">
                            <i class="fa fa-bars"></i>
                            <span>Categories</span>
                        </div>

                        <ul class="categories-list">
                            <?php
                            $c=0;
                            $res=$pdo->query("SELECT 	category_name FROM category");
                            while($row = $res->fetch()){

                                ?>
                            <li id="echo $c;"><a href="shop-grid.php?category=<?php echo urlencode($row['category_name']); ?>"><?php echo $row["category_name"]; ?></a></li>
                            <?php $c=$c+1; } ?>

                        </ul>

                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="#">
                                <input type="text" placeholder="What do you need?">
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
    
    <section class="categories">
        <div class="container">
            <div class="row">
                <div class="categories__slider owl-carousel">
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="img/categories/cat-1.jpg">
                            <h5><a href="#">Laces</a></h5>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="img/categories/cat-2.jpg">
                            <h5><a href="#">Pins</a></h5>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="img/categories/cat-3.jpg">
                            <h5><a href="#">Tumblers</a></h5>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="img/categories/cat-4.jpg">
                            <h5><a href="#">Notebooks</a></h5>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="img/categories/cat-5.jpg">
                            <h5><a href="#">Textiles</a></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Featured Product</h2>
                    </div>
                    <div class="featured__controls">
                        <ul>
                            <li class="active" data-filter="*">All</li>
                            <?php
                            try {
                                $catStmt = $pdo->query("SELECT category_name FROM category");
                                while ($catRow = $catStmt->fetch()) {
                                    $catName = $catRow['category_name'];
                                    $catClass = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '', $catName));
                                    echo '<li data-filter=".' . htmlspecialchars($catClass, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($catName, ENT_QUOTES, 'UTF-8') . '</li>';
                                }
                            } catch (Exception $e) {
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row featured__filter">
            <?php
            $c=0;

            $res=$pdo->query("SELECT *  FROM product");
            while($row = $res->fetch()){
            $catValue = isset($row['category_name']) ? $row['category_name'] : '';
            $x = $catValue !== '' ? preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '', $catValue)) : 'Uncategorized';

            $loc = '../admin_portal/admin/';

            ?>

                <div class="col-lg-3 col-md-4 col-sm-6 mix <?php echo htmlspecialchars($x, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="<?php echo htmlspecialchars($loc . $row['image_file_name'], ENT_QUOTES, 'UTF-8'); ?>">
                            <ul class="featured__item__pic__hover">
                                <li><a href="shop-details.php?product=<?php echo urlencode($row['product_name']); ?>"><i class="fa fa-shopping-cart"></i></a></li>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <?php if ($catValue !== ''): ?>
                          
                            <?php endif; ?>
                            <h6><a href="#"><?php echo htmlspecialchars($row["product_name"], ENT_QUOTES, 'UTF-8'); ?></a></h6>
                            <h5>$<?php echo htmlspecialchars($row["product_price"], ENT_QUOTES, 'UTF-8'); ?></h5>
                        </div>
                    </div>
                </div>
                <?php $c=$c+1; } ?>


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



</body>

</html>