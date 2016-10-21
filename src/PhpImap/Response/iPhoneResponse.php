<?php

namespace PhpImap\Response;

class iPhoneResponse extends Response {

    public function getContent() {
        $dom = new \DOMDocument();
        $dom->loadHTML($this->email->getTextHtml(), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
        $body = $xpath->query("//body");
        $children = $body->item(0)->childNodes;
        for ($i = 1; $i < $children->length; $i = $i) {
            $body->item(0)->removeChild(
                $children->item($i)
            );
        }
        return $dom->saveHTML();
    }

}