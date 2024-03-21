<?php

namespace App\Test\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ArticleRepository $repository;
    private string $path = '/article/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Article::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'article[titleArticle]' => 'Testing',
            'article[imgArticle]' => 'Testing',
            'article[descriptionArticle]' => 'Testing',
            'article[nbLikesArticle]' => 'Testing',
            'article[datePublished]' => 'Testing',
            'article[tags]' => 'Testing',
            'article[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/article/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Article();
        $fixture->setTitleArticle('My Title');
        $fixture->setImgArticle('My Title');
        $fixture->setDescriptionArticle('My Title');
        $fixture->setNbLikesArticle('My Title');
        $fixture->setDatePublished('My Title');
        $fixture->setTags('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Article');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Article();
        $fixture->setTitleArticle('My Title');
        $fixture->setImgArticle('My Title');
        $fixture->setDescriptionArticle('My Title');
        $fixture->setNbLikesArticle('My Title');
        $fixture->setDatePublished('My Title');
        $fixture->setTags('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'article[titleArticle]' => 'Something New',
            'article[imgArticle]' => 'Something New',
            'article[descriptionArticle]' => 'Something New',
            'article[nbLikesArticle]' => 'Something New',
            'article[datePublished]' => 'Something New',
            'article[tags]' => 'Something New',
            'article[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/article/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitleArticle());
        self::assertSame('Something New', $fixture[0]->getImgArticle());
        self::assertSame('Something New', $fixture[0]->getDescriptionArticle());
        self::assertSame('Something New', $fixture[0]->getNbLikesArticle());
        self::assertSame('Something New', $fixture[0]->getDatePublished());
        self::assertSame('Something New', $fixture[0]->getTags());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Article();
        $fixture->setTitleArticle('My Title');
        $fixture->setImgArticle('My Title');
        $fixture->setDescriptionArticle('My Title');
        $fixture->setNbLikesArticle('My Title');
        $fixture->setDatePublished('My Title');
        $fixture->setTags('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/article/');
    }
}
