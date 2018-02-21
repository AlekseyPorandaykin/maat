<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use Exception;

class ImportExpiredPassportsCommand extends BaseCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('app:import-expired-passport')
            ->setDescription('Скопировать файл с данными')
            ->setHelp('Скопировать данные о просроченных паспортах')
        ;
    }

    protected function start()
    {
        $this->copyFile();
    }

    private function copyFile()
    {
        $file = $this->getContainer()->getParameter('url_list_of_expired_passports');
        $newfile = $this->getContainer()->getParameter('web_files_dir') . 'list_of_expired_passports.csv';

        if (!copy($file, $newfile)) {
            throw new Exception("не удалось скопировать $file...");
        }
    }
}