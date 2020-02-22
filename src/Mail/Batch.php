<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
namespace Pluf\Mail;

use Pluf\Bootstrap;
use Pluf\Mail;
use Mail_mime;

/**
 * Generate and send multipart emails in batch mode.
 *
 * This class is just a small wrapper around the PEAR packages Mail
 * and Mail/mime.
 *
 * Class to easily generate multipart emails. It supports embedded
 * images within the email. It can be used to send a text with
 * possible attachments.
 *
 * The encoding of the message is utf-8 by default.
 *
 * Usage example:
 * <code>
 * $email = new Pluf_Mail_Batch('from_email@example.com');
 * foreach($emails as $m) {
 * $email->setSubject($m['subject']);
 * $email->setTo($m['to']);
 * $img_id = $email->addAttachment('/var/www/html/img/pic.jpg', 'image/jpg');
 * $email->addTextMessage($m['content']);
 * $email->sendMail();
 * }
 * $email->close();
 * </code>
 *
 * The configuration parameters are the one for Mail::factory with the
 * 'mail_' prefix not to conflict with the other parameters.
 *
 * @see http://pear.php.net/manual/en/package.mail.mail.factory.php 'mail_backend' - 'mail', 'smtp' or 'sendmail' (default 'mail').
 *     
 *      List of parameter for the backends:
 *     
 *      mail backend
 *      --------------
 *     
 *      If safe mode is disabled, an array with all the 'mail_*' parameters
 *      excluding 'mail_backend' will be passed as the fifth argument to
 *      the PHP mail() function. The elements will be joined as a
 *      space-delimited string.
 *     
 *      sendmail backend
 *      ------------------
 *     
 *      'mail_sendmail_path' - The location of the sendmail program on the
 *      filesystem. Default is /usr/bin/sendmail
 *      'sendmail_args' - Additional parameters to pass to the
 *      sendmail. Default is -i
 *     
 *      smtp backend
 *      --------------
 *     
 *      'mail_host' - The server to connect. Default is localhost
 *      'mail_port' - The port to connect. Default is 25
 *      'mail_auth' - Whether or not to use SMTP authentication. Default is
 *      FALSE
 *     
 *      'mail_username' - The username to use for SMTP authentication.
 *      'mail_password' - The password to use for SMTP authentication.
 *      'mail_localhost' - The value to give when sending EHLO or
 *      HELO. Default is localhost
 *      'mail_timeout' - The SMTP connection timeout. Default is NULL (no
 *      timeout)
 *      'mail_verp' - Whether to use VERP or not. Default is FALSE
 *      'mail_debug' - Whether to enable SMTP debug mode or not. Default is
 *      FALSE
 *      'mail_persist' - Will automatically be set to true.
 *     
 *      If you are doing some testing, you should use the smtp backend
 *      together with fakemail: http://www.lastcraft.com/fakemail.php
 */
class Batch
{

    public $headers = array();

    public $message;

    public $encoding = 'utf-8';

    public $crlf = "\n";

    public $from = '';

    protected $backend = null;

    /**
     * Construct the base email.
     *
     * @param
     *            string The email of the sender.
     * @param
     *            string Encoding of the message ('UTF-8')
     * @param
     *            string End of line type ("\n")
     */
    function __construct($src, $encoding = 'UTF-8', $crlf = "\n")
    {
        // Note that the Pluf autoloader will correctly load this PEAR
        // object.
        $this->message = new \Mail_mime($crlf);
        $this->message->_build_params['html_charset'] = $encoding;
        $this->message->_build_params['text_charset'] = $encoding;
        $this->message->_build_params['head_charset'] = $encoding;
        $this->headers = array(
            'From' => $src
        );
        $this->encoding = $encoding;
        $this->crlf = $crlf;
        $this->from = $src;
    }

    /**
     * Set the subject of the email.
     *
     * @param
     *            string Subject
     */
    function setSubject($subject)
    {
        $this->headers['Subject'] = $subject;
    }

    /**
     * Set the recipient of the email.
     *
     * @param
     *            string Recipient email
     */
    function setTo($email)
    {
        $this->headers['To'] = $email;
    }

    /**
     * Add the base plain text message to the email.
     *
     * @param
     *            string The message
     */
    function addTextMessage($msg)
    {
        $this->message->setTXTBody($msg);
    }

    /**
     * Set the return path for the email.
     *
     * @param
     *            string Email
     */
    function setReturnPath($email)
    {
        $this->headers['Return-Path'] = $email;
    }

    /**
     * Add headers to an email.
     *
     * @param
     *            array Array of headers
     */
    function addHeaders($hdrs)
    {
        $this->headers = array_merge($this->headers, $hdrs);
    }

    /**
     * Add the alternate HTML message to the email.
     *
     * @param
     *            string The HTML message
     */
    function addHtmlMessage($msg)
    {
        $this->message->setHTMLBody($msg);
    }

    /**
     * Add an attachment to the message.
     *
     * The file to attach must be available on disk and you need to
     * provide the mimetype of the attachment manually.
     *
     * @param
     *            string Path to the file to be added.
     * @param
     *            string Mimetype of the file to be added ('text/plain').
     * @return bool True.
     */
    function addAttachment($file, $ctype = 'text/plain')
    {
        $this->message->addAttachment($file, $ctype);
    }

    /**
     * Effectively sends the email.
     */
    function sendMail()
    {
        if ($this->backend === null) {
            $params = Bootstrap::pf('mail_', true); // strip the prefix 'mail_'
            unset($params['backend']);
            $gmail = new Mail();
            if (Bootstrap::f('mail_backend') == 'smtp') {
                $params['persist'] = true;
            }
            $this->backend = $gmail->factory(Bootstrap::f('mail_backend', 'mail'), $params);
        }
        $body = $this->message->get();
        $hdrs = $this->message->headers($this->headers);
        if (Bootstrap::f('send_emails', true)) {
            $this->backend->send($this->headers['To'], $hdrs, $body);
        }
        $this->message = new Mail_mime($this->crlf);
        $this->message->_build_params['html_charset'] = $this->encoding;
        $this->message->_build_params['text_charset'] = $this->encoding;
        $this->message->_build_params['head_charset'] = $this->encoding;
        $this->headers = array(
            'From' => $this->from
        );
    }

    function close()
    {
        unset($this->backend);
        $this->backend = null;
    }
}
