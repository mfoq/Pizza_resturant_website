<?php

require_once 'config.php';

session_start();

if(isset($_SESSION['user_id'])){ 
    $user_id  = $_SESSION['user_id'];

} else {
    $user_id = '';
}

if(isset($_POST['register'])){

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);

    $pass = sha1($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $cpass = sha1($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

    //Check if username exist 
    $select_user = $con->prepare("SELECT * FROM `user` where name = ? ");
    $select_user->execute([$name]);

    if($select_user->rowCount() > 0 ){
        $message[] = 'User name is exist choose another one!';
    } else {
        if($pass != $cpass){
            $message[] = 'confirm password not matched!';
        } else {
            $insert_query = $con->prepare("INSERT INTO `user` (name, email, password) 
                                        VALUES(:zname, :zemail, :zpass)");
            $insert_query->execute(array(
                ":zname"    => $name,
                ":zemail"   => $email,
                ":zpass"    => $pass,
            ));

            $message[] = 'successfully registerd, login now please!';

        }
    }

}

if(isset($_POST['update_qty'])){

    $cart_id = $_POST['cart_id'];
    $new_qty = $_POST['qty'];
    $new_qty = filter_var($new_qty, FILTER_SANITIZE_STRING);
    $update_qty = $con->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$new_qty, $cart_id]);
    $message[] = 'cart has been updated!';
}

if(isset($_GET['delete_cart_item'])){
    $delete_id = $_GET['delete_cart_item'];
    $delete_query = $con->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_query->execute([$delete_id]);
    header('location:index.php');
}

if(isset($_GET['logout'])){
    session_unset();
    session_destroy();
    header('location:index.php');
}

if(isset($_POST['add_to_cart'])){
    if($user_id == ''){
        $message[] = 'please login first!';
    } else {
        $pid = $_POST['pid'];
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        $qty = $_POST['qty'];
        $qty = filter_var($qty, FILTER_SANITIZE_STRING);

        //First Check if product added inside cart
        $select_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id = ? AND
        name = ? ");
        $select_cart->execute([$user_id, $name]);

        if($select_cart->rowCount() > 0){
            $message[] = 'Already Added to cart!';
        } else {
            //insert To cart
            $insert_cart = $con->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image)
            VALUES(?, ?, ?, ?, ?, ?)");
            $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
            $message[] = 'added to cart!';
        }


    }
}

if(isset($_POST['order'])){

    if($user_id == ''){
        $message[] = 'please login first!';
    }else{
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);
    
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_STRING);
    
        $address = 'flat no.' . $_POST['flat'] . ', ' . $_POST['street'] . ' - ' . $_POST['pin_code'];
        $address = filter_var($address, FILTER_SANITIZE_STRING);
    
        $method = $_POST['method'];
        $method = filter_var($method, FILTER_SANITIZE_STRING);
    
        $method = $_POST['method'];
        $method = filter_var($method, FILTER_SANITIZE_STRING);
    
        $total_price = $_POST['total_price'];
        $total_products = $_POST['total_products'];
    
        $select_cart = $con->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        
        if($select_cart->rowCount() > 0){
            $insert_order = $con->prepare("INSERT INTO `orders`(user_id, name, number, method, address, total_products, total_price)
                                                        VALUES(?,?,?,?,?,?,?)");
            $insert_order->execute([$user_id, $name, $number, $method, $address, $total_products, $total_price]);
            
            $delete_cart = $con->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);
            
            $message[] = 'order Placed successfully!';
        }else{
            $message[] = 'Your cart is empty!';
        }
    }
   

}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Shop</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- custom css file link -->
    <link rel="stylesheet" href="css/style.css" />

</head>
<body>

<?php
        if(isset($message)){
            foreach($message as $msg){
                echo ' 
                    <div class="message">
                        <span>'. $msg .'</span>
                        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>
                ';
            }
        }
?>

