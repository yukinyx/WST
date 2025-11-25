<?php
session_start();
include "lib/functions.php";
$pdo = get_connection();
$DateAndTime = date('m-d-Y h:i a', time());

$name=$pdo->prepare("select UserId from user where email =?");
$name->execute([$_SESSION["email"]]);
$id_name=$name->fetch();
if (isset($_POST["order"])) {
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
        $cartStmt = $pdo->prepare("SELECT sc.product_name, sc.quantity AS qty, p.product_price AS price FROM shopping_cart sc JOIN product p ON sc.product_name = p.product_name WHERE sc.user_email = ?");
    } else {
        
        $cartStmt = $pdo->prepare("SELECT sc.product_name, 1 AS qty, p.product_price AS price FROM shopping_cart sc JOIN product p ON sc.product_name = p.product_name WHERE sc.user_email = ?");
    }
    $cartStmt->execute([$_SESSION["email"]]);
    $totalQty = 0;
    $totalCost = 0.0;
    $items = [];
    while ($r = $cartStmt->fetch()) {
        $q = isset($r['qty']) ? (int)$r['qty'] : 1;
        $price = isset($r['price']) ? (float)$r['price'] : 0.0;
        $line = $q * $price;
        $totalQty += $q;
        $totalCost += $line;
        $items[] = [
            'product_name' => $r['product_name'],
            'quantity' => $q,
            'unit_price' => $price,
            'line_total' => $line,
        ];
    }

     $stmt = $pdo->prepare("INSERT INTO `order` (quantity, data_created, total_cost, customer_id) VALUES (?,?,?,?)");
    $stmt->execute([$totalQty, $DateAndTime, $totalCost, $id_name[0]]);

    $orderId = (int)$pdo->lastInsertId();

    if (!empty($items)) {
        $lineStmt = $pdo->prepare("INSERT INTO order_items (order_id, product_name, quantity, unit_price, line_total) VALUES (?,?,?,?,?)");
        foreach ($items as $it) {
            $lineStmt->execute([
                $orderId,
                $it['product_name'],
                $it['quantity'],
                number_format($it['unit_price'], 2, '.', ''),
                number_format($it['line_total'], 2, '.', '')
            ]);
        }
    }

    $_SESSION['last_order_items'] = $items;
    $_SESSION['total'] = $totalCost;

    $del = $pdo->prepare("DELETE FROM shopping_cart WHERE user_email = ?");
    $del->execute([$_SESSION["email"]]);

    header('location:recipt.php');

}
