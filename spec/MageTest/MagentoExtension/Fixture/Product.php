<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\Specification;

class Product implements Specification
{
    function it_should_create_a_product_given_all_required_attributes()
    {
        $this->product->create(
            array(
                'sku' => 'sku1'
            )
        );
        $productData = $this->_retrieveProductData('sku1');
        if ('sku1' !== $productData['sku']) {
            throw new \Exception("sku retrieved is not correct <sku1> !== <{$productData['sku']}>");
        }
    }

    protected function _retrieveProductData($sku)
    {
        $product = \Mage::getModel('catalog/product')->loadByAttribute('sku', $sku);
        if (!$product || !$product->getId()) {
            throw new \Exception("cannot find product by given sku <$sku>");
        }
        return $product->getData();
    }
}
