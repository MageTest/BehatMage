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
use Mage;
use Mage_Customer_Model_Customer;
use Symfony\Component\Process\Exception\InvalidArgumentException;

/**
 * User fixtures functionality provider
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Address implements FixtureInterface
{
    private $modelFactory = null;
    private $model;
    private $customer = null;
    private $customerId = null;

    /**
     * @param $modelFactory \Closure optional
     */
    public function __construct($modelFactory = null)
    {
        $this->modelFactory = $modelFactory ?: $this->defaultModelFactory();
    }

    /**
     * Create a fixture in Magento DB using the given attributes map and return its ID
     *
     * @param $attributes array attributes map using 'label' => 'value' format
     * @return $this
     */
    public function create(array $attributes)
    {
        $modelFactory = $this->modelFactory;
        $this->model = $modelFactory();

        $this->model->setData($attributes);

        $this->model->setCustomerId($this->getCustomerId());

        if ($this->validate($this->model)) {
            $this->model->save();
        }

        return $this;
    }

    /**
     * Delete the requested fixture from Magento DB
     *
     * @param $identifier int object identifier
     * @return null
     */
    public function delete()
    {
        if ($this->model) {
            $this->model->delete();
        }
    }

    /**
     * retrieve default product model factory
     *
     * @return \Closure
     */
    public function defaultModelFactory()
    {
        return function () {
            return Mage::getModel('customer/address');
        };
    }

    /**
     * Validates the model and ensures it is ready to be saved. Throws exception if validation fails
     *
     * @param Mage_Core_Model_Abstract $model
     * @return boolean
     * @throws \Exception
     */
    public function validate($model)
    {
        if (!$this->getCustomer() && !$this->getCustomerId()) {
            throw new \Exception('There is no customer assigned to the address');
        }

        if (!Mage::getModel('customer/customer')->validateAddress($model->getData(), false)) {
            throw new \Exception('Provided address is not valid, please check if all fields are filled correctly');
        }

        return true;
    }

    /**
     * Set customer model to be assigned with the created address(es)
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return Address
     */
    public function setCustomer(Mage_Customer_Model_Customer $customer)
    {
        $this->customer = $customer;
        $this->customerId = $customer->getId();

        return $this;
    }

    /**
     * Get customer model
     *
     * @return Mage_Customer_Model_Customer|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set the id of the customer to assign to created address(es)
     *
     * @param int $id
     * @return Address
     */
    public function setCustomerId($id)
    {
        $this->customerId = $id;

        if ($this->customer instanceof Mage_Customer_Model_Customer && $this->customer->getId() != $id) {
            $this->customer = null;
        }

        return $this;
    }

    /**
     * Get id of the assigned customer
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        if (!$this->customerId && $this->customer instanceof Mage_Customer_Model_Customer) {
            return $this->customer->getId();
        }
        return $this->customerId;
    }
}
