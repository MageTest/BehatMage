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

use Mockery;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CategorySpec extends ObjectBehavior
{
    /** @var \Mage_Catalog_Model_Category $model */
    private $model;

    /** @var \Mage_Catalog_Model_Resource_Category_Collection $collection */
    private $collection;

    function let()
    {
        $this->model = $categoryModel = Mockery::mock('Mage_Catalog_Model_Category');
        $websiteHelper = Mockery::mock('MageTest\MagentoExtension\Helper\Website');
        $websiteHelper->shouldReceive('getWebsiteIds')->andReturn(array());
        $websiteHelper->shouldReceive('getWebsites')->andReturn(array());

        $serviceContainer = array(
            'categoryModel'  => function() use($categoryModel) {
                return $categoryModel;
            },
            'websiteHelper' => function() use($websiteHelper) {
                return $websiteHelper;
            },
        );

        $this->beConstructedWith($serviceContainer);

        $this->collection = $collection = Mockery::mock('Mage_Catalog_Model_Resource_Category_Collection');
        $categoryModel->shouldReceive('getCollection')->andReturn($collection);
        $categoryModel->shouldReceive('getId')->andReturn(1)->byDefault();
        $categoryModel->shouldReceive('load')->andReturn($categoryModel)->byDefault();
        $categoryModel->shouldReceive('getData')->andReturn(array())->byDefault();
        $categoryModel->shouldReceive('setData')->andReturn($categoryModel)->byDefault();
        $categoryModel->shouldReceive('save')->andReturn($categoryModel)->byDefault();
        $nameAttribute = Mockery::mock('Mage_Eav_Model_Entity_Attribute')
            ->shouldReceive('getAttributeCode')
            ->andReturn('name')
            ->getMock();
        $categoryModel->shouldReceive('getAttributes')->andReturn(array($nameAttribute))->byDefault();

        $collection->shouldReceive('addFieldToFilter')->andReturn($collection)->byDefault();
        $collection->shouldReceive('addPathsFilter')->andReturn($collection)->byDefault();
        $collection->shouldReceive('getFirstItem')->andReturn($categoryModel)->byDefault();
    }

    function letgo()
    {
        Mockery::close();
    }

    function it_loads_category_model_by_name_on_default_path()
    {
        $categoryData = array(
            'name' => 'Category1',
        );

        $this->collection->shouldReceive('addFieldToFilter')->with('name', 'Category1')
            ->once()
            ->andReturn($this->collection)
            ->ordered();
        $this->collection->shouldReceive('addPathsFilter')
            ->with(\MageTest\MagentoExtension\Fixture\Category::DEFAULT_ROOT_CATEGORY_ID)
            ->once()
            ->andReturn($this->collection)
            ->ordered();
        $this->collection->shouldReceive('getFirstItem')->once()->ordered();

        $this->create($categoryData);
    }

    function it_loads_category_model_by_name_on_custom_path()
    {
        $categoryData = array(
            'name' => 'Category1',
            'path' => '1/2/3',
        );

        $this->collection->shouldReceive('addFieldToFilter')->with('name', 'Category1')
            ->once()
            ->andReturn($this->collection)
            ->ordered();
        $this->collection->shouldReceive('addPathsFilter')
            ->with('1/2/3')
            ->once()
            ->andReturn($this->collection)
            ->ordered();
        $this->collection->shouldReceive('getFirstItem')->once()->ordered();

        $this->create($categoryData);
    }
}