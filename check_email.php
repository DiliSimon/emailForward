<?php
$q=$_GET["q"];

if($q == "no"){
    read_only();
}else{
    check_email();
}

function check_email()
{
    set_time_limit(3000);
    $output = array();
    /* connect to gmail with your credentials */
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $username = 'test.epidemiology@gmail.com';
    $password = '7890yuiop';

    /* try to connect */
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
    $emails = imap_search($inbox, 'UNSEEN');

    /* if any emails found, iterate through each email */
    if ($emails) {

        $count = 1;

        /* put the newest emails on top */
        rsort($emails);

        /* for every email... */
        foreach ($emails as $email_number) {
            $has_attachment = false;
            $log = fopen('log.txt','w');
            $recipients = array();
            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $subject = $overview[0]->subject;
            //echo $subject;
            fwrite($log, $subject);

            $message = '';

            fwrite($log, $message);

            /* get mail structure */
            $structure = imap_fetchstructure($inbox, $email_number);

            $attachments = array();

            /* if any attachments found... */
            if (isset($structure->parts) && count($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); $i++) {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => ''
                    );

                    if ($structure->parts[$i]->ifdparameters) {
                        foreach ($structure->parts[$i]->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                                //echo $attachments[$i]['filename'];
                            }
                        }
                    }

                    if ($structure->parts[$i]->ifparameters) {
                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                                //echo $attachments[$i]['name'];
                            }
                        }
                    }

                    if ($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                        /* 3 = BASE64 encoding */
                        if ($structure->parts[$i]->encoding == 3) {
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        } /* 4 = QUOTED-PRINTABLE encoding */
                        elseif ($structure->parts[$i]->encoding == 4) {
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }
            $attachment_paths = array();
            /* iterate through each attachment and save it */
            foreach ($attachments as $attachment) {
                if($attachment['is_attachment']) {
                    $message = imap_fetchbody($inbox, $email_number, '1.2');
                    $has_attachment = true;
                }
                if ($attachment['is_attachment'] == 1) {
                    $filename = $attachment['name'];
                    if (empty($filename)) $filename = $attachment['filename'];

                    if (empty($filename)) $filename = time() . ".dat";
                    $folder = "attachment";
                    if (!is_dir($folder)) {
                        mkdir($folder);
                    }
                    $fp = fopen("./" . $folder . "/" . $email_number . "-" . $filename, "w+");
                    fwrite($fp, $attachment['attachment']);
                    fclose($fp);
                    array_push($attachment_paths, "./" . $folder . "/" . $email_number . "-" . $filename);
                }
            }
            if(!$has_attachment){
                $message = imap_fetchbody($inbox, $email_number, '2');
            }
            //echo $message;
            array_push($recipients, 'gtingwen@outlook.com');//TODO: Add recipients;
            //send_mail($recipients, $subject, $message, $attachment_paths);
            fclose($log);
        }
        return true;
    }

    /* close the connection */
    imap_close($inbox);
}

function read_only()
{
    set_time_limit(3000);
    $output = array();
    /* connect to gmail with your credentials */
    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $username = 'test.epidemiology@gmail.com';
    $password = '7890yuiop';

    /* try to connect */
    $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
    $emails = imap_search($inbox, 'UNSEEN');

    /* if any emails found, iterate through each email */
    if ($emails) {

        $count = 1;

        /* put the newest emails on top */
        rsort($emails);

        /* for every email... */
        foreach ($emails as $email_number) {
            $has_attachment = false;
            $log = fopen('log.txt','w');
            $recipients = array();
            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            $subject = $overview[0]->subject;
            echo $subject;
            fwrite($log, $subject);

            $message = '';

            fwrite($log, $message);

            /* get mail structure */
            $structure = imap_fetchstructure($inbox, $email_number);

            $attachments = array();

            /* if any attachments found... */
            if (isset($structure->parts) && count($structure->parts)) {
                for ($i = 0; $i < count($structure->parts); $i++) {
                    $attachments[$i] = array(
                        'is_attachment' => false,
                        'filename' => '',
                        'name' => '',
                        'attachment' => ''
                    );

                    if ($structure->parts[$i]->ifdparameters) {
                        foreach ($structure->parts[$i]->dparameters as $object) {
                            if (strtolower($object->attribute) == 'filename') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['filename'] = $object->value;
                                //echo $attachments[$i]['filename'];
                            }
                        }
                    }

                    if ($structure->parts[$i]->ifparameters) {
                        foreach ($structure->parts[$i]->parameters as $object) {
                            if (strtolower($object->attribute) == 'name') {
                                $attachments[$i]['is_attachment'] = true;
                                $attachments[$i]['name'] = $object->value;
                                //echo $attachments[$i]['name'];
                            }
                        }
                    }

                    if ($attachments[$i]['is_attachment']) {
                        $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                        /* 3 = BASE64 encoding */
                        if ($structure->parts[$i]->encoding == 3) {
                            $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                        } /* 4 = QUOTED-PRINTABLE encoding */
                        elseif ($structure->parts[$i]->encoding == 4) {
                            $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                        }
                    }
                }
            }
            $attachment_paths = array();
            /* iterate through each attachment and save it */
            foreach ($attachments as $attachment) {
                if($attachment['is_attachment']) {
                    $message = imap_fetchbody($inbox, $email_number, '1.2');
                    $has_attachment = true;
                }
                if ($attachment['is_attachment'] == 1) {
                    $filename = $attachment['name'];
                    if (empty($filename)) $filename = $attachment['filename'];

                    if (empty($filename)) $filename = time() . ".dat";
                    $folder = "attachment";
                    if (!is_dir($folder)) {
                        mkdir($folder);
                    }
                    $fp = fopen("./" . $folder . "/" . $email_number . "-" . $filename, "w+");
                    fwrite($fp, $attachment['attachment']);
                    fclose($fp);
                    array_push($attachment_paths, "./" . $folder . "/" . $email_number . "-" . $filename);
                }
            }
            if(!$has_attachment){
                $message = imap_fetchbody($inbox, $email_number, '2');
            }
            //echo $message;
            imap_clearflag_full($inbox, $email_number, "\\Seen");
            array_push($recipients, 'gtingwen@outlook.com');//TODO: Add recipients;
            //send_mail($recipients, $subject, $message, $attachment_paths);
            fclose($log);
        }
        return true;
    }

    /* close the connection */
    imap_close($inbox);
}
