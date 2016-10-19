<?php

namespace PhpImap\Factory;

use PhpImap\Contract\ImapFactory as ContractImapFactory;
use PhpImap\Contract\Email;
use PhpImap\Contract\Inbox;

class ImapFactory implements ContractImapFactory {

    /**
     * EmailFactory constructor.
     * @param Inbox $inbox
     * @param Email $email
     * @param $mailId
     * @param bool $markAsSeen
     * @return Email
     */
    public function parseEmailFromInbox(Inbox $inbox, Email $email, $mailId, $markAsSeen = true) {
        $class = get_class($email);
        $email = new $class;

        $headersRaw = imap_fetchheader($inbox->getImapStream(), $mailId, FT_UID);
        $head = imap_rfc822_parse_headers($headersRaw);

        $email->setHeadersRaw($headersRaw);
        $email->setHeaders($head);
        $email->setId($mailId);
        $email->setDate(date('Y-m-d H:i:s', isset($head->date) ? strtotime(preg_replace('/\(.*?\)/', '', $head->date)) : time()));
        $email->setSubject(isset($head->subject) ? $inbox->decodeMimeStr($head->subject, $inbox->getServerEncoding()) : null);
        $email->setFromName(isset($head->from[0]->personal) ? $inbox->decodeMimeStr($head->from[0]->personal, $inbox->getServerEncoding()) : null);
        $email->setFromAddress(strtolower($head->from[0]->mailbox . '@' . $head->from[0]->host));

        if (isset($head->to)) {
            $toStrings = array();
            foreach ($head->to as $to) {
                if (!empty($to->mailbox) && !empty($to->host)) {
                    $toEmail = strtolower($to->mailbox . '@' . $to->host);
                    $toName = isset($to->personal) ? $inbox->decodeMimeStr($to->personal, $inbox->getServerEncoding()) : null;
                    $toStrings[] = $toName ? "$toName <$toEmail>" : $toEmail;
                    $email->appendTo($toEmail, $toName);
                }
            }
            $email->setToString(implode(', ', $toStrings));
        }

        if (isset($head->cc)) {
            foreach ($head->cc as $cc) {
                $email->addCc(strtolower($cc->mailbox . '@' . $cc->host), isset($cc->personal) ? $inbox->decodeMimeStr($cc->personal, $inbox->getServerEncoding()) : null);
            }
        }

        if (isset($head->bcc)) {
            foreach ($head->bcc as $bcc) {
                $email->addBcc(strtolower($bcc->mailbox . '@' . $bcc->host), isset($bcc->personal) ? $inbox->decodeMimeStr($bcc->personal, $inbox->getServerEncoding()) : null);
            }
        }

        if (isset($head->reply_to)) {
            foreach ($head->reply_to as $replyTo) {
                $email->addReplyTo(strtolower($replyTo->mailbox . '@' . $replyTo->host), isset($replyTo->personal) ? $inbox->decodeMimeStr($replyTo->personal, $inbox->getServerEncoding()) : null);
            }
        }

        if (isset($head->message_id)) {
            $email->setMessageId($head->message_id);
        }

        $mailStructure = imap_fetchstructure($inbox->getImapStream(), $mailId, FT_UID);

        if (empty($mailStructure->parts)) {
            $inbox->initMailPart($email, $mailStructure, 0, $markAsSeen);
        } else {
            foreach ($mailStructure->parts as $partNum => $partStructure) {
                $inbox->initMailPart($email, $partStructure, $partNum + 1, $markAsSeen);
            }
        }

        return $email;
    }    


}