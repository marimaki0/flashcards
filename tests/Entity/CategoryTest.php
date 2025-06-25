<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    private Category $category;
    
    protected function setUp(): void
    {
        $this->category = new Category();
    }

    public function testCreateCategory(): void
    {
        $this->assertInstanceOf(Category::class, $this->category);
        $this->assertNull($this->category->getId());
    }

    public function testSetAndGetName(): void
    {
        $name = 'English';
        $this->category->setName($name);
        
        $this->assertEquals($name, $this->category->getName());
    }

    public function testSetAndGetDescription(): void
    {
        $description = 'English language learning';
        $this->category->setDescription($description);
        
        $this->assertEquals($description, $this->category->getDescription());
    }

    public function testSetAndGetColor(): void
    {
        $color = '#3b82f6';
        $this->category->setColor($color);
        
        $this->assertEquals($color, $this->category->getColor());
    }

    public function testEmptyDescription(): void
    {
        $this->assertNull($this->category->getDescription());
        
        $this->category->setDescription('');
        $this->assertEquals('', $this->category->getDescription());
    }

    public function testEmptyColor(): void
    {
        $this->assertNull($this->category->getColor());
        
        $this->category->setColor('');
        $this->assertEquals('', $this->category->getColor());
    }

    public function testSettersReturnSelf(): void
    {
        $this->assertInstanceOf(Category::class, $this->category->setName('Test'));
        $this->assertInstanceOf(Category::class, $this->category->setDescription('Test'));
        $this->assertInstanceOf(Category::class, $this->category->setColor('#000000'));
    }

    public function testCategoryWithCompleteData(): void
    {
        $name = 'Mathematics';
        $description = 'Mathematical concepts and formulas';
        $color = '#f59e0b';
        
        $this->category
            ->setName($name)
            ->setDescription($description)
            ->setColor($color);
        
        $this->assertEquals($name, $this->category->getName());
        $this->assertEquals($description, $this->category->getDescription());
        $this->assertEquals($color, $this->category->getColor());
    }
}