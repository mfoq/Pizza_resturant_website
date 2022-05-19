<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php');
    }

    if(isset($_GET['upid'])){
        $product_id = $_GET['upid'];
    } else {
        header('location:admin_products.php');
    }

    if(isset($_POST['update_product'])){

        $pid = $_POST['pid'];

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);

        $old_image = $_POST['old_image'];

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/' . $image; //هاي عشان اتجنب تكرار الصوره بنفس الاسم واوجه مشكله , ف مجرد ما احط صوره اسمها موجود بعمللها ريبليس

        $update_query = $con->prepare("UPDATE `products` SET name = ?, price = ? WHERE id = ? ");
        $update_query->execute([$name, $price, $pid]);
        $message[] = 'product has been updated successfully!'; 

        if(!empty($image)){
            if($image_size > 2000000){
                $message[] = 'image size is too large!';
        } else {
            $update_image = $con->prepare("UPDATE `products` SET image = ? WHERE id = ? ");
            $update_image->execute([$image, $product_id]);
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/' . $old_image);
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
    <title>update product</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>

 
<?php require_once 'admin_header.php'; ?>
<div class="seperator"></div>
<section class="update-product">

    <h1 class="heading">update product</h1>

<?php

    $select_product = $con->prepare("SELECT * FROM `products` WHERE id = ?");
    $select_product->execute([$product_id]);
    $result = $select_product->fetch(PDO::FETCH_ASSOC);
    if(!empty($result)){ 
?>

    <form action="" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="pid" value="<?php echo $result['id']; ?>"> <!-- هاظ ما في داعي ابعته مع الفورم لانه انا جبته فوق اولريدي -->
        <input type="hidden" name="old_image" value="<?php echo $result['image']; ?>">
        <img src="uploaded_img/<?php echo $result['image']; ?>" alt="">
        <input type="text" name="name" class="box" required maxlength="100" placeholder="enter product name" value="<?php echo $result['name']; ?>">
        <input type="number" name="price" min="0" max="9999999999" class="box" required placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;" value="<?php echo $result['price']; ?>">
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" > <!-- This validation not enough i have to validate from server side -->
        <div class="flex-btn">
            <input type="submit" value="update product" class="btn" name="update_product">
            <a href="admin_products.php" class="option-btn" onclick="return confirm('are you sure to go back ?');">go back</a>
        </div>
    </form>

<?php 

    } else {
        header('locaction:admin_products.php');
    }
?>

</section>


<!-- custom js file  -->
<script src="js/admin_script.js"></script>

</body>
</html>