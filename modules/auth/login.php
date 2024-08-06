<?php 
if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
$data = [
    'pageTitle'=>'đăng nhập tài khoản', // thay đổi lable 
];
layouts('header',$data); // hàm chuyền PATH 

// $check = IsEmail('tung@gmail.com');
// var_dump($check);
//----------------------------------------------------------
// $check = IsNumberInt('34,444');
// var_dump($check)
//----------------------------------------------------------
$password='123456';
$md5 = md5($password);
$sha1 = sha1($password);
// echo $sha1;
 
// echo $md5;
 
// $passwordhash = password_hash($password, PASSWORD_DEFAULT);
// echo $passwordhash;
// $checkPass = password_verify($password, $passwordhash);
// echo '<br>';
// var_dump($checkPass);

 
 
if(IsPost())
{
    $FillterAll=Filter();
    if  (!empty(trim($FillterAll['email'])) && !empty(trim($FillterAll['password'])))
    {
        // kiểm tra đăng nhập
        $password = $FillterAll['password'];
        $email = $FillterAll['email'];
        $userQuery = OneRaw ("SELECT password, id  FROM user WHERE email = '$email'"); // lấy ra password từ cơ sở dữ liệu
        if(!empty($userQuery)){
            $checkPass = $userQuery['password'];
            $UserId = $userQuery['id'];
            
            if(password_verify($password, $checkPass)){ // nếu mật khẩu đúng 
            $TokenLogin = sha1(uniqid().time());
            $dataInsert = [
                'user_id' => $UserId,     
                'token' => $TokenLogin,
                'create_at' => date('Y-m-d H:i:s'),
            ];
                $InsertStatus = insert('tokenlogin', $dataInsert); // truyền dữ liệu vào database tokenlogin
                Setsession('tokenlogin', $TokenLogin); // lưu lại session khi đã truyền dữ liệu thành công 
                    if($InsertStatus){ // đăng nhập thành công 
                    redirect('?module=home&action=dashboard');     // chuyển hướng nếu truy cập hợp lệ 
                    }
                    else 
                    {
                    SetFlashData('msg','Đăng nhập không thành công'); 
                    SetFlashData('msg_type','danger');
                    }

            }
            else 
            {
                SetFlashData('msg','Email không tồn tại'); 
                SetFlashData('msg_type','danger');
                redirect('?module=auth&action=login'); // tải lại trang 
            }
        }
        else //  nếu tên email không có trong cơ sở dữ liệu 
        {
          
            SetFlashData('msg','Email không tồn tại'); 
            SetFlashData('msg_type','danger');
            redirect('?module=auth&action=login'); // tải lại trang
        }
    }
    else {  
    SetFlashData('msg','vui lòng nhập Email và password'); 
    SetFlashData('msg_type','danger');
    redirect('?module=auth&action=login'); // tải lại trang 
    }

}
$msg = GetFlashData('msg');
$msg_type = GetFlashData('msg_type'); 

if(IsLogin()){ // nếu đã đăng kí 
    redirect('?module=home&action=dashboard');
}


?>
<div class="row">
    <div class="col-6" style="margin: 50px auto">
        <h2 class="form-group text-center text-upercase"  >Đăng nhập User</h2>
        <?php
        if($msg){
                getSmg($msg,$msg_type);
        }
        ?>
        <form action="" method="post"> 
            <div class="form-group mg-form">
                <label>Email</label>
                <input name='email'type="email" class="form-control" placeholder="địa chỉ email">
            </div>   
            <div class="form-group mg-form">
                <lable>Password</lable>
                <input name='password'type="password" class="form-control" placeholder="mật khẩu">    
            </div>
            <button type="submit" class="mg-form btn btn-primary btn-block">Đăng nhập</button>
            <p class="text-center"><a href="?module=auth&action=forgot">Quên mật khẩu</p>
            <p class="text-center"><a href="?module=auth&action=register">Đăng kí tài khoản<p>
        </form>
    </div>

</div>

<?php
    layouts('footer');
?>



