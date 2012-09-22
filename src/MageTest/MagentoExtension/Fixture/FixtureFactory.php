<?php

namespace MageTest\MagentoExtension\Fixture;

class FixtureFactory
{
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
            case 'product': return new Product();
            case 'user': return new User();
            default: throw new \InvalidArgumentException();
        }
    }
}
