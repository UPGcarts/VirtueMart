<?php

namespace Upg\Library\Request\Objects;

use Upg\Library\Request\Objects\Attributes\FileInterface;
use Upg\Library\Validation\Helper\Constants;

/**
 * Class CompanyMember
 * For CompanyMember Json objects
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class CompanyMember extends AbstractObject
{

    /**
     * The contact data for the person
     * @var Person
     */
    private $contactData;

    /**
     * @var string
     */
    private $nationality;

    /**
     * The address for the company memeber
     * @var Address
     */
    private $residence;

    /**
     * @var FileInterface
     */
    private $identificationDocument;

    /**
     * Set the contact details for the company member
     * @param Person $person
     * @return $this
     */
    public function setContactData(Person $person)
    {
        $this->contactData = $person;
        return $this;
    }

    /**
     * Return the person
     * @return Person
     */
    public function getContactData()
    {
        return $this->contactData;
    }

    /**
     * String of the nationality
     * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
     * @param string $nationality
     * @return $this
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
        return $this;
    }

    /**
     * Get the set nationality
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set the residence for the company member
     * @param Address $residence
     * @return $this
     */
    public function setResidence(Address $residence)
    {
        $this->residence = $residence;
        return $this;
    }

    /**
     * Return the residence address object
     * @return Address
     */
    public function getResidence()
    {
        return $this->residence;
    }

    /**
     * Set the identification documents
     * @param FileInterface $identificationDoc
     */
    public function setIdentificationDocument(FileInterface $identificationDoc)
    {
        $this->identificationDocument = $identificationDoc;
    }

    /**
     * Get the identification document
     * @return FileInterface
     */
    public function getIdentificationDocument()
    {
        return $this->identificationDocument;
    }

    public function toArray()
    {
        $return = array(
            'contactData' => $this->getContactData(),
            'nationality' => $this->getNationality(),
            'residence' => $this->getResidence(),
        );

        if ($this->getIdentificationDocument() instanceof FileInterface) {
            $return['locale'] = $this->getIdentificationDocument()->getFileBase64String();
        }

        return $return;
    }

    public function getValidationData()
    {
        $validationData = array();

        $validationData['contactData'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "ContactData is required"
        );

        $validationData['nationality'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "Nationality is required"
        );

        $validationData['nationality'][] = array(
            'name' => 'Regex',
            'value' => '/^[A-Za-z0-9]{2}$/',
            'message' => "Nationality must be alphanumeric and two characters"
        );

        $validationData['residence'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "Residence is required"
        );

        return $validationData;
    }
}
