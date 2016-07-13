<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Validation\Helper\Constants as Constants;

/**
 * Class Company
 * For company json objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class Company extends AbstractObject
{
    /**
     * Company type: Firmenbuch – Commercial register
     */
    const COMPANY_TYPE_FN = "FN";

    /**
     * Company type: Handelsregister Abteilung A (HRA) – Trade register department A (HRA)
     */
    const COMPANY_TYPE_HRA = "HRA";

    /**
     * Company type: Handelsregister Abteilung B (HRB) – Trade register department B (HRB)
     */
    const COMPANY_TYPE_HRB = "HRB";

    /**
     * Company type: Partnerschaftsregister – Partnership register
     */
    const COMPANY_TYPE_PARTR = "PARTR";

    /**
     * Company type: Genossenschaftsregister – Cooperative society register
     */
    const COMPANY_TYPE_GENR = "GENR";

    /**
     * Company type: Vereinsregister – Register of associations
     */
    const COMPANY_TYPE_VERR = "VERR";

    /**
     * Company type: Luxembourg A
     */
    const COMPANY_TYPE_LUA = "LUA";

    /**
     * Company type: Luxembourg B
     */
    const COMPANY_TYPE_LUB = "LUB";

    /**
     * Company type: Luxembourg C
     */
    const COMPANY_TYPE_LUC = "LUC";

    /**
     * Company type: Luxembourg D
     */
    const COMPANY_TYPE_LUD = "LUD";

    /**
     * Company type: Luxembourg E
     */
    const COMPANY_TYPE_LUE = "LUE";

    /**
     * Company type: Luxembourg F
     */
    const COMPANY_TYPE_LUF = "LUF";

    /**
     * @var string The company name which should be no more than 100 characters long and alpha numeric characters only
     */
    private $companyName;

    /**
     * @var string The company registration should be no more than 30 characters long and alpha numeric characters only
     */
    private $companyRegistrationID;

    /**
     * @var string The company vat id should be no more than 30 characters long and alpha numeric characters only
     */
    private $companyVatID;

    /**
     * @var string The company tax id should be no more than 30 characters long and alpha numeric characters only
     */
    private $companyTaxID;

    /**
     * @var string The company register type, please see class constants what possible values are
     */
    private $companyRegisterType;

    /**
     * Set the company name
     * @param string $companyName
     * @return $this
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * Get the company name
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set the company registration id
     * @param string $companyRegistrationID
     * @return $this
     */
    public function setCompanyRegistrationID($companyRegistrationID)
    {
        $this->companyRegistrationID = $companyRegistrationID;
        return $this;
    }

    /**
     * Get the company registration id
     * @return string
     */
    public function getCompanyRegistrationID()
    {
        return $this->companyRegistrationID;
    }

    /**
     * Set the company vat id
     * @param $companyVatID
     * @return $this
     */
    public function setCompanyVatID($companyVatID)
    {
        $this->companyVatID = $companyVatID;
        return $this;
    }

    /**
     * Get the company vat id
     * @return string
     */
    public function getCompanyVatID()
    {
        return $this->companyVatID;
    }

    /**
     * Set the company Tax ID
     * @param $companyTaxID
     * @return $this
     */
    public function setCompanyTaxID($companyTaxID)
    {
        $this->companyTaxID = $companyTaxID;
        return $this;
    }

    /**
     * Get the company Vat Id
     * @return string
     */
    public function getCompanyTaxID()
    {
        return $this->companyTaxID;
    }

    /**
     * Set the company register Type
     * @param $companyRegisterType
     * @return $this
     */
    public function setCompanyRegisterType($companyRegisterType)
    {
        $this->companyRegisterType = $companyRegisterType;
        return $this;
    }

    /**
     * Return the company register type
     * @return string
     */
    public function getCompanyRegisterType()
    {
        return $this->companyRegisterType;
    }

    public function toArray()
    {
        $return = array();

        if ($this->companyName) {
            $return['companyName'] = $this->getCompanyName();
        }

        if ($this->companyRegistrationID) {
            $return['companyRegistrationID'] = $this->getCompanyRegistrationID();
        }

        if ($this->companyVatID) {
            $return['companyVatID'] = $this->getCompanyVatID();
        }

        if ($this->companyTaxID) {
            $return['companyTaxID'] = $this->getCompanyTaxID();
        }

        if ($this->companyRegisterType) {
            $return['companyRegisterType'] = $this->getCompanyRegisterType();
        }

        return $return;
    }

    /**
     * Validation data
     * @see http://www.manula.com/manuals/payco/payment-api/2.0/en/topic/json-objects
     * @return array
     */
    public function getValidationData()
    {
        $validationData = array();

        $validationData['companyName'][] = array(
            'name' => 'MaxLength',
            'value' => '100',
            'message' => "CompanyName must be between 1 and 100 characters"
        );

        $validationData['companyRegistrationID'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "CompanyRegistrationID must be between 1 and 100 characters"
        );

        $validationData['companyVatID'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "CompanyVatID must be between 1 and 100 characters"
        );

        $validationData['companyTaxID'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "CompanyTaxID must be between 1 and 100 characters"
        );

        $validationData['companyRegisterType'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validateCompanyRegisterType',
            'message' => "CompanyRegisterType must certain values or be empty"
        );

        return $validationData;
    }

    /**
     * Validate the company Register type
     * @param $value
     * @return bool
     */
    public static function validateCompanyRegisterType($value)
    {
        return Constants::validateConstant(__CLASS__, $value, 'COMPANY_TYPE');
    }
}
