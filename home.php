<?php
session_start(); 
include "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Velvet Vogue | Fashion for All</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        header {
            background-color: #111;
            padding: 15px 40px;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: auto;
        }

        .logo {
            color: white;
            font-size: 28px;
            font-weight: 700;
            text-decoration: none;
        }

        .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .nav-links a:hover {
            background-color: #c62828;
        }

        .hero {
            height: 80vh;
            background:
                linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('https://images.unsplash.com/photo-1441986300917-64674bd600d8')
                center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-content {
            text-align: center;
            color: white;
        }

        .hero-content h2 {
            font-size: 60px;
            letter-spacing: 4px;
        }

        .hero-content p {
            font-size: 22px;
            margin: 20px 0;
        }

        .hero-content button {
            padding: 15px 40px;
            background-color: #c62828;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }

        .collections {
            padding: 80px 40px;
            max-width: 1200px;
            margin: auto;
        }

        .collections h2 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 50px;
        }

        .collection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .collection-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s;
        }

        .collection-card:hover {
            transform: translateY(-8px);
        }

        .collection-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .card-content {
            padding: 25px;
            text-align: center;
        }

        .card-content h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .price {
            color: #c62828;
            font-weight: 700;
            margin-top: 10px;
        }

        footer {
            background-color: #111;
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 60px;
        }

        @media (max-width: 768px) {
            .hero-content h2 {
                font-size: 40px;
            }
        }
    </style>
</head>

<body>

<?php include "header.php"; ?>


<section class="hero">
    <div class="hero-content">
        <h2>VELVET VOGUE</h2>
        <p>Fashion for Men, Women & Kids</p>
        <a href="#collections"><button>Shop Now</button></a>
    </div>
</section>

<section class="collections" id="collections">
    <h2>Featured Products</h2>

    <div class="collection-grid">

        

        <a href="kids.php" class="collection-card">
            <img src="https://images.unsplash.com/photo-1766918780914-5df4a5a98c44?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGtpZHMlMjBjbG90aCUyMGNvbGxlY3Rpb258ZW58MHx8MHx8fDA%3D" alt="">
            <div class="card-content">
                <h3>Kids Collections</h3>
                <p>Comfortable, playful, and stylish outfits for your little ones</p>
            </div>
        </a>

        <a href="mens.php" class="collection-card">
            <img src="https://media.istockphoto.com/id/1293366109/photo/this-one-match-perfect-with-me.webp?a=1&b=1&s=612x612&w=0&k=20&c=e0bOeR004rMmcAvXUYAIiJZJ01pgAFX7ThFqfzyvMac=" alt="">
            <div class="card-content">
                <h3>Mens Collections</h3>
                <p>Modern styles for everyday confidence</p>
            </div>
        </a>

        <a href="womens.php" class="collection-card">
            <img src="https://plus.unsplash.com/premium_photo-1675106698071-0e5560734ad1?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NXx8d29tZW4lMjBjbG90aHxlbnwwfHwwfHx8MA%3D%3D" alt="">
            <div class="card-content">
                <h3>Womens Collections</h3>
                <p>Elegant, trendy fashion for the modern woman. Discover styles that empower and inspire.</p>
            </div>
        </a>


    </div>
</section>

<footer>
    <p>© 2026 Velvet Vogue. All rights reserved.</p>
</footer>

</body>
</html>