<!-- header section starts -->
<header class="header">

    <section class="flex">

        <a href="#home" class="logo">Pizza.</a>

        <nav class="navbar">
            <a href="#home">Home</a>
            <a href="#about">about</a>
            <a href="#menu">menu</a>
            <a href="#order">order</a>
            <a href="#faq">faq</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
            <div id="order-btn" class="fas fa-box"></div>
            <?php 
                $count_cart_items = $con->prepare("SELECT * FROM `cart` WHERE user_id = ? ");
                $count_cart_items->execute([$user_id]);
            ?>
            <div id="cart-btn" class="fas fa-shopping-cart"><span>(<?php echo $count_cart_items->rowCount(); ?>)</span></div>
        </div>
    </section>

</header>
<!-- header section ends -->

<!-- user account section Starts-->
<div class="user-account">

    <section>
        <div id="close-account"><span>close</span></div>

        <div class="user">
            <?php 
                $select_user = $con->prepare("SELECT * FROM `user` where id = ?");
                $select_user->execute([$user_id]);
                $result = $select_user->fetch(PDO::FETCH_ASSOC);
                if($select_user->rowCount() > 0 ){
                        echo ' <p>welcome ! <span>'. $result['name'] .'</span></p>';
                        echo ' <a href="index.php?logout" class="btn">Logout</a> ';
                } else {
                    echo '<p><span>you are not logged in now!</span></p>';
                }
            ?>
        </div> 

        <div class="display-orders">

            <?php 
                $select_cart = $con->prepare("SELECT * FROM `cart` where user_id = ?");
                $select_cart->execute([$user_id]);
                $results = $select_cart->fetchAll(PDO::FETCH_ASSOC);
                if($select_cart->rowCount() > 0 ){
                    foreach( $results as  $result){
                        echo '<p>'.$result['name'].'<span>('.$result['price'].'x  '.$result['quantity'].')</span></p>';
                    }
                } else {
                    echo '<p><span>your cart is empty!</span></p>';
                }
            ?>
        </div>

        <?php
            if($select_user->rowCount() == 0 ){ 
        ?>
            <div class="flex">
                <form action="user_login.php" method="post">
                    <h3>login now</h3>
                    <input type="email" name="email" required class="box" placeholder="enter your email" maxlength="50" readonly onfocus="this.removeAttribute('readonly')" >
                    <input type="password" name="pass" required class="box" placeholder="enter your password" maxlength="20" >
                    <input type="submit" name="login" value="Login now" class="btn">
                </form>

                <form action="" method="post" autocomplete="off">
                    <h3>Register now</h3>
                    <input type="text" name="name" required class="box" placeholder="enter your username" maxlength="20" >
                    <input type="email" name="email" required class="box" placeholder="enter your email" maxlength="50" readonly onfocus="this.removeAttribute('readonly')">
                    <input type="password" name="pass" required class="box" placeholder="enter your password" maxlength="20" oninput="this.value = this.value.replace(/\s/g,'')"> 
                    <input type="password" name="cpass" required class="box" placeholder="confirm your password" maxlength="20" oninput="this.value = this.value.replace(/\s/g,'')">
                    <input type="submit" name="register" value="register now" class="btn">
                </form>
            </div>

        <?php } ?>
    </section>
</div>
<!-- user account section ends-->

