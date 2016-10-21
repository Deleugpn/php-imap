<?php

namespace PhpImap\Factory;


use PhpImap\Contract\Email;
use PhpImap\Response\AsusAndroidResponse;
use PhpImap\Response\GmailResponse;
use PhpImap\Response\iPhoneResponse;
use PhpImap\Response\Response;
use PhpImap\Response\SamsungAndroidResponse;
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
        if (strpos($email->getHeadersRaw(), 'X-Google-DKIM-Signature:') !== false)
            return new GmailResponse($email);

        if (strpos($email->getHeadersRaw(), 'X-Mailer: Zimbra') !== false)
            return new ZimbraResponse($email);

        if (strpos($email->getHeadersRaw(), 'X-Mailer: iPhone Mail') !== false)
            return new iPhoneResponse($email);

        if (strpos($email->getHeadersRaw(), '--_com.asus.email_') !== false)
            return new AsusAndroidResponse($email);

        if (strpos($email->getHeadersRaw(), '--_com.samsung.android.email_') !== false)
            return new SamsungAndroidResponse($email);

        return new Response($email);
    }

}