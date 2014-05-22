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
 * Set of assertions for emails sent and caught with MailCatcher.  These are used
 * by email-helper.php that is the main thing to look at. This is like a helper
 * for a helper.
 *
 */

trait MailCatcher_Assertions {



    /**
     * Asserts whether email was sent to someone
     *
     * @param string $recipient Email address of recipient
     * @param \Alex\MailCatcher\Message $emailMessage   Message sent
     * @param string $message What to say if it fails
     */
    public static function assertEmailHasRecipient ($recipient, \Alex\MailCatcher\Message $emailMessage, $message) {

        // I tried doing this one properly with new assertions.  Not convinced it's worth it,
        // this should just be like all the oters in this trait.
        // ie:
        //
        // self::assertContains($recipient, $emailMessage->getRecipients(), $message);
        //
        // but I've left this just for the curiosity value.
        require_once __DIR__ . '/MailCatcher/Constraint/HasRecipient.php';
        $constraint = new PHPUnit_MailCatcher_Constraint_HasRecipient($recipient);
        self::assertThat($emailMessage, $constraint, $message);

    }


    /**
     * Asserts that email has a particular number of recipients
     *
     * @param int $num Number expected
     * @param \Alex\MailCatcher\Message $emailMessage  The email
     * @param string $message What to say if it fails
     */
    public static function assertEmailRecipientsCount ($num, \Alex\MailCatcher\Message $emailMessage, $message) {
        self::assertEquals($num, count($emailMessage->getRecipients()), $message);
    }



    /**
     * Asserts whether email subject line contains a value
     *
     * @param string $subject Subject text to look for
     * @param \Alex\MailCatcher\Message $emailMessage   Message sent
     * @param string $message What to say if it fails
     * @param boolean $ignoreCase Whether to do case sensitive search
     */
    public static function assertEmailSubjectContains ($subject, \Alex\MailCatcher\Message $emailMessage, $message, $ignoreCase = false) {
        self::assertContains($subject, $emailMessage->getSubject(), $message, $ignoreCase);
    }


    /**
     * Asserts whether email body contains particular (plain) text
     *
     * @param string $text Text to look for
     * @param \Alex\MailCatcher\Message $emailMessage   Message sent
     * @param string $message What to say if it fails
     * @param boolean $ignoreCase Whether to do case sensitive search
     */
    public static function assertEmailBodyContains ($text, \Alex\MailCatcher\Message $emailMessage, $message, $ignoreCase = false) {
        $content = $emailMessage->getPart('text/plain')->getContent();
        self::assertContains($text, $content, $message, $ignoreCase);
    }


    /**
     * Asserts whether email was sent by someone (by email address)
     *
     * @param string $emailAddress Email address of sender
     * @param \Alex\MailCatcher\Message $emailMessage   Message sent
     * @param string $message What to say if it fails
     */
    public static function assertEmailFromEmail ($emailAddress, \Alex\MailCatcher\Message $emailMessage, $message) {
        $from = $emailMessage->getSender()->getEmail();
        self::assertEquals($emailAddress, $from, $message);
    }


    /**
     * Asserts whether email was sent by someone (by name)
     *
     * @param string $senderName Name of recipient
     * @param \Alex\MailCatcher\Message $emailMessage   Message sent
     * @param string $message What to say if it fails
     */
    public static function assertEmailFromName ($senderName, \Alex\MailCatcher\Message $emailMessage, $message) {
        $from = $emailMessage->getSender()->getName();
        self::assertEquals($senderName, $from, $message);
    }



}


