<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php)');
    }

    if(isset($_GET['delid'])){

        $delete_id = $_GET['delid'];

        //del record from dataBase
        $delete_order = $con->prepare("DELETE FROM `admin` WHERE id = ? ");
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
    <title>Admin accounts</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>

 
<?php require_once 'admin_header.php'; ?>

<div class="seperator"></div>

<section class="accounts">

    <h1 class="heading">admin accounts</h1>

    <div class="box-container">

    <div class="box">
        <p>add new admin</p>
        <a href="admin_register.php" class="option-btn">add admin</a>
    </div>

    <?php
        $select_accounts = $con->prepare("SELECT * FROM `admin`");
        $select_accounts->execute();
        if($select_accounts-> rowCount() > 0 ){

            while($account = $select_accounts->fetch(PDO::FETCH_ASSOC)){//بكل لفه بجيبلي اكاونت 
    ?>

        <div class="box">
            <p>user id : <span><?php echo $account['id']; ?></span></p>
            <p>name : <span><?php echo $account['name']; ?></span></p>
            <div class="flex-btn">
                <a href="admin_accounts.php?delid=<?php echo $account['id'];?>" class="delete-btn" onclick="return confirm('delete this admin?');">Delete</a>
                <?php
                    if($account['id'] == $admin_id){
                       echo '<a href="admin_profile_update.php" class="option-btn">update</a>';
                      
                    }
                ?>  
                
            </div>

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