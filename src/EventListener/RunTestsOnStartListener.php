<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;

class RunTestsOnStartListener
{
    private static bool $alreadyRun = false;

    public function onKernelRequest(RequestEvent $event): void
    {
        if (self::$alreadyRun) {
            return;
        }

        self::$alreadyRun = true;

        $rootDir = dirname(__DIR__, 2);
        $phpunit = $rootDir . '/vendor/bin/phpunit';
        $config  = $rootDir . '/phpunit.dist.xml';

        $stdout = fopen('php://stdout', 'w');

        fwrite($stdout, "\n🧪 Lancement des tests...\n\n");

        $command = sprintf(
            'php %s --configuration %s --testdox 2>&1',
            escapeshellarg($phpunit),
            escapeshellarg($config)
        );

        $output = shell_exec($command);

        fwrite($stdout, $output . "\n");

        fclose($stdout);
    }
}