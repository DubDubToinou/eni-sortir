<?php

namespace App\Test\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParticipantControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ParticipantRepository $repository;
    private string $path = '/participant/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Participant::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Participant index');

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
            'participant[email]' => 'Testing',
            'participant[roles]' => 'Testing',
            'participant[password]' => 'Testing',
            'participant[nom]' => 'Testing',
            'participant[prenom]' => 'Testing',
            'participant[telephone]' => 'Testing',
            'participant[administrateur]' => 'Testing',
            'participant[actif]' => 'Testing',
            'participant[Campus]' => 'Testing',
            'participant[sortieInscrit]' => 'Testing',
        ]);

        self::assertResponseRedirects('/participant/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Participant();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setAdministrateur('My Title');
        $fixture->setActif('My Title');
        $fixture->setCampus('My Title');
        $fixture->setSortieInscrit('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Participant');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Participant();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setAdministrateur('My Title');
        $fixture->setActif('My Title');
        $fixture->setCampus('My Title');
        $fixture->setSortieInscrit('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'participant[email]' => 'Something New',
            'participant[roles]' => 'Something New',
            'participant[password]' => 'Something New',
            'participant[nom]' => 'Something New',
            'participant[prenom]' => 'Something New',
            'participant[telephone]' => 'Something New',
            'participant[administrateur]' => 'Something New',
            'participant[actif]' => 'Something New',
            'participant[Campus]' => 'Something New',
            'participant[sortieInscrit]' => 'Something New',
        ]);

        self::assertResponseRedirects('/participant/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getRoles());
        self::assertSame('Something New', $fixture[0]->getPassword());
        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getPrenom());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getAdministrateur());
        self::assertSame('Something New', $fixture[0]->getActif());
        self::assertSame('Something New', $fixture[0]->getCampus());
        self::assertSame('Something New', $fixture[0]->getSortieInscrit());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Participant();
        $fixture->setEmail('My Title');
        $fixture->setRoles('My Title');
        $fixture->setPassword('My Title');
        $fixture->setNom('My Title');
        $fixture->setPrenom('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setAdministrateur('My Title');
        $fixture->setActif('My Title');
        $fixture->setCampus('My Title');
        $fixture->setSortieInscrit('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/participant/');
    }
}
