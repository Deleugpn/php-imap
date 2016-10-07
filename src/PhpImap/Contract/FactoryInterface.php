<?php

namespace PhpImap\Contract;

interface ImapFactoryInterface {

    public function parseEmailFromInbox(InboxInterface $mailBoxInterface, EmailInterface $emailInterface, $mailId, $markAsSeen = true);

}