<!-- my Orders section starts -->
<div class="my-orders">
    <section>

        <div id="close-orders"><span>close</span></div>

        <h3 class="title">my orders</h3>

        <?php 
                $select_orders = $con->prepare("SELECT * FROM `orders` where user_id = ?");
                $select_orders->execute([$user_id]);
                $results = $select_orders->fetchAll(PDO::FETCH_ASSOC);
                if($select_orders->rowCount() > 0 ){
                    foreach( $results as  $result){   
        ?>

        <div class="box">
            <p>placed on : <span><?php echo $result['placed_on']; ?></span></p>
            <p>name : <span><?php echo $result['name']; ?></span></p>
            <p>number : <span><?php echo $result['number']; ?></span></p>
            <p>address : <span><?php echo $result['address']; ?></span></p>
            <p>payment method : <span><?php echo $result['method']; ?></span></p>
            <p>total orders : <span><?php echo $result['total_products']; ?></span></p>
            <p>total price : <span><?php echo $result['total_price']; ?></span></p>
            <p>payment status : <span style="color: <?php if($result['payment_status'] == 'pending'){echo 'red';} else {echo 'green';} ?>"><?php echo $result['payment_status']; ?></span></p>
        </div>

        <?php
              }
            } else {
                echo '<p class="empty">nothing orderd yet!</p>';
            }
        ?>
        
    </section>
</div>
<!-- my Orders section ends -->

<!-- Starts Shopping cart -->
<div class="shopping-cart">
    <section>

       <div id="close-cart"><span>close</span></div>

       <?php 
                $grand_total = 0;
                $select_cart = $con->prepare("SELECT * FROM `cart` where user_id = ?");
                $select_cart->execute([$user_id]);
                $results = $select_cart->fetchAll(PDO::FETCH_ASSOC);
                if($select_cart->rowCount() > 0 ){
                    foreach( $results as  $result){
                        $subtotal = ($result['price'] * $result['quantity']);
                        $grand_total += $subtotal;        
        ?>

        <div class="box">
            <a href="index.php?delete_cart_item=<?php echo $result['id'];?>" class="fas fa-times" onclick="return confirm('dlete this item?');"></a>
            <img src="uploaded_img/<?=  $result['image']; ?>" alt="">
            <div class="content">
                <p><?= $result['name']; ?><span>( <?= $result['price']; ?>/- x <?= $result['quantity'];?>)</span></p>
                <form action="" method="post">
                    <input type="hidden" name="cart_id" value="<?= $result['id'];?>">
                    <input type="number" class="qty" name="qty" min="1" max="99" value="<?= $result['quantity'];?>" onkeypress="if(this.value.length == 2) return false;">
                    <button type="submit" class="fas fa-edit" name="update_qty" class="qty-btn"></button>
                </form>
            </div>
        </div>

        <?php
                    }
                } else {
                    echo '<p class="empty"><span>your cart is empty!</span></p>';
                }

        ?>
       
       <div class="cart-total">grand total : <span><?= $grand_total; ?>$</span></div>

        <a href="#order" class="btn">Order now</a>
    </section>
</div>
<!-- Ends Shopping cart -->

<!-- starts home section -->
<div class="home-bg" id="home">

    <section class="home">

        <div class="slide-container">

            <div class="slide active">               
                <div class="image">
                    <img src="images/home-img-1.png" alt="">
                </div>
                <div class="content">
                    <h3>Homemade Pepperoni Pizza</h3>
                    <div class="fas fa-angle-left" onclick="prev()"></div>
                    <div class="fas fa-angle-right" onclick="next()"></div>
                </div>
            </div>

            <div class="slide">
                <div class="image">
                    <img src="images/home-img-2.png" alt="">
                </div>
                <div class="content">
                    <h3>Pizza With Mushrooms</h3>
                    <div class="fas fa-angle-left" onclick="prev()"></div>
                    <div class="fas fa-angle-right" onclick="next()"></div>
                </div>
            </div>

            <div class="slide">    
                <div class="image">
                    <img src="images/home-img-3.png" alt="">
                </div>
                <div class="content">
                    <h3>Mascarpone And Mushrooms</h3>
                    <div class="fas fa-angle-left" onclick="prev()"></div>
                    <div class="fas fa-angle-right" onclick="next()"></div>
                </div>
            </div>

        </div>

    </section>

</div>
<!-- ends home section -->

