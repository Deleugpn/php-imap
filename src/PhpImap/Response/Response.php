<?php

namespace PhpImap\Response;

use PhpImap\Contract\Email;
use PhpImap\Contract\Response as ResponseContract;

class Response implements ResponseContract {

    /**
     * @var Email
     */
    protected $email;

    /**
     * GmailResponse constructor.
     * @param Email $email
     */
    public function __construct(Email $email) {
        $this->email = $email;
    }

    public function getContent() {
        $dom = new \DOMDocument();
        $dom->loadHTML($this->email->getTextHtml(), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
        $reply = $xpath->query("//section[@id='linus-desk-reply']");
        $reply->item(0)->parentNode->removeChild($reply->item(0));
        return $dom->saveHTML();
    }

}