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
use MageTest\MagentoExtension\Fixture;

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
    private $_modelFactory = null;

    private $_customer = null;
    private $_customerId = null;

    /**
     * @param $modelFactory \Closure optional
     */
    public function __construct($modelFactory = null)
    {
        $this->_modelFactory = $modelFactory ?: $this->defaultModelFactory();
    }

    /**
     * Create a fixture in Magento DB using the given attributes map and return its ID
     *
     * @param $attributes array attributes map using 'label' => 'value' format
     *
     * @return int
     */
    public function create(array $attributes)
    {
        $modelFactory = $this->_modelFactory;
        $model = $modelFactory();

        $model->setData($attributes);

        $model->setCustomerId($this->getCustomerId());

        if ($this->validate($model)) {

            $model->save();

            return $model->getId();
        }

        return null;
    }

    /**
     * Delete the requested fixture from Magento DB
     *
     * @param $identifier int object identifier
     *
     * @return null
     */
    public function delete($identifier)
    {
        $modelFactory = $this->_modelFactory;
        $model = $modelFactory();

        $model->load($identifier);
        $model->delete();
    }

    /**
     * retrieve default product model factory
     *
     * @return \Closure
     */
    public function defaultModelFactory()
    {
        return function () {
            return \Mage::getModel('customer/address');
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

        if (!\Mage::getModel('customer/customer')->validateAddress($model->getData(), false)) {
            throw new \Exception('Provided address is not valid, please check if all fields are filled correctly');
        }

        return true;
    }

    /**
     * Set customer model to be assigned with the created address(es)
     *
     * @param \Mage_Customer_Model_Customer $customer
     * @return \MageTest\MagentoExtension\Fixture\Address
     */
    public function setCustomer(\Mage_Customer_Model_Customer $customer)
    {
        $this->_customer = $customer;
        $this->_customerId = $customer->getId();

        return $this;
    }

    /**
     * Get customer model
     *
     * @return \Mage_Customer_Model_Customer|null
     */
    public function getCustomer()
    {
        return $this->_customer;
    }

    /**
     * Set the id of the customer to assign to created address(es)
     *
     * @param int $id
     * @return \MageTest\MagentoExtension\Fixture\Address
     */
    public function setCustomerId($id)
    {
        $this->_customerId = $id;

        if ($this->_customer instanceof \Mage_Customer_Model_Customer && $this->_customer->getId() != $id) {
            $this->_customer = null;
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
        if (!$this->_customerId && $this->_customer instanceof \Mage_Customer_Model_Customer) {
            return $this->_customer->getId();
        }
        return $this->_customerId;
    }
}