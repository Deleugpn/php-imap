<?php

namespace PhpImap\Contract;

interface Inbox {

    public function connect();

    public function getImapStream($forceConnection = true);

    public function switchMailbox($imapPath = '');

    public function setExpungeOnDisconnect($isEnabled);

    public function checkMailbox();

    public function createMailbox();

    public function statusMailbox();

    public function getListingFolders();

    public function searchMailbox($criteria = 'ALL');

    public function saveMail($mailId, $filename = 'email.eml');

    public function deleteMail($mailId);

    public function moveMail($mailId, $mailBox);

    public function copyMail($mailId, $mailBox);

    public function expungeDeletedMails();

    public function markMailAsRead($mailId);

    public function markMailAsUnread($mailId);

    public function markMailAsImportant($mailId);

    public function markMailsAsRead(array $mailId);

    public function markMailsAsUnread(array $mailId);

    public function markMailsAsImportant(array $mailId);

    public function setFlag(array $mailsIds, $flag);

    public function clearFlag(array $mailsIds, $flag);

    public function getMailsInfo(array $mailsIds);

    public function getMailboxInfo();

    public function sortMails($criteria = SORTARRIVAL, $reverse = true);

    public function countMails();

    public function getQuotaLimit();

    public function getQuotaUsage();

    public function getRawMail($msgId, $markAsSeen = true);

    public function getMail($mailId, $markAsSeen = true);

    public function isUrlEncoded($string);

    public function settings($imapPath, $login, $password, $attachmentsDir = null, $serverEncoding = 'UTF-8');

    public function decodeMimeStr($string, $charset = 'utf-8');

    public function getServerEncoding();

    public function initMailPart(Email $emailInterface, $partStructure, $param, $markAsSeen = true);
}