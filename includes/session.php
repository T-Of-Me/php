<!-- hàm liên quan đến session hay cookie -->
<?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};

// gán session 

function SetSession($key,$value){
    return $_SESSION[$key] = $value;
};
function GetSession($key=''){
    if(empty($key)){
        return $_SESSION;
    }
    else{
    if (isset($_SESSION[$key]))
    {
        return $_SESSION[$key]; 
    };      
    }
};

function RemoveSession($key=''){
    if(empty($key)){ // chuỗi rỗng 
        session_destroy();
        return true;
    }
    else{
        if (isset($_SESSION[$key])) // kiểm tra biến tồn tại hay chưa 
        {
            unset($_SESSION[$key]); // xóa biến 
        }
        return true;
    }
}

function SetFlashData($key,$value){
    $key ='flash_'.$key;
    return SetSession($key,$value);
}
function GetFlashData($key= ''){
    $key='flash_'.$key;
    $data = GetSession($key); // lưu lại dữ liệu 
    RemoveSession($key);
    return $data;
}





