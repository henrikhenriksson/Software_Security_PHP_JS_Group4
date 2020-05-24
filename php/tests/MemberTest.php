<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../vendor/autoload.php';  // Let composer handle autoloads
require_once __DIR__.'/../globals.php';

final class MemberTest extends TestCase
{
    protected static $db;

    public static function setUpBeforeClass(): void
    {
        static::$db = getLocalEasyDB();
    }

    public function testCanCreateMemberWithLocalDB(): void
    {
        $this->assertInstanceOf(
            Member::class,
            new Member(static::$db)
        );
    }

    public function testCanGetAllMembersFromDatabase(): void
    {
        $members = Member::fetchAll(self::$db);
        $this->assertNotEmpty($members);
        $this->assertGreaterThan(1, count($members));
        $isMemberClass = true;
        foreach ($members as $m) {
            if (!($m instanceof Member)) {
                $isMemberClass = false;
            }
        }
        $this->assertTrue($isMemberClass, "Array from getAll does not contain members");
    }

    public function testCanLimitResultFromFetchMembers(): void
    {
        $members = Member::fetchMembers(1, 0, static::$db);
        $this->assertEquals(1, count($members));
    }

    public function testCanOffsetResultFromFetchMembers(): void
    {
        $first = Member::fetchMembers(1, 0, static::$db)[0];
        $second = Member::fetchMembers(1, 1, static::$db)[0];
        $this->assertNotEquals($first, $second);
    }
}
