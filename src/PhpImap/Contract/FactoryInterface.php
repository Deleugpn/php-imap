<?php

namespace PhpImap\Contract;

interface ImapFactoryInterface {

    public function getEmail(MailBoxInterface $mailBoxInterface, EmailInterface $emailInterface, $mailId, $markAsSeen = true);

}