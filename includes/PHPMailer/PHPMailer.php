<?php
/**
 * PHPMailer - Simplified Version for CyberX
 * Full-featured email creation and transport class for PHP
 */

namespace PHPMailer\PHPMailer;

class PHPMailer
{
    const VERSION = '6.8.0';
    const CHARSET_ASCII = 'us-ascii';
    const CHARSET_ISO88591 = 'iso-8859-1';
    const CHARSET_UTF8 = 'utf-8';
    const CONTENT_TYPE_PLAINTEXT = 'text/plain';
    const CONTENT_TYPE_TEXT_CALENDAR = 'text/calendar';
    const CONTENT_TYPE_TEXT_HTML = 'text/html';
    const CONTENT_TYPE_MULTIPART_ALTERNATIVE = 'multipart/alternative';
    const CONTENT_TYPE_MULTIPART_MIXED = 'multipart/mixed';
    const CONTENT_TYPE_MULTIPART_RELATED = 'multipart/related';
    const ENCODING_7BIT = '7bit';
    const ENCODING_8BIT = '8bit';
    const ENCODING_BASE64 = 'base64';
    const ENCODING_BINARY = 'binary';
    const ENCODING_QUOTED_PRINTABLE = 'quoted-printable';
    const ENCRYPTION_STARTTLS = 'tls';
    const ENCRYPTION_SMTPS = 'ssl';

    public $Priority;
    public $CharSet = self::CHARSET_UTF8;
    public $ContentType = self::CONTENT_TYPE_PLAINTEXT;
    public $Encoding = self::ENCODING_8BIT;
    public $ErrorInfo = '';
    public $From = '';
    public $FromName = '';
    public $Sender = '';
    public $Subject = '';
    public $Body = '';
    public $AltBody = '';
    public $WordWrap = 0;
    public $Mailer = 'smtp';
    public $Sendmail = '/usr/sbin/sendmail';
    public $UseSendmailOptions = true;
    public $Host = 'localhost';
    public $Port = 25;
    public $Helo = '';
    public $SMTPSecure = '';
    public $SMTPAutoTLS = true;
    public $SMTPAuth = false;
    public $SMTPOptions = [];
    public $Username = '';
    public $Password = '';
    public $AuthType = '';
    public $Timeout = 300;
    public $SMTPDebug = 0;
    public $Debugoutput = 'echo';
    public $SMTPKeepAlive = false;
    public $XMailer = '';
    public $MessageID = '';
    public $MessageDate = '';
    
    protected $to = [];
    protected $cc = [];
    protected $bcc = [];
    protected $ReplyTo = [];
    protected $all_recipients = [];
    protected $attachment = [];
    protected $CustomHeader = [];
    protected $lastMessageID = '';
    protected $message_type = '';
    protected $boundary = [];
    protected $uniqueid = '';
    protected $smtp;
    protected $exceptions = false;
    
    const STOP_MESSAGE = 0;
    const STOP_CONTINUE = 1;
    const STOP_CRITICAL = 2;
    
    public function __construct($exceptions = null)
    {
        if (null !== $exceptions) {
            $this->exceptions = (bool) $exceptions;
        }
        $this->Debugoutput = 'error_log';
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        $this->smtpClose();
    }
    
    /**
     * Add a "To" address
     */
    public function addAddress($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('to', $address, $name);
    }
    
    /**
     * Add a "CC" address
     */
    public function addCC($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('cc', $address, $name);
    }
    
    /**
     * Add a "BCC" address
     */
    public function addBCC($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('bcc', $address, $name);
    }
    
    /**
     * Add a "Reply-To" address
     */
    public function addReplyTo($address, $name = '')
    {
        return $this->addOrEnqueueAnAddress('Reply-To', $address, $name);
    }
    
