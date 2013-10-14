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
 * Product fixtures functionality provider
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Category implements FixtureInterface
{
    const DEFAULT_ROOT_CATEGORY_ID = 1;
    const DEFAULT_ATTRIBUTE_SET_ID = 3;

    private $modelFactory = null;
    private $model;
    private $attributes;
    private $defaultAttributes;

    /**
     * @param $productModelFactory \Closure optional
     */
    public function __construct($modelFactory = null)
    {
        $this->modelFactory = $modelFactory ?: $this->defaultModelFactory();
    }

    /**
     * Create a product fixture using the given attributes map
     *
     * @param $attributes array product attributes map using 'label' => 'value' format
     *
     * @return int
     */
    public function create(array $attributes)
    {
        if (empty($attributes['name'])) {
            throw new \RuntimeException("Cannot generate a category when no 'name' attribute is provided");
        }

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);

        $modelFactory = $this->modelFactory;
        $this->model = $modelFactory();

        $id = $this->getIdByName($attributes['name']);
        if ($id) {
            $this->model->load($id);
        }

        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $this->validateAttributes(array_keys($attributes));

        $this->model->setData($this->mergeAttributes($attributes))->save();
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::DISTRO_STORE_ID);

        return $this->model->getId();
    }

    function mergeAttributes($attributes)
    {
        return array_merge($this->getDefaultAttributes(), $this->model->getData(), $attributes);
    }

    function validateAttributes($attributes)
    {
        foreach ($attributes as $attribute) {
            if (!$this->attributeExists($attribute)) {
                throw new \RuntimeException("$attribute is not yet defined as an attribute of Category");
            }
        }
    }

    function attributeExists($attribute)
    {
        return in_array($attribute, array_keys($this->getDefaultAttributes()));
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
        $modelFactory = $this->modelFactory;
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
            return \Mage::getModel('catalog/category');
        };
    }

    protected function getDefaultAttributes()
    {
        if ($this->defaultAttributes) {
            return $this->defaultAttributes;
        }
        $eavAttributes = $this->model->getAttributes();
        $attributeCodes = array();
        foreach ($eavAttributes as $attributeObject) {
            $attributeCodes[$attributeObject->getAttributeCode()] = "";
        }

        return $this->defaultAttributes = array_merge($attributeCodes, array(
            'is_active' => 1,
            'is_anchor' => 1,
            'include_in_menu' => 1,
            'website_ids' => $this->getWebsiteIds(),
            'parent_id' => self::DEFAULT_ROOT_CATEGORY_ID,
            'path' => self::DEFAULT_ROOT_CATEGORY_ID,
            'created_at' => date('Y-m-d H:i:s'),
            'attribute_set_id' => self::DEFAULT_ATTRIBUTE_SET_ID,
        ));
    }

    protected function getWebsiteIds()
    {
        $ids = array();
        foreach (\Mage::getModel('core/website')->getCollection() as $website) {
            $ids[] = $website->getId();
        }
        return $ids;
    }

    protected function retrieveDefaultAttributeSetId()
    {
        return $this->model->getResource()
            ->getEntityType()
            ->getDefaultAttributeSetId();
    }

    protected function getIdByName($name)
    {
        $category = $this->model->getCollection()->addFieldToFilter('name', $name)->getFirstItem();

        return $category ? $category->getId() : null;
    }
}
