<?php
session_start();
include "lib/functions.php";
$pdo = get_connection();

if (isset($_POST["register"])) {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    $phone_number = $_POST["phone_number"];

    // 1. Email Domain Validation
    $allowed_domain = '@g.batstate-u.edu.ph';
    // Check if email ends with the allowed domain
    if (substr($email, -strlen($allowed_domain)) !== $allowed_domain) {
        ?>
        <script>
            alert("Registration restricted. You must use a <?php echo $allowed_domain; ?> email address.");
            window.location.href = "register_form.php"; // Redirect back to login/register
        </script>
        <?php
        exit(); // Stop execution
    }

    // 2. Password Match Validation
    if ($password != $cpassword) {
        echo '<script>alert("Passwords do not match!")</script>';
    } else {
        // 3. Check if email already exists
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $count = $stmt->rowCount();

        if ($count > 0) {
            ?>
            <script type="text/javascript">
                alert("The Email Address is already taken.");
            </script>
            <?php
        } else {
            // 4. Insert User
            $stmt = $pdo->prepare("INSERT INTO user(customerName, email, password, phone_number) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$username, $email, $password, $phone_number])) {
                ?>
                <script type="text/javascript">
                    alert("Registration Successful! Please Login.");
                    window.location.href = "login.php";
                </script>
                <?php
            }
        }
    }
}
header('location: register_form.php');
?>
