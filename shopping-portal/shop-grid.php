<?php
include "check_ath.php";
?>
<?php include "./template/top.php"; ?>
<?php include "lib/functions.php";
$pdo = get_connection();
?>
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
  
    <section class="breadcrumb-section set-bg" data-setbg="img/batstateu-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Redmarket</h2>
                        <div class="breadcrumb__option">
                            <a href="./index.php">Home</a>
                            <span class="breadcrumb-separator"></span>
                            <span>Shop</span>
                        </div>
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
                            <h4>Department</h4>
                            
                            <ul>
                            <?php
                            $c=0;
                            $res=$pdo->query("SELECT 	category_name FROM category");
                            while($row = $res->fetch()){

                                ?>
                            <li id="echo $c;"><a href="#"><?php echo $row["category_name"]; ?></a></li>
                            <?php $c=$c+1; } ?>

                        </ul>

                        </div>
                        <div class="sidebar__item">
                            <h4>Price</h4>
                            <div class="price-range-wrap">
                                <div class="price-range ui-slider ui-corner-all ui-slider-horizontal ui-widget ui-widget-content"
                                    data-min="10" data-max="540">
                                    <div class="ui-slider-range ui-corner-all ui-widget-header"></div>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                    <span tabindex="0" class="ui-slider-handle ui-corner-all ui-state-default"></span>
                                </div>
                                <div class="range-slider">
                                    <div class="price-input">
                                        <input type="text" id="minamount">
                                        <input type="text" id="maxamount">
                                    </div>
                                </div>
                            </div>
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
							
							$res=$pdo->query("SELECT *  FROM product");
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