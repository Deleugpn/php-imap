<?php

namespace PhpImap\Contract;

interface ImapFactoryInterface {

    public function parseEmailFromInbox(MailBoxInterface $mailBoxInterface, EmailInterface $emailInterface, $mailId, $markAsSeen = true);

}