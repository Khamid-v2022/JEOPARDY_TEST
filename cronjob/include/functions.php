<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once __DIR__ . "/../../vendor/autoload.php";
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
    $dotenv->load();

    function send_email($email, $mail_subject, $mail_content){
        $mail = new PHPMailer(true); 

        try {
            $mail->SMTPDebug = false; 
            $mail->CharSet = 'UTF-8'; // Set the character encoding to UTF-8
            $mail->Encoding = 'quoted-printable';  
            // $mail->SMTPDebug = 4;                                       
            $mail->isSMTP();                                            
            $mail->Host       = $_ENV['MAIL_HOST'];                    
            $mail->SMTPAuth   = true;                             
            $mail->Username   = $_ENV['MAIL_USERNAME'];                 
            $mail->Password   = $_ENV['MAIL_PASSWORD'];                        
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];                              
            $mail->Port       = $_ENV['MAIL_PORT'];  
        
            $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], "J!Study App");           
            $mail->addAddress($email);
            
            $mail->isHTML(true);                                  
            $mail->Subject = $mail_subject;
            $mail->Body = $mail_content;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
?>