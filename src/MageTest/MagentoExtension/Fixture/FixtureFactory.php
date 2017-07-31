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

use MageTest\MagentoExtension\Fixture\FixtureInterface;

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
    private $fixtures = array();
    private $registry;

    public function __construct()
    {
        $this->registry = array();
    }

    /**
     * @param string $name the identification key for the fixture
     * @param FixtureInterface $fixture
     */
    public function addFixture($name, FixtureInterface $fixture)
    {
        $this->fixtures[$name] = $fixture;
    }

    /**
     * create the requested fixture generator
     *
     * @param string $identifier name of fixture generator
     * @param array $data
     *
     * @throws \InvalidArgumentException
     */
    public function create($identifier, $data = array())
    {
        if (0 === count($this->fixtures)) {
            throw new \RuntimeException('You need to add a fixture to build');
        }
        if (!array_key_exists($identifier, $this->fixtures)) {
            throw new \InvalidArgumentException(sprintf('Unable to create a fixture of type %s', $identifier));
        }

        $fixture = clone $this->fixtures[$identifier];
        try {
            $fixture->create($data);
            array_push($this->registry, $fixture);
        } catch (\Exception $e) {
            throw new \RuntimeException('Unable to create the Magento fixture ' . $e->getMessage());
        }
    }

    public function clean()
    {
        foreach ($this->getRegistry() as $fixture) {
            $fixture->delete();
        }
        $this->registry = array();
    }

    public function getRegistry()
    {
        return $this->registry;
    }

}
