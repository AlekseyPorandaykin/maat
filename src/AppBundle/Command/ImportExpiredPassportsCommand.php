<?php

namespace AppBundle\Command;

use AppBundle\Command\BaseCommand;
use AppBundle\Exception\CommandException;
use \wapmorgan\UnifiedArchive\UnifiedArchive;

class ImportExpiredPassportsCommand extends BaseCommand
{
    /**
     * @var
     */
    private $newFile;
    /**
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        ini_set('memory_limit', '10792M');
        $this
            ->setName('app:import-expired-passport')
            ->setDescription('Скопировать файл с данными')
            ->setHelp('Скопировать данные о просроченных паспортах')
        ;
    }

    /**
     * @throws CommandException
     */
    protected function start()
    {
        $this->copyFile();
        $this->unarchiveFile();
    }

    /**
     * @throws CommandException
     */
    private function copyFile()
    {
        $file = $this->getContainer()->getParameter('url_list_of_expired_passports');
        $this->newFile = $this->getContainer()->getParameter('web_files_dir') . basename($file);

//        if (!copy($file, $this->newFile)) {
//            throw new CommandException("не удалось скопировать {$file}...");
//        }
    }

    private function unarchiveFile()
    {
        $archive = UnifiedArchive::open($this->newFile);
        $archive->extractNode($this->getContainer()->getParameter('web_files_dir'));
    }


}