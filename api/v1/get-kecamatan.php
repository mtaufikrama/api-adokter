<?php
include "cek-token.php";

//id_kota
if ($id_kota == '') {
	$datax['code']=500;
	$datax['msg']="ID Kota tidak ada";
	echo json_encode($datax);
	die();
}

$SqlGetSpesialisasi="SELECT id_dc_kecamatan as kode, nama_kecamatan as nama from dc_kecamatan where id_dc_kota=$id_kota;";

$RunGetSpesialisasi=$db->Execute($SqlGetSpesialisasi);

while($TplGetSpesialisasi=$RunGetSpesialisasi->fetchRow()){
	$data[]=$TplGetSpesialisasi;
}

if(is_array($data)){
	$datax['code']=200;
	$datax['list']=$data;
}else{
	$datax['code']=500;
	$datax['msg']="Tidak ada data ditemukan";
}
echo json_encode($datax);
?>