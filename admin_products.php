<?php 

    require_once 'config.php';

    session_start();

    $admin_id = $_SESSION['admin_id'];


    if(!isset($admin_id)){
        header('location:admin_login.php)');
    };

    if(isset($_POST['add_product'])){

        $name = $_POST['name'];
        $name = filter_var($name, FILTER_SANITIZE_STRING);

        $price = $_POST['price'];
        $price = filter_var($price, FILTER_SANITIZE_STRING);

        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/' . $image; //هاي عشان اتجنب تكرار الصوره بنفس الاسم واوجه مشكله , ف مجرد ما احط صوره اسمها موجود بعمللها ريبليس

        $select_product = $con->prepare("SELECT * FROM `products` WHERE name = ?");
        $select_product->execute([$name]);
        $result =   $select_product->fetch();

        if($select_product->rowCount() > 0){
            $message[] = "Product Already exist!";
        } else {
            if($image_size > 2000000){
                $message[] = 'image size is too large!';
            }else{
                $insert_query = $con->prepare("INSERT INTO `products` (name, price, image)
                                                            VALUES(?, ?, ?)");
                $insert_query->execute([$name, $price, $image]);
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = "new Product added!";
            }

        }
    }

    if(isset($_GET['delid'])){

        $delete_id = $_GET['delid'];

        //del image from file(bcs i cant access image name atfer del record from database)
        $delete_product_image = $con->prepare("SELECT image FROM `products` WHERE id = ?");
        $delete_product_image->execute([$delete_id]);
        $result = $delete_product_image->fetch(PDO::FETCH_ASSOC);
        unlink('uploaded_img/'. $result['image']); //Deletes a file

        //del record from dataBase
        $delete_product = $con->prepare("DELETE FROM `products` WHERE id = ? ");
        $delete_product->execute([$delete_id]);

        //del products from Cart table
        $delete_cart = $con->prepare("DELETE FROM `cart` WHERE pid = ? ");
        $delete_cart->execute([$delete_id]);

        header('location:admin_products.php');

    }

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- fontawesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- custom admin style link -->
    <link rel="stylesheet" href="css/admin_style.css"/>
</head>
<body>

 
<?php require_once 'admin_header.php'; ?>

<div class="seperator"></div>

<section class="add-products">

    <h1 class="heading">add product</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="text" name="name" class="box" required maxlength="100" placeholder="enter product name">
        <input type="number" name="price" min="0" max="9999999999" class="box" required placeholder="enter product price" onkeypress="if(this.value.length == 10) return false;">
        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="box" required> <!-- This validation not enough i have to validate from server side -->
        <input type="submit" value="add products" class="btn" name="add_product">
    </form>

</section>

<section class="show-products">

    <h1 class="heading">Published produts</h1>

    <div class="box-container">

        <?php
            $select_products = $con->prepare("SELECT * FROM `products`");
            $select_products->execute();
            $products = $select_products->fetchAll(PDO::FETCH_ASSOC);
            if(!empty($products)){

                foreach($products as $product){ ?>

                    <div class="box">
                        <img src="uploaded_img/<?php echo $product['image'];?>" alt="">
                        <div class="name"><?php echo $product['name'];?></div>
                        <div class="price">$<span><?php echo $product['price'];?></span></div>
                        <div class="flex-btn">
                            <a href="admin_product_update.php?upid=<?php echo $product['id']; ?>" class="option-btn">update</a>
                            <a href="admin_products.php?delid=<?php echo $product['id']; ?>" class="delete-btn" onclick="return confirm('delete this product ?');">delete</a>
                        </div>
                    </div>
                    
               <?php }

            } else {
                echo '<p class="empty">No products added yet!</p>';
            }
        ?>
    </div>

</section>

<!-- custom js file  -->
<script src="js/admin_script.js"></script>

</body>
</html>