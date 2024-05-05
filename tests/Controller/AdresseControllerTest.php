<?php

namespace App\Test\Controller;

use App\Entity\Adresse;
use App\Repository\AdresseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdresseControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private AdresseRepository $repository;
    private string $path = '/adresse/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Adresse::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adresse index');

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
            'adresse[gouvernorat]' => 'Testing',
            'adresse[ville]' => 'Testing',
            'adresse[rue]' => 'Testing',
            'adresse[codepostal]' => 'Testing',
        ]);

        self::assertResponseRedirects('/adresse/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Adresse();
        $fixture->setGouvernorat('My Title');
        $fixture->setVille('My Title');
        $fixture->setRue('My Title');
        $fixture->setCodepostal('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Adresse');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Adresse();
        $fixture->setGouvernorat('My Title');
        $fixture->setVille('My Title');
        $fixture->setRue('My Title');
        $fixture->setCodepostal('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'adresse[gouvernorat]' => 'Something New',
            'adresse[ville]' => 'Something New',
            'adresse[rue]' => 'Something New',
            'adresse[codepostal]' => 'Something New',
        ]);

        self::assertResponseRedirects('/adresse/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getGouvernorat());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getRue());
        self::assertSame('Something New', $fixture[0]->getCodepostal());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Adresse();
        $fixture->setGouvernorat('My Title');
        $fixture->setVille('My Title');
        $fixture->setRue('My Title');
        $fixture->setCodepostal('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/adresse/');
    }
}