<!-- about section starts -->
<section class="about" id="about">

    <h1 class="heading">About us</h1>

    <div class="box-container">
       
        <div class="box">
            <img src="images/about-1.svg" alt="">
            <h3>made with love</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quasi natus unde laboriosam? Obcaecati, veniam iste impedit velit exercitationem cumque voluptas?</p>
            <a href="#menu" class="btn">Our menu</a>
        </div>

        <div class="box">
            <img src="images/about-2.svg" alt="">
            <h3>30 minutes delivery</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quasi natus unde laboriosam? Obcaecati, veniam iste impedit velit exercitationem cumque voluptas?</p>
            <a href="#menu" class="btn">Our menu</a>

        </div>

        <div class="box">
            <img src="images/about-3.svg" alt="">
            <h3>Share With friends</h3>
            <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quasi natus unde laboriosam? Obcaecati, veniam iste impedit velit exercitationem cumque voluptas?</p>
            <a href="#menu" class="btn">Our menu</a>

        </div>
    </div>

</section>
<!-- about section endss -->


<!-- menu section starts -->
<section id="menu" class="menu">

    <h1 class="heading">our menu</h1>

    <div class="box-container">

    <?php
        $select_products = $con->prepare("SELECT * FROM `products`");
        $select_products->execute();
        if($select_products->rowCount() >0){
            while($result = $select_products->fetch(PDO::FETCH_ASSOC)){
    ?>
    
        <div class="box">
                <div class="price">$<span><?= $result['price']?></span>/-</div>
                <img src="uploaded_img/<?= $result['image']; ?>" alt="">
                <div class="name"><?= $result['name']; ?></div>
                <form action="" method="POST">
                    <input type="hidden" name="pid" value="<?= $result['id']?>">
                    <input type="hidden" name="name" value="<?= $result['name']?>">
                    <input type="hidden" name="price" value="<?= $result['price']?>">
                    <input type="hidden" name="image" value="<?= $result['image']?>">
                    <input type="number" min="1" max="99" value="1" class="qty" name="qty" onkeypress="if(this.value.length == 2) return false;">
                    <input type="submit" value="add to cart" name="add_to_cart" class="btn"> 
                </form>
        </div>


    <?php
            }

        } else {
            echo '<p class="empty">no products added yet!</p>';
        }


    ?>
    
    </div>
</section>
<!-- menu section ends -->



<!-- Order section Starts -->
<section class="order" id="order">

    <h1 class="heading">Order here</h1>

    <form action="" method="POST">

        <div class="display-orders">
            <?php 
                    $grand_total = 0;
                    $cart_item[] = '';
                    $select_cart = $con->prepare("SELECT * FROM `cart` where user_id = ?");
                    $select_cart->execute([$user_id]);
                    $results = $select_cart->fetchAll(PDO::FETCH_ASSOC);
                    if($select_cart->rowCount() > 0 ){
                        foreach( $results as  $result){
                            $subtotal = ($result['price'] * $result['quantity']);
                            $grand_total += $subtotal;   
                            $cart_item[] = $result['name'] . ' ( ' . $result['price'] . ' x ' . $result['quantity'] . ' ) - ';
                            $total_products = implode($cart_item);
                            echo '<p>'.$result['name'].'<span>('.$result['price'].'x  '.$result['quantity'].')</span></p>';
                            
                        }
                    } else {
                        echo '<p class="empty"><span>your cart is empty!</span></p>';
                    }

            ?>

        </div>
        
       
        <div class="grand-total" style="display:<?php if($grand_total == 0){echo "none";}else{echo "block";} ?>" >grand total : $<span><?= $grand_total; ?></span></div>
            
       
        
        <input type="hidden" name="total_products" value="<?=  $total_products; ?>">
        <input type="hidden" name="total_price" value="<?=  $grand_total; ?>">

        
        <div class="flex">
            <div class="inputBox">
                <span>your name : </span>
                <input type="text" name="name" class="box" required placeholder="enter your name" maxlenght="20">
            </div>

            <div class="inputBox">
                <span>your number : </span>
                <input type="number" name="number" class="box" required placeholder="enter your number" min="0">
            </div>

            <div class="inputBox">
                <span>payment method :</span>
                <select name="method" class="box">
                    <option value="cash on delivery">cash on delivery</option>
                    <option value="credit card">credit card</option>
                    <option value="paypal">paypal</option>
                    <option value="Pioneer">Pioneer</option>
                </select>
            </div>

            <div class="inputBox">
                <span>address line 01 : </span>
                <input type="text" name="flat" class="box" required placeholder="e.g. flat no." maxlenght="50">
            </div>

            <div class="inputBox">
                <span>address line 02 : </span>
                <input type="text" name="street" class="box" required placeholder="e.g. street name." maxlenght="50">
            </div>

            <div class="inputBox">
                <span>pin code : </span>
                <input type="number" name="pin_code" class="box" required placeholder="e.g. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;">
            </div>
        </div>

        <input type="submit" value="Place Order" class="btn" name="order">
    </form>

