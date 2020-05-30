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

    public static function generateToken(string $action)
    {
        ///@todo implement token generator
        return "12";
    }

    public static function validateToken(string $userAction, string $userTS, string $userToken)
    {
        ///@todo implement validateToken

        return true;
    }

    public static function generateTs()
    {
        ///@todo implement generateTs
        return "34";
    }

    public static function validateTs(string $userTs)
    {
        ///@todo implement validateTs
    }
}
