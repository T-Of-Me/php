<?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
$data = [
    'pageTitle'=>'đăng ký tài khoản', // thay đổi lable 
];
if(IsLogin()){ // nếu đã đăng kí thì chuyển về dashboard 
    redirect('?module=home&action=dashboard');
}
// $data1=[
//         'fullname'=> 'trung',
//         'email'=> 'đawdawd@@',
//         'phone'=> '1111111',
// ];
// $kq = update('user', $data1,2);
// var_dump($kq);
// $kq = GetRow('SELECT * FROM user');
// echo'<pre>';
// print_r($kq);
// echo'<pre>';
if(IsPost()){
    $filterAll = Filter();  
    $errors = [];      
        if(empty($filterAll['fullname'])){
            $errors['fullname']['require'] = 'họ tên phải nhập';
        }
        else {
           if(strlen($filterAll['fullname']) < 5){
            $errors['fullname']['min'] = 'họ tên có ít nhất 5 kí tự ';
           }
        }

        // email
        if(empty($filterAll['email'])){
            $errors['email']['require'] = 'email phải được nhập';
        }
        else {
            $email = $filterAll['email'];
            $sql = "SELECT id FROM user WHERE email = '$email'";
            if (GetRow($sql) > 0 ) {
                $errors['email']['unique'] = "Email đã tồn tại";
            }
                    
        }

        // số điện thoại 
        if(empty($filterAll['number'])){
            $errors['number']['require'] = 'số điện thoại phải được nhập';
        }
        else {
           if(!IsPhone($filterAll['number'])){
            $errors['number']['Isphone'] = 'không phải số điện thoại hợp lệ ';
           }
        }
        // password 
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
         
if(empty($errors)){
            $acticeToken = sha1(uniqid().time()); // mã kích hoạt random 
            $dataInsert=[
                'fullname'=> $filterAll['fullname'],
                'email'=> $filterAll['email'],
                'password'=> password_hash($filterAll['password'], PASSWORD_DEFAULT),
                'activeToken' => $acticeToken,
                'create_at' => date('Y-m-d H;i:s'),
            ];
            $insertStatus = insert('user',$dataInsert); // đưa dữ liệu lên database
                if($insertStatus)
                {
                    // viết nội dung mail 
                    $linkActive = _WEB_HOST . '?module=auth&action=active&token='. $acticeToken;
                    $Subject = $filterAll['fullname'] . ' vui lòng kích hoạt tài khoản';
                    $content .= 'Vui lòng ấn vào link sau để kích hoạt tài khoản '  ;
                    $content .= $linkActive . '</br>';
                    $content .= ' TRÂN TRỌNG !!!!!!!!!!!';  

                    // tiến hành gửi mail
                    
                    $sendMail = SendMail ($filterAll['email'],$Subject,$content);
                        if($sendMail){
                            SetFlashData('smg','Đăng kí thành công vui lòng kiểm tra mail để kich hoạt tài khoản');
                            SetFlashData('smg-type','success');
                        }
                        else{ // nếu không gửi được mail
                            SetFlashData('smg','lỗi hệ thống !!!!!!');
                            SetFlashData('smg-type','danger');
                        }
                }
                else  // nếu không thêm thông tin không được 
                {
                    SetFlashData('smg','Đăng kí không thành công');
                    SetFlashData('smg-type','danger');
                }
                var_dump($insertStatus); 
                SetFlashData('smg','đăng kí thành công');  // để stringg có key là smg
                SetFlashData('smg-type','success'); //  để string có key là smg-type
                redirect('?module=auth&action=register');  // chuyển hướng nếu đăng kí thành công
}
else
{     
        SetFlashData('smg','kiểm tra lại dữ liệu'); // tương tự như dòng dưới 
        SetFlashData('smg-type','danger'); // đẻ string danger có key là smg-type
        SetFlashData('errors',$errors); // lưu lại dữ liệu
        SetFlashData('old',$filterAll); // lưu lại dữ liệu
        redirect('?module=auth&action=register');// điều hướng khi nhập thông tin sai . ấn reload lại sẽ tự động reload lại cả trang 
} 
}
$smg = GetFlashData('smg'); // lưu string có key là smg vào biến 
$smg_type = GetFlashData('smg-type');  // như trên
$errors=GetFlashData('errors'); // như trên 
$old=GetFlashData('old'); // lưu dữ liệu cũ 
// =====================================================================================================
layouts('header',$data); // hàm chuyền PATH  
// echo'<pre>' ;
// print_r($old);
// echo'</pre>' ;
?>
<div class="row">
    <div class="col-6" style="margin: 50px auto">
        <h2 class="form-group text-center text-upercase">Đăng ký tài khoản Users</h2>
        <?php 
            if(!empty($smg))
            {
                GetSmg($smg, $smg_type); // in ra thông báo khi đăng kí xong 
            }
        ?>
        <form action="" method="post">

            <div class="form-group mg-form">
                <label>Họ tên</label>
                <input name='fullname' type="fullname" class="form-control" placeholder="Họ tên" value="<?php 
                //echo(!empty($old['fullname']))? $old['fullname'] : null  // lưu lại dữ liệu
                echo old('fullname',$old); // có thể dùng hàm cũng tương tự như trên 
                ?>">
                <?php 
                echo (!empty($errors['fullname'])) ?'<span class="error">'.reset($errors['fullname']). '</span>': null; // reset lấy phần tử đầu tiên của mảng nếu không in ra lỗi
                ?>
            </div>
            <div class="form-group mg-form">
                <label>Email</label>
                <input name="email" type="email" class="form-control" placeholder="địa chỉ email" value="<?php 
                echo(!empty($old['email']))? $old['email'] : null  // lưu lại dữ liệu
                ?> ">
                <?php 
                // echo (!empty($errors['email'])) ?'<span class="error">'.reset($errors['email']).'</span>': null;
                echo form_errors('email',$errors); // dùng hàm cũng tương tự như trên
                ?>
            </div>
            <div class="form-group mg-form">
                <label>Sdt</label>
                <input name="number" type="number" class="form-control" placeholder="Số điện thoại" value="<?php 
                echo(!empty($old['number']))? $old['number'] : null  // lưu lại dữ liệu
                ?>">
                <?php
                // echo (!empty($errors['number'])) ?'<span class="error">'.reset($errors['number']).'</span>': null; 
                echo form_errors('number',$errors);
                ?>
            </div>
            <div class="form-group mg-form">
                <lable>Password</lable>
                <input name="password" type="password" class="form-control" placeholder="mật khẩu">
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
            <button type="submit" class="mg-form btn btn-primary btn-block">Đăng ký</button>
            <p class="text-center"><a href="?module=auth&action=login">Đăng nhập tài khoản</p>
        </form>
    </div>

</div>

<?php
     
    layouts('footer'); 
?>