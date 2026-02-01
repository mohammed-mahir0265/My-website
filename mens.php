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

    $_SESSION['cart_message'] = "Product added to cart!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Men's Collection | Velvet Vogue</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;}
body{background:#f9f9f9;color:#333;line-height:1.6;}
header{background:#111;padding:15px 40px;position:sticky;top:0;z-index:100;}
.navbar{display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto;}
.logo{color:#fff;font-size:28px;font-weight:700;text-decoration:none;}
.nav-links{list-style:none;display:flex;gap:25px;}
.nav-links li a{color:#fff;text-decoration:none;padding:8px 15px;border-radius:4px;transition:0.3s;}
.nav-links li a:hover{background:#c62828;}
.page-header{text-align:center;padding:60px 20px;color:white;background:linear-gradient(rgba(0,0,0,0.6),rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0') center/cover no-repeat;}
.page-header h1{font-size:42px;margin-bottom:10px;}
.page-header p{font-size:18px;color:#eee;}
.page-content{max-width:1200px;margin:0 auto;padding:0 40px 60px;}
.products-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:30px;margin-top:40px;}
.product-card{background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,0.1);position:relative;transition:0.3s;}
.product-card:hover{transform:translateY(-5px);}
.product-card img{width:100%;height:300px;object-fit:cover;}
.product-info{padding:15px;text-align:center;}
.product-info h3{font-size:20px;margin-bottom:5px;}
.product-info .price{color:#c62828;font-weight:700;margin-bottom:10px;}
.add-to-cart-btn{background:#111;color:#fff;border:none;padding:10px 20px;border-radius:5px;cursor:pointer;width:100%;transition:0.3s;}
.add-to-cart-btn:hover{background:#c62828;}
footer{text-align:center;padding:30px;background:#111;color:#fff;margin-top:40px;}
.back-home{display:inline-block;margin-top:40px;color:#c62828;text-decoration:none;font-weight:500;font-size:16px;padding:10px 20px;border:1px solid #c62828;border-radius:4px;transition:0.3s;}
.back-home:hover{background:#c62828;color:white;}
@media(max-width:768px){.products-grid{grid-template-columns:1fr;}}
.cart-message{color:green;text-align:center;margin:15px 0;font-weight:600;}
</style>
</head>
<body>

<?php include "header.php"; ?>

<?php

if(isset($_SESSION['cart_message'])) {
    echo '<p class="cart-message">'.$_SESSION['cart_message'].'</p>';
    unset($_SESSION['cart_message']);
}
?>

<section class="page-header">
    <h1>Men's Collection</h1>
    <p>Modern styles for everyday confidence</p>
</section>

<section class="page-content">

    <h2 style="text-align:center;font-size:32px;margin-bottom:20px;">Men Collection</h2>

    <div class="products-grid">
        <?php
        $stmt = $conn->prepare("SELECT * FROM products WHERE category='Men'");
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)):
            $images = json_decode($row['images'], true);
        ?>
        <div class="product-card">
            <img src="uploads/<?= $images[0] ?>" alt="<?= htmlspecialchars($row['name']) ?>">
            <div class="product-info">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p class="price">Rs. <?= number_format($row['price'],2) ?></p>
                <form method="POST" action="">
                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($row['name']) ?>">
                    <input type="hidden" name="product_price" value="<?= $row['price'] ?>">
                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <div style="text-align:center;margin-top:40px;">
        <a href="home.php" class="back-home">← Back to Home</a>
    </div>
</section>

<footer>
    <p>© 2026 Velvet Vogue. All rights reserved.</p>
</footer>

</body>
</html>
