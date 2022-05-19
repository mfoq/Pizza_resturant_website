<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php)');
    }

    if(isset($_POST['add'])){

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $pass = sha1($_POST['pass']);
        $pass = filter_var($pass, FILTER_SANITIZE_STRING);

        $cpass = sha1($_POST['cpass']);
        $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

        //Check database
        $query = $con-> prepare("SELECT * FROM `admin` WHERE name = ? ");
        $query->execute([$name]);

        if($query->rowCount() > 0 ){
            $message[] = 'username alraedy exist!';
        } else{
            if($pass != $cpass){
                $message[] = 'password confirmation not matched';
            } else {
                $insert_query = $con->prepare("INSERT into `admin`(name, password) 
                                               VALUES(?,?)");
                $insert_query->execute([$name, $pass]);
                $message[] = "New Admin Has Been Added Successfully!";
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
    <title>Add Admin</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>
 
<?php require_once 'admin_header.php'; ?>

<section class="form-container">
    <form action="" method="post">
            <h3>Add Admin</h3>
            <input type="text" class="box" name="name" required placeholder="enter username" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" readonly onfocus="this.removeAttribute('readonly')">
            <input type="password" class="box" name="pass" required placeholder="enter password" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')"  >
            <input type="password" class="box" name="cpass" required placeholder="confirm password" max-length="20" oninput="this.value = this.value.replace(/\s/g,'')" >
            <input type="submit" value="Add Now" class="btn" name="add"> 
    </form>
</section>

<!-- custom js file  -->
<script src="js/admin_script.js"></script>

</body>
</html>