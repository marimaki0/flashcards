<?php

namespace App\Tests\Entity;

use App\Entity\Flashcard;
use App\Entity\User;
use App\Entity\Category;
use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class FlashcardTest extends TestCase
{
    private Flashcard $flashcard;
    
    protected function setUp(): void
    {
        $this->flashcard = new Flashcard();
    }

    public function testCreateFlashcard(): void
    {
        $this->assertInstanceOf(Flashcard::class, $this->flashcard);
        $this->assertNull($this->flashcard->getId());
        $this->assertNotNull($this->flashcard->getTags());
        $this->assertCount(0, $this->flashcard->getTags());
    }

    public function testSetAndGetQuestion(): void
    {
        $question = 'What is the capital of France?';
        $this->flashcard->setQuestion($question);
        
        $this->assertEquals($question, $this->flashcard->getQuestion());
    }

    public function testSetAndGetAnswer(): void
    {
        $answer = 'Paris';
        $this->flashcard->setAnswer($answer);
        
        $this->assertEquals($answer, $this->flashcard->getAnswer());
    }

    public function testSetAndGetDifficulty(): void
    {
        $difficulty = 2; // Hard
        $this->flashcard->setDifficulty($difficulty);
        
        $this->assertEquals($difficulty, $this->flashcard->getDifficulty());
    }

    public function testDifficultyName(): void
    {
        // Test Simple (0)
        $this->flashcard->setDifficulty(0);
        $this->assertEquals('Simple', $this->flashcard->getDifficultyName());
        
        // Test Average (1) 
        $this->flashcard->setDifficulty(1);
        $this->assertEquals('Average', $this->flashcard->getDifficultyName());
        
        // Test Hard (2)
        $this->flashcard->setDifficulty(2);
        $this->assertEquals('Hard', $this->flashcard->getDifficultyName());
        
        // Test Unknown (other values)
        $this->flashcard->setDifficulty(99);
        $this->assertEquals('Unknown', $this->flashcard->getDifficultyName());
    }

    public function testSetAndGetUser(): void
    {
        $user = new User();
        $this->flashcard->setUser($user);
        
        $this->assertEquals($user, $this->flashcard->getUser());
    }

    public function testSetAndGetCategory(): void
    {
        $category = new Category();
        $this->flashcard->setCategory($category);
        
        $this->assertEquals($category, $this->flashcard->getCategory());
    }

    public function testAddTag(): void
    {
        $tag = new Tag();
        $this->flashcard->addTag($tag);
        
        $this->assertCount(1, $this->flashcard->getTags());
        $this->assertTrue($this->flashcard->getTags()->contains($tag));
    }

    public function testAddSameTagTwice(): void
    {
        $tag = new Tag();
        $this->flashcard->addTag($tag);
        $this->flashcard->addTag($tag); // Add same tag again
        
        $this->assertCount(1, $this->flashcard->getTags()); // Should still be 1
    }

    public function testRemoveTag(): void
    {
        $tag = new Tag();
        $this->flashcard->addTag($tag);
        $this->assertCount(1, $this->flashcard->getTags());
        
        $this->flashcard->removeTag($tag);
        $this->assertCount(0, $this->flashcard->getTags());
        $this->assertFalse($this->flashcard->getTags()->contains($tag));
    }

    public function testRemoveNonExistentTag(): void
    {
        $tag1 = new Tag();
        $tag2 = new Tag();
        
        $this->flashcard->addTag($tag1);
        $this->flashcard->removeTag($tag2); // Remove tag that wasn't added
        
        $this->assertCount(1, $this->flashcard->getTags());
        $this->assertTrue($this->flashcard->getTags()->contains($tag1));
    }

    public function testMultipleTags(): void
    {
        $tag1 = new Tag();
        $tag2 = new Tag();
        $tag3 = new Tag();
        
        $this->flashcard->addTag($tag1);
        $this->flashcard->addTag($tag2);
        $this->flashcard->addTag($tag3);
        
        $this->assertCount(3, $this->flashcard->getTags());
        $this->assertTrue($this->flashcard->getTags()->contains($tag1));
        $this->assertTrue($this->flashcard->getTags()->contains($tag2));
        $this->assertTrue($this->flashcard->getTags()->contains($tag3));
    }

    public function testSettersReturnSelf(): void
    {
        $this->assertInstanceOf(Flashcard::class, $this->flashcard->setQuestion('Test'));
        $this->assertInstanceOf(Flashcard::class, $this->flashcard->setAnswer('Test'));
        $this->assertInstanceOf(Flashcard::class, $this->flashcard->setDifficulty(1));
        $this->assertInstanceOf(Flashcard::class, $this->flashcard->setUser(new User()));
        $this->assertInstanceOf(Flashcard::class, $this->flashcard->setCategory(new Category()));
        $this->assertInstanceOf(Flashcard::class, $this->flashcard->addTag(new Tag()));
        $this->assertInstanceOf(Flashcard::class, $this->flashcard->removeTag(new Tag()));
    }
}