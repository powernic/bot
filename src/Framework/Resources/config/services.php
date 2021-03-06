<?php

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\Persistence\ManagerRegistry;
use Powernic\Bot\Framework\Doctrine\EntityManagerFactory;
use Powernic\Bot\Framework\Doctrine\EventSubscriber\NullableEmbeddable;
use Powernic\Bot\Framework\Doctrine\Registry;
use Powernic\Bot\Framework\Handler\Resolver\CallbackHandlerResolver;
use Powernic\Bot\Framework\Handler\Resolver\TextHandlerResolver;
use Powernic\Bot\Framework\Handler\Resolver\CommandHandlerResolver;
use Powernic\Bot\Framework\Handler\Resolver\ContainerHandlerResolver;
use Powernic\Bot\Framework\Repository\ContainerRepositoryFactory;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set(Client::class, Client::class)
            ->public()
            ->arg('$token', '%env(TOKEN)%')
        ->set('handler_resolver.callback', CallbackHandlerResolver::class)
            ->args(
                [
                    service('service_container'),
                    service(Client::class),
                ]
            )
        ->set('handler_resolver.command', CommandHandlerResolver::class)
            ->args(
                [
                    service('service_container'),
                    service(Client::class),
                ]
            )
        ->set('handler_resolver.text.callback', TextHandlerResolver::class)
            ->args(
                [
                    service('service_container'),
                    service(Client::class),
                ]
            )
        ->set('handler_resolver.container', ContainerHandlerResolver::class)
            ->public()
            ->args(
                [
                    [
                        service('handler_resolver.callback'),
                        service('handler_resolver.command'),
                        service('handler_resolver.text.callback'),
                        ]
                ]
            )
        ->set(BotApi::class, BotApi::class)
            ->public()
            ->arg('$token', '%env(TOKEN)%')
        ->set(EntityManager::class, EntityManager::class)
            ->public()
            ->factory([EntityManagerFactory::class, 'create'])
            ->arg('$url', '%env(DATABASE_URL)%')
        ->alias(EntityManagerInterface::class, EntityManager::class)
        ->set(EntityRepository::class, EntityRepository::class)
            ->public()
            ->factory([EntityManagerFactory::class, 'create'])
            ->arg('$url', '%env(DATABASE_URL)%')
        ->alias(EntityManagerInterface::class, EntityManager::class)
        ->set('doctrine.connection', Connection::class)
            ->public()
        ->set(ManagerRegistry::class, Registry::class)
            ->args([
                service("service_container"),
                [],
                [EntityManager::class],
                "",
                EntityManager::class
            ])
        ->alias('doctrine', ManagerRegistry::class)
        ->set('doctrine.orm.container_repository_factory', ContainerRepositoryFactory::class)
            ->args([service(ServiceLocator::class)])
        ->set('doctrine.orm.metadata.attribute.class', AttributeDriver::class)
            ->args([service(ServiceLocator::class)]);
};
