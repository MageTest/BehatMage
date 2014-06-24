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
        /** @var \Mage_Core_Model_Website $coreWebsite */
        $coreWebsite = \Mage::app()->getModel('core/website');
        $ids = array();

        foreach ($coreWebsite->getCollection() as $website) {
            $ids[] = $website->getId();
        }

        return $ids;
    }

} 