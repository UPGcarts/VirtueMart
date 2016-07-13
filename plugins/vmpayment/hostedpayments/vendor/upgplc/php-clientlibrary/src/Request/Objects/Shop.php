<?php

namespace Upg\Library\Request\Objects;

/**
 * Class Shop
 * For shop json objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class Shop extends AbstractObject
{
    /**
     * @var string The unique name that is used by the merchant to reference to this shop
     */
    private $shopName;

    /**
     * @var string The id of the shop that was created by PayCo and will be used by the merchant to reference the shop
     */
    private $shopID;

    /**
     * Set the shop name
     * @param string $shopName Set the shop name
     * @return $this
     */
    public function setShopName($shopName)
    {
        $this->shopName = $shopName;
        return $this;
    }

    /**
     * Return the shop name
     * @return string
     */
    public function getShopName()
    {
        return $this->shopName;
    }

    /**
     * Set the shop id
     * @param string $shopID The shop id to be set
     * @return $this
     */
    public function setShopID($shopID)
    {
        $this->shopID = $shopID;
        return $this;
    }

    /**
     * Return the shop id
     * @return string
     */
    public function getShopID()
    {
        return $this->shopID;
    }

    public function toArray()
    {
        return array(
            'shopName' => $this->shopName,
            'shopID' => $this->shopID,
        );
    }

    public function getValidationData()
    {
        return array();
    }

}
