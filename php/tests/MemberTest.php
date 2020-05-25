<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../vendor/autoload.php';  // Let composer handle autoloads
require_once __DIR__.'/../globals.php';

final class MemberTest extends TestCase
{
    protected static $db;
    protected static $addedUsers;

    public static function setUpBeforeClass(): void
    {
        static::$db = getLocalEasyDB();
        static::$addedUsers = [];
    }

    /**
     * @before
     */
    public static function resetSession(): void
    {
        Session::init(new TestSession());
    }

    /**
     * @after
     */
    public static function cleanDatabase(): void
    {
        foreach (static::$addedUsers as $username) {
            if (static::$db->single(
                'SELECT COUNT(id) FROM users WHERE username = :username',
                ['username'=> $username]
            )) {
                static::$db->delete('users', ['username' => $username]);
            }
        }
    }

    public function testCanCreateMemberWithLocalDB(): void
    {
        $this->assertInstanceOf(
            Member::class,
            new Member(static::$db)
        );
    }

    public function testCanSetUsername(): void
    {
        $member = new Member(static::$db);
        $member->setUsername('abc');
        $this->assertEquals('abc', $member->username());
    }

    public function testUsernameIsEscapedForXSS(): void
    {
        $member = new Member(static::$db);
        $dangerous = "<script>alert('xss attack');</script>";
        $member->setUsername($dangerous);
        $this->assertFalse(\strpos("<", $member->username()));
        $this->assertFalse(\strpos(">", $member->username()));
    }

    public function testCanInsertMemberInDatabase(): void
    {
        $member = new Member(static::$db);
        $member->setUsername('abc');
        $this->assertTrue($member->save('abc'), $member->errorMessage());
        $this->assertNotEquals(-1, $member->id());
        static::$addedUsers[] = $member->username();
    }

    public function testCantInsertSameMemberTwice(): void
    {
        $member = new Member(static::$db);
        $member->setUsername('abc');
        $this->assertTrue($member->save('abc'), $member->errorMessage());
        $this->assertFalse($member->save('abc'), "Inserted same member twice?");
        $this->assertNotEquals(-1, $member->id());
        static::$addedUsers[] = $member->username();
    }

    public function testCantInsertMemberWithoutusername(): void
    {
        $member = new Member(static::$db);
        $this->assertFalse(
            $member->save('abc'),
            "Could insert member without username"
        );
        static::$addedUsers[] = $member->username();
    }

    public function testCantUsePasswordLongerThan64Characters(): void
    {
        $member = new Member(static::$db);
        $member->setUsername('abc');
        $this->assertFalse(
            $member->save(\str_repeat('a', 65)),
            "Could insert member with password length > 64"
        );
        static::$addedUsers[] = $member->username();
    }

    public function testCanGetAllMembersFromDatabase(): void
    {
        $members = Member::fetchAll(self::$db);
        $this->assertNotEmpty($members);
        $this->assertGreaterThan(1, \count($members));
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
        $this->assertEquals(1, \count($members));
    }

    public function testCanOffsetResultFromFetchMembers(): void
    {
        $first = Member::fetchMembers(1, 0, static::$db)[0];
        $second = Member::fetchMembers(1, 1, static::$db)[0];
        $this->assertNotEquals($first, $second);
    }

    public function testCanLoginUser(): void
    {
        $member = Member::login('a', 'a', static::$db);
        $this->assertFalse($member->error(), $member->errorMessage());
        $this->assertTrue(Session::has('userid'));
        // Do not clean up this user!
    }

    public function testCantLoginWithEmptyUsername(): void
    {
        $member = Member::login(' ', 'a', static::$db);
        $this->assertTrue($member->error());
        $this->assertFalse(Session::has('userid'));
        static::$addedUsers[] = $member->username();
    }

    public function testCantLoginWithEmptyPassword(): void
    {
        $member = Member::login('a', ' ', static::$db);
        $this->assertTrue($member->error());
        $this->assertFalse(Session::has('userid'));
        static::$addedUsers[] = $member->username();
    }

    public function testCantLoginWithInvalidPassword(): void
    {
        $member = Member::login('a', 'z', static::$db);
        $this->assertTrue($member->error());
        $this->assertFalse(Session::has('userid'));
        static::$addedUsers[] = $member->username();
    }

    public function testCantLoginWithInvalidUsername(): void
    {
        $member = Member::login('z', 'a', static::$db);
        $this->assertTrue($member->error());
        $this->assertFalse(Session::has('userid'));
        static::$addedUsers[] = $member->username();
    }

    public function testCanCheckIfUserIsLoggedIn(): void
    {
        $this->assertFalse(Member::loggedIn(), "User was already logged in!");
        $member = Member::login('a', 'a', static::$db);
        $this->assertTrue(Member::loggedIn(), "User was never logged in!");
    }

    public function testMemberCanBeRestoredFromSession(): void
    {
        Session::set('userid', '2');
        $member = Member::fromSession(self::$db);
        $this->assertFalse($member->error());
    }

    public function testMemberCantBeRestoredIfNotInSession(): void
    {
        Session::unset('userid');
        $member = Member::fromSession(self::$db);
        $this->assertTrue($member->error());
    }

    public function testMemberCantBeRestoredIfSessionIsHijacked(): void
    {
        Session::set('userid', '9999');
        $member = Member::fromSession(self::$db);
        $this->assertTrue($member->error());
    }
}
