<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\ExpiredPassport;

class ExpiredPassportsCommand extends Command
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:expired-passports')

            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        try{
            $this->storeData();
            $this->output->writeln('done');
        } catch (Exception  $e){
            $this->output->writeln($e->getMessage());
        }
    }

    private function testStore()
    {
        $batchSize = 20;
        for ($i = 1; $i <= 100000; ++$i) {
            $this->insertData(rand(1, 100), rand(1, 34567));
        }
        $this->getEm()->flush(); //Persist objects that did not make up an entire batch
        $this->getEm()->clear();
    }
    private function getPathToFile()
    {
        $pathToFile = $this->getContainer()->getParameter('web_files_dir') . 'list_of_expired_passports.csv';
        if(!file_exists($pathToFile)) {
            throw new Exception('Файл не обнаружен');
        }
        return $pathToFile;

    }
    private function storeData()
    {
        $this->truncateTable();
        $a = 0;
        $batchSize = 20;
        if (($handle = fopen($this->getPathToFile(), 'r')) !== false)
        {
            while(($data = fgetcsv($handle)) !== false) {
                if ($a !== 0) {
                    echo $a . "\n";
                    $this->insertData($data[0], $data[1]);
                }
                $a++;
                unset($data);
            }
            fclose($handle);
        }
    }

    private function insertData($series, $number)
    {
        $expiredPassport = new ExpiredPassport();
        $expiredPassport->setSeries((int) $series);
        $expiredPassport->setNumber((int) $number);
        $this->getEm()->persist($expiredPassport);
        $this->getEm()->flush();
        $this->getEm()->clear();
    }

    private function truncateTable()
    {
        $connection = $this->getEm()->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeUpdate($platform->getTruncateTableSQL('expired_passport', true /* whether to cascade */));
    }







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
}