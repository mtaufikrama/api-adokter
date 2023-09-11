<?php
include "cek-token.php";
include "../../_lib/function/function.olah_tabel_demo.php";

//nama, email, no_hp, universitas, no_induk_mahasiswa, jenjang_pendidikan, fakultas, tahun_masuk, semester

$cekSebelummasukemail=baca_tabel("mt_karyawan","count(email)","  where email='$email'");

if($cekSebelummasukemail > 0 ){
	$datarest['code']=500;
	$datarest['msg']="Email Sudah Terdaftar";
	echo json_encode($datarest);
	die();
}

$cekSebelummasukhp=baca_tabel("mt_karyawan","count(telp)"," where telp='$no_hp'");

if($cekSebelummasukhp > 0 ){
	$datarest['code']=500;
	$datarest['msg']="No Hp Sudah Terdaftar";
	echo json_encode($datarest);
	die();
}

$kode_dokter 	= max_kode_number("mt_karyawan", "kode_dokter");
$no_induk 		= max_kode_text("mt_karyawan", "no_induk", "");

unset($insertMtKaryawan);
$insertMtKaryawan["no_induk"] 			= $no_induk;
$insertMtKaryawan["kode_dokter"] 		= $kode_dokter;
// $insertMtKaryawan["kode_bagian"] 		= $kode_bagian;		
$insertMtKaryawan["flag_dokter"] 		= '3';
$insertMtKaryawan["nama_pegawai"]		= $nama;
$insertMtKaryawan["email"] 				= $email;
$insertMtKaryawan["telp"] 				= $telp;
$insertMtKaryawan["instansi"] 			= $universitas;
$insertMtKaryawan["no_induk_dokter"] 	= $no_induk_mahasiswa;
$insertMtKaryawan["fakultas"] 			= $fakultas;
$insertMtKaryawan["jenjang_pendidikan"]	= $jenjang_pendidikan;
// $insertMtKaryawan["spesialis"] 			= $spesialis;
$insertMtKaryawan["tgl_masuk"] 			= date("Y-m-d H:i:s");
$insertMtKaryawan["tahun_masuk"] 		= $tahun_masuk;
$insertMtKaryawan["semester"] 			= $semester;
$insertMtKaryawan["referensi"] 			= $referensi;
$insertMtKaryawan["flag_device"] 	    = 2;

$result 								= insert_tabel("mt_karyawan", $insertMtKaryawan);
$resultDemo 							= insert_tabel_demo("mt_karyawan", $insertMtKaryawan);

$id_mt_karyawan 						= baca_tabel('mt_karyawan','id_mt_karyawan',"where kode_dokter='$kode_dokter'"); 

unset($insertDokterDetail);
$insertDokterDetail["kode_dokter"] 		= $kode_dokter;
// $insertDokterDetail["kode_bagian"] 		= $kode_bagian;

if($result)	$result = insert_tabel("mt_dokter_detail", $insertDokterDetail);
if($resultDemo)	$resultDemo = insert_tabel_demo("mt_dokter_detail", $insertDokterDetail);

unset($insertDokterBagian);
$insertDokterBagian["kode_dokter"] 		= $kode_dokter;
// $insertDokterBagian["kode_bagian"]		= $kode_bagian;
// $insertDokterBagian["kd_bagian"]		= $kode_bagian;

if($result)	$result = insert_tabel("mt_dokter_bagian", $insertDokterBagian);
if($resultDemo)	$resultDemo = insert_tabel_demo("mt_dokter_bagian", $insertDokterBagian);

$arrUsername=explode("@",$email);
$username = $arrUsername[0];
$password=$username."123!";

unset($insertDdUser);
$insertDdUser["username"] 			= $email;
$insertDdUser["password"] 			= md5($password);
$insertDdUser["no_induk"] 			= $no_induk;
$insertDdUser["id_dd_user_group"] 	= 1216;
$insertDdUser["status"] 			= 0;
$insertDdUser["ko_wil"] 			= 101;
$insertDdUser["input_id"] 			= $loginInfo["id_dd_user"];
$insertDdUser["input_tgl"] 			= date("Y-m-d");
$insertDdUser["id_mt_karyawan"] 	= $id_mt_karyawan;
$insertDdUser["kode_klinik"] 		= $kode_user;
if($result){
	$result = insert_tabel("dd_user", $insertDdUser);
} else {
	$resultInsert['code'] = 500;
	$resultInsert['msg'] = 'Maaf, Data Dokter tidak berhasil di tambahkan!';
	echo json_encode($resultInsert);
	die();
}
if($resultDemo){
	$resultDemo = insert_tabel_demo("dd_user", $insertDdUser);
} else {
	$resultInsert['code'] = 500;
	$resultInsert['msg'] = 'Maaf, Data Dokter tidak berhasil di tambahkan!';
	echo json_encode($resultInsert);
	die();
}

