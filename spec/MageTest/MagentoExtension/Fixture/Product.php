<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\Specification;

class Product implements Specification
{
    function described_with()
    {
        \Mage::app();
        $this->productModel = \Mockery::mock(new \Mage_Catalog_Model_Product());

        $productModel = $this->productModel;

        $factory = function () use ($productModel) { return $productModel; };
        $this->product->isAnInstanceOf('MageTest\MagentoExtension\Fixture\Product', array($factory));
    }

    function it_should_create_a_product_given_all_required_attributes()
    {
        $this->productModel->shouldReceive('setData')->once()->andReturn($this->productModel)->ordered();
        $this->productModel->shouldReceive('save')->once()->andReturn(true)->ordered();

        $this->product->create(
            array(
                'sku' => 'sku1'
            )
        );
    }
}
