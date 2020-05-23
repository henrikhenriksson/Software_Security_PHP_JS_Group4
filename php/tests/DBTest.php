<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../classes/db.class.php';
require_once __DIR__.'/../classes/config.class.php';

final class DBTest extends TestCase
{
    protected static $config;

    public static function setUpBeforeClass(): void
    {
        self::$config = new Config(__DIR__.'/../config.php');
    }

    private function goodDB(): DB
    {
        return new DB(self::$config->getLocalDbDsn());
    }

    private function badDB(): DB
    {
        return new DB('Invalid dsn');
    }

    public function testCanGetInstance(): void
    {
        $this->assertInstanceOf(
            DB::class,
            $this->goodDB()
        );
    }

    public function testCanCreateWithInvalidDsn(): void
    {
        $db = $this->badDB();
        $this->assertInstanceOf(
            DB::class,
            $this->badDB()
        );
    }

    public function testQueryOnBadConnectionReturnsFalse(): void
    {
        $db = $this->badDB();
        $this->assertFalse($db->query("SELECT * FROM dt161g.users"));
        $this->assertTrue($db->error());
        $this->assertNotEmpty($db->errorMessage());
    }

    public function testValidQueryReturnsTrue(): void
    {
        $db = $this->goodDB();
        $this->assertTrue($db->query("SELECT * FROM dt167g.users"));
        $this->assertFalse($db->error());
        $this->assertEmpty($db->errorMessage());
    }


    public function testValidQueryWithParamsReturnsTrue(): void
    {
        $db = $this->goodDB();
        $this->assertTrue($db->query(
            "SELECT * FROM dt167g.users WHERE username = $1",
            ['a']
        ));
    }

    public function testInvalidQueryReturnsFalse(): void
    {
        $db = $this->goodDB();
        $this->assertFalse($db->query(
            "SELECT * FROM wrong_table WHERE username = $1",
            ['a']
        ));
    }

    public function testCanGetAllRows(): void
    {
        $db = $this->goodDB();
        $db->query("SELECT * FROM dt167g.users");
        $this->assertGreaterThan(0, $db->resultCount());
        $this->assertGreaterThan(0, count($db->getAllRows()));
    }

    public function testCanGetAllRowsIndividually(): void
    {
        $db = $this->goodDB();
        $db->query("SELECT * FROM dt167g.users");
        $expected = $db->resultCount();

        $actual = 0;
        for ($i = 0; $i < $expected; ++$i) {
            if (!empty($db->getNextRow())) {
                ++$actual;
            }
        }
        $this->assertEquals($expected, $actual);
    }

    public function testNextResultReturnsEmptyArrayWhenNoMoreRowsInResult(): void
    {
        $db = $this->goodDB();
        $db->query("SELECT * FROM dt167g.users LIMIT 0");
        $this->assertEmpty($db->getNextRow());
    }

    public function testGetAllRowsReturnsEmptyArrayWhenThereAreNoMoreRows(): void
    {
        $db = $this->goodDB();
        $db->query("SELECT * FROM dt167g.users LIMIT 0");
        $this->assertEmpty($db->getAllRows());
    }
}