$mahasiswa['no_induk'] = $no_induk;
$mahasiswa['kode'] = $kode_dokter;
$mahasiswa['no_induk_mahasiswa'] = $no_induk_mahasiswa;
$mahasiswa['email'] = $email;
$mahasiswa['password'] = $password;
$mahasiswa['nama'] = $nama;
$mahasiswa['id_mt_karyawan'] = $id_mt_karyawan;

// $data['code'] = 200;
$data['mahasiswa'] = $mahasiswa;
// $data['MT karyawan'] = $insertMtKaryawan;
// $data['Dokter Detail'] = $insertDokterDetail;
// $data['Dokter bagian'] = $insertDokterBagian;
// $data['DD User'] = $insertDdUser;

echo json_encode($data);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../../library/PHPMailer.php";
require_once "../../library/Exception.php";
require_once "../../library/OAuth.php";
require_once "../../library/POP3.php";
require_once "../../library/SMTP.php";

// $mail->SMTPDebug = 3;
$mail = new PHPMailer;      
$mail->isSMTP();                          
//$mail->Host = "srv42.niagahoster.com"; //host mail server (sesuaikan dengan mail hosting Anda)
// $mail->Host = "smtp.gmail.com"; //host mail server (sesuaikan dengan mail hosting Anda)
$mail->Host = "mbx.averin.co.id"; //host mail server (sesuaikan dengan mail hosting Anda)
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;     
//Provide username and password               
//$mail->Username = "saga@averin.co.id";   //nama-email smtp     
//$mail->Password = "Averin1234!";           //password email smtp  
               
$mail->Username = "adokter@averin.co.id";   //nama-email smtp  
$mail->Password = "Averin@2023!";           //password email smtp  

$mail->SMTPSecure = "ssl"; 
$mail->Port = 465;  
$mail->From = "adokter@averin.co.id"; //email pengirim
$mail->FromName = "A-DOKTER"; //nama pengirim
$mail->addAddress($email, "");

$recipients = array(
'adokter@averin.co.id' => 'CC 1',
// ..
);/**/
foreach($recipients as $email => $name)
{
   $mail->AddCC($email, $name);
}

$mail->isHTML(true);
$subject='REGISTRASI A-DOKTER';
$message=$keterangan;

$mail->isHTML(true);
$subject='PASSWORD AKTIVASI A-DOKTER';
$message ="Dear " .$nama;
$message .="<br>";
$message .="Halo ".$nama;
$message .="<br>";
// $message .="Selamat kami dari team A-Dokter, menginformasikan bahwa Sistem A-Dokter telah dapat digunakan dan bapak/ibu yang telah terdaftar dapat melengkapi data-data secara langsung dengan menggunakan email yang sudah terdaftar dan password yang kami kirimkan melalui email";
$message .= "Selamat anda telah mendaftar sebagai Mitra A-Dokter , diinformasikan kepada bapak/ibu Mitra A-Dokter yang telah terdaftar diharapkan untuk dapat melengkapi data-data secara langsung dengan menggunakan email yang sudah terdaftar dan password yang kami kirimkan melalui email";
$message .= "<br>";
$message .= "<br>";
// $message .= "Login A-Dokter : https://a-dokter.id/form_login.php";
$message .= "<br>";
$message .= "Username      			: " . $username;
$message .= "<br>";
$message .= "Password             	: " . $password;
$message .= "<br>";
$message .= "<br>";

$message .= "Adapun untuk Mitra A-Dokter yang ingin melakukan trial aplikasi, Mitra A-Dokter dapat login melalui link berikut : https://demo.a-dokter.id/form_login.php dengan menggunakan username dan password seperti diatas.";
$message .= "<br>";
$message .= "Jika bapak/ibu Mitra A-Dokter belum mendaftarkan diri ke Satu Sehat Kemenkes RI, bapak/ibu Mitra A-Dokter dapat mengakses melalu link berikut : https://satusehat.kemkes.go.id/platform/welcome";
$message .= "<br>";
$message .= "Kami berharap bapak/ibu langsung menganti password untuk menjaga kerahasiaan, apabila ada kesulitan dalam menggunakan aplikasi silahkan hubungi kami";

$message .= "<br>";

$message .= "<br>";


$message .= "Terimakasih";
$message .= "Salam A-Dokter";
/****************************************************************************************/	
		$mail->Subject = $subject;
		$mail->Body = nl2br($message); //isi email
		$mail->AltBody = "PHP mailer"; //body email
		$mail->send();

if(!$mail->send()) {
	// echo "test";
	// echo 'Message could not be sent.';
	// echo 'Mailer Error: ' . $mail->ErrorInfo;
	// $result=false;
	
} else {

} 
?>