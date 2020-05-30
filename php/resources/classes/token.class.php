<?php

declare(strict_types=1);

use \ParagonIE\AntiCSRF\AntiCSRF as TokenLib;

/*******************************************************************************
 * Project Group 4 DT167G
 * File: token.class.php
 ******************************************************************************/
///@todo replace wiht AntiCSRf
class Token
{

    public static function generateTokenForm(TokenLib $token, string $idPrefix, string $lockTo = '', bool $echo = true): string
    {
        $token_array = $token->getTokenArray($lockTo);
        $prefixArray = array_fill(0, count($token_array), $idPrefix );
        $ret = \implode(
            \array_map(
                function( string $idPrefix, string $key, string $value): string {
                    return "<!--\n-->".
                        "<input type=\"hidden\"" .
                        " name=\"". $key . "\"" .
                        " id=\"" . $idPrefix . $key . "\"" .
                        " value=\"" .  \htmlentities($value, ENT_QUOTES, 'UTF-8') . "\"" .
                        " />";
                },
                $prefixArray,
                \array_keys($token_array),
                $token_array
            )
        );
        if ($echo) {
            echo $ret;
            return '';
        }
        return $ret;
    }

    public static function generateTokenArray(TokenLib $token, string $lockTo): array
    {
        $newCluster = $token->getTokenArray($lockTo);
        $result = ['_CSRF_TOKEN'=>\htmlentities($newCluster['_CSRF_TOKEN'], ENT_QUOTES, 'UTF-8'),
            '_CSRF_INDEX'=>\htmlentities($newCluster['_CSRF_INDEX'], ENT_QUOTES, 'UTF-8')];
        return $result;
    }



}
