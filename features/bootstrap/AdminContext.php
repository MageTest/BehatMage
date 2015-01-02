<?php

use Behat\Behat\Tester\Exception\PendingException;
use MageTest\MagentoExtension\Context\MagentoContext;

class AdminContext extends MagentoContext
{
    /**
     * @Given there is an admin user :username :password
     */
    public function thereIsAnAdminUser($username, $password)
    {
        $user = Mage::getModel('admin/user')
            ->load($username, 'username');

        if (null === $user->getUsername()) {
            $user->setData(array(
                    'username' => $username,
                    'firstname' => 'Admin',
                    'lastname' => 'Admin',
                    'email' => 'test@test.com',
                    'password' => $password,
                    'is_active' => 1
                ))
                ->save()
                ->setRoleIds(array(1))
                ->setRoleUserId($user->getUserId())
                ->saveRelations();
        }
    }

    /**
     * @Given I am logged in as admin :username with credentials :password
     */
    public function iAmASiteAdmin($username, $password)
    {
        $sessionId = $this->getSessionService()->adminLogin($username, $password);
        $this->getSession()->setCookie('adminhtml', $sessionId);
    }

    /**
     * @Then I should see that the page title is :title
     */
    public function iShouldSeeThatThePageTitleIs($title)
    {
        $page = $this->getSession()->getPage();
        expect($page->find('css', 'h3')->getText())->toBe($title);
    }
}
