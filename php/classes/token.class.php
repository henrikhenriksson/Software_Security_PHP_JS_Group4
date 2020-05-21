<?php


class Token
{
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