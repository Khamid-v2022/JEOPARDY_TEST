<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once "include/db_configure.php";
    require_once "include/functions.php";

    sendBalanceAlertEmail();

    $conn->close();


    function sendBalanceAlertEmail() {
        global $conn;

        // get users
        $sql_users = "SELECT `id`, `name`, `email`, `is_trial_used`, `created_at` FROM `users` WHERE `is_email_verified` = 1 AND `subscription_status` = 0 AND `created_at` > (CURDATE() - INTERVAL 34 DAY)";
        $result_users = $conn->query($sql_users);

        if($result_users->num_rows == 0){
            echo "No users to send email" . PHP_EOL . "<br/>";
            return;
        }

        while($row = $result_users->fetch_assoc()){
            $today = strtotime(date("Y-m-d"));
            $created_date = strtotime($row['created_at']);

            $datediff = $today - $created_date;
            $diff_days = round($datediff / (60 * 60 * 24));

            if($diff_days == 3 && $row['is_trial_used'] == 0) {
                $mail_subject = "You haven't taken your free test session at J!Study App";
                $mail_content = get_email_content_3days($row['name']);
                send_email($row['email'], $mail_subject, $mail_content);
            } else if($diff_days == 33) {
                // give Free Trial Test one time
                $update_sql = "UPDATE `users` SET `is_trial_used` = 0, `updated_at` = '" . date("Y-m-d H:i:s") . "' WHERE `id` = " . $row['id'];
                $conn->query($sql_update);

                $mail_subject = "You've been gifted another free J!Study App Test session";
                $mail_content = get_email_content_33days($row['name']);
                send_email($row['email'], $mail_subject, $mail_content);
            }
        }
    }

    function get_email_content_3days($username){   
        // mail html content
        $mail_content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            </head>';
            $mail_content .= '<body>';
                $mail_content .= '<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; padding: 0; width: 570px; color: #718096;line-height: 1.75rem;">';
                    $mail_content .= '<tr>';
                        $mail_content .= '<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">';
                            $mail_content .= '<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="padding: 32px;text-align: center;">';
      
                                // --content---<<
                                $mail_content .= '<tr style="text-align: left">';
                                    $mail_content .= '<td class="content-cell" style="max-width: 100vw; color:#718096; font-size: 16px">';
                                        $mail_content .= "<p style='color: #718096;'>Hey {$username},</p>
                                                        <p style='color: #718096;'>You've yet to take your first practice test with J!Study App. Login <a href='https://jstudy.app/' target='_blank'>here</a> to claim your free test before it expires.</p>";
                                        $mail_content .= "<p style='color: #718096; margin-top:50px'>Cheers,<br>J!Study Team</p>";
                                    $mail_content .= '</td>';
                                $mail_content .= '</tr>';
                                // --content--->>
                            $mail_content .= '</table>';
                        $mail_content .= '</td>';
                    $mail_content .= '</tr>';

                $mail_content .= '</table>';
            $mail_content .= '</body>';
        $mail_content .= '</html>';
        return $mail_content;
    }

    function get_email_content_33days($username){   
        // mail html content
        $mail_content = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            </head>';
            $mail_content .= '<body>';
                $mail_content .= '<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation" style="margin: 0 auto; padding: 0; width: 570px; color: #718096;line-height: 1.75rem;">';
                    $mail_content .= '<tr>';
                        $mail_content .= '<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">';
                            $mail_content .= '<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation" style="padding: 32px;text-align: center;">';
      
                                // --content---<<
                                $mail_content .= '<tr style="text-align: left">';
                                    $mail_content .= '<td class="content-cell" style="max-width: 100vw; color:#718096; font-size: 16px">';
                                        $mail_content .= "<p style='color: #718096;'>Hey {$username},</p>
                                                        <p style='color: #718096;'>You've yet to take your first practice test with J!Study App. Login <a href='https://jstudy.app/' target='_blank'>here</a> to claim your free test before it expires.</p>";
                                        $mail_content .= "<p style='color: #718096; margin-top:50px'>Cheers,<br>J!Study Team</p>";
                                    $mail_content .= '</td>';
                                $mail_content .= '</tr>';
                                // --content--->>
                            $mail_content .= '</table>';
                        $mail_content .= '</td>';
                    $mail_content .= '</tr>';

                $mail_content .= '</table>';
            $mail_content .= '</body>';
        $mail_content .= '</html>';
        return $mail_content;
    }

?>