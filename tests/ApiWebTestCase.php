<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\RouterInterface;

/**
 * Bootstraps a client and prepares a fresh schema
 */
abstract class ApiWebTestCase extends WebTestCase
{
    protected KernelBrowser          $client;
    protected EntityManagerInterface $em;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();

        $this->client = static::createClient();
        $this->em     = self::getContainer()->get('doctrine')->getManager();

        $this->prepareNewSchema();
    }

    protected function tearDown(): void
    {
        $this->em->close();
        parent::tearDown();
    }


    protected function prepareNewSchema() {
        $tool = new SchemaTool($this->em);
        $metadata = $this->em->getMetadataFactory()->getAllMetadata();
        if ($metadata) {
            $tool->dropSchema($metadata);
            $tool->createSchema($metadata);
        }
    }

    protected function uri(string $route, array $params = []): string
    {
        /** @var RouterInterface $router */
        $router = self::getContainer()->get(RouterInterface::class);

        return $router->generate($route, $params);
    }

    protected function decodeResponseJson() {
        return json_decode($this->client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);
    }
}