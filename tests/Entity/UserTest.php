<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\StudySession;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    /**
     * Test podstawowego tworzenia użytkownika
     */
    public function testCanCreateUser(): void
    {
        $this->assertInstanceOf(User::class, $this->user);
        $this->assertNull($this->user->getId());
    }

    /**
     * Test email jako identyfikator
     */
    public function testEmailAsUserIdentifier(): void
    {
        $email = 'jan@example.com';
        $this->user->setEmail($email);
        
        $this->assertEquals($email, $this->user->getEmail());
        $this->assertEquals($email, $this->user->getUserIdentifier());
    }

    /**
     * Test username
     */
    public function testUsername(): void
    {
        $username = 'jankowalski';
        $result = $this->user->setUsername($username);
        
        $this->assertEquals($username, $this->user->getUsername());
        $this->assertSame($this->user, $result); // fluent interface
    }

    /**
     * Test imię i nazwisko
     */
    public function testFirstAndLastName(): void
    {
        $this->user->setFirstName('Jan');
        $this->user->setLastName('Kowalski');
        
        $this->assertEquals('Jan', $this->user->getFirstName());
        $this->assertEquals('Kowalski', $this->user->getLastName());
    }

    /**
     * Test hasła
     */
    public function testPassword(): void
    {
        $password = 'hashed_password_123';
        $this->user->setPassword($password);
        
        $this->assertEquals($password, $this->user->getPassword());
    }

    /**
     * Test domyślnych ról
     */
    public function testDefaultRoles(): void
    {
        $roles = $this->user->getRoles();
        
        $this->assertIsArray($roles);
        $this->assertContains('ROLE_USER', $roles);
    }

    /**
     * Test ustawiania ról
     */
    public function testSetRoles(): void
    {
        $this->user->setRoles(['ROLE_ADMIN', 'ROLE_MODERATOR']);
        $roles = $this->user->getRoles();
        
        $this->assertContains('ROLE_USER', $roles); // zawsze jest ROLE_USER
        $this->assertContains('ROLE_ADMIN', $roles);
        $this->assertContains('ROLE_MODERATOR', $roles);
    }

    /**
     * Test unikalności ról
     */
    public function testRolesUniqueness(): void
    {
        $this->user->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_USER']);
        $roles = $this->user->getRoles();
        
        $this->assertEquals(2, count($roles)); // tylko ROLE_USER i ROLE_ADMIN
    }

    /**
     * Test kolekcji kategorii
     */
    public function testCategoriesCollection(): void
    {
        $categories = $this->user->getCategories();
        
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $categories);
        $this->assertCount(0, $categories); // pusta na początku
    }

    /**
     * Test dodawania kategorii
     */
    public function testAddCategory(): void
    {
        $category = new Category();
        $result = $this->user->addCategory($category);
        
        $this->assertCount(1, $this->user->getCategories());
        $this->assertTrue($this->user->getCategories()->contains($category));
        $this->assertSame($this->user, $result); // fluent interface
    }

    /**
     * Test usuwania kategorii
     */
    public function testRemoveCategory(): void
    {
        $category = new Category();
        $this->user->addCategory($category);
        
        $result = $this->user->removeCategory($category);
        
        $this->assertCount(0, $this->user->getCategories());
        $this->assertFalse($this->user->getCategories()->contains($category));
        $this->assertSame($this->user, $result); // fluent interface
    }

    /**
     * Test kolekcji sesji nauki
     */
    public function testStudySessionsCollection(): void
    {
        $studySessions = $this->user->getStudySessions();
        
        $this->assertInstanceOf(\Doctrine\Common\Collections\Collection::class, $studySessions);
        $this->assertCount(0, $studySessions); // pusta na początku
    }

    /**
     * Test dodawania sesji nauki
     */
    public function testAddStudySession(): void
    {
        $studySession = new StudySession();
        $result = $this->user->addStudySession($studySession);
        
        $this->assertCount(1, $this->user->getStudySessions());
        $this->assertTrue($this->user->getStudySessions()->contains($studySession));
        $this->assertSame($this->user, $result); // fluent interface
    }

    /**
     * Test usuwania sesji nauki
     */
    public function testRemoveStudySession(): void
    {
        $studySession = new StudySession();
        $this->user->addStudySession($studySession);
        
        $result = $this->user->removeStudySession($studySession);
        
        $this->assertCount(0, $this->user->getStudySessions());
        $this->assertFalse($this->user->getStudySessions()->contains($studySession));
        $this->assertSame($this->user, $result); // fluent interface
    }

    /**
     * Test czyszczenia danych uwierzytelniania
     */
    public function testEraseCredentials(): void
    {
        // Ta metoda powinna istnieć ale zwykle nic nie robi
        $this->user->eraseCredentials();
        
        // Test że metoda nie rzuca wyjątku
        $this->assertTrue(true);
    }

    /**
     * Test polskich znaków w imionach
     */
    public function testPolishCharactersInNames(): void
    {
        $this->user->setFirstName('Paweł');
        $this->user->setLastName('Żółć');
        
        $this->assertEquals('Paweł', $this->user->getFirstName());
        $this->assertEquals('Żółć', $this->user->getLastName());
    }

    /**
     * Test długich wartości
     */
    public function testLongValues(): void
    {
        $longEmail = 'bardzo.dluga.nazwa.uzytkownika@bardzo-dluga-domena-email.com';
        $longUsername = 'bardzo_dluga_nazwa_uzytkownika_123';
        
        $this->user->setEmail($longEmail);
        $this->user->setUsername($longUsername);
        
        $this->assertEquals($longEmail, $this->user->getEmail());
        $this->assertEquals($longUsername, $this->user->getUsername());
    }
}