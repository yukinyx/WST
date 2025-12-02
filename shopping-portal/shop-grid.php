<?php
include "check_ath.php";
include "lib/functions.php";
$pdo = get_connection();

// Fetch User Image for Header
$profile_img_header = 'img/logo.png'; // Default fallback
if(isset($_SESSION['email'])) {
    $uStmt = $pdo->prepare("SELECT IMG_URL FROM user WHERE email = ?");
    $uStmt->execute([$_SESSION['email']]);
    $uRow = $uStmt->fetch();
    if($uRow && !empty($uRow['IMG_URL'])) {
        $profile_img_header = $uRow['IMG_URL'];
    } else {
        $profile_img_header = "img/default-user.png"; // Fallback if logged in but no image
    }
}
?>
<?php include "./template/top.php"; ?>
<style>
    /* Mobile Styles from previous request */
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
    .mobile-bottom-nav .nav-item.active i, .mobile-bottom-nav .nav-item:hover i { color: #9c340bff; }
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
    .sticky { position: fixed; top: 0; width: 100%; background: #ffffff; z-index: 9990; box-shadow: 0 5px 10px rgba(0,0,0,0.1); animation: fadeInDown 0.5s; }
    @media (max-width: 991px) { .hero__search__phone { display: none !important; } }
    @media (max-width: 767px) {
        .header__logo { text-align: center; margin-bottom: 10px; }
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
        .header__cart { text-align: center; padding: 10px 0; }
        .humberger__open { left: 15px; top: 25px; }
        .mobile-bottom-nav { display: flex; }
        body { padding-bottom: 70px; }
        .header.sticky { padding: 0; }
        .header.sticky .header__logo, .header.sticky .header__menu, .header.sticky .header__cart, .header.sticky .humberger__open, .header.sticky .container { display: none !important; }
        .header.sticky .mobile-sticky-top-bar { display: flex !important; }
    }
    @keyframes fadeInDown { from { opacity: 0; transform: translate3d(0, -100%, 0); } to { opacity: 1; transform: none; } }
    
    /* Profile Image in Header Style */
    .header-profile-img {
        width: 20px; height: 20px; border-radius: 50%; object-fit: cover;
        border: 1px solid #ddd; margin-right: 0; vertical-align: middle;
    }
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
            <li class="active"><a href="./shop-grid.php" >Shop</a></li>
            <li><a href="./shoping-cart.php" >Shopping Cart</a></li>
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

    <!-- MOVED: Breadcrumb Section is now BEFORE the Hero Section -->
    <section class="breadcrumb-section set-bg" data-setbg="img/batstateu-banner.png" style="margin-bottom: 20px;">
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
            <h4>Search</h4>
            <form action="shop-grid.php" method="GET">
                <input type="text" name="search" placeholder="Search..." style="width: 100%; border: 1px solid #ddd; padding: 10px; color: #666;" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </form>
        </div>

        <div class="sidebar__item">
            
            <h4 class="d-none d-lg-block">Categories</h4>

            <div class="mobile-category-toggle d-lg-none" id="mobileCatToggle">
                <i class="fa fa-bars"></i>
                <span>Categories</span>
                <i class="fa fa-angle-down arrow-icon"></i>
            </div>

            <ul id="mobileCatList">
                <li>
                    <a href="shop-grid.php" class="<?php echo (!isset($_GET['category'])) ? 'active-category' : ''; ?>">
                        All Products
                    </a>
                </li>

                <?php
                // Fetch Categories
                $catStmt = $pdo->query("SELECT category_name FROM category");
                while ($catRow = $catStmt->fetch()) {
                    $isActive = (isset($_GET['category']) && $_GET['category'] == $catRow['category_name']) ? 'active-category' : '';
                ?>
                    <li>
                        <a href="shop-grid.php?category=<?php echo urlencode($catRow['category_name']); ?>" class="<?php echo $isActive; ?>">
                            <?php echo htmlspecialchars($catRow['category_name']); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

    </div>
</div>


            <div class="col-lg-9 col-md-7">
                <div class="row">
                    <?php
                        // --- YOUR ORIGINAL PHP LOGIC STARTS HERE ---
                        
                        // 1. Search Logic
                        if(isset($_GET['search']) && !empty($_GET['search'])){
                            $search_term = "%" . $_GET['search'] . "%";
                            $stmt = $pdo->prepare("SELECT * FROM product WHERE product_name LIKE ?");
                            $stmt->execute([$search_term]);
                        } 
                        // 2. Category Logic
                        else if(isset($_GET['category']) && !empty($_GET['category'])){
                            $cat = $_GET['category'];
                            $stmt = $pdo->prepare("SELECT * FROM product WHERE category_name = ?");
                            $stmt->execute([$cat]);
                        }
                        // 3. Default (Show All)
                        else {
                            $stmt = $pdo->prepare("SELECT * FROM product");
                            $stmt->execute();
                        }

                        $count = $stmt->rowCount();
                        
                        if($count == 0) {
                            echo "<div class='col-lg-12'><h5>No products found.</h5></div>";
                        }

                        while($row = $stmt->fetch()){
                    ?>
                        <div class="col-lg-4 col-md-6 col-sm-6">
                            <div class="product__item">
                                <div class="product__item__pic set-bg" data-setbg="<?php echo '../admin_portal/admin/'.$row['image_file_name']; ?>">
                                    <ul class="product__item__pic__hover">
                                        <li><a href="shop-details.php?product=<?php echo urlencode($row["product_name"]); ?>"><i class="fa fa-shopping-cart"></i></a></li>
                                    </ul>
                                </div>
                                <div class="product__item__text">
                                    <h6><a href="shop-details.php?product=<?php echo urlencode($row["product_name"]); ?>"><?php echo htmlspecialchars($row["product_name"]); ?></a></h6>
                                    <h5>$<?php echo htmlspecialchars($row["product_price"]); ?></h5>
                                </div>
                            </div>
                        </div>
                    <?php } // End While Loop ?>
                    
                </div>
            </div>
            </div>
    </div>
</section>

    
    <!-- MOBILE BOTTOM NAV -->
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

    <script>
    var lastScrollTop = 0;
    var header = document.getElementById("myHeader");

    window.addEventListener("scroll", function() {
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
       
        if (scrollTop <= 0) {
            header.classList.remove("header-hidden");
            header.classList.remove("header-visible");
            header.style.position = "relative"; 
            return;
        }

        if (scrollTop > lastScrollTop) {
        
            header.classList.remove("header-visible");
            header.classList.add("header-hidden");
        } else {
            header.style.position = "fixed"; 
            header.classList.remove("header-hidden");
            header.classList.add("header-visible");
        }
        
        lastScrollTop = scrollTop;
    });
</script>

<script>
    $(document).ready(function() {
        // Toggle Category List on Mobile
        $("#mobileCatToggle").on("click", function() {
            $("#mobileCatList").slideToggle(300);
            $(this).find(".arrow-icon").toggleClass("fa-angle-down fa-angle-up");
        });

        // --- FIX: Reset styles when resizing to desktop ---
        $(window).resize(function() {
            if ($(window).width() > 991) {
                // Remove the "display: none" added by jQuery
                $("#mobileCatList").css("display", ""); 
            }
        });
    });
</script>

</body>
</html>