</section>
<!-- Order section ends -->

<!-- faq section starts -->
<section class="faq" id="faq">

    <h1 class="heading">FAQ</h1>

    <div class="accordion-container">

        <div class="accordion active">
            <div class="accordion-heading">
                <span>how does it work ?</span>
                <i class="fas fa-angle-down"></i>
            </div>
            <p class="accordion-content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam molestias beatae ducimus quasi nostrum ut, ex necessitatibus rerum itaque quisquam?
            </p>
        </div>

        <div class="accordion">
            <div class="accordion-heading">
                <span>how long does it take for delivery ?</span>
                <i class="fas fa-angle-down"></i>
            </div>
            <p class="accordion-content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam molestias beatae ducimus quasi nostrum ut, ex necessitatibus rerum itaque quisquam?
            </p>
        </div>

         <div class="accordion">
            <div class="accordion-heading">
                <span>can i order for huge parties ?</span>
                <i class="fas fa-angle-down"></i>
            </div>
            <p class="accordion-content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam molestias beatae ducimus quasi nostrum ut, ex necessitatibus rerum itaque quisquam?
            </p> 
        </div>

         <div class="accordion">
            <div class="accordion-heading">
                <span>how muach protin it contains ?</span>
                <i class="fas fa-angle-down"></i>
            </div>
            <p class="accordion-content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam molestias beatae ducimus quasi nostrum ut, ex necessitatibus rerum itaque quisquam?
            </p>
        </div>

         <div class="accordion">
            <div class="accordion-heading">
                <span>it is cooked with oil ?</span>
                <i class="fas fa-angle-down"></i>
            </div>
            <p class="accordion-content">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam molestias beatae ducimus quasi nostrum ut, ex necessitatibus rerum itaque quisquam?
            </p>
        </div>

    </div>

</section>
<!-- faq section ends -->

<!-- footer section starts -->
<section class="footer"> 

    <div class="box-container">

        <div class="box">
            <i class="fas fa-phone"></i>
            <h3>phone number</h3>
            <p>+123-455-4348</p>
            <p>+444-645-7765</p>
        </div>

        <div class="box">
            <i class="fas fa-map-marker-alt"></i>
            <h3>our address</h3>
            <p>Amman, Jordan - 400104 </p>
        </div>

        <div class="box">
            <i class="fas fa-clock"></i>
            <h3>opening hours</h3>
            <p>09:00 AM to 10:00 PM</p>
        </div>


        <div class="box">
            <i class="fas fa-envelope"></i>
            <h3>email address</h3>
            <p>mahmood@gmail.com</p>
            <p>alfoqahaa@yahoo.com</p>
        </div>

    </div>

    <div class="credit">
        &copy; copyright @ 2022 by <span>mfoq desinger</span> | all rights reserved!
    </div>

</section>
<!-- footer section ends -->

<!-- custom js file link -->
<script src="js/script.js"></script>
</body>
</html>