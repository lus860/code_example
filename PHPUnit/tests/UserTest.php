<?php

use App\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    private $user;

    protected function setUp(): void
    {
        $this->user = new User(33, 'John', '12345678', 'test@mail.loc');
    }

    protected function tearDown(): void
    {

    }

    public function testTellName()
    {
        $this->user->setName('Jim');
        $this->assertSame('Jim', $this->user->getName());
        // return value is a string
        $this->assertIsString($this->user->tellName());
        // return value is a string and that it contains the string Jim
        $this->assertStringContainsStringIgnoringCase('Jim', $this->user->tellName());
    }

    public function testTellAge()
    {
        $this->assertIsString($this->user->tellAge());
        $this->assertStringContainsStringIgnoringCase('18', $this->user->tellAge());
    }

    public function testAddFavoriteMovie()
    {
        $this->assertTrue($this->user->addFavoriteMovie('Avengers'));
        // contains the new added string
        $this->assertContains('Avengers', $this->user->favorite_movies);
        $this->assertCount(1, $this->user->favorite_movies);
    }

    public function testRemoveFavoriteMovie()
    {
        $this->assertTrue($this->user->addFavoriteMovie('Avengers'));
        $this->assertTrue($this->user->addFavoriteMovie('Justice League'));
        $this->assertTrue($this->user->removeFavoriteMovie('Avengers'));
        $this->assertNotContains('Avengers', $this->user->favorite_movies);
        $this->assertCount(1, $this->user->favorite_movies);
    }

    public function testFavoriteMovie()
    {
        $this->assertEmpty($this->user->favorite_movies);
    }

    public function testAge1()
    {
        /// 33 === $this->user->getAge()
        $this->assertSame('33', $this->user->getAge());
        return 33;
    }

    /**
     * @depends testAge1
     */
    public function testAge2($age)
    {
        /// 33 == $this->user->getAge()
        $this->assertEquals($age, $this->user->getAge());
    }

    /**
     * dataProvider userProvider
     */
    public function testAge3($age)
    {
        $this->assertEquals($age, $this->user->getAge());
    }

    /**
     * dataProvider userProvider2
     */
    public function testAge4($age1, $age2)
    {
        $this->assertEquals($age1, $age2);
    }

    public function userProvider()
    {
        return [
            [1],
            [2],
            [33],
        ];
    }

    public function userProvider2()
    {
        return [
            [2, 3],
            [-2, 5],
            ['33', 33],
        ];
    }

    /**
     * expectedException InvalidArgumentException
     */
    public function testEmailException()
    {
        $this->expectException(\PHPUnit\Framework\Error\Error::class);
        //$this->expectExceptionCode(10);
        //$this->expectExceptionMessage("Error email1");
        //$this->expectExceptionMessageRegExp("/\d/");
        $this->user->getEmail();
    }

}