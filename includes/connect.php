 <!-- kết nối với database -->
 <?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};
 
try {   
    if(class_exists('PDO')){
        $dsn = 'mysql:dbname='._DB.';host='._HOST;
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        $conn = new PDO($dsn,_USER,_PASS,$options); 
    }

}
catch (Exception $e) {
    echo ''. $e->getMessage();
    
}