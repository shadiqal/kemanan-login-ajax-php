<?php

session_start(); 

//Mengecek jenis request apakah xmlHttpRequest
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    
    //Mendefenisikan bahwa request untuk ke file ini emang dari app kita
    if(@isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']=="http://localhost/secureLogin/") //baris ini bisa di ganti dengan domain / folder kamu
    {
        
        include 'koneksi.php';
        //pembuatan token terbaru setiap kali user login
        $tokenAkses = token();

        //Meloloskan Jenis" karakter atau sama seperti html spescial character
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $query = mysqli_query($conn, $sql);
        $data = mysqli_fetch_array($query);

        if ($data > 0){
            //Melakukan updating token
            $sql = "UPDATE user set token='$tokenAkses' WHERE username='$username'";
            if($conn->query($sql) === TRUE){
            echo $tokenAkses;
            }else{
                echo "Error loh : " . $conn->error;
            }
        } else {
        echo 0;
        }

    } else {
        echo 401;
        exit;
    }

} else{
        echo "Request tidak dikenal";
        exit;
    }

function token()
{
    $batas = 30;
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); 

    for ($i=0; $i < $batas; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}

mysqli_close($conn);

?>