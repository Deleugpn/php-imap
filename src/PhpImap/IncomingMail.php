<?php namespace PhpImap;

use PhpImap\Contract\EmailInterface;

/**
 * @see https://github.com/barbushin/php-imap
 * @author Barbushin Sergey http://linkedin.com/in/barbushin
 */
class IncomingMail implements EmailInterface {

    public $id;
    public $date;
    public $headersRaw;
    public $headers;
    public $subject;

    public $fromName;
    public $fromAddress;

    public $to = array();
    public $toString;
    public $cc = array();
    public $bcc = array();
    public $replyTo = array();

    public $messageId;

    public $textPlain;
    public $textHtml;
    /** @var IncomingMailAttachment[] */
    protected $attachments = array();

    public function addAttachment(IncomingMailAttachment $attachment) {
        $this->attachments[$attachment->id] = $attachment;
    }

    /**
     * @return IncomingMailAttachment[]
     */
    public function getAttachments() {
        return $this->attachments;
    }

    public function appendTo($toEmail, $toName) {
        $this->to[$toEmail] = $toName;
    }

    /**
     * Get array of internal HTML links placeholders
     * @return array attachmentId => link placeholder
     */
    public function getInternalLinksPlaceholders() {
        return preg_match_all('/=["\'](ci?d:([\w\.%*@-]+))["\']/i', $this->textHtml, $matches) ? array_combine($matches[2], $matches[1]) : array();

    }

    public function replaceInternalLinks($baseUri) {
        $baseUri = rtrim($baseUri, '\\/') . '/';
        $fetchedHtml = $this->textHtml;
        foreach ($this->getInternalLinksPlaceholders() as $attachmentId => $placeholder) {
            if (isset($this->attachments[$attachmentId])) {
                $fetchedHtml = str_replace($placeholder, $baseUri . basename($this->attachments[$attachmentId]->filePath), $fetchedHtml);
            }
        }
        return $fetchedHtml;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getHeadersRaw() {
        return $this->headersRaw;
    }

    /**
     * @param mixed $headersRaw
     */
    public function setHeadersRaw($headersRaw) {
        $this->headersRaw = $headersRaw;
    }

    /**
     * @return mixed
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param mixed $headers
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    /**
     * @return mixed
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject) {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getFromName() {
        return $this->fromName;
    }

    /**
     * @param mixed $fromName
     */
    public function setFromName($fromName) {
        $this->fromName = $fromName;
    }

    /**
     * @return mixed
     */
    public function getFromAddress() {
        return $this->fromAddress;
    }

    /**
     * @param mixed $fromAddress
     */
    public function setFromAddress($fromAddress) {
        $this->fromAddress = $fromAddress;
    }

    /**
     * @return array
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * @param array $to
     */
    public function setTo($to) {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getToString() {
        return $this->toString;
    }

    /**
     * @param mixed $toString
     */
    public function setToString($toString) {
        $this->toString = $toString;
    }

    /**
     * @return array
     */
    public function getCc() {
        return $this->cc;
    }

    /**
     * @param array $cc
     */
    public function setCc($cc) {
        $this->cc = $cc;
    }

    /**
     * @return array
     */
    public function getBcc() {
        return $this->bcc;
    }

    /**
     * @param array $bcc
     */
    public function setBcc($bcc) {
        $this->bcc = $bcc;
    }

    /**
     * @return array
     */
    public function getReplyTo() {
        return $this->replyTo;
    }

    /**
     * @param array $replyTo
     */
    public function setReplyTo($replyTo) {
        $this->replyTo = $replyTo;
    }

    /**
     * @return mixed
     */
    public function getMessageId() {
        return $this->messageId;
    }

    /**
     * @param mixed $messageId
     */
    public function setMessageId($messageId) {
        $this->messageId = $messageId;
    }

    /**
     * @return mixed
     */
    public function getTextPlain() {
        return $this->textPlain;
    }

    /**
     * @param mixed $textPlain
     */
    public function setTextPlain($textPlain) {
        $this->textPlain = $textPlain;
    }

    /**
     * @return mixed
     */
    public function getTextHtml() {
        return $this->textHtml;
    }

    /**
     * @param mixed $textHtml
     */
    public function setTextHtml($textHtml) {
        $this->textHtml = $textHtml;
    }

    public function setPlainTo($plain) {
        $this->textPlain = $plain;
    }

    public function concatTextPlain($text) {
        $this->textPlain .= $text;
    }

    public function concatTextHtml($text) {
        $this->textHtml .= $text;
    }

    public function addCc($index, $value) {
        $this->cc[$index] = $value;
    }

    public function addBcc($index, $value) {
        $this->bcc[$index] = $value;
    }

    public function clean() {
        foreach ($this as $attr) {
            unset($attr);
        }
    }


}

class IncomingMailAttachment {

    public $id;
    public $name;
    public $filePath;
    public $disposition;
}
