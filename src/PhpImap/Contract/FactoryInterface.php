<?php

namespace PhpImap\Contract;

interface EmailFactoryInterface {


    public function getEmail(MailBoxInterface $mailBoxInterface, EmailInterface $emailInterface, $mailId, $markAsSeen = true);

}