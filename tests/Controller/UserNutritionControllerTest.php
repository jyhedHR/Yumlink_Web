<?php

namespace App\Test\Controller;

use App\Entity\UserNutrition;
use App\Repository\UserNutritionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserNutritionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserNutritionRepository $repository;
    private string $path = '/user/nutrition/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(UserNutrition::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('UserNutrition index');

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
            'user_nutrition[age]' => 'Testing',
            'user_nutrition[weight]' => 'Testing',
            'user_nutrition[height]' => 'Testing',
            'user_nutrition[activityLevel]' => 'Testing',
            'user_nutrition[gender]' => 'Testing',
            'user_nutrition[calorie]' => 'Testing',
            'user_nutrition[protein]' => 'Testing',
            'user_nutrition[carbs]' => 'Testing',
            'user_nutrition[fat]' => 'Testing',
            'user_nutrition[user]' => 'Testing',
        ]);

        self::assertResponseRedirects('/user/nutrition/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new UserNutrition();
        $fixture->setAge('My Title');
        $fixture->setWeight('My Title');
        $fixture->setHeight('My Title');
        $fixture->setActivityLevel('My Title');
        $fixture->setGender('My Title');
        $fixture->setCalorie('My Title');
        $fixture->setProtein('My Title');
        $fixture->setCarbs('My Title');
        $fixture->setFat('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('UserNutrition');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new UserNutrition();
        $fixture->setAge('My Title');
        $fixture->setWeight('My Title');
        $fixture->setHeight('My Title');
        $fixture->setActivityLevel('My Title');
        $fixture->setGender('My Title');
        $fixture->setCalorie('My Title');
        $fixture->setProtein('My Title');
        $fixture->setCarbs('My Title');
        $fixture->setFat('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'user_nutrition[age]' => 'Something New',
            'user_nutrition[weight]' => 'Something New',
            'user_nutrition[height]' => 'Something New',
            'user_nutrition[activityLevel]' => 'Something New',
            'user_nutrition[gender]' => 'Something New',
            'user_nutrition[calorie]' => 'Something New',
            'user_nutrition[protein]' => 'Something New',
            'user_nutrition[carbs]' => 'Something New',
            'user_nutrition[fat]' => 'Something New',
            'user_nutrition[user]' => 'Something New',
        ]);

        self::assertResponseRedirects('/user/nutrition/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getAge());
        self::assertSame('Something New', $fixture[0]->getWeight());
        self::assertSame('Something New', $fixture[0]->getHeight());
        self::assertSame('Something New', $fixture[0]->getActivityLevel());
        self::assertSame('Something New', $fixture[0]->getGender());
        self::assertSame('Something New', $fixture[0]->getCalorie());
        self::assertSame('Something New', $fixture[0]->getProtein());
        self::assertSame('Something New', $fixture[0]->getCarbs());
        self::assertSame('Something New', $fixture[0]->getFat());
        self::assertSame('Something New', $fixture[0]->getUser());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new UserNutrition();
        $fixture->setAge('My Title');
        $fixture->setWeight('My Title');
        $fixture->setHeight('My Title');
        $fixture->setActivityLevel('My Title');
        $fixture->setGender('My Title');
        $fixture->setCalorie('My Title');
        $fixture->setProtein('My Title');
        $fixture->setCarbs('My Title');
        $fixture->setFat('My Title');
        $fixture->setUser('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/user/nutrition/');
    }
}
