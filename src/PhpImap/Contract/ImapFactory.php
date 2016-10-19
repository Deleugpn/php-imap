<?php

namespace PhpImap\Contract;

interface ImapFactory {

    public function parseEmailFromInbox(Inbox $inbox, Email $email, $mailId, $markAsSeen = true);

}