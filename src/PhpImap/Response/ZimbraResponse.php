<?php

namespace PhpImap\Response;

class ZimbraResponse extends Response {

    /**
     * @inheritdoc
     */
    public function getContent() {
        // This is a bit trickier since we have to rely on DOM manipulation
        $dom = new \DOMDocument();
        // Make sure to accept invalid HTML because the Email content is not a full HTML file.
        $dom->loadHTML(
            $this->email->getTextHtml(),
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
        $toRemove = [
            $xpath->query("//hr[@id='zwchr']/following-sibling::*[1]"), // Remove the sibling of this element
            $xpath->query("//hr[@id='zwchr']")                          // Remove this element itself.
        ];
        foreach ($toRemove as $node) {
            $node->item(0)->parentNode->removeChild(
                $node->item(0)
            );
        }
        return utf8_decode($dom->saveHTML($dom->documentElement));
    }

}