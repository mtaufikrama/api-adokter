<?php
ini_set('max_execution_time', '0');
include "../../_lib/function/db_login.php";
include "../src/jwt.php";
include "../../_lib/function/function.olah_tabel.php";
include "../../_lib/function/function.max_kode_number.php";
header('Content-Type: application/json; charset=utf-8');
// $db->debug=true;
$useRR=extrakToken($token);
// print_r($useRR);
/* -- cek akses out -- */
$KodeApi	=$useRR['uid'];
$KeyApi		=$useRR['uname'];
$KeyCode	=$useRR['password'];
$iss		=$useRR['iss'];
$SqlGetToken="select IDApi,KodeApi,KeyApi,KeyCode from ref_api_dmedis where KodeApi='$KodeApi' AND KeyApi='$KeyApi' AND KeyCode='$KeyCode'";
$RunGetToken=$db->Execute($SqlGetToken);
while($TplGetToken=$RunGetToken->fetchRow()){
	foreach($TplGetToken as $key=>$val){
		$$key=$val;
	}
}
if($IDApi < 1){
	$Resdata['code']=500;
	$Resdata['msg']='Akses Ditolak';
	echo json_encode($dataRes);
	die;
}
/* -- cek time out -- */
if($useRR['exp']< strtotime(date("Y-m-d H:i:s"))){
	$dataRes['code']=500;
	$dataRes['msg']='Batas waktu akses selesai, silahkan generate token ulang :)';
	echo json_encode($dataRes);
	die;
}
// $dataSend = json_decode(file_get_contents("php://input"),TRUE);
// foreach($dataSend as $key=>$val){
// 	$$key=$val;
// }

foreach($_GET as $key=>$val){
	$$key=$val;
}
?>