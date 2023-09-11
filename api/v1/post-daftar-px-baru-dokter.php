<?php

include "cek-token.php";
include "../../_lib/function/function.olah_tabel_demo.php";

// kode_bagian, sip, email, no_hp, nama, no_rek, bank, nama_pemilik, referensi

$kode_rs = 'A00001';

$cekSebelummasukemail=baca_tabel("mt_karyawan", "count(email)", "  where email='$email'");

if($cekSebelummasukemail > 0) {

    $datarest['code']=500;
    $datarest['msg']="Email Sudah Terdaftar";
    echo json_encode($datarest);
    die();

}

$cekSebelummasukhp=baca_tabel("mt_karyawan", "count(telp)", "  where telp='$no_hp'");

if($cekSebelummasukhp > 0) {

    $datarest['code']=500;
    $datarest['msg']="No Hp Sudah Terdaftar";
    echo json_encode($datarest);
    die();
}
/****************************************************************************************/
/*BUAT KODE*/
$kode_depan="LP";// CALON SURVIOR // DITAMBAHKAN C DIBELAKANG

$kode_dokter 	= max_kode_number("mt_karyawan", "kode_dokter");
$no_induk 		= max_kode_text("mt_karyawan", "no_induk", "");

unset($insertMtKaryawan);
$insertMtKaryawan["no_induk"] 			= $no_induk;
$insertMtKaryawan["kode_dokter"] 		= $kode_dokter;
$insertMtKaryawan["nama_pegawai"]		= $nama;
$insertMtKaryawan["kode_bagian"] 		= $kode_bagian;
$insertMtKaryawan["flag_dokter"] 		= '1';
$insertMtKaryawan["telp"] 			    = $no_hp;
$insertMtKaryawan["email"] 				= $email;
$insertMtKaryawan["no_rek"] 			= $no_rek;
$insertMtKaryawan["nama_bank"] 		    = $id_bank;
$insertMtKaryawan["nama_bank_pemilik"] 	= $nama_pemilik;
$insertMtKaryawan["referensi"] 			= $referensi;
$insertMtKaryawan["tgl_masuk"] 			= date("Y-m-d H:i:s");
$insertMtKaryawan["kode_spesialisasi"] 	= '1';
$insertMtKaryawan["no_induk_dokter"] 	= $sip;
$insertMtKaryawan["flag_device"] 	    = 2;
$insertMtKaryawan["flag_verifikasi"] 	= 0;
$result 								= insert_tabel("mt_karyawan", $insertMtKaryawan);
$resultDemo 							= insert_tabel_demo("mt_karyawan", $insertMtKaryawan);

$id_mt_karyawan 						= baca_tabel('mt_karyawan', 'id_mt_karyawan', "where email='$email'");

unset($insertDokterDetail);
$insertDokterDetail["kode_dokter"] 		= $kode_dokter;
$insertDokterDetail["kode_bagian"] 		= $kode_bagian;
$insertDokterDetail["no_izin_praktek"] 	= $sip;

if($result) {
    $result = insert_tabel("mt_dokter_detail", $insertDokterDetail);
}

if($resultDemo) {
    $resultDemo = insert_tabel_demo("mt_dokter_detail", $insertDokterDetail);
}





//***********************************************************************************//
unset($insertDokterBagian);
$insertDokterBagian["kode_dokter"] 		= $kode_dokter;
$insertDokterBagian["kode_bagian"]		= $kode_bagian;
$insertDokterBagian["kd_bagian"]		= $kode_bagian;

if($result) {
    $result = insert_tabel("mt_dokter_bagian", $insertDokterBagian);
}

if($resultDemo) {
    $resultDemo = insert_tabel_demo("mt_dokter_bagian", $insertDokterBagian);
}

$arrUsername=explode("@", $email);
$username = $arrUsername[0];
$password=$username."123!";

