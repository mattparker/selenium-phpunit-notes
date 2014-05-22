<?php
/**
 *
 * Copyright (c) 2014 Matt Parker.
 * All rights reserved.
 *
 * @author     Matt Parker <matt@lamplightdb.co.uk>
 * @copyright  2014 Matt Parker <matt@lamplightdb.co.uk>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 *
 */



/**
 * This is just a code sample to accompany the Nomad php lightning talk on
 * Selenium and phpunit.
 *
 * Check whether an email sent (to Mailcatcher) is as expected.
 *
 */
trait EmailHelpers {


    /**
     * @var Alex\MailCatcher\Client
     */
    protected $_commsHelpersMailCatcherClient = null;



    /**
     * Checks the last email sent
     *
     * @param array $recipients Expected recipients
     * @param string $subject Expected subject
     * @param string $body Expected body content (partial)
     * @param string $from Expected email from address
     * @return  \Alex\MailCatcher\Message $email Message received
     */
    protected function verifyLastEmail (array $recipients = array(), $subject = '', $body = '', $from = '') {

        sleep(1);
        $msg = $this->retrieveEmailSent();
        if ($msg !== false) {
            return $this->verifyEmail($msg, $recipients, $subject, $body, $from);
        } else {
            $this->assertTrue(false, 'Message sent was not actually received');
        }
    }


    /**
     * Checks an email
     *
     * @param \Alex\MailCatcher\Message $email Message received
     * @param array $recipients Expected recipients
     * @param string $subject Expected subject
     * @param string $body Expected body content (partial)
     * @param string $from Expected email from address
     * @return  \Alex\MailCatcher\Message $email Message received
     */
    protected function verifyEmail (\Alex\MailCatcher\Message $email, array $recipients = array(), $subject = '', $body = '', $from = '') {


        foreach ($recipients as $recip) {
            $this->assertEmailHasRecipient($recip, $email, 'Test recipients');
        }
        if ($subject) {
            $this->assertEmailSubjectContains($subject, $email, "Testing subject line");
        }
        if ($body) {
            $this->assertEmailBodyContains($body, $email, "Testing content");
        }
        if ($from) {
            $this->assertEmailFromEmail($from, $email, 'Testing email from');
        }
        return $email;
    }


    /**
     * Gets an email that's been sent - the $num -th most recent
     * i.e. $this->retrieveEmailSent(0) gets that most recently sent,
     * $this->retrieveEmailSent(2) gets the third in the inbox.
     *
     * @param int $num  How many emails back to get
     * @return Alex\MailCatcher\Message | false if the message isn't there
     */
    protected function retrieveEmailSent ($num = 0) {
        $client = $this->_getMailCatcherClient();
        $messages = $client->search();

        if ($num > count($messages)) {
            return false;
        }
        return $messages[$num];

    }


    /**
     * Removes emails from the MailCatcher net
     */
    protected function purgeEmails () {
        $this->_getMailCatcherClient()->purge();
    }


    /**
     * Gets the MailCatcher client instance
     * @return Alex\MailCatcher\Client
     */
    protected function _getMailCatcherClient () {
        if ($this->_commsHelpersMailCatcherClient === null) {
            $this->_commsHelpersMailCatcherClient = new Alex\MailCatcher\Client('http://lamplight:1080');
        }
        return $this->_commsHelpersMailCatcherClient;
    }

}


