<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\ExpiredPassport;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use AppBundle\DataFixtures\BaseFixtures;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ExpiredPassportsFixtures extends BaseFixtures
{
    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var AppBundle\Repository\ExpiredPassport
     */
    private $expiredPassportRepository;


    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        try{
            $this->manager = $manager;
            $this->testStore();
        } catch (Exception  $e){
            echo $e->getMessage();
        }
    }

    /**
     * @return string
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    private function getPathToFile()
    {
        $pathToFile = $this->getParameter('web_files_dir') . 'list_of_expired_passports.csv';
        if(!file_exists($pathToFile)) {
            throw new Exception('Файл не обнаружен');
        }
        return $pathToFile;

    }

    /**
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    private function storeData()
    {
        $a = 0;
        $batchSize = 20;
        if (($handle = fopen($this->getPathToFile(), 'r')) !== false)
        {
            while(($data = fgetcsv($handle)) !== false && $a < 1000) {
                if ($a !== 0) {
                    echo $a . ') ' . $data[0] . ' ' . $data[1] . "\n";
                    $this->insertData(rand(1, 234), rand(1, 10000));
                }
                if (($a % $batchSize) == 0) {
                    $this->manager->flush();
                    $this->manager->clear();
                }
                $a++;
                unset($data);
            }

            $this->manager->flush();
            $this->manager->clear();
            fclose($handle);
        }
    }


    private function insertData($series, $number)
    {
        $expiredPassport = new ExpiredPassport();
        $expiredPassport->setSeries((int) $series);
        $expiredPassport->setNumber((int) $number);
        $this->manager->persist($expiredPassport);
        $this->manager->flush();
        $this->manager->clear();
    }

    private function testStore()
    {
        $batchSize = 20;
        for ($i = 1; $i <= 10000; ++$i) {
            $expiredPassport = new ExpiredPassport();
            $expiredPassport->setSeries(rand(1, 100));
            $expiredPassport->setNumber(rand(1, 34567));
            $this->manager->persist($expiredPassport);
            $this->manager->flush($expiredPassport);
            $this->manager->clear(); // Detaches all objects from Doctrine!
        }
        $this->manager->flush(); //Persist objects that did not make up an entire batch
        $this->manager->clear();
    }
}