<?php

    require_once 'config.php';

    session_start();
    if(isset($_POST['login'])){
        
        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        //Check database
        $query = $con-> prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
        $query->execute([$name, $pass]);
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if($query->rowCount() > 0 ){
            $_SESSION['admin_id'] = $row[id];
            header('location:admin_page.php');
        } else {
            $message[] = "incorrect username or password";
        }

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin login</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
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

    <section class="form-container">
        <form action="" method="POST">
            <h3>login now</h3>
            <p>default username = <span>admin</span> & password = <span>1111</span></p>
            <input type="text" class="box" name="name" required placeholde="enter your username" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" readonly onfocus="this.removeAttribute('readonly')">
            <input type="password" class="box" name="pass" required placeholde="enter your password" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" >
            <input type="submit" value="login now" class="btn" name="login">
        </form>
    </section>
</body>
</html>