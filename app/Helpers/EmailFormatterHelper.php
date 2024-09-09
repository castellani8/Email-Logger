<?php

namespace App\Helpers;

class EmailFormatterHelper
{
    public static function extractPlainTextFromHtml($html): string
    {
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $bodyContent = $matches[1];
        } else {
            $bodyContent = $html;
        }

        $plainText = strip_tags($bodyContent);

        $plainText = preg_replace('/[ \t]+/', ' ', $plainText);

        $plainText = preg_replace('/\n+/', "\n", $plainText);

        $plainText = preg_replace('/^\s+|\s+$/m', '', $plainText);

        $plainText = preg_replace('/[^\PC\n\r]/u', '', $plainText);

        $plainText = preg_replace('/\s{2,}/', ' ', $plainText);

        $plainText = preg_replace('/(\r\n|\r|\n){2,}/', "\n", $plainText);

        return trim($plainText);
    }
}
