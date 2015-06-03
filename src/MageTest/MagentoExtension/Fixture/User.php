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
class User implements FixtureInterface
{
    private $modelFactory = null;
    private $model;

    /**
     * @param $productModelFactory \Closure optional
     */
    public function __construct($userModelFactory = null)
    {
        $this->modelFactory = $userModelFactory ?: $this->defaultModelFactory();
    }

    /**
     * Create a fixture in Magento DB using the given attributes map and return its ID
     *
     * @param $attributes array attributes map using 'label' => 'value' format
     *
     * @return $this
     */
    public function create(array $attributes)
    {
        $modelFactory = $this->modelFactory;
        $this->model = $modelFactory();

        $this->model->setData($attributes);
        if ($this->model->userExists()) {
            throw new \Exception('Username provided to user fixture should not be existing');
        }
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);

        $this->model->save();

        if (array_key_exists('role_id', $attributes)) {
            $this->model->setRoleIds(array($attributes['role_id']))
                ->setRoleUserId($this->model->getUserId())
                ->saveRelations();
        }

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);

        return $this;
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
     * retrieve default product model factory
     *
     * @return \Closure
     */
    public function defaultModelFactory()
    {
        return function () {
            return \Mage::getModel('admin/user');
        };
    }
}
