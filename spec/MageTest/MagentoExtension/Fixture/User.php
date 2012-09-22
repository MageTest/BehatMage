<?php

namespace spec\MageTest\MagentoExtension\Fixture;

use PHPSpec2\Specification;

class User implements Specification
{
    function described_with()
    {
        \Mage::app();
        $this->userModel = \Mockery::mock(new \Mage_Admin_Model_User);

        $userModel = $this->userModel;
        $factory = function () use ($userModel) { return $userModel; };

        $this->user->isAnInstanceOf('MageTest\MagentoExtension\Fixture\User', array($factory));
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

        $this->user->create($data);
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

        $this->user->shouldThrow('\Exception', 'Username provided to user fixture should not be existing')->during('create', array($data));
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

        $this->user->create($data)->shouldBe(554);
    }

    function it_should_load_object_and_delete_it_when_delete_is_requested()
    {
        $this->userModel->shouldReceive('load')->with(554)->once()->andReturn($this->userModel)->ordered();
        $this->userModel->shouldReceive('delete')->once()->andReturn(null)->ordered();

        $this->user->delete(554);
    }
}
