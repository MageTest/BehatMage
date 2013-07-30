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
class Customer implements FixtureInterface
{
    private $_modelFactory = null;

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

        if (!empty($attributes['email'])) {

            if (!empty($attributes['website_id'])) {
                $model->setWebsiteId($attributes['website_id']);
            } else {
                $model->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
            }

            $model->loadByEmail($attributes['email']);
        }

        $model->addData($attributes);

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
            return \Mage::getModel('customer/customer');
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
        if (($errors = $model->validate()) !== true) {
            throw new \Exception("Customer data is incomplete or invalid:\n- " . implode("\n- ", $errors));
        }
        return true;
    }

    protected function getWebsiteIds()
    {
        return Mage::app()->getWebsites();
    }
}