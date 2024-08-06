<?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
if(IsLogin()){ // nếu đã đăng kí thì chuyển về dashboard 
    redirect('?module=home&action=dashboard');
}
$msg = GetFlashData('msg');
$msg_type = GetFlashData('msg_type'); 

// code tính năng quên mật khẩu 
// tạo ra forgotToken 
// gửi email chưa link đến trang reset 
// xác thực kiểm tra token có chính xác không 
// submit reset password -> update password 
$data = [
    'pageTitle'=>'trang đổi mật khấu', // thay đổi lable 
];
layouts('header',$data); // hàm chuyền PATH 
if(IsPost()){
    $filterAll = Filter();
    if(!empty($filterAll['email'])){
        $email = $filterAll['email'];  
        $query = OneRaw("SELECT id FROM user WHERE email = '$email'"); // lấy id from database
        if(!empty($query)){
            $userId = $query['id'];
            // tạo fogotToken 
            $ForgotToken = sha1(uniqid().time()); // tạo token 
            $update = [ 
                'forgotToken' => $ForgotToken, // truyền token vào SQL 
            ];
            $updateStatus = update('user', $update,"id=$userId");
            if($updateStatus){
                $linkReset = _WEB_HOST . "?module=auth&action=reset&token=".$ForgotToken; 
                // gửi mail
                $subject = "Yêu cầu khôi phục mật khẩu";
                $content = 'Chào bạn.</br>';
                $content .= 'chúng tôi nhận được yêu cầu khôi phục từ bạn Hãy ấn vào đường link sau để khôi phục mật khẩu : .</br>';
                $content .= $linkReset;

                $sendMail = sendMail($email, $subject, $content);
                if($sendMail){
                    SetFlashData('msg','vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu'); 
                    SetFlashData('msg_type','success');
                }
                else {
                    SetFlashData('msg','lỗiiiii hệ thống'); 
                    SetFlashData('msg_type','danger');
                }

            }
            else {
                SetFlashData('msg','lỗiiiii hệ thốnggggggggg'); 
                SetFlashData('msg_type','danger');
            }
        }
        else{
            SetFlashData('msg','Email không tồn tại'); 
            SetFlashData('msg_type','danger');
        }
    }
    else {
        SetFlashData('msg','Vui lòng nhập địa chỉ Email'); 
        SetFlashData('msg_type','danger');
    }

 

}
?>
<div class="row">
    <div class="col-6" style="margin: 50px auto">
        <h2 class="form-group text-center text-upercase">Quên Mật Khẩu</h2>
        <?php
        if($msg){
                getSmg($msg,$msg_type);
        }
        ?>
        <form action="" method="post">
            <div class="form-group mg-form">
                <label>Email</label>
                <input name='email' type="email" class="form-control" placeholder="địa chỉ email">
            </div>
            <button type="submit" class="mg-form btn btn-primary btn-block">Gửi</button>

        </form>
    </div>

</div>

<?php
    layouts('footer');
?>