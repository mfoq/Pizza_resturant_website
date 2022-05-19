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


<header class="header">

       <section class="flex">
            <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>

            <nav class="navbar">
                <a href="admin_page.php">Home</a>
                <a href="admin_products.php">Products</a>
                <a href="admin_orders.php">Orders</a>
                <a href="admin_accounts.php">Admins</a>
                <a href="user_accounts.php">users</a>
            </nav>

            <div class="icons">
                <div id="menu-btn" class="fas fa-bars"></div>
                <div id="user-btn" class="fas fa-user"></div>
            </div>

            <div class="profile">

                <?php
                    $select_admin_info = $con->prepare("SELECT * FROM `admin` WHERE id = ?");
                    $select_admin_info->execute([$admin_id]);
                    $result = $select_admin_info->fetch(PDO::FETCH_ASSOC);
                ?>

                <p><?php echo $result['name']; ?></p>
                <a href="admin_profile_update.php" class="btn">update profile</a>
                <a href="logout.php" class="delete-btn">logout</a>

                <div class="flex-btn">
                    <a href="index.php" class="option-btn">Visit Shop</a>
                    <a href="admin_register.php" class="option-btn">Add Admin</a>
                </div>
                
            </div>
       </section>

</header>