    /**
     * Add an address
     */
    protected function addOrEnqueueAnAddress($kind, $address, $name)
    {
        $address = trim($address);
        $name = trim(preg_replace('/[\r\n]+/', '', $name));
        $pos = strrpos($address, '@');
        if (false === $pos) {
            $error_message = $this->lang('invalid_address') . " (addAnAddress $kind): $address";
            $this->setError($error_message);
            if ($this->exceptions) {
                throw new Exception($error_message);
            }
            return false;
        }
        if ('Reply-To' !== $kind) {
            if (!array_key_exists(strtolower($address), $this->all_recipients)) {
                $this->{$kind}[] = [$address, $name];
                $this->all_recipients[strtolower($address)] = true;
                return true;
            }
        } else {
            if (!array_key_exists(strtolower($address), $this->ReplyTo)) {
                $this->ReplyTo[strtolower($address)] = [$address, $name];
                return true;
            }
        }
        return false;
    }
    
    /**
     * Set the From address
     */
    public function setFrom($address, $name = '', $auto = true)
    {
        $address = trim($address);
        $name = trim(preg_replace('/[\r\n]+/', '', $name));
        if (!static::validateAddress($address)) {
            $error_message = $this->lang('invalid_address') . " (setFrom) $address";
            $this->setError($error_message);
            if ($this->exceptions) {
                throw new Exception($error_message);
            }
            return false;
        }
        $this->From = $address;
        $this->FromName = $name;
        if ($auto && empty($this->Sender)) {
            $this->Sender = $address;
        }
        return true;
    }
    
