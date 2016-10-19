<?php

namespace PhpImap\Response;

class GmailResponse extends Response {

    public function getContent() {
        return substr(
            $this->email->getTextHtml(), 0, strpos($this->email->getTextHtml(), '</div>') + 6
        );
    }

}