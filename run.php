<?php 
/**
*@author yudha tira pamunkas
*@version 1.0
*/

function curl($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, true);

	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function check_email($email) {
	$check_email = curl('https://apiyudha.000webhostapp.com/spotify/check_mail.php?email='.$email);

	if (strrpos($check_email, 'registered')) {
		$result = "registered";
	} elseif (strrpos($check_email,  'ok')) {
		$result = "ok";
	} else {
		$result = "failed";
	}
	return $result;

}

function create_account($email,$password) {
	$create = curl('https://spclient.wg.spotify.com:443/signup/public/v1/account/', 'iagree=true&birth_day=12&platform=Android-ARM&creation_point=client_mobile&password='.$password.'&key=142b583129b2df829de3656f9eb484e6&birth_year=2000&email='.$email.'&gender=male&app_version=849800892&birth_month=12&password_repeat='.$password.'');

	if (strrpos($create, 'success')) {
		echo "[+] Success | ".$email." | ".$password."\n";
		$data = "[+] Success | ".$email." | ".$password."\r\n";
		$fopen = fopen('result.txt', 'a');
		fwrite($fopen, $data);
		fclose($fopen);
	} else {
		echo "[!] Failed | ".$email." | ".$password."\n";
	}
}


function start() {
	print("=============================\n");
	print("   Spotify account creator  \n");
	print("         Version 1.0        \n");
	print("   Created by yudha tira p  \n");
	print("=============================\n");
	echo "\n[#] Email: ";
	$email = trim(fgets(STDIN));

	if ($email != "") {
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "[!] Invalid email address\n";
		} else {
			echo "[~] Checking email";
			for ($i=0; $i < 3; $i++) { 
				usleep(500000);
				echo ".";
			}
			echo "\n";
			$check = check_email($email);

			if ($check == "registered") {
				echo "[!] Email already register | ".$email."\n";
			} elseif ($check == "ok") {
				echo "[#] Password: ";
				$password = trim(fgets(STDIN));
				if ($password != "") {
					create_account($email, $password);
				} else {
					echo "[!] Password cannot be blank\n";
				}
			} elseif ($check == "failed") {
				echo "[!] Failed | ".$email."\n";
			}
		}		
	} else {
		echo "[!] Email cannot be blank\n";
	}
}

start();

while (true) {
	echo "\nWanna register again? (y/n): ";
	$select = trim(fgets(STDIN));
	if ($select == "y") {
		echo "\n";
		start();
	} else {
		die("Done!\n");
	}
}

?>
