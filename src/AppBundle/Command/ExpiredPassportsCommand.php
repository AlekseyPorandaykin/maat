<?php
namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use AppBundle\Entity\ExpiredPassport;
use Exception;

class ExpiredPassportsCommand extends BaseCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('app:expired-passports')
            ->setDescription('Перезаписать данные о истекших паспортах')
            ->setHelp('Очистим таблицу expired_passport и заполним её новыми даннми')
        ;
    }

    protected function start()
    {
        $this->output->writeln('yee'); exit();
        $this->storeData();
        $this->output->writeln('done');
    }

    /**
     * @return string
     */
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
        $batchSize = 1000;
        if (($handle = fopen($this->getPathToFile(), 'r')) !== false)
        {
            while(($data = fgetcsv($handle)) !== false) {
                if ($a !== 0) {
                    $this->insertData($data[0], $data[1]);
                }
                if ($a % $batchSize === 0){
                    echo $a . "\n";
                }
                $a++;
                unset($data);
            }
            fclose($handle);
        }
    }

    /**
     * @param $series
     * @param $number
     */
    private function insertData($series, $number)
    {
        $expiredPassport = new ExpiredPassport();
        $expiredPassport->setSeries((int) $series);
        $expiredPassport->setNumber((int) $number);
        $this->getEm()->persist($expiredPassport);
        $this->getEm()->flush();
        $this->getEm()->clear();
    }

    /**
     * Очищаем таблицу
     */
    private function truncateTable()
    {
        $connection = $this->getEm()->getConnection();
        $platform   = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('expired_passport', true /* whether to cascade */));
    }








}