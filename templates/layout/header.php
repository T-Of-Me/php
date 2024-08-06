<?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?php 
    echo !empty($data['pageTitle']) ? $data['pageTitle'] :'Quản lý người dùng';?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES;?>/css/bootstrap.min.css"> 
    <link rel="stylesheet" href=" <?php echo _WEB_HOST_TEMPLATES;?>/css/style.css?ver=<?php echo rand()?>"><!-- khi có nhiều file css thì phải thêm đuôi ver vào đường link để chương trình xác định được file css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"> <!-- liên kết thư viện bằng font awesome  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Braah+One&display=swap" rel="stylesheet"> <!-- link để css chữ đăng nhập -->
</head>
<body>
</body>
</html>