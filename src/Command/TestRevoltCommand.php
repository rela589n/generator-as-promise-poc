<?php

namespace App\Command;

use Revolt\EventLoop;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:test-revolt',
    description: 'Test revolt event loop',
)]
class TestRevoltCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $suspension = EventLoop::getSuspension();

        $repeatId = EventLoop::repeat(1, function (): void {
            print '++ Executing callback created by EventLoop::repeat()'.PHP_EOL;
        });

        EventLoop::defer(function () {
            print '++ Executing callback created by EventLoop::defer()'.PHP_EOL;
        });

        EventLoop::delay(0, function () {
            print '++ Executing callback created by EventLoop::delay(0)'.PHP_EOL;
        });

        EventLoop::defer(function () {
            print '++ Executing callback created by EventLoop::defer() - 2'.PHP_EOL;
        });

        EventLoop::repeat(5, function () {
            print '++ Executing callback created by EventLoop::repeat(5)'.PHP_EOL;
        });

        EventLoop::delay(5, function () use ($suspension, $repeatId): void {
            print '++ Executing callback created by EventLoop::delay()'.PHP_EOL;

            EventLoop::cancel($repeatId);
            $suspension->resume('resume value');

            print '++ Suspension::resume() is async!'.PHP_EOL;
        });

        print '++ Suspending to event loop...'.PHP_EOL;

        $suspend = $suspension->suspend();

        print '++ Script end: '.$suspend.PHP_EOL;
        return 0;
    }
}
/*
++ Suspending to event loop...
++ Executing callback created by EventLoop::defer()
++ Executing callback created by EventLoop::defer() - 2
++ Executing callback created by EventLoop::delay(0)
++ Executing callback created by EventLoop::repeat()
++ Executing callback created by EventLoop::repeat()
++ Executing callback created by EventLoop::repeat()
++ Executing callback created by EventLoop::repeat()
++ Executing callback created by EventLoop::repeat(5)
++ Executing callback created by EventLoop::delay()
++ Suspension::resume() is async!
++ Script end: resume value
 */
