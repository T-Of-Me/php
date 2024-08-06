<?php
session_start();
require_once('config.php');
require_once('./includes/connect.php'); // kết nối với PDO 
require_once('./includes/phpMailer/Exception.php');
require_once('./includes/phpMailer/PHPMailer.php');
require_once('./includes/phpMailer/SMTP.php');
require_once('./includes/functions.php');
require_once('./includes/database.php');
require_once('./includes/session.php');
$module = _MODULE; // gán giá trị const home
$action = _ACTION; // gán giá trị const 
if(!empty($_GET["module"])){ // kiểm tra giá trị 
    $module = $_GET["module"];    
}
// $bien=SetFlashData('tung','giá trị của tung'); 
// echo $bien;
// $content="ádasd";
// $subject= "ádasd";
// SendMail('phantung2k4nd@gmail.com',$subject,$content);
// $bien=GetFlashData('tung');
// echo $bien;
if(!empty($_GET["action"])){
    $action = $_GET["action"];
}
// echo $module.'<br>'; 
// echo $action;    


$path ='modules/'. $module .'/'.$action.'.php'; // nếu đúng module thì sẽ chuyển hướng đến các file module 


if(file_exists($path)){
    require_once($path);
}
else {
    require_once 'modules/error/404.php'; 
    // nếu sai module thì sẽ chuyển đến folder lỗi 
};







