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

use PHPSpec2\ObjectBehavior;

/**
 * User
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class User extends ObjectBehavior
{
    private $userModel = null;

    function let()
    {
        \Mage::app();

        // Class is final, we can only use a partial mock
        $this->userModel = $userModel = \Mockery::mock(new \Mage_Admin_Model_User);

        $factory = function () use ($userModel) { return $userModel; };

        $this->beConstructedWith($factory);
    }

    function it_should_create_user_given_login_and_password()
    {
        $data = array(
            'username' => 'username'.time(),
            'password' => 'pass!',
        );

        $expected = $data;

        $this->userModel->shouldReceive('setData')
            ->with($expected)->once()->andReturn($this->userModel)->ordered();
        $this->userModel->shouldReceive('userExists')->once()->andReturn(false);
        $this->userModel->shouldReceive('save')->once()->andReturn(true)->ordered();
        $this->userModel->shouldReceive('getId')->andReturn(1);

        $this->create($data);
    }

    function it_should_throw_an_exception_if_creating_with_existing_username()
    {
        $data = array(
            'username' => 'username'.time(),
            'password' => 'pass!',
        );

        $this->userModel->shouldReceive('setData')
            ->with($data)->once()->andReturn($this->userModel)->ordered();
        $this->userModel->shouldReceive('userExists')->once()->andReturn(true);

        $this->shouldThrow('\Exception', 'Username provided to user fixture should not be existing')->during('create', array($data));
    }

    function it_should_return_the_created_objects_id()
    {
        $data = array(
            'sku' => 'sku'.time()
        );

        $this->userModel->shouldReceive('setData')->once()->andReturn($this->userModel)->ordered();
        $this->userModel->shouldReceive('save')->once()->andReturn(true)->ordered();
        $this->userModel->shouldReceive('getId')->once()->andReturn(554)->ordered();
        $this->userModel->shouldReceive('userExists')->andReturn(false);

        $this->create($data)->shouldBe(554);
    }

    function it_should_load_object_and_delete_it_when_delete_is_requested()
    {
        $this->userModel->shouldReceive('load')->with(554)->once()->andReturn($this->userModel)->ordered();
        $this->userModel->shouldReceive('delete')->once()->andReturn(null)->ordered();

        $this->delete(554);
    }
}
