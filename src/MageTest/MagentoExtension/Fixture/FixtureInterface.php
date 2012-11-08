<?php

namespace MageTest\MagentoExtension\Fixture;

interface FixtureInterface
{
    /**
     * Create a fixture in Magento DB using the given attributes map and return its ID
     *
     * @param $attributes array attributes map using 'label' => 'value' format
     *
     * @return int
     */
    public function create(array $attributes);

    /**
     * Delete the requested fixture from Magento DB
     *
     * @param $identifier int object identifier
     *
     * @return null
     */
    public function delete($identifier);
}
