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
use MageTest\MagentoExtension\Helper\Website;

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
    private $model;
    /**
     * @var \MageTest\MagentoExtension\Helper\Website
     */
    protected $serviceContainer;

    /**
     * @param array $serviceContainer
     */
    public function __construct(array $serviceContainer = null)
    {
        $this->serviceContainer['customerModel'] = isset($serviceContainer['customerModel']) ? $serviceContainer['customerModel'] : $this->defaultModelFactory();
        $this->serviceContainer['websiteHelper'] = isset($serviceContainer['websiteHelper']) ? $serviceContainer['websiteHelper'] : $this->defaultWebsiteHelperFactory();
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
        $this->model = $this->serviceContainer['customerModel']();

        if (!empty($attributes['email'])) {

            if (!empty($attributes['website_id'])) {
                $this->model->setWebsiteId($attributes['website_id']);
            } else {
                $this->model->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
            }

            $this->model->loadByEmail($attributes['email']);
        }

        $this->model->addData($attributes);

        if ($this->validate($this->model)) {

            $this->model->save();

            return $this->model->getId();
        }

        return $this->model;
    }

    /**
     * Delete the requested fixture from Magento DB
     *
     * @return null
     */
    public function delete()
    {
        if ($this->model) {
            $this->model->delete();
        }
    }

    /**
        $model = $this->serviceContainer['customerModel']();
        $model->load($identifier);
        $model->delete();
        };
    }

    /**
     * Validates the model and ensures it is ready to be saved. Throws exception if validation fails
     *
     * @param \Mage_Core_Model_Abstract $model
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
        return $this->serviceContainer['websiteHelper']()->getWebsiteIds();
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
     * Retrieve default Website helper used in the class
     *
     * @return \Closure
     */
    private function defaultWebsiteHelperFactory()
    {
        return function() {
            return new Website();
        };
    }
}
