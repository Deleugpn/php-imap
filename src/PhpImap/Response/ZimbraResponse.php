<?php

namespace PhpImap\Response;

class ZimbraResponse extends Response {

    public function getContent() {
        $dom = new \DOMDocument();
        $dom->loadHTML($this->email->getTextHtml(), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
        $toRemove = [
            $xpath->query("//hr[@id='zwchr']/following-sibling::*[1]"),
            $xpath->query("//hr[@id='zwchr']")
        ];
        foreach($toRemove as $node) {
            $node->item(0)->parentNode->removeChild(
                $node->item(0)
            );
        }
        return $dom->saveHTML();
    }

}