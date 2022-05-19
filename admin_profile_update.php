<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php)');
    }

    if(isset($_POST['udpate'])){

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $update_query = $con->prepare("UPDATE `admin` SET name = ? WHERE id = ? ");
        $update_query->execute([$name, $admin_id]);

        $prev_pass = $_POST['prev_pass'];

        $old_pass = sha1($_POST['old_pass']);
        $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);

        $new_pass = sha1($_POST['new_pass']);
        $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);

        $confirm_pass = sha1($_POST['confirm_pass']);
        $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);
        $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';

        

        if($old_pass != $empty_pass){
            if($old_pass != $prev_pass){
                $message[] = 'old password not matched';
            }elseif (strlen($_POST['new_pass']) <= 2 or strlen($_POST['new_pass']) >= 10 ) {
                $message[] = "your password must be greater than 2 chars , less than 10";
            } elseif ($new_pass == $old_pass){
                $message[] = "You cant use the old password";
            } elseif ($new_pass != $confirm_pass){
                $message[] = "confirm password not matched";
            } else {
                $update_query = $con->prepare("UPDATE `admin` SET password = ? WHERE id ? ");
                $update_query->execute([$new_pass, $admin_id]);
                $message[] = "password updated successfully";
            }
            
        } else {
            $message[] = "Please fill blanks fields";
        }


    }

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>update profile</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>
 
<?php require_once 'admin_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
            <h3>update your information</h3>
            <input type="hidden" name="prev_pass" value="<?php echo $result['password']; ?>" >
            <input type="text" class="box" name="name"  value="<?php echo $result['name']; ?>" required placeholder="enter your username" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" >
            <input type="password" class="box" name="old_pass"  placeholder="enter old password" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" >
            <input type="password" class="box" name="new_pass"  placeholder="enter new password" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" >
            <input type="password" class="box" name="confirm_pass"  placeholder="confirm new password" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" >
            <input type="submit" value="update now" class="btn" name="udpate">
        
    </form>
</section>

<!-- custom js file  -->
<script src="js/admin_script.js"></script>

</body>
</html>