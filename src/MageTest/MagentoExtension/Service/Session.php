<?php

namespace MageTest\MagentoExtension\Service;

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
}
