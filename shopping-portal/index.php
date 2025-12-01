<?php
include "check_ath.php";
?>
<?php include "lib/functions.php";
$pdo = get_connection();
?>
<?php include "./template/top.php"; ?>
<style>
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

    .mobile-bottom-nav .nav-item i {
        font-size: 18px;
        margin-bottom: 4px;
        color: #666;
        transition: 0.3s;
    }

    .mobile-bottom-nav .nav-item.active i,
    .mobile-bottom-nav .nav-item:hover i {
        color: #9c340bff; 
    }

    .mobile-sticky-top-bar {
        display: none; /* Hidden by default */
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        background: #fff;
        width: 100%;
    }

    .mobile-sticky-top-bar .search-wrapper {
        flex-grow: 1;
        margin-right: 15px;
    }

    .mobile-sticky-top-bar form {
        display: flex;
        width: 100%;
        position: relative;
    }

    .mobile-sticky-top-bar input {
        width: 100%;
        border: 1px solid #e1e1e1;
        padding: 8px 15px;
        padding-right: 40px; /* Space for button */
        border-radius: 20px;
        font-size: 14px;
        background: #f5f5f5;
        outline: none;
    }

    .mobile-sticky-top-bar button {
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 40px;
        background: transparent;
        border: none;
        color: #1c1c1c;
        border-radius: 0 20px 20px 0;
    }

    .mobile-sticky-top-bar .icons-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .mobile-sticky-top-bar .icons-wrapper a {
        position: relative;
        color: #1c1c1c;
        font-size: 20px;
    }

    /* -------------------------------------------
       RESPONSIVE & STICKY BEHAVIOR
    ------------------------------------------- */
    
    /* Default Sticky styles for Desktop */
    .sticky {
        position: fixed;
        top: 0;
        width: 100%;
        background: #ffffff;
        z-index: 9990;
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        animation: fadeInDown 0.5s;
    }

    @media (max-width: 991px) {
        .hero__search__phone {
            display: none !important; /* Hide big phone number */
        }
    }

    /* Mobile Only (Phones) */
    @media (max-width: 767px) {
        /* Center default header items */
        .header__logo { text-align: center; margin-bottom: 10px; }
        .header__cart { text-align: center; padding: 10px 0; }
        .humberger__open { left: 15px; top: 25px; }

        /* Show Bottom Nav */
        .mobile-bottom-nav { display: flex; }
        body { padding-bottom: 70px; } /* Prevent content from hiding behind nav */

        /* Sticky Logic for Mobile */
        .header.sticky {
            padding: 0; /* Remove default padding */
        }

        /* When Sticky: HIDE standard header elements */
        .header.sticky .header__logo,
        .header.sticky .header__menu,
        .header.sticky .header__cart,
        .header.sticky .humberger__open,
        .header.sticky .container { 
            display: none !important; 
        }

        /* When Sticky: SHOW custom mobile top bar */
        .header.sticky .mobile-sticky-top-bar {
            display: flex !important;
        }
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translate3d(0, -100%, 0); }
        to { opacity: 1; transform: none; }
    }
</style>

<body>

    <!-- Header Section Begin -->
    <header class="header" id="myHeader">
        <!-- This container holds the desktop/normal mobile view -->
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
                            <li class="active"><a href="./index.php">Home</a></li>
                            <li><a href="./shop-grid.php">Shop</a></li>
                            <li><a href="./shoping-cart.php">Shopping Cart</a></li>
                            <li><a href="./contact.php">Contact</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="header__cart">
                        <ul>
                            <!-- Normal view Cart -->
                            <li><a href="shoping-cart.php"><i class="fa fa-shopping-bag"></i></a></li>
                            <!-- Profile Icon (Desktop/Normal View) -->
                            <li><a href="./profile.php"><i class="fa fa-user"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="humberger__open">
                <i class="fa fa-bars"></i>
            </div>
        </div>

        <!-- MOBILE STICKY TOP BAR (Hidden by default, shown via CSS when sticky) -->
        <div class="mobile-sticky-top-bar">
            <!-- Search Bar (Left) -->
            <div class="search-wrapper">
                <form action="shop-grid.php" method="GET">
                    <input type="text" name="search" placeholder="Search products...">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <!-- Right Side Icons -->
            <div class="icons-wrapper">
                <!-- Cart -->
                <a href="shoping-cart.php">
                    <i class="fa fa-shopping-bag"></i>
                </a>
                <!-- Contact -->
                <a href="contact.php">
                    <i class="fa fa-envelope"></i>
                </a>
            </div>
        </div>
    </header>
   
     <section class="breadcrumb-section set-bg" data-setbg="img/batstateu-banner.png" style="margin-bottom: 20px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>RedMarket</h2>
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

            $res=$pdo->query("SELECT * FROM product");
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

    <!-- MOBILE STICKY BOTTOM NAVIGATION -->
    <div class="mobile-bottom-nav">
        <a href="./index.php" class="nav-item active">
            <i class="fa fa-home"></i>
            <span>Home</span>
        </a>
        <a href="./shop-grid.php" class="nav-item">
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
                if(window.innerWidth > 767) {
                    // Only resize logo on Desktop (since we hide it on mobile sticky)
                    logo.style.maxWidth = "120px";
                }
            } else {
                header.classList.remove("sticky");
                logo.style.maxWidth = "180px"; // Reset logo
            }
        }
    </script>

</body>

</html>
