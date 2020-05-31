<?php

declare(strict_types=1);

use \ParagonIE\EasyDB\EasyDB;
use function Latitude\QueryBuilder\field;

use \Latitude\QueryBuilder as Q;

/**
 * Class InvReqRate Helps to detect if multiple invalid request is made from the same IP address
 * If an invalid request is detected the request should be stored in the database and can be done with this class
 */
class InvReq
{

    /**
     * overloaded version of addInvalidRequestWIp that users internal function to get the ip address
     * @param string $action
     * @param $userName
     */
    public static function addInvalidRequest(string $action, $userName)
    {
        self::addInvalidRequestWIp($action, self::getUserIpAddress(), $userName);
    }

    /**
     * Adds an invalid request to the database table.
     * The current time is used as the current time.
     * The action should be added since it gives the possibility to analyze in which areas invalid requests has been detected.
     * But the action is not used otherwise
     *
     * @param string $action In which are were the invalid request detected.
     * @param string $ip The ip address of the user.
     * @param string $userName The username of the logged in user, if available
     */
    public static function addInvalidRequestWIp(string $action, string $ip, $userName):void
    {
        $data = array('iplog'=>$ip, 'req_page'=>$action, 'user_name'=>$userName);

        $db = getEasyDB();
        $db->setAllowSeparators(true);

        $db->insert('invalid_requests', $data);
    }

    /**
     * Checks if the ip address of the current caller is blacklisted due to many invalid request.
     * The nr of invalid attempts is counter during the last 1 hour
     * @return bool Should the caller be blocked or not
     */
    public static function validIpCurUser():bool
    {
        return self::validIp(self::getUserIpAddress());
    }

    /**
     * Checks if the given ip address should be blacklisted due to to many invalid requests.
     * The nr of invalid attempts is counted during the last 1 hour
     * @param string $checkIp
     * @return bool
     */
    public static function validIp(string $checkIp): bool
    {
        $time = date('Y-m-d H:i:s.u', time() - 3600);

        $db = getEasyDB();

//        $factory = makeQueryFactory();

        $count = $db->cell(
            "SELECT count(*) FROM dt167g.invalid_requests where iplog = ? and timelog > ?",
            $checkIp,
            $time
        );
        ;
        return 3 > $count;
    }

    /**
     * Gets the ip address of the caller from the _SERVER variables
     *
     * @return mixed|string representation of the ip address
     */
    public static function getUserIpAddress(): string
    {
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } else {
            return "N/A";
        }
    }
}
