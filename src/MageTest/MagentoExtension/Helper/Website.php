<?php

namespace MageTest\MagentoExtension\Helper;

/**
 * Class Website
 * @package MageTest\MagentoExtension\Helper
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
        foreach (\Mage::app()->getModel('core/website')->getCollection() as $website) {
            $ids[] = $website->getId();
        }

        return $ids;
    }

} 