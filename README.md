## BehatMage

Behat extension for Magento, providing Behat context with specific Magento requirements allowing you to quickly define Magento scenarios and steps to enable BDD within Magento projects.

## Target

* To create a tool that makes testing easy and straight forward to use for testing external behavior of Magento.
* To create a tool that provides clear feedback when exceptions are raised. The feedback should coach the developer on how to resolve the issue and the messaging should be correct to the Magento domain.

## How?

* The tool can be installed easily with composer.
* The creation of standard scenarios can be accomplished without the need to create new contexts.
* There are no exceptions raised that do not provide feedback on how to proceed or resolve the issue.
* The documentation includes examples of how to use each feature of the tool.

## Installation

### Prerequisites

BehatMage requires PHP 5.3.x or greater.

### Method 1 (composer)

First, add BehatMage to the list of dependencies inside your `composer.json` and be sure to register few paths for autoloading:

```json
{
    "config": {
        "bin-dir": "bin"
    },
    "require": {
            "php": ">=5.3.0"
    },
    "require-dev": {
        "magetest/magento-behat-extension": "dev-develop"
    },
    "autoload": {
        "psr-0": {
            "": [
                "app",
                "app/code/local",
                "app/code/community",
                "app/code/core",
                "lib"
            ]
        }
    },
    "minimum-stability": "dev"
}
```

Then simply install it with composer:

```bash
$ composer install --dev --prefer-dist
```

