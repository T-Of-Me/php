<?php 
if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
$data = [
    'pageTitle'=>'trang active ', // thay đổi lable 
];
layouts('header',$data); // hàm chuyền PATH 


$token = Filter()['token']; // lấy mã token trên thanh URL 
 
if(!empty($token)){
    $TokenQuery = OneRaw ("SELECT id FROM user WHERE activeToken = '$token'");
   
    if(empty($TokenQuery)){
    GetSmg('liên kết đã hết hạn xxx ','danger');
    }
    else 
    {
 
        $UserId = $TokenQuery['id'];
        $dataUpdate = [
            'status'=> 1 ,
            'activeToken'=> NULL,
        ];
        $updateStatus = update('user',$dataUpdate , "id = $UserId" );  
        if($updateStatus){
            GetSmg('Kích  hoạt tài khoản thành công', 'success');
         
        }
        else {
            GetSmg('Kích hoạt tài khoản không thành công ','danger');
        }  
        redirect('?module=auth&action=login'); // tải lại trang 
    }
}
else 
{
        GetSmg('liên kết đã hết hạn', 'danger');
}

?>
 

<?php
    layouts('footer');
?>