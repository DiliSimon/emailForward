<?php
set_time_limit(3000);
$output = array();
/* connect to gmail with your credentials */
$hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
$username = 'test.epidemiology@gmail.com';
$password = '7890yuiop';

/* try to connect */
$inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
$emails = imap_search($inbox, "ALL");

/* if any emails found, iterate through each email */
if ($emails) {

    $count = 1;

    /* put the newest emails on top */
    rsort($emails);

    /* for every email... */
    foreach ($emails as $email_number) {
        $status = imap_clearflag_full($inbox, $email_number, "\\Seen");
        echo $status;
    }
}

imap_close($inbox, CL_EXPUNGE);