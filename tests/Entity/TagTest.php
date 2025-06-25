<?php

namespace App\Tests\Entity;

use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    private Tag $tag;

    protected function setUp(): void
    {
        $this->tag = new Tag();
    }

    /**
     * Test podstawowego tworzenia tagu
     */
    public function testCanCreateTag(): void
    {
        $this->assertInstanceOf(Tag::class, $this->tag);
        $this->assertNull($this->tag->getId());
    }

    /**
     * Test ustawiania i pobierania nazwy
     */
    public function testCanSetAndGetName(): void
    {
        $name = 'gramatyka';
        $result = $this->tag->setName($name);
        
        $this->assertEquals($name, $this->tag->getName());
        $this->assertSame($this->tag, $result); // fluent interface
    }

    /**
     * Test z polskimi znakami
     */
    public function testNameWithPolishCharacters(): void
    {
        $name = 'słówka-trudne';
        $this->tag->setName($name);
        
        $this->assertEquals($name, $this->tag->getName());
    }

    /**
     * Test wrażliwości na wielkość liter
     */
    public function testNameCaseSensitivity(): void
    {
        $this->tag->setName('GRAMATYKA');
        $this->assertEquals('GRAMATYKA', $this->tag->getName());
        
        $this->tag->setName('gramatyka');
        $this->assertEquals('gramatyka', $this->tag->getName());
    }

    /**
     * Test pustej nazwy
     */
    public function testEmptyName(): void
    {
        $this->tag->setName('');
        $this->assertEquals('', $this->tag->getName());
    }

    /**
     * Test długiej nazwy
     */
    public function testLongName(): void
    {
        $longName = 'bardzo-długa-nazwa-tagu-która-może-być-używana-w-aplikacji';
        $this->tag->setName($longName);
        
        $this->assertEquals($longName, $this->tag->getName());
    }
}