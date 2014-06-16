<?php
namespace Main\AdminBundle\Command;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WebsocketCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('websocket:start')
            ->setDescription('Start ratchet server');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $chat = $this->getContainer()->get('widget');
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new $chat
                )
            ),
            8080
        );

        $server->run();
    }
}