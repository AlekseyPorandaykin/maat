<?php

namespace AppBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\ExpiredPassport;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

abstract class BaseFixtures extends Fixture implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param $name
     * @return mixed|null
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function getParameter($name)
    {
        if (null !== $this->container && $this->container->hasParameter($name)){
            return $this->container->getParameter($name);
        }
        return null;
    }
}