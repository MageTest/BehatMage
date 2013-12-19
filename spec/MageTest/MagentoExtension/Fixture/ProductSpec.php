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
 * @subpackage Fixure
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\MagentoExtension\Fixture;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


/**
 * ProductSpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class ProductSpec extends ObjectBehavior
{
    private $productModel = null;

    function let()
    {
        \Mage::app();

        // Class is final, we can only use a partial mock
        $this->productModel = $productModel = \Mockery::mock(new \Mage_Catalog_Model_Product);

        $factory = function () use ($productModel) { return $productModel; };
        $this->beConstructedWith($factory);

        $entityType = \Mockery::mock(new \Mage_Eav_Model_Entity_Type);
        $entityType->shouldReceive('getDefaultAttributeSetId')->andReturn(7);

        $productResourceModel = \Mockery::mock('Mage_Catalog_Model_Resource_Product');
        $productResourceModel->shouldReceive('getEntityType')->andReturn($entityType);

        $this->productModel->shouldReceive('getResource')->andReturn($productResourceModel)->ordered();
        $this->productModel->shouldReceive('getAttributes')->andReturn(array());
    }

    function it_should_create_a_product_given_all_required_attributes()
    {
        $data = array(
            'attribute_set_id' => 7,
            'name' => 'product name',
            'weight' => 2,
            'visibility'=> \Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'status' => \Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'price' => 100,
            'description' => 'Product description',
            'short_description' => 'Product short description',
            'tax_class_id' => 1,
            'type_id' => \Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'stock_data' => array( 'is_in_stock' => 1, 'qty' => 99999 ),
            'sku' => 'sdf'
        );

        $this->productModel->shouldReceive('setData')
            ->with(\Mockery::any())->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();
        $this->productModel->shouldReceive('getId')->andReturn(1);
        $this->productModel->shouldReceive('getIdBySku')->andReturn(false);

        $this->create($data);
    }

    function it_should_populate_missing_attributes_when_creating_product()
    {
        $data = array(
            'sku' => 'sku'.time()
        );

        $expected = array(
            'attribute_set_id' => 7,
            'name' => 'product name',
            'weight' => 2,
            'visibility'=> \Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'status' => \Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'price' => 100,
            'description' => 'Product description',
            'short_description' => 'Product short description',
            'tax_class_id' => 1,
            'type_id' => \Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'stock_data' => array( 'is_in_stock' => 1, 'qty' => 99999 ),
            'sku' => $data['sku']
        );

        $this->productModel->shouldReceive('setData')
            ->with(\Mockery::any())->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();
        $this->productModel->shouldReceive('getId')->andReturn(1);
        $this->productModel->shouldReceive('getIdBySku')->andReturn(false);

        $this->create($data);
    }

    function it_should_load_product_first_if_creating_with_existing_sku()
    {
        $data = array(
            'sku' => 'sku1'
        );

      $expectedData = array(
            'attribute_set_id' => 7,
            'name' => 'product name',
            'weight' => 2,
            'visibility'=> \Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'status' => \Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'price' => 100,
            'description' => 'Product description',
            'short_description' => 'Product short description',
            'tax_class_id' => 1,
            'loaded_attr' => 27,
            'type_id' => \Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'stock_data' => array( 'is_in_stock' => 1, 'qty' => 99999 ),
            'sku' => $data['sku']
        );

        $this->productModel->shouldReceive('getIdBySku')->with('sku1')->once()->andReturn(123);

        $this->productModel->shouldReceive('getData')
            ->once()->andReturn(array('loaded_attr' => 27));
        $this->productModel->shouldReceive('setData')
            ->with(\Mockery::any())->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();
        $this->productModel->shouldReceive('getId')->andReturn(1);
        $this->productModel->shouldReceive('load')->with(123)->once()->andReturn(1);

        $this->create($data);
    }

    function it_should_return_the_created_objects_id()
    {
        $data = array(
            'sku' => 'sku'.time()
        );

        $this->productModel->shouldReceive('setData')->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();
        $this->productModel->shouldReceive('getId')->once()->andReturn(554)->ordered();
        $this->productModel->shouldReceive('getIdBySku')->andReturn(false);

        $this->create($data)->shouldBe(554);
    }

    function it_should_load_object_and_delete_it_when_delete_is_requested()
    {
        $this->productModel->shouldReceive('load')->with(554)->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('delete')->once()->andReturn(null)->ordered();

        $this->delete(554);
    }
}
