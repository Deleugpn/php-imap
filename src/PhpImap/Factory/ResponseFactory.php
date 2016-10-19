<?php

namespace PhpImap\Factory;


use PhpImap\Contract\Email;
use PhpImap\Response\GmailResponse;
use PhpImap\Response\Response;
use PhpImap\Response\ZimbraResponse;
use \PhpImap\Contract\Response as ContractResponse;

class ResponseFactory {

    /**
     * @param Email $email
     * @return ContractResponse
     */
    public static function detect(Email $email) {
        if (!empty($email->getTextHtml()))
            return self::detectByHtml($email);
        return self::detectByPlain($email);
    }

    private static function detectByHtml(Email $email) {
        if (strpos($email->getHeadersRaw(), 'X-Gmail-Original-Message-ID:') !== false)
            return new GmailResponse($email);

        if (strpos($email->getHeadersRaw(), 'X-Mailer: Zimbra') !== false)
            return new ZimbraResponse($email);

        return new Response($email);
    }

}