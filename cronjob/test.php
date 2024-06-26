<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "include/db_configure.php";
    require_once "include/functions.php";

    sendEmailForReminder();

    $conn->close();

    function sendEmailForReminder() {
        global $conn;

        // get users
        $sql_users = "SELECT `id`, `name`, `email`, `is_trial_used`, `last_reminder_emailed_at`, `last_tested_at`, `created_at` FROM `users` WHERE `is_email_verified` = 1 AND `subscription_status` = 0 AND `is_receive_email` = 1";
        $result_users = $conn->query($sql_users);

        if($result_users->num_rows == 0){
            echo "No users to send email" . PHP_EOL . "<br/>";
            return;
        }

        while($row = $result_users->fetch_assoc()) {
            $now = strtotime(date("Y-m-d H:00:00"));

            if(!$row['last_reminder_emailed_at']) {
                $created_date = strtotime($row['created_at']);

                $datediff = $now - $created_date;
                $diff_days = (int)($datediff / (60 * 60 * 24));

                if($diff_days >= 1) {
                    if($row['is_trial_used'] == 0) {

                        $encrypted_email = str_replace('/', '-:-', encrypt($row['email']));
                        $mail_subject = "You haven't taken your free test session at J!Study App";
                        $mail_content = get_email_content($row['name'], $encrypted_email);
                        send_email($row['email'], $mail_subject, $mail_content);

                        $current_date = date("Y-m-d H:i:s");
                        $update_sql = "UPDATE `users` SET `last_reminder_emailed_at` = '" . $current_date . "', `updated_at` = '" . $current_date . "' WHERE `id` = " . $row['id'];
                        $conn->query($update_sql);
                    } else {
                        // did trial test already so send an email after 24 hours later from the last tested at
                        if($row['last_tested_at']) {
                            $last_tested_at = strtotime($row['last_tested_at']);

                            $datediff = $now - $last_tested_at;
                            $diff_days = (int)($datediff / (60 * 60 * 24));

                            if($diff_days >= 1) {
                               send_reminder_email($row['email'], $row['name'], $row['id']);
                            }
                        } else {
                            // first time for existing users - send an email
                            send_reminder_email($row['email'], $row['name'], $row['id']);
                        }
                       
                    }
                }
            } else {
                $last_emailed_at = strtotime($row['last_reminder_emailed_at']);
                $datediff = $now - $last_emailed_at;
                $diff_days = (int)($datediff / (60 * 60 * 24));

                if($diff_days >= 1) {
                    if($row['last_tested_at']) {
                        $last_tested_at = strtotime($row['last_tested_at']);
                        $diff_last_test = $now - $last_tested_at;
                        $diff_days_last_test = (int)($diff_last_test / (60 * 60 * 24));
                        if($diff_days >= 1 && $diff_days_last_test >= 1) {
                            send_reminder_email($row['email'], $row['name'], $row['id']);
                        }
                    } else {
                        send_reminder_email($row['email'], $row['name'], $row['id']);
                    }
                }
                
            }
        }
    }

    function send_reminder_email($email, $name, $user_id) {
        $encrypted_email = str_replace('/', '-:-', encrypt($email));
        $mail_subject = "Your daily J!Study Practice Test is Ready";
        $mail_content = get_email_content($name, $encrypted_email);
        send_email($email, $mail_subject, $mail_content);

        global $conn;
        $current_date = date("Y-m-d H:i:s");
        $update_sql = "UPDATE `users` SET `is_trial_used` = 0, `last_reminder_emailed_at` = '" . $current_date . "', `updated_at` = '" . $current_date . "' WHERE `id` = " . $user_id;
        $conn->query($update_sql);
    }

    function get_email_content($username, $encrypted_email){   
        // mail html content
        $mail_content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            </head>';
            $mail_content .= '<body style="background-color:rgba(242,242,242,1)!important">';
                $mail_content .= '<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; padding: 0; width: 570px; color: #718096;line-height: 1.75rem; background-color: white; border: 1px solid rgba(230,230,230,1)">';
                    $mail_content .= '<tr>';
                        $mail_content .= '<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">';
                            $mail_content .= '<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="padding: 32px;text-align: center;">';
                                // --header---<<
                                $mail_content .= '<tr style="text-align: center">';
                                    $mail_content .= '<td class="content-cell" style="max-width: 100vw; color:#718096;">';
                                        // $mail_content .= '<div style="display: flex; justify-content: center; align-items: center;">';
                                            $mail_content .= '<img src="https://jstudy.app/assets/img/icons/brands/brand.png">';
                                            $mail_content .= '<span style="font-size: 36px;">Study Simulator</span>';
                                        // $mail_content .= '</div>';
                                    $mail_content .= '</td>';
                                $mail_content .= '</tr>';
                                // --header--->>
      
                                // --content---<<
                                $mail_content .= '<tr style="text-align: left">';
                                    $mail_content .= '<td class="content-cell" style="max-width: 100vw; color:#718096; font-size: 16px">';
                                        $mail_content .= "<p style='color: #718096;'>Hey {$username},</p>
                                                        <p style='color: #718096;'>You've yet to take your today's free test with J!Study App. Login <a href='https://jstudy.app/' target='_blank'>here</a> to claim your free test before it expires.</p>";
                                        $mail_content .= "<p style='color: #718096; margin-top:50px'>Cheers,<br>J!Study Team</p>";
                                    $mail_content .= '</td>';
                                $mail_content .= '</tr>';
                                // --content--->>
                            $mail_content .= '</table>';
                        $mail_content .= '</td>';
                    $mail_content .= '</tr>';

                    // footer --<
                    $mail_content .= '<tr style="text-align: center; background-color: black; ">';
                        $mail_content .= '<td>';
                            $mail_content .= '<div style="color:white; padding: 16px">';
                                $mail_content .= '<p style="line-height: 17px;">J!Study is a product of loyal fans to our favorite game show. <br>The Jeopardy! game show and all elements thereof, including but not limited to copyright and trademark thereto, are the property of Jeopardy Productions, Inc. / Sony Inc. and are protected under law.</p>';
                                $mail_content .= '<p><small><a href="https://jstudy.app/unsubscribe/' . $encrypted_email . '" style="color: white" target="_blank">Unsubscribe</a> from this type of email</small></p>';
                            $mail_content .= '</div>';
                        $mail_content .= '</td>';
                    $mail_content .= '</tr>';
                    // footer -->

                $mail_content .= '</table>';
            $mail_content .= '</body>';
        $mail_content .= '</html>';
        return $mail_content;
    }


    function encrypt($message) {
        $cipher_method = 'aes-128-ctr';

        
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
        $crypted_key = openssl_encrypt($message, $cipher_method, $_ENV['ENCRYPT_KEY'], 0, $enc_iv) . "-" . bin2hex($enc_iv);

        return $crypted_key;
    }
?>