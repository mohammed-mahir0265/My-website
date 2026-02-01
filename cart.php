<?php
session_start();
include "db.php";


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];


if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id=? AND product_id=?");
    $stmt->execute([$user_id, $product_id]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id=? AND product_id=?");
        $stmt->execute([$user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }
}


if (isset($_POST['update_quantity'])) {
    $product_id = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    if ($quantity <= 0) {
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=? AND product_id=?");
        $stmt->execute([$user_id, $product_id]);
    } else {
        $stmt = $conn->prepare("UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?");
        $stmt->execute([$quantity, $user_id, $product_id]);
    }
}


if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id=? AND product_id=?");
    $stmt->execute([$user_id, $product_id]);
}


$stmt = $conn->prepare("
    SELECT c.product_id, c.quantity, p.name, p.price, p.images
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shopping Cart | Velvet Vogue</title>
<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background:#f9f9f9;color:#333;line-height:1.6;}
header{background:#111;padding:15px 40px;position:sticky;top:0;z-index:100;}
.navbar{display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto;}
.logo{color:#fff;font-size:28px;font-weight:700;text-decoration:none;}
.nav-links{list-style:none;display:flex;gap:25px;}
.nav-links li a{color:#fff;text-decoration:none;padding:8px 15px;border-radius:4px;transition:0.3s;}
.nav-links li a:hover{background:#c62828;}
.page-title{font-size:36px;margin-bottom:30px;color:#111;text-align:center;}
.cart-page{max-width:1200px;margin:0 auto;padding:40px 20px;min-height:70vh;}
.cart-container{display:flex;flex-wrap:wrap;gap:30px;}
.cart-items{flex:1;min-width:300px;}
.cart-item{display:flex;background:#fff;border-radius:8px;padding:20px;margin-bottom:20px;box-shadow:0 5px 15px rgba(0,0,0,0.1);align-items:center;transition:transform 0.3s;}
.cart-item:hover{transform:translateY(-3px);}
.cart-item img{width:120px;height:120px;object-fit:cover;border-radius:4px;margin-right:25px;}
.cart-item-details{flex-grow:1;}
.cart-item-details h3{margin-bottom:10px;font-size:20px;color:#111;}
.cart-item-price{font-size:20px;font-weight:700;color:#c62828;margin-bottom:15px;}
.cart-quantity{display:flex;align-items:center;gap:10px;}
.quantity-btn{background:#f1f1f1;border:none;width:80px;height:35px;border-radius:10%;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;transition:0.3s;}
.quantity-btn:hover{background:#ddd;}
.quantity{font-size:18px;font-weight:600;min-width:30px;text-align:center;}
.remove-item{background:#ff5252;color:#fff;border:none;padding:10px 20px;border-radius:4px;cursor:pointer;font-weight:500;transition:0.3s;}
.remove-item:hover{background:#ff0000;}
.cart-summary{background:#fff;border-radius:8px;padding:30px;box-shadow:0 5px 15px rgba(0,0,0,0.1);min-width:300px;height:fit-content;position:sticky;top:100px;}
.cart-summary h3{margin-bottom:25px;font-size:24px;color:#111;border-bottom:2px solid #eee;padding-bottom:15px;}
.summary-row{display:flex;justify-content:space-between;margin-bottom:20px;padding-bottom:15px;border-bottom:1px solid #eee;}
.summary-label{color:#666;}
.summary-value{font-weight:600;color:#111;}
.total-row{font-size:22px;font-weight:700;color:#111;margin-top:20px;padding-top:20px;border-top:2px solid #eee;}
.checkout-btn{background:#c62828;color:#fff;border:none;padding:18px;border-radius:4px;font-size:18px;font-weight:600;cursor:pointer;width:100%;margin-top:25px;transition:0.3s;}
.checkout-btn:hover{background:#a71c1c;}
.continue-shopping{display:inline-block;background:#111;color:#fff;padding:15px 30px;border-radius:4px;text-decoration:none;font-weight:600;margin-top:20px;transition:0.3s;text-align:center;width:100%;}
.continue-shopping:hover{background:#333;}
.empty-cart{text-align:center;padding:60px 20px;background:#fff;border-radius:8px;box-shadow:0 5px 15px rgba(0,0,0,0.1);margin:50px auto;max-width:500px;}
.cart-actions{display:flex;gap:15px;justify-content:center;flex-wrap:wrap;}
.action-btn{padding:12px 25px;border-radius:4px;text-decoration:none;font-weight:600;transition:0.3s;}
.action-btn.primary{background:#c62828;color:#fff;}
.action-btn.primary:hover{background:#a71c1c;}
.action-btn.secondary{background:#111;color:#fff;}
.action-btn.secondary:hover{background:#333;}
footer{background:#111;color:#fff;text-align:center;padding:30px;margin-top:60px;}
footer p{font-size:16px;opacity:0.8;}
</style>
</head>
<body>
<?php include "header.php"; ?>

<section class="cart-page">
    <h1 class="page-title">Your Shopping Cart</h1>

    <?php if(empty($cart_items)): ?>
        <div class="empty-cart">
            <h2>Your cart is empty</h2>
            <p>Looks like you haven't added any items to your cart yet.</p>
            <div class="cart-actions">
                <a href="home.php" class="action-btn primary">Continue Shopping</a>
                <a href="mens.php" class="action-btn secondary">Shop Men's</a>
                <a href="womens.php" class="action-btn secondary">Shop Women's</a>
                <a href="kids.php" class="action-btn secondary">Shop Kids</a>
            </div>
        </div>
    <?php else: ?>
        <div class="cart-container">
            <div class="cart-items">
                <?php $subtotal = 0; ?>
                <?php foreach($cart_items as $item): ?>
                    <?php 
                    $images = json_decode($item['images'], true);
                    $itemTotal = $item['price'] * $item['quantity']; 
                    $subtotal += $itemTotal; 
                    ?>
                    <div class="cart-item">
                        <img src="uploads/<?= $images[0] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="cart-item-details">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <div class="cart-item-price">Rs. <?= number_format($item['price'],2) ?></div>
                            <form method="POST" style="display:flex;align-items:center;gap:10px;">
                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width:50px;text-align:center;">
                                <button type="submit" name="update_quantity" class="quantity-btn">Update</button>
                            </form>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                            <button type="submit" name="remove_item" class="remove-item">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-summary">
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span class="summary-label">Subtotal (<?= array_sum(array_column($cart_items,'quantity')) ?> items)</span>
                    <span class="summary-value">Rs. <?= number_format($subtotal,2) ?></span>
                </div>
                <?php $shipping = $subtotal > 1000 ? 0 : 200; ?>
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value"><?= $shipping === 0 ? 'FREE' : 'Rs. '.number_format($shipping,2) ?></span>
                </div>
                <?php $tax = $subtotal * 0.08; ?>
                <div class="summary-row">
                    <span class="summary-label">Tax (8%)</span>
                    <span class="summary-value">Rs. <?= number_format($tax,2) ?></span>
                </div>
                <div class="summary-row total-row">
                    <span>Total</span>
                    <span>Rs. <?= number_format($subtotal+$shipping+$tax,2) ?></span>
                </div>

                <a href="home.php" class="checkout-btn">Proceed to Checkout</a>
                <a href="home.php" class="continue-shopping">Continue Shopping</a>
            </div>
        </div>
    <?php endif; ?>
</section>

<footer>
    <p>© 2026 Velvet Vogue. All rights reserved.</p>
</footer>
</body>
</html>
