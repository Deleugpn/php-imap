<?php

namespace PhpImap\Contract;

use PhpImap\Email\IncomingMailAttachment;

interface EmailInterface {

    public function addAttachment(IncomingMailAttachment $attachment);

    public function getAttachments();

    public function getInternalLinksPlaceholders();

    public function replaceInternalLinks($baseUri);

    public function setHeadersRaw($headersRaw);

    public function setId($mailId);

    public function setDate($date);

    public function setSubject($subject);

    public function setHeaders($headers);

    public function setFromName($fromName);

    public function setFromAddress($fromAddress);

    public function appendTo($toEmail, $toName);

    public function setMessageId($messageId);

    public function getId();

    public function concatTextPlain($text);

    public function concatTextHtml($text);

    public function addCc($index, $value);

    public function addBcc($index, $value);

    public function clean();

    public function addReplyTo($index, $value);

    public function setToString($strings);
}