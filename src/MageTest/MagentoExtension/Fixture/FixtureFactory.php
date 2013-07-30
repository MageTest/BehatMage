<?php
/**
 * BehatMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Fixture;

/**
 * AfterScenarioListener
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
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
            case 'customer':
                $fixture = new Customer();
                break;
            case 'address':
                $fixture = new Address();
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
        $this->registry = array();
    }

    public function getRegistry()
    {
        return $this->registry;
    }
}