    /**
     * Check that a string looks like an email address
     */
    public static function validateAddress($address, $patternselect = null)
    {
        return (bool) filter_var($address, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Create a message and send it
     */
    public function send()
    {
        try {
            if (!$this->preSend()) {
                return false;
            }
            return $this->postSend();
        } catch (Exception $exc) {
            $this->setError($exc->getMessage());
            if ($this->exceptions) {
                throw $exc;
            }
            return false;
        }
    }
    
    /**
     * Prepare message before sending
     */
    public function preSend()
    {
        if (empty($this->to) && empty($this->cc) && empty($this->bcc)) {
            $this->setError($this->lang('provide_address'));
            return false;
        }
        
        // Only switch to multipart if we have both HTML and plain text bodies
        // AND the simplified mailer supports it (which it doesn't fully)
        // So we keep the current ContentType (text/html if isHTML was called)
        
        $this->setMessageType();
        
        if ('' === $this->Subject) {
            $this->setError($this->lang('empty_message'));
        }
        
        return true;
    }
    
    /**
     * Actually send a message via the selected mechanism
     */
    public function postSend()
    {
        switch ($this->Mailer) {
            case 'smtp':
                return $this->smtpSend();
            case 'mail':
                return $this->mailSend();
            default:
                $this->setError($this->lang('mailer_not_supported') . $this->Mailer);
                return false;
        }
    }
    
    /**
     * Send mail using the $Sendmail program
     */
    protected function mailSend()
    {
        $header = $this->createHeader();
        $body = $this->createBody();
        
        $to = implode(', ', array_map(function($recipient) {
            return $recipient[0];
        }, $this->to));
        
        $params = sprintf('-f%s', $this->Sender);
        $result = @mail($to, $this->encodeHeader($this->Subject), $body, $header, $params);
        
        if (!$result) {
            $this->setError('Could not send mail using mail()');
            return false;
        }
        return true;
    }
    
    /**
     * Send mail via SMTP
     */
    protected function smtpSend()
    {
        $header = $this->createHeader();
        $body = $this->createBody();
        
        if (!$this->smtpConnect()) {
            return false;
        }
        
        $smtp_from = $this->Sender ?: $this->From;
        if (!$this->smtp->mail($smtp_from)) {
            $this->setError('SMTP Error: Could not send MAIL FROM');
            return false;
        }
        
        $addresses = [];
        foreach ($this->to as $recipient) {
            $addresses[] = $recipient[0];
        }
        foreach ($this->cc as $recipient) {
            $addresses[] = $recipient[0];
        }
        foreach ($this->bcc as $recipient) {
            $addresses[] = $recipient[0];
        }
        
        foreach ($addresses as $address) {
            if (!$this->smtp->recipient($address)) {
                $error = $this->smtp->getError();
                $this->setError('SMTP Error: Could not send to: ' . $address . ' ' . $error['detail']);
                return false;
            }
        }
        
        if (!$this->smtp->data($header . "\r\n\r\n" . $body)) {
            $this->setError('SMTP Error: Data not accepted');
            return false;
        }
        
        if (!$this->SMTPKeepAlive) {
            $this->smtpClose();
        }
        
        return true;
    }
    
    /**
     * Initiate a connection to an SMTP server
     */
    public function smtpConnect($options = null)
    {
        if (null === $this->smtp) {
            $this->smtp = new SMTP();
        }
        
        $this->smtp->do_debug = $this->SMTPDebug;
        $this->smtp->Debugoutput = $this->Debugoutput;
        $this->smtp->Timeout = $this->Timeout;
        
        $hosts = explode(';', $this->Host);
        $lastexception = null;
        
        foreach ($hosts as $hostentry) {
            $hostinfo = [];
            $host = $hostentry;
            $port = $this->Port;
            $prefix = '';
            
            if (preg_match('/^(ssl|tls):\/\/(.+)$/', $host, $hostinfo)) {
                $prefix = $hostinfo[1];
                $host = $hostinfo[2];
            }
            
            if ($this->SMTPSecure === static::ENCRYPTION_SMTPS) {
                $prefix = 'ssl://';
            }
            
            $this->smtp->do_debug = $this->SMTPDebug;
            
            $connected = $this->smtp->connect($prefix . $host, $port, $this->Timeout, $options);
            
            if ($connected) {
                try {
                    $hello = $this->Helo ?: gethostname();
                    $this->smtp->hello($hello);
                    
                    if ($this->SMTPSecure === static::ENCRYPTION_STARTTLS) {
                        if (!$this->smtp->startTLS()) {
                            $this->setError('STARTTLS not supported');
                            return false;
                        }
                        $this->smtp->hello($hello);
                    }
                    
                    if ($this->SMTPAuth) {
                        if (!$this->smtp->authenticate($this->Username, $this->Password, $this->AuthType)) {
                            $this->setError('SMTP Authentication failed');
                            return false;
                        }
                    }
                    
                    return true;
                } catch (Exception $exc) {
                    $lastexception = $exc;
                    $this->smtp->quit();
                }
            }
        }
        
        $this->smtp->close();
        if ($this->exceptions && null !== $lastexception) {
            throw $lastexception;
        }
        
        return false;
    }
    
    /**
     * Close the SMTP connection
     */
    public function smtpClose()
    {
        if (null !== $this->smtp && $this->smtp->connected()) {
            $this->smtp->quit();
            $this->smtp->close();
        }
    }
    
    /**
     * Use SMTP
     */
    public function isSMTP()
    {
        $this->Mailer = 'smtp';
    }
    
    /**
     * Use PHP mail()
     */
    public function isMail()
    {
        $this->Mailer = 'mail';
    }
    
    /**
     * Check if this message has an alternative body (plain text)
     */
    public function alternativeExists()
    {
        return !empty($this->AltBody);
    }
    
    /**
     * Set message type
     */
    protected function setMessageType()
    {
        $type = [];
        if ($this->alternativeExists()) {
            $type[] = 'alt';
        }
        if (count($this->attachment) > 0) {
            $type[] = 'attach';
        }
        $this->message_type = implode('_', $type);
        if ('' === $this->message_type) {
            $this->message_type = 'plain';
        }
    }
    
    /**
     * Create email header
     */
    public function createHeader()
    {
        $result = '';
        
        $result .= 'Date: ' . date('r') . static::CRLF;
        
        if ($this->MessageID !== '') {
            $this->lastMessageID = $this->MessageID;
        } else {
            $this->lastMessageID = sprintf('<%s@%s>', $this->generateId(), gethostname());
        }
        $result .= 'Message-ID: ' . $this->lastMessageID . static::CRLF;
        
        $result .= 'From: ' . $this->addrFormat([$this->From, $this->FromName]) . static::CRLF;
        
        foreach ($this->to as $toaddr) {
            $result .= 'To: ' . $this->addrFormat($toaddr) . static::CRLF;
        }
        
        foreach ($this->cc as $cc) {
            $result .= 'Cc: ' . $this->addrFormat($cc) . static::CRLF;
        }
        
        $result .= 'Subject: ' . $this->encodeHeader($this->Subject) . static::CRLF;
        
        if (!empty($this->ReplyTo)) {
            foreach ($this->ReplyTo as $replyTo) {
                $result .= 'Reply-To: ' . $this->addrFormat($replyTo) . static::CRLF;
                break;
            }
        }
        
        $result .= 'MIME-Version: 1.0' . static::CRLF;
        $result .= 'Content-Type: ' . $this->ContentType . '; charset=' . $this->CharSet . static::CRLF;
        $result .= 'Content-Transfer-Encoding: ' . $this->Encoding . static::CRLF;
        
        $result .= 'X-Mailer: PHPMailer ' . static::VERSION . static::CRLF;
        
        return $result;
    }
    
    const CRLF = "\r\n";
    
    /**
     * Create message body
     */
    public function createBody()
    {
        $body = '';
        if ($this->ContentType === static::CONTENT_TYPE_TEXT_HTML || 
            stripos($this->Body, '<html') !== false || 
            stripos($this->Body, '<body') !== false) {
            $body = $this->Body;
        } else {
            $body = $this->Body;
        }
        return $body;
    }
    
    /**
     * Format an address for header
     */
    public function addrFormat($addr)
    {
        if (empty($addr[1])) {
            return $addr[0];
        }
        return $this->encodeHeader($addr[1]) . ' <' . $addr[0] . '>';
    }
    
    /**
     * Encode a header value
     */
    public function encodeHeader($str)
    {
        $x = 0;
        if (preg_match('/[\x80-\xFF]/', $str)) {
            return '=?' . $this->CharSet . '?B?' . base64_encode($str) . '?=';
        }
        return $str;
    }
    
    /**
     * Generate unique ID
     */
    protected function generateId()
    {
        return sprintf(
            '%s.%s.%s',
            base_convert(bin2hex(random_bytes(8)), 16, 36),
            base_convert(bin2hex(random_bytes(4)), 16, 36),
            base_convert(bin2hex(random_bytes(4)), 16, 36)
        );
    }
    
    /**
     * Set error message
     */
    protected function setError($msg)
    {
        $this->ErrorInfo = $msg;
    }
    
    /**
     * Clear all recipients
     */
    public function clearAddresses()
    {
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        $this->all_recipients = [];
    }
    
    /**
     * Clear all attachments
     */
    public function clearAttachments()
    {
        $this->attachment = [];
    }
    
    /**
     * Clear all custom headers
     */
    public function clearCustomHeaders()
    {
        $this->CustomHeader = [];
    }
    
    /**
     * Clear replytos
     */
    public function clearReplyTos()
    {
        $this->ReplyTo = [];
    }
    
    /**
     * Clear all TO, CC, BCC recipients
     */
    public function clearAllRecipients()
    {
        $this->to = [];
        $this->cc = [];
        $this->bcc = [];
        $this->ReplyTo = [];
        $this->all_recipients = [];
    }
    
    /**
     * Check if HTML mode
     */
    public function isHTML($isHtml = true)
    {
        if ($isHtml) {
            $this->ContentType = static::CONTENT_TYPE_TEXT_HTML;
        } else {
            $this->ContentType = static::CONTENT_TYPE_PLAINTEXT;
        }
    }
    
    /**
     * Return language string
     */
    protected function lang($key)
    {
        $messages = [
            'provide_address' => 'You must provide at least one recipient email address.',
            'mailer_not_supported' => 'Mailer is not supported: ',
            'invalid_address' => 'Invalid address: ',
            'empty_message' => 'Message body empty',
            'encoding' => 'Unknown encoding: ',
            'execute' => 'Could not execute: ',
            'authenticate' => 'SMTP Error: Could not authenticate.',
            'connect_host' => 'SMTP Error: Could not connect to SMTP host.',
            'data_not_accepted' => 'SMTP Error: data not accepted.',
        ];
        return $messages[$key] ?? 'Unknown error: ' . $key;
    }
}
