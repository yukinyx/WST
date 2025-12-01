<?php
session_start();
if(isset( $_SESSION["username"]) &&  $_SESSION["username"] != "" ) {
    if(isset($_SESSION["total"]) && $_SESSION["total"] != 0){
        $DateAndTime = date('m-d-Y h:i a', time());

    }else{
        header('location:shoping-cart.php');
    }

} else {
    header('location:login.php');
}


?>
<?php include "lib/functions.php";
$pdo = get_connection();
?>
<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" type="text/css">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<style>
body {
    background: #eee
}
</style>
</head>
<div class="container mt-5">
    <div class="d-flex justify-content-center row">
        <div class="col-md-8">
            <div class="p-3 bg-white rounded">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="text-uppercase">Invoice</h1>
                        <div class="billed"><span class="font-weight-bold text-uppercase">Billed:</span><span class="ml-1"><?php echo $_SESSION["username"]; ?></span></div>
                        <div class="billed"><span class="font-weight-bold text-uppercase">Date:</span><span class="ml-1"><?php echo $DateAndTime; ?></span></div>
                        <div class="billed"><span class="font-weight-bold text-uppercase">Order ID:</span><span class="ml-1">#<?php echo rand(1223,8371); ?></span></div>
                    </div>
                    <div class="col-md-6 text-right mt-3">
                        <h4 style="color:red !important;" class="text-danger mb-0">RedMarket</h4><span>g.batstate-u.edu.ph</span>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Unit</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <?php
                            $runningTotal = 0.0;
                            if (isset($_SESSION['last_order_items']) && is_array($_SESSION['last_order_items'])) {
                                foreach ($_SESSION['last_order_items'] as $it) {
                                    $prodName = isset($it['product_name']) ? $it['product_name'] : '';
                                    $qty = isset($it['quantity']) ? (int)$it['quantity'] : 1;
                                    $price = isset($it['unit_price']) ? (float)$it['unit_price'] : 0.0;
                                    $line = isset($it['line_total']) ? (float)$it['line_total'] : ($qty * $price);
                                    $runningTotal += $line;
                            ?>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlspecialchars($prodName, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo $qty; ?></td>
                                    <td><?php echo number_format($price, 2); ?></td>
                                    <td><?php echo number_format($line, 2); ?></td>
                                </tr>
                            </tbody>
                            <?php }
                            } else { 
                                $res = $pdo->prepare("SELECT sc.product_name, COALESCE(sc.quantity,1) AS quantity, p.product_price FROM shopping_cart sc JOIN product p ON sc.product_name = p.product_name WHERE sc.user_email = ?");
                                $res->execute([$_SESSION["email"]]);
                                while ($row = $res->fetch()) {
                                    $prodName = $row['product_name'];
                                    $qty = isset($row['quantity']) ? (int)$row['quantity'] : 1;
                                    $price = isset($row['product_price']) ? (float)$row['product_price'] : 0.0;
                                    $line = $qty * $price;
                                    $runningTotal += $line;
                            ?>
                            <tbody>
                                <tr>
                                    <td><?php echo htmlspecialchars($prodName, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo $qty; ?></td>
                                    <td><?php echo number_format($price, 2); ?></td>
                                    <td><?php echo number_format($line, 2); ?></td>
                                </tr>
                            </tbody>
                            <?php }
                            }
                                ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td><?php echo number_format($runningTotal, 2); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <form method="post" action="">
                <div class="text-right mb-3"><a href=""><button name="check-out" class="btn btn-danger btn-sm mr-5" type="submit">Home</button></a></div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    if(isset($_POST["check-out"])) {
        if (isset($_SESSION['last_order_items'])) {
            unset($_SESSION['last_order_items']);
        }
        if (isset($_SESSION['total'])) {
            unset($_SESSION['total']);
        }
        header('location:index.php');
    }

?>