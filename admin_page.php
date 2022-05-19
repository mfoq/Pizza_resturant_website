<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php');
    }

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>

 
<?php require_once 'admin_header.php'; ?>

<div class="seperator"></div>

<section class="dashboard">

    <h1 class="heading">Dashboard</h1>

    <div class="box-container">

        <div class="box">

            <?php
                $total_pendings = 0;
                $select_pendings = $con->prepare("SELECT * FROM `orders` WHERE payment_status = ? ");
                $select_pendings->execute(['pending']);
                if($select_pendings->rowCount() > 0 ) {
                    while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
                        $total_pendings += $fetch_pendings['total_price'];
                    }
                }
            ?>
            <h3>$<?php echo $total_pendings; ?></h3>
            <p>Pending orders</p>
            <a href="admin_orders.php"  class="btn">see orders</a>

        </div>

        
        <div class="box">

            <?php
                $total_completes = 0;
                $select_completes = $con->prepare("SELECT * FROM `orders` WHERE payment_status = ? ");
                $select_completes->execute(['completed']);
                if($select_completes->rowCount() > 0 ) {
                    while($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)){
                        $total_completes += $fetch_completes['total_price'];
                    }
                }
            ?>
            <h3>$<?php echo  $total_completes; ?></h3>
            <p>Completed orders</p>
            <a href="admin_orders.php?status=complete"  class="btn">see orders</a>

        </div>

        <div class="box">

            <?php
                
                $select_orders = $con->prepare("SELECT * FROM `orders` ");
                $select_orders->execute(['completed']);
                $orders_number = $select_orders->rowCount();
               
            ?>
            <h3><?php echo  $orders_number; ?></h3>
            <p>Orders placed</p>
            <a href="admin_orders.php"  class="btn">see orders</a>

        </div>
        
        <div class="box">

            <?php
                
                $select_products = $con->prepare("SELECT * FROM `products` ");
                $select_products->execute(['completed']);
                $products_number = $select_products->rowCount();
               
            ?>
            <h3><?php echo  $products_number; ?></h3>
            <p>number of products</p>
            <a href="admin_products.php"  class="btn">see more</a>

        </div>

        <div class="box">

            <?php
                
                $select_users = $con->prepare("SELECT * FROM `user` ");
                $select_users->execute(['completed']);
                $users_number = $select_users->rowCount();
               
            ?>
            <h3><?php echo  $users_number; ?></h3>
            <p>Normal users</p>
            <a href="user_accounts.php"  class="btn">see users</a>

        </div>

        <div class="box">

            <?php
                
                $select_admins = $con->prepare("SELECT * FROM `admin` ");
                $select_admins->execute();
                $admins_number = $select_admins->rowCount();
               
            ?>
            <h3><?php echo  $admins_number; ?></h3>
            <p>Admins</p>
            <a href="admin_accounts.php" class="btn">see admins</a>

        </div>


    </div>

</section>


<!-- custom js file  -->
<script src="js/admin_script.js"></script>

</body>
</html>