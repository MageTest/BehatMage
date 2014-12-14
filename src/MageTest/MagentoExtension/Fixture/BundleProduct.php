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
use MageTest\MagentoExtension\Helper\Website;

/**
 * Product fixtures functionality provider
 *
 * Background usage example:
 *
 * Background:
 * Given the following products exist:
 * | sku         | name              | price | tax_class_id  | url_key        | qty   |is_in_stock |
 * | 678.678.679 | Product Name      | 5.00  | 11            | simple-product | 100   |1           |
 * And the following bundle products exist:
 * |sku_type    |sku           | name               |url_key              |price_type|was_price_presented|is_available_for_backorder|
 * |1           |BUNDLE-FIXTURE| Bundle Fixture     |bundle-fixture       |0         |1                  |0                         |
 * And the bundle with sku "BUNDLE-FIXTURE" have the following option data:
 * |required  |position  | type  | title    |default_title|
 * |1         |0         |select |FooOption |FooOption    |
 * And the following products are added to the bundle with "BUNDLE-FIXTURE" sku:
 * | sku         | selection_qty  | selection_can_change_qty  |position|is_default|selection_price_type|selection_price_value|
 * | 678.678.679 |  10            | 1                         |0       |0         |0                   |0.00                 |
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
final class BundleProduct implements FixtureInterface
{
    const REGISTRY_FIXTURE_PREFIX = 'bundle_fixture_';

    private $bundle;
    private $websiteNames;

    private static $registeredBundles = array();

    /**
     * @var array
     */
    protected $serviceContainer;

    /**
     * @param array $serviceContainer
     */
    public function __construct(array $serviceContainer = null)
    {
        $this->serviceContainer['productModel']  = isset($serviceContainer['productModel'])  ? $serviceContainer['productModel']  : $this->defaultModelFactory();
        $this->serviceContainer['websiteHelper'] = isset($serviceContainer['websiteHelper']) ? $serviceContainer['websiteHelper'] : $this->defaultWebsiteHelperFactory();
    }

    /**
     * Create a fixture in Magento DB using the given attributes map and return its ID
     *
     * @param $attributes array attributes map using 'label' => 'value' format
     *
     * @throws \RuntimeException
     * @return BundleProduct
     */
    public function create(array $attributes)
    {
        $this->bundle = $this->serviceContainer['productModel']();

        if (!isset($attributes['sku']))
            throw new \RuntimeException('Bundle product fixture must have a sku');

        if (isset($attributes['website_names'])) {
            $this->websiteNames = explode(',', $attributes['website_names']);
            unset($attributes['website_names']);
        }

        $id = $this->bundle->getIdBySku($attributes['sku']);

        if ($id > 0) {
            $this->bundle = $this->bundle->load($id);
            $this->registerBundle($attributes);
            return $this;
        }

        if (!$this->validateBundleTypeId($attributes))
            $this->bundle->setTypeId($this->getBundleTypeId());

        $attributes = $this->sanitizeAttributes($attributes);

        $this->validateAttributes($attributes);
        \Mage::app()->setCurrentStore(\Mage_Core_Model_App::ADMIN_STORE_ID);
        $this->bundle->setData($this->mergeAttributes($attributes));
        $this->registerBundle($attributes);
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
        $this->mageUnregisterBundle(self::REGISTRY_FIXTURE_PREFIX . $this->bundle->getData('sku'));
        unset(self::$registeredBundles[static::REGISTRY_FIXTURE_PREFIX . $this->bundle->getData('sku')]);

        $this->bundle->delete();
    }

    /**
     * retrieve default bundle product model factory
     *
     * @return \Closure
     */
    public function defaultModelFactory()
    {
        return function () {
            return \Mage::getModel('catalog/product');
        };
    }

    /**
     * @param $sku
     * @return Mage_Catalog_Model_Product|null
     */
    public static function getRegisteredBundle($sku)
    {
        return \Mage::registry(self::REGISTRY_FIXTURE_PREFIX . $sku);
    }

    /**
     *
     * Gets registered bundle products
     * Can be used in context classes to delete fixture when needed
     *
     * @return array
     */
    public static function getRegisteredBundles()
    {
        return self::$registeredBundles;
    }

    /**
     * Resets registered bundled
     */
    public static function resetRegisteredBundles()
    {
        self::$registeredBundles = array();
    }

    /**
     * Returns true if
     *
     * @param $identifier
     * @param array $selections
     * @return bool
     */
    public static function isSelectionProduct($identifier, array $selections)
    {
        foreach ($selections as $selection) {
            if (in_array($identifier, $selection))
                return true;
        }
        return false;
    }


    /**
     * @param array $attributes
     * @return array
     */
    private function sanitizeAttributes(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (!$this->attributeExists($key, $attributes) && empty($value)) {
                unset($attributes[$key]);
            }
        }

        return $attributes;
    }

    /**
     * @param $attributes
     * @throws \RuntimeException
     */
    private function validateAttributes($attributes)
    {
        foreach ($attributes as $attributeKey => $attribute) {
            if (!$this->attributeExists($attributeKey, $attributes)) {
                throw new \RuntimeException("$attributeKey is not yet defined as an attribute of Product");
            }
        }
    }

    /**
     * @param $attributeCode
     * @param $attributes
     * @return bool
     */
    private function attributeExists($attributeCode, $attributes)
    {
        return in_array($attributeCode, array_keys($this->getDefaultAttributes($attributes)));
    }

    /**
     * @param $attributes
     * @return array
     */
    private function getDefaultAttributes($attributes)
    {
        return array_merge($attributes, array(
            'sku_type'              => 1,
            'sku'                   => '',
            'shipment_type'         => 1,
            'price_type'            => 0,
            'created_at'            => strtotime('now'),
            'attribute_set_id'      => $this->retrieveDefaultAttributeSetId(),
            'name'                  => 'product name',
            'weight_type'           => 0,
            'visibility'            => \Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'status'                => \Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'description'           => 'Bundle product description',
            'short_description'     => 'Bundle product short description',
            'type_id'               => \Mage_Catalog_Model_Product_Type::TYPE_BUNDLE,
            'stock_data'            => $this->getDefaultStockData(),
            'website_ids'           => $this->serviceContainer['websiteHelper']()->getWebsiteIds(),
        ));
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

    /**
     * @param $attributes
     * @return array
     */
    private function mergeAttributes(array $attributes)
    {
        return array_merge($this->getDefaultAttributes($attributes), $attributes);
    }

    /**
     * @return array
     */
    private function getDefaultStockData()
    {
        return array(
            'use_config_manage_stock'          => 1,
            'use_config_enable_qty_increments' => 1,
            'use_config_qty_increments'        => 1,
            'is_in_stock'                      => 1
        );
    }

    /**
     * @param array $attributes
     * @return bool
     */
    private function validateBundleTypeId(array $attributes)
    {
        if (!empty($attributes['type_id']) && $attributes['type_id'] != $this->getBundleTypeId())
            return false;

        return true;
    }

    /**
     * @return string
     */
    private function getBundleTypeId()
    {
        return \Mage_Catalog_Model_Product_Type::TYPE_BUNDLE;
    }

    /**
     * @return int
     */
    private function retrieveDefaultAttributeSetId()
    {
        return $this->bundle->getResource()
            ->getEntityType()
            ->getDefaultAttributeSetId();
    }

    /**
     * @param string $registryKey
     */
    private function mageRegisterBundle($registryKey)
    {
        \Mage::register($registryKey, $this->bundle, true);
    }

    /**
     * @param string $registryKey
     */
    private function mageUnregisterBundle($registryKey)
    {
        \Mage::unregister($registryKey);
    }

    /**
     * @param array $attributes
     */
    private function registerBundle(array $attributes)
    {
        $this->mageRegisterBundle('product');
        $this->mageRegisterBundle(static::REGISTRY_FIXTURE_PREFIX . $attributes['sku']);
        self::$registeredBundles[static::REGISTRY_FIXTURE_PREFIX . $attributes['sku']] = $this->bundle;
    }
}