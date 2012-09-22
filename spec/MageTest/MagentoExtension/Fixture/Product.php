<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\Specification;

class Product implements Specification
{
    function described_with()
    {
        \Mage::app();
        $this->productModel = \Mockery::mock(\Mage::getModel('catalog/product'));

        $productModel = $this->productModel;

        $factory = function () use ($productModel) { return $productModel; };
        $this->product->isAnInstanceOf('MageTest\MagentoExtension\Fixture\Product', array($factory));
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
            ->with($data)->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();

        $this->product->create($data);
    }

    function it_should_populate_missing_attributes_when_creating_product()
    {
        $data = array(
            'sku' => 'sku'.time()
        );

        $expected = array(
            'attribute_set_id' => 9,
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
            ->with($expected)->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();

        $this->product->create($data);
    }

    function it_should_throw_an_exception_if_creating_with_existing_sku()
    {
        $data = array(
            'sku' => 'sku1'
        );

        $this->productModel->shouldReceive('getIdBySku')->with('sku1')->once()->andReturn(false);

        $this->product->shouldThrow('\Exception')->during(array($this->product, 'create'), array($data));
    }
}