

<?php
function mail_to_user($musername,$to,$questionData,$asker_mail,$name,$from,$extradata)
{
    #print $musername.$to.$questionData.$asker_mail.$name.$from;

    $to      = $to;
    $subject = $name."[".$asker_mail."] asked you a Question a question ".$questionData['question']['web_link'];
    $message = "Hello ".$musername . ",\nYou have been asked a question by ".$name."[".$asker_mail."]"."\nYou can answer the question from here ".$questionData['question']['web_link'] ;

    foreach($extradata as $key => $value) {
        //do something with your $key and $value;
        $message=$message. "\n".$key . ':  '.urldecode($value);
    }

    $message=$message . "\n\nThank you.";

    $headers = "From: ".$from. "\r\n" .
        "Reply-To:" .$asker_mail. "\r\n" .
        "X-Mailer: PHP/" . phpversion();

    #print $message;
    if(wp_mail($to, $subject, $message, $headers))
        return true;
    else
        return false;
}



?>

