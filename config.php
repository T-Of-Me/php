<!-- hằng số của project -->
<?php
const _MODULE = 'home';
const _ACTION = 'dashboard';
const _CODE = true; // kiểm tra tính hợp lệ của truy cập 
// thiết lập host 
define('_WEB_HOST','http://'. $_SERVER['HTTP_HOST'].'/manager_user/') ; // đường dẫn đến manager
define('_WEB_HOST_TEMPLATES',_WEB_HOST .'/templates') ; // đường dẫn đến template
// biến server['HTTP_HOST'] giúp xác định tên miền ở đây là localhost

// thiết lập path 
define('_WEB_PATH',__DIR__);// đường dẫn path đến manager 
define('_WEB_PATH_TEMPLATES',_WEB_PATH . '/templates'); // đường dẫn path đến template 

// thông tin  kết nối 
const _HOST = 'localhost';
const _DB = 'tungphan';
const _USER = 'root';
const _PASS = '';