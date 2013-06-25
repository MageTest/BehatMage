<?php

namespace MageTest\MagentoExtension\Page;

use SensioLabs\Behat\PageObjectExtension\PageObject\Page;

class Dashboard extends Page
{
    protected $path = '/admin/dashboard/index/key/{secretKey}/';

    public function getMainController()
    {
        // @todo get it from the path
        return 'dashboard';
    }

    public function getModuleName()
    {
        // @todo get it from the path
        return 'index';
    }
}