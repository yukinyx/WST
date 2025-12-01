<?php
session_start();
include "lib/functions.php";
$pdo = get_connection();

// 1. Check Authentication
if(!isset($_SESSION["email"]) || $_SESSION["email"] == "") {
    header('location:login.php');
    exit;
}

$email = $_SESSION["email"];

// 2. Handle Form Submission (Save Changes)
if(isset($_POST["save"])){

    $customer_name = $_POST["name"];
    $phone_number = $_POST["phone_number"];
    
    // Handle Image Upload
    if (isset($_FILES["profile"]["name"]) && $_FILES["profile"]["name"] != ""){
        $v1 = rand(1111,9999);
        $v2 = rand(1111,9999);
        $v3 = md5($v1.$v2);
        $fnm = $_FILES["profile"]["name"];
        $dst = "user-image/".$v3.$fnm;
        
        if(move_uploaded_file($_FILES["profile"]["tmp_name"], $dst)){
            $stmt = $pdo->prepare('UPDATE user SET IMG_URL = :dst WHERE email = :email');
            $stmt->execute(['dst' => $dst, 'email' => $email]);
        }
    }
    
    // Update Info
    $sql = "UPDATE user SET ";
    $params = [];
    $updates = [];


    if(!empty($phone_number)) { $updates[] = "phone_number = ?"; $params[] = $phone_number; }
    if(!empty($customer_name)) { $updates[] = "customerName = ?"; $params[] = $customer_name; }

    if(count($updates) > 0){
        $sql .= implode(", ", $updates) . " WHERE email = ?";
        $params[] = $email;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }
    
    // Redirect to self to refresh data and prevent form resubmission
    header('Location: profile.php');
    exit;
}

// 3. Handle Account Deactivation
if(isset($_POST['deactivate'])){
    $stmt=$pdo->prepare('DELETE FROM user WHERE email = ?');
    $stmt->execute([$email]);
    header('location:logout.php');
    exit;
}

// 4. Handle Cancel
if(isset($_POST['cancel'])){
    header('location:index.php');
    exit;
}

// 5. Fetch Current User Data (AFTER updates are processed)
$res=$pdo->prepare("SELECT * FROM user where email =?");
$res->execute([$email]);
$row = $res->fetch();

// Default image fallback
$profileImage = !empty($row["IMG_URL"]) ? $row["IMG_URL"] : 'img/default-user.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <!-- Use the same CSS as the rest of the site + FontAwesome -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f5f5f5;
            padding-bottom: 80px; /* Space for mobile nav */
        }
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .profile-img-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }
        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid #f9f9f9;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-upload {
            margin-top: 10px;
            font-size: 12px;
        }
        .form-label {
            font-weight: 600;
            color: #1c1c1c;
            margin-bottom: 8px;
        }
        .form-control {
            height: 45px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 24px;
            font-weight: 700;
            color: #1c1c1c;
            border-bottom: 1px solid #e1e1e1;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }
        .btn-primary {
            background: #7fad39;
            border: none;
            padding: 10px 30px;
        }
        .btn-primary:hover {
            background: #719a32;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
        }
        .top-nav-bar {
            background: #fff;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        /* Mobile Bottom Nav Styles */
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
        
        @media (max-width: 767px) {
            .mobile-bottom-nav { display: flex; }
            .profile-container { margin: 20px; padding: 20px; }
        }
    </style>
</head>
<body>

    <!-- Simple Top Bar for Desktop Navigation -->
    <div class="top-nav-bar d-none d-md-block">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="index.php"><img src="img/logo.png" style="max-width: 120px;"></a>
                <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>

    <!-- Main Profile Content -->
    <div class="container">
        <div class="profile-container">
            <h4 class="section-title">Account Settings</h4>
            
            <form method="POST" enctype="multipart/form-data">
                <!-- Profile Image Section -->
                <div class="profile-header">
                    <div class="profile-img-container">
                        <!-- Fallback image logic handled in PHP above -->
                        <img src="<?php echo $profileImage; ?>" class="profile-img" alt="Profile Photo">
                    </div>
                    <div class="upload-btn-wrapper">
                        <label class="btn btn-outline-dark btn-sm" for="upload-photo">
                            <i class="fa fa-camera"></i> Change Photo
                        </label>
                        <input type="file" name="profile" id="upload-photo" style="display:none;">
                    </div>
                    <small class="text-muted d-block mt-2">Allowed .png, .jpg. Max size 1MB</small>
                </div>

                <!-- Form Fields -->
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row["customerName"] ?? ''); ?>" placeholder="Enter your name">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($row["email"] ?? ''); ?>" readonly style="background-color: #e9ecef;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" value="<?php echo htmlspecialchars($row["phone_number"] ?? ''); ?>" placeholder="Enter phone number">
                    </div>
                    
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary" name="save">Save Changes</button>
                        <button type="submit" name="cancel" class="btn btn-light ml-2">Cancel</button>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deactivateModal">Deactivate Account</button>
                </div>
                
                <!-- Deactivate Modal Confirmation -->
                <div class="modal fade" id="deactivateModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Deactivation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to deactivate your account? This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" name="deactivate" class="btn btn-danger">Yes, Deactivate</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- MOBILE BOTTOM NAV -->
    <div class="mobile-bottom-nav">
        <a href="./index.php" class="nav-item">
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
        <a href="./profile.php" class="nav-item active">
            <i class="fa fa-user"></i>
            <span>Profile</span>
        </a>
    </div>

    <!-- JS Scripts -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    
</body>
</html>
