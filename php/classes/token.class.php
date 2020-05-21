<?php


class Token
{
    public static function generateToken(string $action)
    {
        ///@todo implement token generator
        return "12";
    }

    public static function validateToken(string $action, string $TS, string $token)
    {
        ///@todo implement validateToken

        return true;
    }

}