unset($insertDdUser);
$insertDdUser["username"] = $email;
$insertDdUser["password"] = md5($password);
$insertDdUser["no_induk"] = $no_induk;
$insertDdUser["id_dd_user_group"] 	= 1214;
$insertDdUser["status"] = 1;
$insertDdUser["ko_wil"] = 101;
$insertDdUser["kode_dokter"] = round($kode_dokter); //tambahan
$insertDdUser["kode_bagian"] = $kode_bagian; //tambahan
$insertDdUser["input_tgl"] = date("Y-m-d");
$insertDdUser["id_mt_karyawan"] 	= $id_mt_karyawan;
// $insertDdUser["kode_klinik"] 		= $kode_rs;
if($result) {
    $result = insert_tabel("dd_user", $insertDdUser);
} else {
    $resultInsert['code'] = 500;
    $resultInsert['msg'] = 'Maaf, Data Dokter tidak berhasil di tambahkan!';
    echo json_encode($resultInsert);
    die();
}
$insertDdUser["status"] = 0;

if ($resultDemo) {
    $result = insert_tabel_demo("dd_user", $insertDdUser);
}

$dokter['email'] = $email;
$dokter['password'] = $password;
$dokter['kode_dokter'] = $kode_dokter;
$dokter['sip'] = $sip;
$dokter['id_mt_karyawan'] = $id_mt_karyawan;

$data['code'] = 200;
$data['dokter'] = $dokter;
// $data['MT karyawan'] = $insertMtKaryawan;
// $data['Dokter Detail'] = $insertDokterDetail;
// $data['Dokter bagian'] = $insertDokterBagian;
// $data['DD User'] = $insertDdUser;

echo json_encode($data);


$emailUser = $email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "../../library/PHPMailer.php";
require_once "../../library/Exception.php";
require_once "../../library/OAuth.php";
require_once "../../library/POP3.php";
require_once "../../library/SMTP.php";

// $mail->SMTPDebug = 3;
$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = "mbx.averin.co.id"; //host mail server (sesuaikan dengan mail hosting Anda)
$mail->SMTPAuth = true;

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
foreach ($recipients as $email => $name) {
    $mail->AddCC($email, $name);
}

$mail->isHTML(true);
$subject = 'RESET PASSWORD A-DOKTER';
$message = $keterangan;

$mail->isHTML(true);
$subject = 'PASSWORD AKTIVASI A-DOKTER';
$message = "Dear " . $nama;
$message .= "<br>";
$message .= "<br>";
$message .= "Halo " . $nama;
$message .= "<br>";
// $message .= "Selamat kami dari team A-Dokter , menginformasikan bahwa Sistem A-Dokter telah dapat digunakan dan bapak/ibu yang telah terdaftar dapat melengkapi data-data secara langsung dengan menggunakan email yang sudah terdaftar dan password yang kami kirimkan melalui email";
$message .= "Selamat anda telah mendaftar sebagai Mitra a-Dokter , diinformasikan kepada bapak/ibu Mitra a-Dokter yang telah terdaftar diharapkan untuk dapat melengkapi data-data secara langsung dengan menggunakan email yang sudah terdaftar dan password yang kami kirimkan melalui email";
$message .= "<br>";
// $message .= "<br>";
// $message .= "Login a-Dokter : https://a-dokter.id/form_login.php";
$message .= "<br>";
$message .= "Username      			: " . $emailUser;
$message .= "<br>";
$message .= "Password             	: " . $password;
$message .= "<br>";
$message .= "<br>";

$message .= "Adapun untuk Mitra a-Dokter yang ingin melakukan trial aplikasi, Mitra a-Dokter dapat login melalui link berikut : https://demo.a-dokter.id/form_login.php dengan menggunakan username dan password seperti diatas.";
$message .= "<br>";
$message .= "Jika bapak/ibu Mitra a-Dokter belum mendaftarkan diri ke Satu Sehat Kemenkes RI, bapak/ibu Mitra A-Dokter dapat mengakses melalu link berikut : https://satusehat.kemkes.go.id/platform/welcome";
$message .= "<br>";
$message .= "Kami berharap bapak/ibu langsung menganti password untuk menjaga kerahasiaan, apabila ada kesulitan dalam menggunakan aplikasi silahkan hubungi kami";

$message .= "<br>";

$message .= "<br>";


$message .= "Terimakasih";
$message .= "<br>";
$message .= "Salam a-Dokter";
/****************************************************************************************/
$mail->Subject = $subject;
$mail->Body = nl2br($message); //isi email
$mail->AltBody = "PHP mailer"; //body email
$mail->send();


/****************************************************************************************/
