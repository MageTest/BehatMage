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
            'sku' => 'sku1'
        );
        $this->productModel->shouldReceive('setData')
            ->with($data)->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();

        $this->product->create($data);
    }

    function it_should_populate_missing_attributes_when_creating_product()
    {
        $data = array(
            'attribute_set_id' => 9,
            'sku' => 'sadsd',
            'name' => 'ad',  
            'weight' => 2,
            'visibility'=>4,
            'status'=>1,
            'price'=>1,
            'description'=>'asdsad',
            'short_description'=>'sdf',
            'qty'=>3, 
            'tax_class_id'=>1,
            'type_id'=>\Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'stock_data' =>array( 'is_in_stock' => 1, 'qty' => 99999 )
        );

        $this->product->create($data);
    }
}    