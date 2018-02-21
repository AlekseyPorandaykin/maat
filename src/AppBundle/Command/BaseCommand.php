<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

abstract class BaseCommand extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    protected function getContainer()
    {
        return $this->getApplication()->getKernel()->getContainer();
    }
    protected function getEm()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        /* Не забиваем логами память */
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        return $em;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        try{
            $this->start();
        } catch (Exception  $e){
            $this->output->writeln($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    protected function start()
    {
        throw new Exception('Не объявлен метод start');
    }
}