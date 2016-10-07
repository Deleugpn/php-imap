<?php

namespace PhpImap\Factory;

use PhpImap\Contract\ImapFactoryInterface;
use PhpImap\Contract\EmailInterface;
use PhpImap\Contract\InboxInterface;

class ImapFactory implements ImapFactoryInterface {

    /**
     * EmailFactory constructor.
     * @param InboxInterface $mailBoxInterface
     * @param EmailInterface $emailInterface
     * @param $mailId
     * @param bool $markAsSeen
     * @return EmailInterface
     */
    public function parseEmailFromInbox(InboxInterface $mailBoxInterface, EmailInterface $emailInterface, $mailId, $markAsSeen = true) {
        $class = get_class($emailInterface);
        $emailInterface = new $class;

        $headersRaw = imap_fetchheader($mailBoxInterface->getImapStream(), $mailId, FT_UID);
        $head = imap_rfc822_parse_headers($headersRaw);

        $emailInterface->setHeadersRaw($headersRaw);
        $emailInterface->setHeaders($head);
        $emailInterface->setId($mailId);
        $emailInterface->setDate(date('Y-m-d H:i:s', isset($head->date) ? strtotime(preg_replace('/\(.*?\)/', '', $head->date)) : time()));
        $emailInterface->setSubject(isset($head->subject) ? $mailBoxInterface->decodeMimeStr($head->subject, $mailBoxInterface->getServerEncoding()) : null);
        $emailInterface->setFromName(isset($head->from[0]->personal) ? $mailBoxInterface->decodeMimeStr($head->from[0]->personal, $mailBoxInterface->getServerEncoding()) : null);
        $emailInterface->setFromAddress(strtolower($head->from[0]->mailbox . '@' . $head->from[0]->host));

        if (isset($head->to)) {
            $toStrings = array();
            foreach ($head->to as $to) {
                if (!empty($to->mailbox) && !empty($to->host)) {
                    $toEmail = strtolower($to->mailbox . '@' . $to->host);
                    $toName = isset($to->personal) ? $mailBoxInterface->decodeMimeStr($to->personal, $mailBoxInterface->getServerEncoding()) : null;
                    $toStrings[] = $toName ? "$toName <$toEmail>" : $toEmail;
                    $emailInterface->appendTo($toEmail, $toName);
                }
            }
            $emailInterface->setToString(implode(', ', $toStrings));
        }

        if (isset($head->cc)) {
            foreach ($head->cc as $cc) {
                $emailInterface->addCc(strtolower($cc->mailbox . '@' . $cc->host), isset($cc->personal) ? $mailBoxInterface->decodeMimeStr($cc->personal, $mailBoxInterface->getServerEncoding()) : null);
            }
        }

        if (isset($head->bcc)) {
            foreach ($head->bcc as $bcc) {
                $emailInterface->addBcc(strtolower($bcc->mailbox . '@' . $bcc->host), isset($bcc->personal) ? $mailBoxInterface->decodeMimeStr($bcc->personal, $mailBoxInterface->getServerEncoding()) : null);
            }
        }

        if (isset($head->reply_to)) {
            foreach ($head->reply_to as $replyTo) {
                $emailInterface->addReplyTo(strtolower($replyTo->mailbox . '@' . $replyTo->host), isset($replyTo->personal) ? $mailBoxInterface->decodeMimeStr($replyTo->personal, $mailBoxInterface->getServerEncoding()) : null);
            }
        }

        if (isset($head->message_id)) {
            $emailInterface->setMessageId($head->message_id);
        }

        $mailStructure = imap_fetchstructure($mailBoxInterface->getImapStream(), $mailId, FT_UID);

        if (empty($mailStructure->parts)) {
            $mailBoxInterface->initMailPart($emailInterface, $mailStructure, 0, $markAsSeen);
        } else {
            foreach ($mailStructure->parts as $partNum => $partStructure) {
                $mailBoxInterface->initMailPart($emailInterface, $partStructure, $partNum + 1, $markAsSeen);
            }
        }

        return $emailInterface;
    }    


}