<?php

namespace App\Command;

use Revolt\EventLoop;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:test-revolt-non-blocking-io',
    description: 'Add a short description for your command',
)]
class TestRevoltNonBlockingIoCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        if (\stream_set_blocking(STDIN, false) !== true) {
            \fwrite(STDERR, "Unable to set STDIN to non-blocking" . PHP_EOL);
            exit(1);
        }

        print "Write something and hit enter" . PHP_EOL;

        $suspension = EventLoop::getSuspension();

        $readableId = EventLoop::onReadable(STDIN, function ($id, $stream) use ($suspension): void {
            EventLoop::cancel($id);

            $chunk = \fread($stream, 8192);

            print "Read " . \strlen($chunk) . " bytes" . PHP_EOL;

            $suspension->resume(null);
        });

        $timeoutId = EventLoop::delay(5, function () use ($readableId, $suspension) {
            EventLoop::cancel($readableId);

            print "Timeout reached" . PHP_EOL;

            $suspension->resume(null);
        });

        $suspension->suspend();

        EventLoop::cancel($readableId);
        EventLoop::cancel($timeoutId);

        return Command::SUCCESS;
    }
}
