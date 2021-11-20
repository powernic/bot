<?php

namespace Powernic\Bot;

use Closure;
use Doctrine\ORM\EntityManager;
use Powernic\Bot\CallbackHandler\CallbackHandlerLoader;
use Powernic\Bot\Framework\DependencyInjection\CallbackHandlerPass;
use Powernic\Bot\Framework\DependencyInjection\CommandHandlerPass;
use Powernic\Bot\Framework\Doctrine\EntityManagerFactory;
use Powernic\Bot\Framework\TextHandler\CallbackTextHandler;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\AbstractConfigurator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader as ContainerPhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client;

use function dirname;

final class Kernel extends BaseKernel
{

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        if (isset($_SERVER['APP_CACHE_DIR'])) {
            return $_SERVER['APP_CACHE_DIR'] . '/' . $this->environment;
        }

        return parent::getCacheDir();
    }


    /**
     * {@inheritdoc}
     */
    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir() . "/config/bundles.php";
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }


    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        } else {
            $container->import('../config/{services}.php');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) use ($loader) {
            $kernelClass = false !== strpos(static::class, "@anonymous\0") ? parent::class : static::class;

            if (!$container->hasDefinition('kernel')) {
                $container->register('kernel', $kernelClass)
                    ->addTag('controller.service_arguments')
                    ->setAutoconfigured(true)
                    ->setSynthetic(true)
                    ->setPublic(true);
            }

            $container->addObjectResource($this);
            $container->fileExists($this->getProjectDir() . '/config/bundles.php');


            $configureContainer = new \ReflectionMethod($this, 'configureContainer');

            // the user has opted into using the ContainerConfigurator
            /* @var ContainerPhpFileLoader $kernelLoader */
            $kernelLoader = $loader->getResolver()->resolve($file = $configureContainer->getFileName());
            $kernelLoader->setCurrentDir(dirname($file));

            $instanceof = &\Closure::bind(function &() {
                return $this->instanceof;
            }, $kernelLoader, $kernelLoader)();

            $valuePreProcessor = AbstractConfigurator::$valuePreProcessor;
            AbstractConfigurator::$valuePreProcessor = function ($value) {
                return $this === $value ? new Reference('kernel') : $value;
            };

            try {
                $this->configureContainer(
                    new ContainerConfigurator(
                        $container,
                        $kernelLoader,
                        $instanceof,
                        $file,
                        $file,
                        $this->getEnvironment()
                    ),
                    $loader
                );
            } finally {
                $instanceof = [];
                $kernelLoader->registerAliasesForSinglyImplementedInterfaces();
                AbstractConfigurator::$valuePreProcessor = $valuePreProcessor;
            }

            $container->setAlias($kernelClass, 'kernel')->setPublic(true);
            $container->addCompilerPass(new CommandHandlerPass());
            $container->addCompilerPass(new CallbackHandlerPass());
            if (!$container->hasDefinition(Client::class)) {
                $container->register(Client::class, Client::class)
                    ->setPublic(true)
                    ->setArgument('$token', '%env(TOKEN)%');
            }
            if (!$container->hasDefinition(BotApi::class)) {
                $container->register(BotApi::class, BotApi::class)
                    ->setPublic(true)
                    ->setArgument('$token', '%env(TOKEN)%');
            }
            if (!$container->hasDefinition(EntityManager::class)) {
                $container->register(EntityManager::class, EntityManager::class)
                    ->setPublic(true)
                    ->setFactory([EntityManagerFactory::class, 'create'])
                    ->setArgument('$url', '%env(DATABASE_URL)%');
            }
            if (!$container->hasDefinition(CallbackTextHandler::class)) {
                $container->register(CallbackTextHandler::class, CallbackTextHandler::class)
                    ->setPublic(true)
                    ->setArguments(
                        [
                            new Reference(EntityManager::class),
                            new Reference(BotApi::class),
                            new Reference(CallbackHandlerLoader::class),
                        ]
                    );
            }
        });
    }
}
