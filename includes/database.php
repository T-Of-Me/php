<!-- các hàm xử lý liên quan đến cơ sở dữ liệu -->
<?php 

if (!defined('_CODE')){ //   kiểm tra biến này có tồn tại không 
    die('Access denied....');
};

function query($sql,$data=[], $check=false){
    global $conn;
    echo '<br>';
    try{
        $statement = $conn->prepare($sql);
        if(!empty($data)){
            $ketqua = $statement->execute($data);
        }
        else{
            $ketqua = $statement->execute();
        }
    }
    catch(Exception $e){
        echo ''.$e->getMessage().'';
        die();
    }
    if($check)
    {
        return $statement; // trả về dữ liệu 
    }
    return $ketqua;
}

function insert($table,$data){ // thêm dữ liệu 
    $key = array_keys($data); // lấy ra thành chuỗi
    $truong = implode(',', $key); // nối giá trị
    $valuetb =':'.implode(',:',$key); //  nối giá trị
    $sql = 'INSERT INTO '. $table . '('.$truong.')'.'VALUES('.$valuetb .')' ; // truyền dữ liệu vào database 
    $kq =query($sql,$data);
    return $kq;
}

function update($table,$data,$condition){ // update dữ liệu 
        $update = '';
        foreach ($data as $key => $value) {
            $update .= $key .'= :'. $key .' ,'; // nối chuỗi update
        }
        $update = trim($update,','); // xóa dấu phẩy cuối
        if(!empty($condition)){
            $sql ='UPDATE '.$table.' SET '.$update.' WHERE '.$condition ;
        }
        else {
            $sql ='UPDATE '.$table.' SET '.$update;
        }
        $kq = query($sql,$data);
        return $kq; // UPDATE user SET fullname= :fullname ,email= :email ,phone= :phone WHERE 2 
}

function delete($table,$condition=''){  // xóa phần tử ở vị trí 1 
    if (empty($condition)){
        $sql = 'DELETE FROM '. $table;
    }
    else {
        $sql='DELETE FROM ' .$table .' WHERE ' . $condition;
    }
    $kq = query($sql);
    return $kq;

}// DELETE FROM user WHERE id = 1

function GetRaw($sql){ // lấy nhiều dòng dữ liệu 
    $sq = query($sql,'',true);
    if(is_object( $sq )) {
        $dataFetch = $sq->fetchAll(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
 

function OneRaw($sql){ // lấy 1 dòng dữ liệu 
    $sq = query($sql,'',true);
    if(is_object( $sq )) {
        $dataFetch = $sq->fetch(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
 

function GetRow($sql){ // đếm dòng
    $sq = query($sql,'',true);
    if(!empty( $sq )) {
        return $sq ->rowcount();
    }
}
 
