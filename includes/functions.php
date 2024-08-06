<!-- viết các hàm chung của project -->
 <?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};

function layouts($layoutName='header', $data=[]){ // chuyền vào có thể header hoặc footer  
    if(file_exists(_WEB_PATH_TEMPLATES .'/layout/'.$layoutName.'.php'))
    {
        require_once(_WEB_PATH_TEMPLATES .'/layout/'.$layoutName.'.php');
    }
}

// copy .................................................
 
function SendMail($to, $subject, $content) {
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'phantung2k4nd@gmail.com'; // của mật khẩu ứng dụng  //SMTP username
    $mail->Password   = 'udcpalwrvtyftaki';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients người gửi 
    $mail->setFrom('phantung2k4nd@gmail.com', 'tung');
    $mail->addAddress($to);     //Add a recipient

    //Content
    $mail->CharSet = "UTF-8";
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $content;
    
    $mail->SMTPOptions = array (
        'ssl' => array (
            'verify_peer'=> false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $kq = $mail->send();
    return $kq;
    
} catch (Exception $e) {
    echo "loss";
}
}

function IsGet(){
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        return true;
    }
    return false;
}

function IsPost(){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        return true;
    }
    return false;
}

function Filter(){
    $FilterArray = [];
    if(IsGet()){
        if (!empty($_GET)){
            foreach ($_GET as $key => $value){ // duyệt input 
                $key = strip_tags($key);
                if(is_array($value)){
                    $FilterArray[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY); // xử lý phương thức GET 
                }
                else {
                    $FilterArray[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    if(IsPost()){
        if (!empty($_POST)){
            foreach ($_POST as $key => $value){
                $key = strip_tags($key);;
                if(is_array($value)){
                    $FilterArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY);
                }
                else 
                {
                    $FilterArray[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    return $FilterArray;
}

function IsEmail($Email){
    $checkEmail = filter_var($Email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}

function IsNumberInt($Number){
    $checkNumber = filter_var($Number, FILTER_VALIDATE_INT);
    return $checkNumber;
}

function IsNumberFloat($Number){
    $checkNumber = filter_var($Number, FILTER_VALIDATE_FLOAT);
    return $checkNumber;
}

function IsPhone($phone)
{
    $checkZero = false;
    if($phone[0] == '0'){
        $checkZero = true;
        $phone = substr($phone,1); // xóa số 0 
    }
    $checkNumber = false; 
    if(IsNumberInt($phone) && (strlen($phone) == 9) ){
        $checkNumber = true;
    }
    if($checkNumber && $checkZero ){
        return true;
    }
}

function GetSmg($smg, $type='success'){
    echo'<div class="alert alert-'.$type.'">';
    echo $smg;
    echo '</div>';
}
function redirect($path='index.php')
{
    header("Location: $path");
    exit();

}

function form_errors($fileName,$errors){
    return   (!empty($errors[$fileName])) ?'<span class="error">'.reset($errors[$fileName]).'</span>': null;
}
// hiển thị dữ liệu cũ 
function old($fileName,$oldData= '',$default = null){
    return  (!empty($oldData[$fileName]))? $oldData[$fileName] : $default;
}

function IsLogin (){ // đã đăng kí 
    $checkLogin = false;
if(GetSession('tokenlogin')){
    $tokenLogin = GetSession('tokenlogin');   //  lấy logintoken từ trang login 
    // kiểm tra có giống trong database không 
    $queryToken = OneRaw("SELECT user_id FROM tokenlogin WHERE token = '$tokenLogin' ");
    if(!empty($tokenLogin)){
        $checkLogin = true;
    }
    else{
        removeSession("tokenlogin");
    }
}
    return $checkLogin;
}