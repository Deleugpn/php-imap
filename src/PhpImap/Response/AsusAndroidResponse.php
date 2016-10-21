<?php

namespace PhpImap\Response;

class AsusAndroidResponse extends Response {

    public function getContent() {
        libxml_use_internal_errors(true);
        return parent::getContent();
    }

}