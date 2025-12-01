<?php
include "check_ath.php";
?>
<?php include "./template/top.php"; ?>
<?php include "lib/functions.php";
$pdo = get_connection();
?>
<style>
    /* --- MOBILE BOTTOM NAV --- */
    .mobile-bottom-nav {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #ffffff;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
        z-index: 99999;
        justify-content: space-around;
        padding: 10px 0;
        border-top: 1px solid #e1e1e1;
    }
    .mobile-bottom-nav .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #1c1c1c;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        width: 20%;
    }
    .mobile-bottom-nav .nav-item i { font-size: 18px; margin-bottom: 4px; color: #666; transition: 0.3s; }
    .mobile-bottom-nav .nav-item.active i, .mobile-bottom-nav .nav-item:hover i { color: #7fad39; }

    /* --- MOBILE TOP STICKY BAR --- */
    .mobile-sticky-top-bar {
        display: none;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        background: #fff;
        width: 100%;
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
                            <li class="active"><a href="./shop-grid.php">Shop</a></li>
                            <li><a href="./shoping-cart.php">Shopping Cart</a></li>
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
    
    <section class="hero hero-normal">
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
                            $res=$pdo->query("SELECT category_name FROM category");
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
  
    <section class="breadcrumb-section set-bg" data-setbg="img/batstateu-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Redmarket</h2>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Categories</h4>
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
                </div>
                <div class="col-lg-9 col-md-7">
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sort By</span>
                                    <select>
                                        <option value="0">Default</option>
                                        <option value="0">yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
								<?php
								$stmt = $pdo->query('SELECT COUNT(product_name) from product');
								$s=$stmt->fetch();
                                ?>    
								<h6><span><?php echo $s[0]; ?></span> Products found</h6>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <span class="icon_grid-2x2"></span>
                                    <span class="icon_ul"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
					    <?php
							$c=0;
							$res=$pdo->query("SELECT * FROM product");
							while($row = $res->fetch()){
								$loc="../admin_portal/admin/"
										?>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?php echo '../admin_portal/admin/'.$row['image_file_name']; ?>">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="shop-details.php?product=<?php echo $row["product_name"]; ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="#"><?php echo $row["product_name"]; ?></a></h6>
                                    <h5>$<?php echo $row["product_price"]; ?></h5>
                                </div>
                            </div>
                        </div>
						<?php $c=$c+1; } ?>
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
        <a href="./shop-grid.php" class="nav-item active">
            <i class="fa fa-shopping-bag"></i>
            <span>Shop</span>
        </a>
        <a href="./shoping-cart.php" class="nav-item">
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
