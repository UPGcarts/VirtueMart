<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Risk\RiskClass;
use Upg\Library\Validation\Helper\Regex;

/**
 * Class BasketItem
 * For basketItem json objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class BasketItem extends AbstractObject
{
    /**
     * Description of the basket item
     * @var string
     */
    private $basketItemText;

    /**
     * Unique ID for a single article of the basket.
     * @var string
     */
    private $basketItemID;

    /**
     * number of basket items of this kind
     * @var int
     */
    private $basketItemCount;

    /**
     * The amount of the basket item
     * @var Amount
     */
    private $basketItemAmount;

    /**
     * @var int Risk class of basket item
     * @see \Upg\Library\Risk\RiskClass
     */
    private $basketItemRiskClass = null;

    /**
     * @var string The basket item type
     * @see \Upg\Library\Basket\BasketItemType
     */
    private $basketItemType;

    /**
     * Set the basket Item Text
     * @param string $basketItemText
     * @return $this
     */
    public function setBasketItemText($basketItemText)
    {
        $this->basketItemText = $basketItemText;
        return $this;
    }

    /**
     * Get the basket Item text
     * @return string
     */
    public function getBasketItemText()
    {
        return $this->basketItemText;
    }

    /**
     * Set the basket item id
     * @param string $basketItemID
     * @return $this
     */
    public function setBasketItemID($basketItemID)
    {
        $this->basketItemID = $basketItemID;
        return $this;
    }

    /**
     * Get the basket Item ID
     * @return int
     */
    public function getBasketItemID()
    {
        return $this->basketItemID;
    }

    /**
     * Set the basket Item Count
     * @param int $basketItemCount
     * @return $this
     */
    public function setBasketItemCount($basketItemCount)
    {
        $this->basketItemCount = $basketItemCount;
        return $this;
    }

    /**
     * Return the basket item count
     * @return int
     */
    public function getBasketItemCount()
    {
        return $this->basketItemCount;
    }

    /**
     * Set the amount associated with the basket
     * @param Amount $basketItemAmount
     * @return $this
     */
    public function setBasketItemAmount(Amount $basketItemAmount)
    {
        $this->basketItemAmount = $basketItemAmount;
        return $this;
    }

    /**
     * Get the amount associated with the basket amount
     * @return Amount
     */
    public function getBasketItemAmount()
    {
        return $this->basketItemAmount;
    }

    /**
     * Set the risk class for the item
     * @param int $basketItemRiskClass
     * @return $this
     */
    public function setBasketItemRiskClass($basketItemRiskClass)
    {
        $this->basketItemRiskClass = $basketItemRiskClass;
        return $this;
    }

    /**
     * Set the risk class
     * @return int
     */
    public function getBasketItemRiskClass()
    {
        return $this->basketItemRiskClass;
    }

    /**
     * Set the Basket Item Type
     * @see BasketItem::$basketItemType
     * @param $basketItemType
     * @return $this
     */
    public function setBasketItemType($basketItemType)
    {
        $this->basketItemType = $basketItemType;
        return $this;
    }

    /**
     * Return the basket item type value
     * @see BasketItem::$basketItemType
     * @return string
     */
    public function getBasketItemType()
    {
        return $this->basketItemType;
    }

    public function toArray()
    {
        $return = array(
            'basketItemText' => $this->getBasketItemText(),
            'basketItemCount' => $this->getBasketItemCount(),
            'basketItemAmount' => $this->getBasketItemAmount(),
        );

        if ($this->basketItemID) {
            $return['basketItemID'] = $this->getBasketItemID();
        }

        if (is_numeric($this->basketItemRiskClass)) {
            $return['basketItemRiskClass'] = $this->getBasketItemRiskClass();
        }

        if($this->basketItemType) {
            $return['basketItemType'] = $this->getBasketItemType();
        }

        return $return;
    }

    public function getValidationData()
    {
        $validationData = array();

        $validationData['basketItemText'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "basketItemText is required"
        );

        $validationData['basketItemText'][] = array(
            'name' => 'MaxLength',
            'value' => '500',
            'message' => "basketItemText must be no more than 500 characters"
        );

        $validationData['basketItemID'][] = array(
            'name' => 'Regex',
            'value' => '/^'.Regex::REGEX_PARTIAL_ALPHANUMERIC.'{1,20}$/',
            'message' => "basketItemID must be no more than 20 characters and alphanumeric"
        );

        $validationData['basketItemCount'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "basketItemCount is required"
        );

        $validationData['basketItemCount'][] = array(
            'name' => 'Regex',
            'value' => '/^[0-9]{1,5}$/',
            'message' => "basketItemCount must be an integer and no more than 5 digits"
        );

        $validationData['basketItemAmount'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "basketItemAmount is required"
        );

        $validationData['basketItemRiskClass'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Risk\RiskClass::validateRiskClass',
            'message' => "basketItemRiskClass must certain values or be empty"
        );

        //basketItemType
        $validationData['basketItemType'][] = array(
            'name' => 'Callback',
            'value' => 'Upg\Library\Basket\BasketItemType::validateBasketItemType',
            'message' => "basketItemType must certain values or be empty"
        );

        return $validationData;
    }
}
