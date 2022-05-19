<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php)');
    }

    if(isset($_POST['update_payment'])){

        if(!isset($_POST['payment_status'])){
            $message[] = "please choose payment status";
        } else {
            $order_id = $_POST['order_id'];
            $payment_status = $_POST['payment_status'];
            $payment_status = filter_var($payment_status, FILTER_SANITIZE_STRING);
            $update_payment = $con->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
            $update_payment->execute([$payment_status, $order_id]);
            $message[] = 'payment status has been updated!';
        }

    }

    if(isset($_GET['delid'])){

        $delete_id = $_GET['delid'];

        //del record from dataBase
        $delete_order = $con->prepare("DELETE FROM `orders` WHERE id = ? ");
        $delete_order->execute([$delete_id]);

        header('location:admin_orders.php');

    }



?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>

 
<?php require_once 'admin_header.php'; ?>

<div class="seperator"></div>

<section class="orders">

    <h1 class="heading">placed orders</h1>

    <div class="box-container">

       
        <?php 

            $select_orders = $con->prepare("SELECT * FROM `orders`");
            $select_orders->execute();
            $rows = $select_orders->fetchAll(PDO::FETCH_ASSOC);

            if($select_orders->rowCount() > 0){
                foreach($rows as $row){
        ?>

        <div class="box">
            <p> placed on : <span><?php echo $row['placed_on'];?></span> </p>
            <p> name : <span><?php echo $row['name'];?></span> </p>
            <p> number : <span><?php echo $row['number'];?></span> </p>
            <p> address : <span><?php echo $row['address'];?></span> </p>
            <p> total products : <span><?php echo $row['total_products'];?></span> </p>
            <p> total price : <span><?php echo $row['total_price'];?></span> </p>
            <p> payment method : <span><?php echo $row['method'];?></span> </p>
            <form action="" method="post">
                <input type="hidden" name="order_id" value="<?php echo $row['id'];?>">
                <select name="payment_status" class="select">
                    <option selected disabled> Choose payment status... </option>
                    <option value="pending">pending</option>
                    <option value="completed">completed</option>
                </select>
                <div class="flex-btn">
                    <input type="submit" value="update" class="option-btn" name="update_payment">
                    <a href="admin_orders.php?delid=<?php  echo $row['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?')">Delete</a>
                </div>
            </form>
        </div>

        <?php
                
            }
                }else {
                echo '<p class="empty">no orders placed yet!</p>';
            }

        ?>
    </div>

</section>



<!-- custom js file  -->
<script src="js/admin_script.js"></script>

</body>
</html>