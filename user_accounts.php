<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php)');
    };

    
    if(isset($_GET['delid'])){

        $delete_id = $_GET['delid'];

        //del record from dataBase
        $delete_order = $con->prepare("DELETE FROM `user` WHERE id = ? ");
        $delete_order->execute([$delete_id]);

        header('location:admin_accounts.php');

    }

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>users accounts</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>

 
<?php require_once 'admin_header.php'; ?>


<div class="seperator"></div>

<section class="accounts">

    <h1 class="heading">users accounts</h1>

    <div class="box-container">

    <?php
        $select_accounts = $con->prepare("SELECT * FROM `user`");
        $select_accounts->execute();
        if($select_accounts-> rowCount() > 0 ){

            while($account = $select_accounts->fetch(PDO::FETCH_ASSOC)){//بكل لفه بجيبلي اكاونت 
    ?>

        <div class="box">
            <p>user id : <span><?php echo $account['id']; ?></span></p>
            <p>name : <span><?php echo $account['name']; ?></span></p>
            <p>email : <span><?php echo $account['email']; ?></span></p>
            <a href="user_accounts.php?delid=<?php echo $account['id'];?>" class="delete-btn" onclick="return confirm('delete this admin?');">Delete</a>     
        </div>

    <?php
                   }

        } else {
                echo '<p class="empty">no accounts available!</p> ';
        }

    ?>

    </div>

</section>

<!-- custom js file  -->
<script src="js/admin_script.js"></script>

</body>
</html>