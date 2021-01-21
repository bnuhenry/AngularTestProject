<?php
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:*');
header('Access-Control-Allow-Headers:content-type,token,id');
header("Access-Control-Request-Headers: Origin, X-Requested-With, content-Type, Accept, Authorization");
header("Content-Type: application/json; charset=utf-8");
//接受前端发来的数据
$postData = file_get_contents('php://input', true);
// $dataary = json_decode($postData);
$obj = json_decode($postData);

$realmip = "127.0.0.1";
$realmport = "8085";
$ip = "127.0.0.1:3306";
$user = "root";
$pass = "tbchenry";
$db = "wechat";
$key = "omgthatsreally@coolyesitis";
$time = "";
global $con;
$dataary = array();

//声明需要输出前端的简单类
class Result{
    var $username = "";
    var $result = "";
    var $success = "";
    var $sign = "";
    }

$resultobj = new Result();
$resultobj->result = "已连接数据库，一切正常";
$resultobj->success = "false";
if($obj->starttime>0){
    $time = strval($obj->starttime);
}else{
    $time = "thereisnodatacomeout";
}
$resultobj->sign = sha1($key."".$time);

if($obj->sign!=$resultobj->sign){
    $resultobj->result = "接口已被篡改";
    echo json_encode($resultobj);
}else{
    $con = mysqli_connect($ip, $user, $pass,$db);
    if (mysqli_connect_errno())
    {
        echo "连接失败: " . mysqli_connect_error();
    }
        unset($query);
        mysqli_query($con, "set names 'UTF8'");
        $query = mysqli_query($con,"SELECT * FROM payment WHERE time>'" . $obj->starttime . "'AND time<'" . $obj->endtime . "' ORDER BY time DESC");
    
        while($row = mysqli_fetch_assoc($query)){
            array_push($dataary,$row);
        }
        mysqli_close($con);
        echo json_encode($dataary);
}

?>