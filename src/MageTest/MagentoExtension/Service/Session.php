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
 * @subpackage Service
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Service;
use Mage_Admin_Model_User;

/**
 * Session
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Session
{
    private $app;

    public function __construct(Bootstrap $bootstrap)
    {
        $this->app = $bootstrap->app();
    }

    /**
     * Login given admin account to Magento session and return session id
     *
     * @param $username string
     * @param $password string
     *
     * @return string
     */
    public function adminLogin($username, $password)
    {
        $_SESSION = null;

        $session = \Mage::getModel('admin/session');
        @$session->start();

        $_POST['login']=array('username'=>$username);

        $this->app->setResponse(new \Mage_Core_Controller_Response_Http());
        $this->app->getStore()->setId(\Mage_Core_Model_App::ADMIN_STORE_ID);

        /** @var $user Mage_Admin_Model_User */
        $user = \Mage::getModel('admin/user');

        $user->login($username, $password);
        if ($user->getId()) {
            @$session->renewSession();

            if (\Mage::getSingleton('adminhtml/url')->useSecretKey()) {
                \Mage::getSingleton('adminhtml/url')->renewSecretUrls();
            }
            $session->setIsFirstPageAfterLogin(true);
            $session->setUser($user);
            $session->setAcl(\Mage::getResourceModel('admin/acl')->loadAcl());
            \Mage::getSingleton('adminhtml/url')->getSecretKey();
        } else {
            throw new \Exception('Invalid User Name or Password.');
        }

        $id = $session->getSessionId();
        session_write_close();
        $_SESSION = null;
        $_POST = array();

        return $id;
    }

    /**
     * Log in the given customer and return the session id
     *
     * @param string $email
     * @param string $password
     * @return string
     */
    public function customerLogin($email, $password)
    {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            if (PHP_SESSION_ACTIVE === session_status()) {
                session_write_close();
                $_SESSION = null;
            }
        } else {
            if (session_id() !== '') {
                $_SESSION = null;
            }
        }

        /** @var $session \Mage_Customer_Model_Session */
        $session = \Mage::getSingleton('customer/session');

        if (! $session->login($email, $password)) {
            throw new \Exception('Invalid Customer Email or Password.');
        }

        $id = $session->getSessionId();
        session_write_close();
        $_SESSION = null;
        $_POST = array();
        return $id;
    }

    public function customerLogout()
    {
        /** @var $session \Mage_Customer_Model_Session */
        $session = \Mage::getSingleton('customer/session');
        $session->logout();
    }
}