You can read more about Composer on its [official webpage](http://getcomposer.org).

## Basic usage

Change directory to your project one and setup behat inside the directory:

```bash
$ cd project
$ behat --init
```

The behat --init will create a features/ directory with some basic things to get your started.
The output on the screen should be similar to:

```bash
$ bin/behat --init
+d features - place your *.feature files here
+d features/bootstrap - place bootstrap scripts and static files here
+f features/bootstrap/FeatureContext.php - place your feature related code here
```

### Define your feature

Everything in Behat always starts with a feature that you want to describe and then implement. In this example, the feature will be give an admin user the ability to manage review visibility, so we can start by creating a features/admin_user_manages_review_visibility.feature file:

```Cucumber
Feature: Admin User can manage review visibility
    So that our Customers are not influenced by a product with bad review history,
    as an Admin User
    I want to disable reviews of those specific products
```

Every feature starts with this same format: a line naming the feature, followed by three lines that describe the benefit, the role and the feature itself. And while this section is required, its contents aren’t actually important to Behat or your eventual test. This section is important, however, so that each feature is described consistently and is readable by other people.

### Define a scenario

Next, add the following scenario to the end of the features/admin_user_manages_review_visibility.feature file:

```Cucumber
Scenario: Turn off reviews per product
    Given the following products exist:
        | sku      | name           | accepts_reviews |
        | Ottoman1 | Ottoman        | 1               |
    And "Ottoman1" has existing reviews
    When I turn reviews off for "Ottoman1" product
    Then no review should be displayed for "Ottoman1"
```

Each feature is defined by one or more “scenarios”, which explain how that feature should act under different conditions. This is the part that will be transformed into a test. Each scenario always follows the same basic format:

```Cucumber
Scenario: Some description of the scenario
    Given [some context]
    When [some event]
    Then [outcome]
```

Each part of the scenario - the context, the event, and the outcome - can be extended by adding the And or But keyword:

```Cucumber
Scenario: Some description of the scenario
    Given [some context]
        And [more context]
    When [some event]
        And [second event occurs]
    Then [outcome]
        And [another outcome]
        But [another outcome]
```

There’s no actual difference between, Then, And But or any of the other words that start each line. These keywords are all made available so that your scenarios are natural and readable.

### Executing Behat

You’ve now defined the feature and one scenario for that feature. You’re ready to see Behat in action! Try executing Behat from inside your project directory:

```bash
$ bin/behat
```

If everything worked correctly, you should see something like this:

```bash
Feature: Admin User can manage review visibility
  So that our Customers are not influenced by a product with bad review history,
  as an Admin User
  I want to disable reviews of those specific products

  Scenario: Turn off reviews per product              # features/reviews/admin_user_manages_review_visibility.feature:7
    Given the following products exist:
      | sku      | name    | accepts_reviews |
      | Ottoman1 | Ottoman | 1               |
    And "Ottoman1" has existing reviews
    When I turn reviews off for "Ottoman1" product
    Then no review should be displayed for "Ottoman1"

1 scenario (1 undefined)
4 steps (4 undefined)
0m1.836s

You can implement step definitions for undefined steps with these snippets:

    /**
     * @Given /^the following products exist:$/
     */
    public function theFollowingProductsExist(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given /^"([^"]*)" has existing reviews$/
     */
    public function hasExistingReviews($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When /^I turn reviews off for "([^"]*)" product$/
     */
    public function iTurnReviewsOffForProduct($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^no review should be displayed for "([^"]*)"$/
     */
    public function noReviewShouldBeDisplayedFor($arg1)
    {
        throw new PendingException();
    }
```

### Writing your step definition

Behat automatically finds the feature/admin_user_manages_review_visibility.feature file and tries to execute its Scenario as a test. However, we haven’t told Behat what to do with statements like Given the following products exist, which causes an error. Behat works by matching each statement of a Scenario to a list of regular expression “steps” that you define. In other words, it’s your job to tell Behat what to do when it sees Given the following products exist. Fortunately, Behat helps you out by printing the regular expression that you probably need in order to create that step definition:

```bash
You can implement step definitions for undefined steps with these snippets:

    /**
     * @Given /^the following products exist:$/
     */
    public function theFollowingProductsExist(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Given /^"([^"]*)" has existing reviews$/
     */
    public function hasExistingReviews($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When /^I turn reviews off for "([^"]*)" product$/
     */
    public function iTurnReviewsOffForProduct($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^no review should be displayed for "([^"]*)"$/
     */
    public function noReviewShouldBeDisplayedFor($arg1)
    {
        throw new PendingException();
    }
```

Behat, however, is not aware yet of the Magento domain and it's requiring us to add step definitions that we likely want to skip because of their repetitive nature. We then have to make Behat be Magento aware using its configuration file behat.yml adding the following lines:

```yml
default:
    extensions:
        MageTest\MagentoExtension\Extension:
            base_url: "http://project.development.local"

```

where we tell Behat which extension to load and what store we want to test. Well done so far, we now have to tell Behat that we want to use, just for clarity, a specific sub context for every actor that we have, in our example admin user. In order to do so we have to update the features/bootstrap/FeatureContext.php file as following:

```php
# features/bootstrap/FeatureContext.php
<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
        $this->useContext('admin_user', new AdminUserContext($parameters));
    }
}
```

and create such a sub context as php class extending the MagentoContext provided by the BehatMage extension as following:

```php
# features/bootstrap/AdminUserContext.php
<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use MageTest\MagentoExtension\Context\MagentoContext;

class AdminUserContext extends MagentoContext
{

}
```

If we run Behat again, now that the framework is aware of the Magento domain, our output should be different and look something this:

```bash
Feature: Admin User can manage review visibility
  So that our Customers are not influenced by a product with bad review history,
  as an Admin User
  I want to disable reviews of those specific products

  Scenario: Turn off reviews per product              # features/reviews/admin_user_manages_review_visibility.feature:7
    Given the following products exist:               # AdminUserContext::theProductsExist()
      | sku      | name    | accepts_reviews |
      | Ottoman1 | Ottoman | 1               |
      accepts_reviews is not yet defined as an attribute of Product
    And "Ottoman1" has existing reviews
    When I turn reviews off for "Ottoman1" product
    Then no review should be displayed for "Ottoman1"

1 scenario (1 failed)
4 steps (3 undefined, 1 failed)
0m1.481s

You can implement step definitions for undefined steps with these snippets:

    /**
     * @Given /^"([^"]*)" has existing reviews$/
     */
    public function hasExistingReviews($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When /^I turn reviews off for "([^"]*)" product$/
     */
    public function iTurnReviewsOffForProduct($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^no review should be displayed for "([^"]*)"$/
     */
    public function noReviewShouldBeDisplayedFor($arg1)
    {
        throw new PendingException();
    }
```

As you can see the recommendation to add the following snippet disappeared

```php
    /**
     * @Given /^the following products exist:$/
     */
    public function theFollowingProductsExist(TableNode $table)
    {
        throw new PendingException();
    }
```

this because BehatMage provides already the implementation of all those common steps generally needed and required to test Magento behaviours. So now let’s use Behat’s advice and add the following to the features/bootstrap/AdminUserContext.php file:

```php
# features/bootstrap/AdminUserContext.php
<?php

use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use MageTest\MagentoExtension\Context\MagentoContext;

class AdminUserContext extends MagentoContext
{
    /**
     * @Given /^"([^"]*)" has existing reviews$/
     */
    public function hasExistingReviews($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When /^I turn reviews off for "([^"]*)" product$/
     */
    public function iTurnReviewsOffForProduct($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then /^no review should be displayed for "([^"]*)"$/
     */
    public function noReviewShouldBeDisplayedFor($arg1)
    {
        throw new PendingException();
    }
}
```

or let Behat do it for us using the following commandline option:

```bash
$ bin/behat --append-to=AdminUserContext
```

Great! Now that you’ve defined all of your steps and told Behat what context to use, run Behat again:

```bash
$ bin/behat
```

If everything worked correctly, you should see something like this:

```bash
Feature: Admin User can manage review visibility
  So that our Customers are not influenced by a product with bad review history,
  as an Admin User
  I want to disable reviews of those specific products

  Scenario: Turn off reviews per product              # features/reviews/admin_user_manages_review_visibility
    Given the following products exist:               # AdminUserContext::theProductsExist()
      | sku      | name    | accepts_reviews |
      | Ottoman1 | Ottoman | 1               |
      accepts_reviews is not yet defined as an attribute of Product
    And "Ottoman1" has existing reviews               # AdminUserContext::hasExistingReviews()
    When I turn reviews off for "Ottoman1" product    # AdminUserContext::iTurnReviewsOffForProduct()
    Then no review should be displayed for "Ottoman1" # AdminUserContext::noReviewShouldBeDisplayedFor()## Some more about Behat basics
```

As you can see Behat is providing to the developer, thanks to the BehatMage extension, meaningful and useful information about the next step to take in order to implement the required behaviour. So let's add the needed code to make the first requirement of our step pass. Create the following files based on the suggested code:

```xml
<!-- app/code/local/BehatMage/Catalog/etc/config.xml -->
<?xml version="1.0"?>
<config>
    <modules>
        <BehatMage_Catalog>
            <version>0.1.0</version>
            <depends>
                <Mage_Catalog />
            </depends>
        </BehatMage_Catalog>
    </modules>
    <global>
        <resources>
            <behatmage_catalog_setup>
                <connection>
                    <use>core_setup</use>
                </connection>
                <setup>
                    <module>BehatMage_Catalog</module>
                    <class>BehatMage_Catalog_Model_Resource_Entity_Setup</class>
                </setup>
            </behatmage_catalog_setup>
        </resources>
    </global>
</config>
```

```xml
<!-- app/etc/modules/BehatMage_Catalog.xml -->
<?xml version="1.0"?>
<config>
    <modules>
        <BehatMage_Catalog>
            <active>true</active>
            <codePool>local</codePool>
            <depends>
                <Mage_Catalog />
            </depends>
        </BehatMage_Catalog>
    </modules>
</config>

```

```php
# app/code/local/BehatMage/Catalog/Model/Resource/Entity/Setup.php

<?php
class BehatMage_Catalog_Model_Resource_Entity_Setup extends Mage_Eav_Model_Entity_Setup
{

}
```

```php
# app/code/local/BehatMage/Catalog/data/behatmage_catalog_setup/data-install-0.1.0.php

<?php
$installer = $this;

$installer->startSetup();

$installer->addAttribute('catalog_product', 'accepts_reviews', array(
    'group' => 'General',
    'input' => 'yesno',
    'type' => 'int',
    'label' => 'Accept Reviews',
    'backend' => '',
    'default' => true,
    'visible' => true,
    'required' => true,
    'user_defined' => true,
    'searchable' => false,
    'filterable' => false,
    'comparable' => false,
    'visible_on_front' => true,
    'visible_in_advanced_search' => false,
    'is_html_allowed_on_front' => false,
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->endSetup();
```

Once the files have been created clear the Magento cache in order to run the setup script and add the required attribute. It's now time to run Behat again:

```bash
$ bin/behat
```

If everything is successful your output should now be something like this:

```bash
Feature: Admin User can manage review visibility
  So that our Customers are not influenced by a product with bad review history,
  as an Admin User
  I want to disable reviews of those specific products

  Scenario: Turn off reviews per product              # features/reviews/admin_user_manages_review_visibility.feature:7
    Given the following products exist:               # AdminUserContext::theProductsExist()
      | sku      | name    | accepts_reviews |
      | Ottoman1 | Ottoman | 1               |
    And "Ottoman1" has existing reviews               # AdminUserContext::hasExistingReviews()
      TODO: write pending definition
    When I turn reviews off for "Ottoman1" product    # AdminUserContext::iTurnReviewsOffForProduct()
    Then no review should be displayed for "Ottoman1" # AdminUserContext::noReviewShouldBeDisplayedFor()

1 scenario (1 pending)
4 steps (1 passed, 2 skipped, 1 pending)
0m6.478s
```

As you can see now that our product definition has the required attribute Behat moved onto the next step of our scenario. As you can imagine now it's only a matter of implementing, step by step, all the required tests and code to adhere to the previously defined scenario.

## More about Features

## More about Steps

## The Context Class: MagentoContext

## The BehatMage Command Line Tool

## What’s Next?

## License and Authors

Authors: <https://github.com/MageTest/BehatMage/contributors>

Copyright (C) 2012-2013

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
of the Software, and to permit persons to whom the Software is furnished to do
so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.