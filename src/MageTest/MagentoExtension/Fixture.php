<?php

namespace MageTest\MagentoExtension;

interface Fixture
{
    /**
     * Create a fixture in Magento DB using the given attributes map
     *
     * @param $attributes array attributes map using 'label' => 'value' format
     *
     * @return null
     */
    public function create(array $attributes);
}
