<?php

namespace App\Tests\Controller;

use App\Entity\Flashcard;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FlashcardControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $flashcardRepository;
    private string $path = '/flashcard/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->flashcardRepository = $this->manager->getRepository(Flashcard::class);

        foreach ($this->flashcardRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Flashcard index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'flashcard[question]' => 'Testing',
            'flashcard[answer]' => 'Testing',
            'flashcard[difficulty]' => 'Testing',
            'flashcard[user]' => 'Testing',
            'flashcard[category]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->flashcardRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Flashcard();
        $fixture->setQuestion('My Title');
        $fixture->setAnswer('My Title');
        $fixture->setDifficulty('My Title');
        $fixture->setUser('My Title');
        $fixture->setCategory('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Flashcard');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Flashcard();
        $fixture->setQuestion('Value');
        $fixture->setAnswer('Value');
        $fixture->setDifficulty('Value');
        $fixture->setUser('Value');
        $fixture->setCategory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'flashcard[question]' => 'Something New',
            'flashcard[answer]' => 'Something New',
            'flashcard[difficulty]' => 'Something New',
            'flashcard[user]' => 'Something New',
            'flashcard[category]' => 'Something New',
        ]);

        self::assertResponseRedirects('/flashcard/');

        $fixture = $this->flashcardRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getQuestion());
        self::assertSame('Something New', $fixture[0]->getAnswer());
        self::assertSame('Something New', $fixture[0]->getDifficulty());
        self::assertSame('Something New', $fixture[0]->getUser());
        self::assertSame('Something New', $fixture[0]->getCategory());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Flashcard();
        $fixture->setQuestion('Value');
        $fixture->setAnswer('Value');
        $fixture->setDifficulty('Value');
        $fixture->setUser('Value');
        $fixture->setCategory('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/flashcard/');
        self::assertSame(0, $this->flashcardRepository->count([]));
    }
}
