<?php

namespace MageTest\MagentoExtension\Fixture;

class FixtureFactory
{
    private $registry;
    
    public function __construct()
    {
        $this->registry = array();
    }

    /**
     * create the requested fixture generator
     *
     * @param $identifier string name of fixture generator
     *
     * @return \MageTest\MagentoExtension\Fixture
     */
    public function create($identifier)
    {
        switch ($identifier) {
            case 'product':
                $fixture = new Product();
                break;
            case 'user':
                $fixture = new User();
                break;
            default: throw new \InvalidArgumentException();
        }

        array_push($this->registry, $fixture);

        return $fixture;
    }

    public function clean()
    {
        foreach ($this->getRegistry() as $fixtures) {
            $fixture->delete();
        }
    }

    public function getRegistry()
    {
        return $this->registry;
    }
}
