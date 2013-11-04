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
 * @subpackage Fixture
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\Fixture;
use MageTest\MagentoExtension\Fixture;
use Mage;
use RuntimeException;

/**
 * Order fixtures functionality provider
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Fixture
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class Order implements FixtureInterface
{
    const DEFAULT_SHIPPING_METHOD = 'standard_standard';
    const DEFAULT_PAYMENT_METHOD = 'checkmo';

    /**
     * @var bool
     */
    private $wasSecureArea;

    /**
     * @var \Mage_Customer_Model_Customer
     */
    protected $customer;

    /**
     * @var array
     */
    protected $items;

    /**
     * @var \Mage_Customer_Model_Address
     */
    protected $billingAddress;

    /**
     * @var \Mage_Customer_Model_Address
     */
    protected $shippingAddress;

    /**
     * @var string
     */
    protected $shippingMethod;

    /**
     * @var string
     */
    protected $paymentMethod;

    /**
     * @var \Closure
     */
    protected $modelFactory;

    /**
     * @param $productModelFactory \Closure optional
     */
    public function __construct($productModelFactory = null)
    {
        $this->modelFactory = $productModelFactory ?: $this->defaultModelFactory();
    }

    /**
     * retrieve default order model factory
     *
     * @return \Closure
     */
    public function defaultModelFactory()
    {
        return function () {
            return \Mage::getModel('sales/order');
        };
    }

    /**
     * Create a order fixture using the given field map
     *
     * Input array structure should be like this:
     * array(
     *      'customer' => Mage_Customer_Model_Customer,
     *      'items' => array(
     *          array(
     *              'product' => Mage_Catalog_Model_Product,
     *              'qty' => int,
     *              'options' => array(
     *                  'optionId' => 'optionValue',
     *                  ...
     *              ),
     *          ),
     *          ...
     *      ),
     *      'billingAddress' => Mage_Customer_Model_Address,
     *      'shippingAddress' => Mage_Customer_Model_Address,
     *      'payment_method' => string,
     *      'shipping_method' => string,
     *  )
     * Any or all elements can be skipped if they were previously provided via dedicated setters.
     * Addresses can be skipped as long as there is at least one address associated with the customer
     *
     * @param $attributes array Order data can be passed as an associative array
     * @return int Order id
     */
    public function create(array $attributes)
    {
        $this->mergeAttributes($attributes);

        $this->validate();

        $this->model = $this->createOrder();

        return $this->model->getId();
    }

    /**
     * Validates if all required data is available and conforms to expected formats. In case of invalid data throws exception.
     *
     * @throws RuntimeException
     */
    protected function validate()
    {
        if (empty($this->customer)) {
            throw new RuntimeException('Customer must be provided for order fixture');
        }

        if (empty($this->items)) {
            throw new RuntimeException('There were no items provided for order fixture');
        } elseif (!is_array($this->items)) {
            throw new RuntimeException('The items provided for order must be contained in an array');
        } else {
            foreach ($this->items as $item) {
                if (empty($item['product'])) {
                    throw new RuntimeException("Item array's elements must contain 'product' key");
                } elseif (!$item['product'] instanceof \Mage_Catalog_Model_Product) {
                    throw new RuntimeException("Item array's elements 'product' key must reference Mage_Catalog_Model_Product object");
                }
            }
        }

        if (!$this->getBillingAddress()) {
            throw new RuntimeException('No billing address provided for order fixture');
        }

        if (!$this->getShippingAddress()) {
            throw new RuntimeException('No shipping address provided for order fixture');
        }
    }

    /**
     * Checks if the given array contains valuable data and sets it via dedicated setters
     *
     * @param array $attributes
     */
    protected function mergeAttributes($attributes)
    {
        if (isset($attributes['customer']) && $attributes['customer'] instanceof \Mage_Customer_Model_Customer) {
            $this->setCustomer($attributes['customer']);
        }

        if (isset($attributes['items']) && is_array($attributes['items'])) {
            $this->setItems($attributes['items']);
        }

        if (isset($attributes['billingAddress']) && $attributes['billingAddress'] instanceof \Mage_Customer_Model_Address) {
            $this->setBillingAddress($attributes['billingAddress']);
        }

        if (isset($attributes['shippingAddress']) && $attributes['shippingAddress'] instanceof \Mage_Customer_Model_Address) {
            $this->setShippingAddress($attributes['shippingAddress']);
        }

        if (isset($attributes['shippingMethod'])) {
            $this->setShippingMethod($attributes['shippingMethod']);
        }

        if (isset($attributes['paymentMethod'])) {
            $this->setPaymentMethod($attributes['paymentMethod']);
        }
    }

    /**
     * Set customer model
     * @param \Mage_Customer_Model_Customer $customer
     * @return \MageTest\MagentoExtension\Fixture\Order
     */
    public function setCustomer(\Mage_Customer_Model_Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get customer model
     * @return \Mage_Customer_Model_Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set items to be ordered.
     *
     * Input is expected in the following format:
     * array(
     *     array(
     *         'product' => Mage_Catalog_Model_Product,
     *         'qty' => int,
     *         'options' => array(
     *            'optionId' => 'optionValue',
     *            ...
     *         ),
     *     ),
     *     ...
     * )
     *
     * @param array $items
     * @return \MageTest\MagentoExtension\Fixture\Order
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Get orderable items
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set billing address
     * @param \Mage_Customer_Model_Address $address
     * @return \MageTest\MagentoExtension\Fixture\Order
     */
    public function setBillingAddress(\Mage_Customer_Model_Address $address)
    {
        $this->billingAddress = $address;
        return $this;
    }

    /**
     * Get billing address
     *
     * If one is not explicitly provided, the default billing address from customer will be used;
     * If that doesn't exit, the first of all customer's address will be taken
     *
     * @return \Mage_Customer_Model_Address
     */
    public function getBillingAddress()
    {
        if (!$this->billingAddress) {

            $this->billingAddress = $this->customer->getDefaultBillingAddress();

            if (!$this->billingAddress) {
                $this->billingAddress = $this->customer->getAddressCollection()->getFirstItem();
            }
        }

        return $this->billingAddress;
    }

    /**
     * Set shipping address
     * @param \Mage_Customer_Model_Address $address
     * @return \MageTest\MagentoExtension\Fixture\Order
     */
    public function setShippingAddress(\Mage_Customer_Model_Address $address)
    {
        $this->shippingAddress = $address;
        return $this;
    }

    /**
     * Get shipping address
     *
     * If one is not explicitly provided, the default shipping address from customer will be used;
     * If that doesn't exit, whatever is returned by getBillingAddress() will be used
     *
     * @return \Mage_Customer_Model_Address
     */
    public function getShippingAddress()
    {
        if (!$this->shippingAddress) {

            $this->shippingAddress = $this->customer->getDefaultShippingAddress();

            if (!$this->shippingAddress) {
                $this->shippingAddress = $this->getBillingAddress();
            }
        }

        return $this->shippingAddress;
    }

    /**
     * Set shipping method
     * @param string $method
     * @return \MageTest\MagentoExtension\Fixture\Order
     */
    public function setShippingMethod($method)
    {
        $this->shippingMethod = $method;
        return $this;
    }

    /**
     * Get shipping method
     * @return string
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod ? $this->shippingMethod : self::DEFAULT_SHIPPING_METHOD;
    }

    /**
     * Set payment method
     * @param string $method
     * @return \MageTest\MagentoExtension\Fixture\Order
     */
    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
        return $this;
    }

    /**
     * Get payment method
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod ? $this->paymentMethod : self::DEFAULT_PAYMENT_METHOD;
    }

    /**
     * Delete the requested fixture from Magento DB
     *
     * @param $identifier int object identifier
     * @return null
     */
    public function delete($identifier)
    {
        $modelFactory = $this->modelFactory;
        $model = $modelFactory();

        $model->load($identifier);

        $this->enableSecureArea();
        $model->delete();
        $this->restoreSecureArea();
    }

    /**
     * Get default structure of data required to create an order.
     *
     * @return array
     */
    public function getDefaultAttributes()
    {
        return array(
            'session' => array(
                'customer_id' => '',
                'store_id' => 1,
            ),
            'payment' => array(
                'method' => '',
            ),
            'add_products' => array(
            ),
            'order' => array(
                'currency' => 'GBP',
                'account' => array(
                    'group_id' => 1,
                    'email' => '',
                ),
                'billing_address' => array(
                    'customer_address_id' => '',
                    'prefix' => '',
                    'firstname' => '',
                    'middlename' => '',
                    'lastname' => '',
                    'suffix' => '',
                    'company' => '',
                    'street' => '',
                    'city' => '',
                    'country_id' => '',
                    'region' => '',
                    'region_id' => '',
                    'postcode' => '',
                    'telephone' => '',
                    'fax' => '',
                ),
                'shipping_address' => array(
                    'customer_address_id' => '',
                    'prefix' => '',
                    'firstname' => '',
                    'middlename' => '',
                    'lastname' => '',
                    'suffix' => '',
                    'company' => '',
                    'street' => '',
                    'city' => '',
                    'country_id' => '',
                    'region' => '',
                    'region_id' => '',
                    'postcode' => '',
                    'telephone' => '',
                    'fax' => '',
                ),
                'shipping_method' => '',
                'comment' => array(
                    'customer_note' => 'This order has been programmatically created as a test fixture',
                ),
                'send_confirmation' => false,
            ),
        );
    }

    /**
     * Handle order data population from provided entities and delegate quote and order processing
     *
     * @return \Mage_Sales_Model_Order
     * @throws RuntimeException
     */
    protected function createOrder()
    {
        $customer = $this->getCustomer();

        $orderData = $this->getDefaultAttributes();

        $orderData['session']['customer_id']    = $customer->getId();
        $orderData['order']['account']['email'] = $customer->getEmail();

        $address = $this->getBillingAddress();

        $orderData['order']['billing_address']['customer_address_id']   = $address->getId();
        $orderData['order']['billing_address']['firstname']             = $address->getFirstname();
        $orderData['order']['billing_address']['lastname']              = $address->getLastname();
        $orderData['order']['billing_address']['street']                = $address->getStreet();
        $orderData['order']['billing_address']['city']                  = $address->getCity();
        $orderData['order']['billing_address']['country_id']            = $address->getCountryId();
        $orderData['order']['billing_address']['region']                = $address->getRegion();
        $orderData['order']['billing_address']['region_id']             = $address->getRegionId();
        $orderData['order']['billing_address']['postcode']              = $address->getPostcode();
        $orderData['order']['billing_address']['telephone']             = $address->getTelephone();

        $address = $this->getShippingAddress();

        $orderData['order']['shipping_address']['customer_address_id']   = $address->getId();
        $orderData['order']['shipping_address']['firstname']             = $address->getFirstname();
        $orderData['order']['shipping_address']['lastname']              = $address->getLastname();
        $orderData['order']['shipping_address']['street']                = $address->getStreet();
        $orderData['order']['shipping_address']['city']                  = $address->getCity();
        $orderData['order']['shipping_address']['country_id']            = $address->getCountryId();
        $orderData['order']['shipping_address']['region']                = $address->getRegion();
        $orderData['order']['shipping_address']['region_id']             = $address->getRegionId();
        $orderData['order']['shipping_address']['postcode']              = $address->getPostcode();
        $orderData['order']['shipping_address']['telephone']             = $address->getTelephone();

        $orderData['order']['shipping_method']  = $this->getShippingMethod();
        $orderData['payment']['method']         = $this->getPaymentMethod();

        foreach ($this->getItems() as $item) {

            if (empty($item['product'])) {
                throw new RuntimeException('No product provided when trying to add item to the order');
            }

            $product = $item['product'];
            $qty = !empty($item['qty']) ? $item['qty'] : 1;
            $options = !empty($item['options']) ? $item['options'] : array();

            $orderData['add_products'][$product->getId()] = array(
                'product' => $product,
                'qty' => $qty,
                'options' => $options,
            );
        }

        $this->enableSecureArea();
        $order = $this->processOrder($orderData);
        $this->restoreSecureArea();

        return $order;
    }


    /**
     * Convert large order data-structure into an actual order and save it
     *
     * @param array $orderData
     * @return \Mage_Sales_Model_Order
     */
    protected function processOrder($orderData)
    {
        if (!empty($orderData)) {

            Mage::unregister('rule_data');

            $orderBuilder = Mage::getSingleton('adminhtml/sales_order_create');

            $this->initSession($orderData['session']);

            $this->processQuote($orderData);

            $orderBuilder->setPaymentData($orderData['payment']);
            $orderBuilder->getQuote()->getPayment()->addData($orderData['payment']);

            foreach ($orderData['add_products'] as $productData) {

                $product = $productData['product'];
                $item = $orderBuilder->getQuote()->getItemByProduct($product);

                foreach ($productData['options'] as $optionCode => $optionValue) {
                    $item->addOption(new Varien_Object(
                        array(
                            'product' => $product,
                            'code' => $optionCode,
                            'value' => $optionValue,
                        )
                    ));
                }
            }

            Mage::app()->getStore()->setConfig(\Mage_Sales_Model_Order::XML_PATH_EMAIL_ENABLED, '0');

            $order = $orderBuilder
                ->importPostData($orderData['order'])
                ->createOrder();

            $this->getAdminSession()->clear();

            Mage::unregister('rule_data');

            return $order;
        }
        return null;
    }

    /**
     * Retrieve admin session object
     *
     * @return Mage_Adminhtml_Model_Session_Quote
     */
    protected function getAdminSession()
    {
        return Mage::getSingleton('adminhtml/session_quote');
    }

    /**
     * Initialize order creation session data
     *
     * @param array $data
     * @return \Mage_Adminhtml_Sales_Order_CreateController
     */
    protected function initSession($data)
    {
        /* Get/identify customer */
        if (!empty($data['customer_id'])) {
            $this->getAdminSession()->setCustomerId((int) $data['customer_id']);
        }
        /* Get/identify store */
        if (!empty($data['store_id'])) {
            $this->getAdminSession()->setStoreId((int) $data['store_id']);
        }
        return $this;
    }

    /**
     * Fill in and process quote before it gets converted to an order
     * @param array $data
     * @return \MageTest\MagentoExtension\Fixture\Order
     */
    protected function processQuote($data)
    {
        $orderBuilder = Mage::getSingleton('adminhtml/sales_order_create');
        /* @var $orderBuilder Mage_Adminhtml_Model_Sales_Order_Create */

        /* Saving order data */
        if (!empty($data['order'])) {
            $orderBuilder->importPostData($data['order']);
        }
        $orderBuilder->getBillingAddress();
        $orderBuilder->setShippingAsBilling(true);
        /* Just like adding products from Magento admin grid */
        if (!empty($data['add_products'])) {
            $orderBuilder->addProducts($data['add_products']);
        }

        /* Collect shipping rates */
        $orderBuilder->collectShippingRates();

        $orderBuilder->setShippingMethod($data['order']['shipping_method']);

        /* Add payment data */
        if (!empty($data['payment'])) {
            $orderBuilder->getQuote()->getPayment()->addData($data['payment']);
        }

        $orderBuilder->initRuleData()->saveQuote();

        if (!empty($data['payment'])) {
            $orderBuilder->getQuote()->getPayment()->addData($data['payment']);
        }
        return $this;
    }

    /**
     * Set registry flag to indicate secure area, as this is required for some actions on the order object
     */
    protected function enableSecureArea()
    {
        $this->wasSecureArea = Mage::registry('isSecureArea');
        Mage::unregister('isSecureArea');
        Mage::register('isSecureArea', true);
    }

    /**
     * Restore registry flag of secure are to whatever it was before
     */
    protected function restoreSecureArea()
    {
        if ($this->wasSecureArea !== Mage::registry('isSecureArea')) {
            Mage::unregister('isSecureArea');
            Mage::register('isSecureArea', $this->wasSecureArea);
        }
    }
}