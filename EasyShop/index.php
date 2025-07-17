<?php
session_start();
$is_admin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyShop</title>
    <link rel="icon" type="image/png" href="images/logo2.jpg">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <a href="/">
           <img src="images/logo2.jpg" width="100" alt="">
        </a>
        <div class="nav-country">
            <img src="images/location icon.png" height="20" alt="">
            <div>
                <p>Deliver to</p>
                <h1>Bangladesh</h1>
            </div>
        </div>
    <form action="search.php" method="GET" class="nav-search">
        <!-----<div class="nav-search">----->
            <div class="nav-search-category">
                <p>All</p>
            </div>
            <input type="text" name="query" class="nav-search-input" placeholder="Search EasyShop">
             <button type="submit" style="background: none; border: none;">
            <img src="images/Search icon.png" class="nav-search-icon"  alt="">
        </button>
    </form>
        <div class="nav-language">
           <img src="images/US Flag icon.png" width="25px" alt="">
           <p>EN</p>
        </div>

        <?php if (isset($_SESSION['user_email'])): ?>
    <div class="nav-user">
        <img src="images/user.jpg" alt="User" width="30px">
        <a href="my_account.php" class="nav-text">
            <h1><?php echo $_SESSION['first_name']; ?></h1>
        </a>
    </div>
<?php else: ?>
    <div class="nav-user">
        <img src="images/user.jpg" alt="User" width="30px">
        <a href="account.php" class="nav-text">
            <h1>Sign in</h1>
        </a>
    </div>
<?php endif; ?>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.querySelector('.dropdown');
        if (dropdown) {
            dropdown.addEventListener('mouseover', () => {
                dropdown.querySelector('.dropdown-content').style.display = 'block';
            });
            dropdown.addEventListener('mouseout', () => {
                dropdown.querySelector('.dropdown-content').style.display = 'none';
            });
        }
    });
</script>


        <a href="cart.php" class="nav-cart">
            <img src="images/shopping-cart.png" width="35px" alt="">
        </a>
    </nav>
<div class="nav-bottom">
        
    <div>
        <img src="images/menu.png" width="25px" alt="">
        <p>All</p>
        </div>

        <a href="deals.php"><p>Today's Deal</p></a>

        <!-----ABOUT US----->
        <a href="#about"><p>About Us</p></a>

        <?php if ($is_admin): ?>
        <a href="admin_panel_redirect.php"><p>Admin pannal</p></a>
        <?php endif; ?>
        <a href="order_history.php"><p>Order History</p></a>
    </div>

    
    <div class="header-slider">
        <a href="#" class="control_prev">&#10094</a>
        <a href="#" class="control_next">&#10095</a>
         <ul>
            <li class="slider-item"><img src="images/beauty1-slider.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/gadgets.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/book_real.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/Kitchen slider.jpg" class="header-img" alt=""></li>
            <li class="slider-item"><img src="images/toy_banner.jpg" class="header-img" alt=""></li>
         </ul>
    </div>

    <div class="box-row header-box"  >
        <div class="box-col">
            <h3>Toys under $25</h3>
            <img src="images/box1-3.jpg" alt="">
            <a href="toys.php">Shop More</a>
       </div>
       <div class="box-col">
            <h3>Digital Moblie Phones</h3>
            <img src="images/box2-2.jpg" alt="">
            <a href="mobile.php">Shop More</a>
       </div>
       <div class="box-col">
           <h3>Beauty Essentials</h3>
           <img src="images/box2-1.jpg" alt="">
           <a href="beauty.php">Shop More</a>
      </div>
      <div class="box-col">
          <h3>Gifts</h3>
          <img src="images/box1-2.jpg" alt="">
          <a href="gifts.php">Shop More</a>
      </div>
</div>

<div class="box-row "  >
    <div class="box-col">
        <h3>Pets Food</h3>
        <img src="images/pet+food.jpg" alt="">
        <a href="pet.php">Shop More</a>
   </div>
   <div class="box-col">
        <h3>Fashion Mart</h3>
        <img src="images/Fashion Mart.jpg" alt="">
        <a href="fashion.php">Shop More</a>
   </div>
   <div class="box-col">
       <h3>Latest Devices</h3>
       <img src="images/Latest Device.jpg" alt="">
       <a href="latest.php">Shop More</a>
  </div>
  <div class="box-col">
    <h3>Grooming Products</h3>
    <img src="images/Grooming products.jpg" alt="">
    <a href="grooming.php">Shop More</a>
  </div>
  <!-----ABOUT US----->
  <div id="about" class="about-card">
    <h2>About <span>Us</span></h2>
    <p>
        Welcome to <strong>EasyShop</strong> — your one-stop destination for seamless online shopping. We aim to deliver high-quality products at competitive prices, from mobile gadgets and beauty essentials to fashion and gifts.
    </p>
    <p>
        Our mission is to make your shopping experience easy, fast, and reliable. With secure payments, real-time support, and trusted delivery, we ensure satisfaction every step of the way.
    </p>
    <p>
        Whether you're shopping for yourself or looking for the perfect gift, EasyShop is here to serve you with care and convenience.
    </p>
</div>
<!---END---->
<!----BEST PRODUCT---->
</div>
<div class="new-trend-section">
    <h2>New <span>Trend</span></h2>
    <div class="trend-products">
        <?php
        $conn = new mysqli("localhost", "root", "", "easyshop");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get some products visually (you can modify LIMIT or add WHERE)
        $sql = "SELECT id, name, price, image FROM products ORDER BY id DESC LIMIT 6";
        $result = $conn->query($sql);
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="trend-card">
    <img src="<?php echo $row['image']; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
    <h3><?php echo $row['name']; ?></h3>
    <p class="price">৳<?php echo $row['price']; ?></p>

    <!-- ✅ ADD THIS FORM -->
    <form action="add_to_cart.php" method="POST" class="cart-form">
        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['name']); ?>">
        <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
        <button type="submit" class="buy-btn">Add to Cart</button>
    </form>
</div>

        <?php endwhile; else: ?>
            <p>No products found.</p>
        <?php endif; $conn->close(); ?>
    </div>
</div>



<!---end---->
<footer class="footer-section" id="contact">
    <div class="footer-content">
      <h4>Contact Us</h4>
      <p>Email: <a href="mailto:tasniasaima6@gmail.com">tasniasaima6@gmail.com</a></p>
      <p>Phone: +880 18420-94272</p>
      <div class="social-icons">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
      </div>
    </div>
  </footer>
<script src="script.js"></script>

</body>
</html>