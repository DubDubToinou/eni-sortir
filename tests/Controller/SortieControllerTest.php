<?php

namespace App\Test\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SortieControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private SortieRepository $repository;
    private string $path = '/sortie/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Sortie::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Sortie index');

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
            'sortie[nom]' => 'Testing',
            'sortie[dateHeureDebut]' => 'Testing',
            'sortie[duree]' => 'Testing',
            'sortie[dateLimiteInscription]' => 'Testing',
            'sortie[nbInscriptionsMax]' => 'Testing',
            'sortie[infosSortie]' => 'Testing',
            'sortie[motif]' => 'Testing',
            'sortie[organisateur]' => 'Testing',
            'sortie[participants]' => 'Testing',
            'sortie[etat]' => 'Testing',
            'sortie[lieu]' => 'Testing',
            'sortie[campus]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sortie/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Sortie();
        $fixture->setNom('My Title');
        $fixture->setDateHeureDebut('My Title');
        $fixture->setDuree('My Title');
        $fixture->setDateLimiteInscription('My Title');
        $fixture->setNbInscriptionsMax('My Title');
        $fixture->setInfosSortie('My Title');
        $fixture->setMotif('My Title');
        $fixture->setOrganisateur('My Title');
        $fixture->setParticipants('My Title');
        $fixture->setEtat('My Title');
        $fixture->setLieu('My Title');
        $fixture->setCampus('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Sortie');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Sortie();
        $fixture->setNom('My Title');
        $fixture->setDateHeureDebut('My Title');
        $fixture->setDuree('My Title');
        $fixture->setDateLimiteInscription('My Title');
        $fixture->setNbInscriptionsMax('My Title');
        $fixture->setInfosSortie('My Title');
        $fixture->setMotif('My Title');
        $fixture->setOrganisateur('My Title');
        $fixture->setParticipants('My Title');
        $fixture->setEtat('My Title');
        $fixture->setLieu('My Title');
        $fixture->setCampus('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'sortie[nom]' => 'Something New',
            'sortie[dateHeureDebut]' => 'Something New',
            'sortie[duree]' => 'Something New',
            'sortie[dateLimiteInscription]' => 'Something New',
            'sortie[nbInscriptionsMax]' => 'Something New',
            'sortie[infosSortie]' => 'Something New',
            'sortie[motif]' => 'Something New',
            'sortie[organisateur]' => 'Something New',
            'sortie[participants]' => 'Something New',
            'sortie[etat]' => 'Something New',
            'sortie[lieu]' => 'Something New',
            'sortie[campus]' => 'Something New',
        ]);

        self::assertResponseRedirects('/sortie/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getDateHeureDebut());
        self::assertSame('Something New', $fixture[0]->getDuree());
        self::assertSame('Something New', $fixture[0]->getDateLimiteInscription());
        self::assertSame('Something New', $fixture[0]->getNbInscriptionsMax());
        self::assertSame('Something New', $fixture[0]->getInfosSortie());
        self::assertSame('Something New', $fixture[0]->getMotif());
        self::assertSame('Something New', $fixture[0]->getOrganisateur());
        self::assertSame('Something New', $fixture[0]->getParticipants());
        self::assertSame('Something New', $fixture[0]->getEtat());
        self::assertSame('Something New', $fixture[0]->getLieu());
        self::assertSame('Something New', $fixture[0]->getCampus());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Sortie();
        $fixture->setNom('My Title');
        $fixture->setDateHeureDebut('My Title');
        $fixture->setDuree('My Title');
        $fixture->setDateLimiteInscription('My Title');
        $fixture->setNbInscriptionsMax('My Title');
        $fixture->setInfosSortie('My Title');
        $fixture->setMotif('My Title');
        $fixture->setOrganisateur('My Title');
        $fixture->setParticipants('My Title');
        $fixture->setEtat('My Title');
        $fixture->setLieu('My Title');
        $fixture->setCampus('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/sortie/');
    }
}
