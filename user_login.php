<?php 

    require_once 'config.php';

    session_start();

    if(isset($_POST['login'])){

        $email = $_POST['email'];
        $email = filter_var($email, FILTER_SANITIZE_STRING);
    
        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    
        //Check if username exist s
        $select_user = $con->prepare("SELECT * FROM `user` where email = ? AND password = ? ");
        $select_user->execute([$email, $pass]);
        $row = $select_user->fetch(PDO::FETCH_ASSOC);
    
        if($select_user->rowCount() > 0 ){
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
        } else {
            header('location:index.php');
        }
    
    }

    ?>