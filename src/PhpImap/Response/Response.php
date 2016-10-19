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

}