<?php
    class cURL {
	var $headers;
	var $user_agent;
	var $compression;
	var $cookie_file;
	var $proxy;
	function __construct($cookies=TRUE,$cookie='cookies.txt',$compression='gzip',$proxy='') {
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg, application/xml, text/xml, */*; q=0.01';
		//$this->headers[] = 'Accept-Language: vi-VN,vi;q=0.8,en-US;q=0.5,en;q=0.3';// su dung cho VASP
		//$this->headers[] = 'Accept-Encoding: gzip, deflate';	// VASP
		//$this->headers[] = 'Faces-Request:	partial/ajax';// VASP
		//$this->headers[] = 'X-Requested-With: XMLHttpRequest';// VASP
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded; charset=UTF-8';
        //$this->headers[] = 'Content-type: text/html; charset=UTF-8';
		$this->user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36';
		$this->compression=$compression;
		$this->proxy=$proxy;
		$this->cookies=$cookies;
		if ($this->cookies == TRUE) {//$this->cookie($cookie);
                    $this->cookie_file=$cookie;
                }
	}
	function cookie($cookie_file) {
		if (file_exists($cookie_file)) {
			$this->cookie_file=$cookie_file;
		} else {
			fopen($cookie_file,'w') or $this->error('The cookie file could not be opened. Make sure this directory has the correct permissions');
			$this->cookie_file=$cookie_file;
			fclose($this->cookie_file);
		}
	}
    function sendemail($tieude,$email,$emailcc = ''){
        // ./ = G:\xampp\htdocs\vas\tracuu\thietke
        
		require_once('./mail/PHPMailerAutoload.php');
        $to = $email;
        $from ='cothenoi741@gmail.com';
        $body = "$tieude";
        $from_name = 'hệ thống application';
        $subject = "$tieude";
        $username = "cothenoi741@gmail.com";
        $password = "9d088689485bd614A@12";
        
           $mail = new PHPMailer;  // create a new object
           $mail->IsSMTP(); // enable SMTP
           //$mail->SMTPDebug = 3;  // debugging: 1=errors and messages, 2=messages only
           $mail->SMTPAuth = true;  // authentication enabled
           $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
           $mail->Host = 'smtp.gmail.com';
           $mail->Port = 465;
           $mail->Username = $username;
           $mail->Password = $password;
           
		   if(!empty($emailcc) ){
			   $mail->addCC($emailcc);
		   }
		   
		   $mail->SetFrom($from, $from_name);
           $mail->Subject = $subject;
           $mail->Body = $body;
           $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
           $mail->AddAddress($to);
           $mail->CharSet="utf-8";
           $mail->IsHTML(true);
           if(!$mail->Send()) {echo $error = 'Mail error: '.$mail->ErrorInfo;
           } else { echo $error = 'Đã gửi thư'; return true; }
	 }
	function get($url,$email='') {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process,CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 300);
        curl_setopt($process, CURLOPT_CONNECTTIMEOUT, 30);
		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		$return = curl_exec($process);
        if(curl_error($process))
        {
			$arr = array(47,52,56);
            if( !in_array( curl_errno($process),$arr ) ){
                $html = curl_error($process).'<br>';
                echo $html.= 'num:<'.curl_errno($process).'>';
                echo 'lỗi không kết nối được đến ' . $url . ' <br>';
                echo 'proxy = ' . $this->proxy;
                die;
                if(!empty($email)){
                    $this->sendemail('máy chủ: '.$html,'',$email);
                }
				
                die;
            }
        }
		curl_close($process);
		return $return;
	}
	function post($url,$data,$email='') {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		curl_setopt($process, CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 300);
        curl_setopt($process, CURLOPT_CONNECTTIMEOUT, 30);
		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
		curl_setopt($process, CURLOPT_POSTFIELDS, $data);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process, CURLOPT_POST, 1);
		$return = curl_exec($process);
        if(curl_error($process))
        {
			$arr = array(47,52,56);
            if( !in_array( curl_errno($process),$arr ) ){
                $html = curl_error($process).'<br>';
                echo $html.= 'num:<'.curl_errno($process).'> <br>';
                echo 'lỗi không kết nối được đến ' . $url . ' <br>';
                echo 'proxy = ' . $this->proxy;
                die;
				if(!empty($email)){
                    $this->sendemail('máy chủ: '.$html,'',$email);
                }
                die;
            }
        }
		curl_close($process);
		return $return;
	}
    function post_file($url,$post_name,$post_file) {
		$process = curl_init($url);
		//curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		if ($this->cookies == TRUE) curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		//curl_setopt($process, CURLOPT_ENCODING , $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 300);

        //function postfile
        $this->curl_custom_postfields($process,$post_name,$post_file);

        if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
		//curl_setopt($process, CURLOPT_POSTFIELDS, $data);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);// dang ngi ngo
		//curl_setopt($process, CURLOPT_POST, 1);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}

    ///func tion posst file
    function curl_custom_postfields($ch, array $assoc = array(), array $files = array()) {

        // invalid characters for "name" and "filename"
        static $disallow = array("\0", "\"", "\r", "\n");

        // build normal parameters
        foreach ($assoc as $k => $v) {
            $k = str_replace($disallow, "_", $k);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"",
                "",
                filter_var($v),
            ));
        }

        // build file parameters
        foreach ($files as $k => $v) {
            switch (true) {
                case false === $v = realpath(filter_var($v)):
                case !is_file($v):
                case !is_readable($v):
                    continue; // or return false, throw new InvalidArgumentException
            }
            //$image_mime = image_type_to_mime_type(exif_imagetype($v));
            $data = file_get_contents($v);
            $v = call_user_func("end", explode(DIRECTORY_SEPARATOR, $v));
            $k = str_replace($disallow, "_", $k);
            $v = str_replace($disallow, "_", $v);
            $body[] = implode("\r\n", array(
                "Content-Disposition: form-data; name=\"{$k}\"; filename=\"{$v}\"",
                "Content-Type: image/jpeg",
                "",
                $data,
            ));
        }

        // generate safe boundary
        do {
            $boundary = "---------------------" . md5(mt_rand() . microtime());
        } while (preg_grep("/{$boundary}/", $body));

        // add boundary for each parameters
        array_walk($body, function (&$part) use ($boundary) {
            $part = "--{$boundary}\r\n{$part}";
        });

        // add final boundary
        $body[] = "--{$boundary}--";
        $body[] = "";

        // set options
        return @curl_setopt_array($ch, array(
            CURLOPT_POST       => true,
            CURLOPT_POSTFIELDS => implode("\r\n", $body),
            CURLOPT_HTTPHEADER => array(
                "Expect: 100-continue",
                "Content-Type: multipart/form-data; boundary={$boundary}", // change Content-Type
            ),
        ));
    }

	function error($error) {
		echo "<center><div style='width:500px;border: 3px solid #FFEEFF; padding: 3px; background-color: #FFDDFF;font-family: verdana; font-size: 10px'><b>cURL Error</b><br>$error</div></center>";
		die;
	}
}
?>