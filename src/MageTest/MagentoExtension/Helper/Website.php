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
 * @subpackage Helper
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Helper;

/**
 * Website helper
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Helper
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Website
{
    /**
     * @param null $withDefault
     * @param bool $codeKey
     * @return array
     */
    public function getWebsites($withDefault = null, $codeKey = false)
    {
        return \Mage::app()->getWebsites($withDefault, $codeKey);
    }

    /**
     * @return array
     */
    public function getWebsiteIds()
    {
        $ids = array();
        foreach (\Mage::getModel('core/website')->getCollection() as $website) {
            $ids[] = $website->getId();
        }

        return $ids;
    }
}
