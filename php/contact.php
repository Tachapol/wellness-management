<?php
    require_once('connect.php');
    if(isset($_POST['btn-submit'])){
        // recaptchaine
        $secretKey = '6LePaAAcAAAAAA7BueQKBLcx8EGP16Lex02rDxDh';
        $response = $_POST['g-recaptcha-response'];
        $remoteip = $_SERVER['REMOTE_ADDR'];

        $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$response&remoteip=$remoteip";
        $resp = json_decode(file_get_contents($url));
        if($resp->success) {
            echo '<pre>',print_r($_POST),'</pre>';
            $sql = "INSERT INTO `contacts` (`name`, `phone`, `email`, `line`, `detail`, `created_at`)
                    VALUES ('".$_POST['name']."',
                             '".$_POST['phone']."',
                             '".$_POST['email']."',
                             '".$_POST['line']."',
                             '".$_POST['message']."',
                             '".date("Y-m-d")."');";
            $result = $conn->query($sql) or die($conn->error);
            if ($result) {
                sendToLine();
            }

        } else {
            echo '<script> alert("Verification Failed!")</script>';
            header('Refresh:0;  url=../en/contact.php');
        }
    } else {
        header('Refresh:0; url=../index.php');
    }

    function sendToLine(){ 
        $text .= "\nName : ". $_POST['name']."\n";         
        $text .= "Phone : ". $_POST['phone']."\n";  
        $text .= "Email : ". $_POST['email']."\n";
        $text .= "Line : ". $_POST['line']."\n";
        $text .= "Message : \n". $_POST['message'];  

        $message = array(
            'message' => $text);
        notify_message($message);
    }

    function notify_message($message) {
        define('LINE_API',"https://notify-api.line.me/api/notify");
        // Active Aura line api
        define('LINE_TOKEN',"PvIBA1DSKLVGsYHjjVYTBcuRAovXmfTkLyvItmT8OzL"); 
        $queryData = http_build_query($message,'','&');
        $headerOptions = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
                          ."Authorization: Bearer ".LINE_TOKEN."\r\n"
                          ."Content-Length: ".strlen($queryData)."\r\n",
              'content'=> $queryData
            )
        );

        $context = stream_context_create($headerOptions);
        $result = file_get_contents(LINE_API,FALSE,$context);
        $resp = json_decode($result);
        if ($resp->status == 200) {
            echo '<script> alert("Your submission has been sent")</script>'; 
            header('Refresh:0; url=../index.php');
        } else {
           echo '<script> alert("Error please contact admin")</script>'; 
        //    print_r($resp);
        //    header('Refresh:0; url=../index.php');
    }
}
?>