<?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
    
if(IsLogin()){ // nếu đã đăng kí thì chuyển về dashboard 
    redirect('?module=home&action=dashboard');
}
$smg = GetFlashData('smg'); // lưu string có key là smg vào biến 
$smg_type = GetFlashData('smg-type');  // như trên
$errors=GetFlashData('errors'); // như trên  
$data = [
    'pageTitle'=>'trang viết lại mật khẩu', // thay đổi lable 
];
 
$token = Filter()['token']; // lấy token từ url 
 
if(!empty($token)){    
    $QueryToken = OneRaw("SELECT id, fullname, email FROM user WHERE forgotToken = '$token' ");
    // var_dump($QueryToken);
  
        if(!empty($QueryToken)){ 
            $IdUser = $QueryToken['id'];    
            if(IsPost()){
                $filterAll = Filter(); 
                $errors = [];  // mảng chứa lỗi 
                if(empty($filterAll['password'])){
                    $errors['password']['require'] = 'password phải được nhập';
                }
                else 
                {
                    if  (strlen($filterAll['password']) < 8 ){
                        $errors['password']['min'] = 'Mật khẩu phải lớn hơn bằng 8 ';
                    }
                }
                
                if(empty($filterAll['passwordconfirm'])){
                    $errors['passwordconfirm']['require'] = 'bạn phải nhập lại mật khẩu';
                }
                else 
                {
                    if  ( $filterAll['password'] != $filterAll['passwordconfirm'] ){
                        $errors['passwordconfirm']['match'] = 'Mật khẩu không trùng lập ';
                    }
                }

                //========================

                if(empty($errors)){
                // update mật khẩu mới 
                $PasswordHash =  password_hash($filterAll['password'], PASSWORD_DEFAULT);
                $UpdateData = [
                    'password' => $PasswordHash,
                    'forgotToken' => null,
                    'update_at' => date('Y-m-d H:i:s'),
                ];
                $UpdateStatus = update('user',$UpdateData,"id ='$IdUser' ");
                    if($UpdateStatus){ // nếu update thành công 

                        SetFlashData('smg','update thành công'); // tương tự như dòng dưới 
                        SetFlashData('smg-type','success'); // đẻ string danger có key là smg-type
                        // redirect('?module=auth&action=login');
                    }
                    else { 
                        SetFlashData('smg','update thất bại'); // tương tự như dòng dưới 
                        SetFlashData('smg-type','success'); // đẻ string danger có key là smg-type       
                    }    
                }
                else{
                    SetFlashData('smg','kiểm tra lại dữ liệu'); // tương tự như dòng dưới 
                    SetFlashData('smg-type','danger'); // đẻ string danger có key là smg-type

                }
            }     
            else 
            {
                
            }    

           

         

           
      
        }
        else {
            SetFlashData('smg','liên kết không tồn tại hoặc hết hạn');
            SetFlashData('smg-type','danger');
        }
}
else { 
  
    SetFlashData('smg','liên kết không tồn tại hoặc hết hạn');
    SetFlashData('smg-type','danger');
}
layouts('header',$data); // hàm chuyền PATH  
?>
                <div class="row">    
                        <div class="col-6" style="margin: 50px auto">  
                            <h2 class="form-group text-center text-upercase">Nhập lại mật khẩu</h2>
                            <?php 
                                if(!empty($smg))
                                {
                                    GetSmg($smg, $smg_type); // in ra thông báo khi đăng kí xong 
                                }
                            ?>
                            <form action="" method="post">


                                <div class="form-group mg-form">
                                    <lable>Password</lable>
                                    <input name="password11" type="password" class="form-control" placeholder="mật khẩu">
                                    <?php
                                    echo form_errors('password',$errors);
                            
                                    ?>
                                </div>
                                <div class="form-group mg-form">
                                    <lable>Nhập lại Password</lable>
                                    <input name="passwordconfirm" type="password" class="form-control" placeholder="nhập lại mật khẩu">
                                    <?php
                                    echo form_errors('passwordconfirm',$errors);
                                    ?>
                                </div>
                                <input type="hidden" name="token" value="<?php echo $token?>">
                                <button type="submit" class="mg-form btn btn-primary btn-block">Gửi</button>

                            </form>
                        </div>

                    </div>

                    <?php


?>            
<?php
    layouts('footer');
?>