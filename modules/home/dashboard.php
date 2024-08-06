<?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
 
$data = [
    'pageTitle'=>'trang Dashboard', // thay đổi lable 
];
layouts('header',$data); // hàm chuyền PATH /
// kiểm tra trạng thái đăng nhập 
 
if(!IsLogin()){ // nếu không phải là session 
    redirect('?module=auth&action=login');
}
 
?>
<?php
layouts('footer'); // hàm chuyền PATH 
?>
<h1> dashboard  xxx </h1>

