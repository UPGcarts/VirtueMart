<?php

namespace Upg\Library\Request\Objects;

/**
 * Class Person
 * for person Json Object
 * @link http://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/json-objects
 * @package Upg\Library\Request\Objects
 */
class Person extends AbstractObject
{

    const SALUTATIONFEMALE = 'F';
    const SALUTATIONMALE = 'M';

    /**
     * @var string The person gender must be either M or F
     */
    private $salutation;

    /**
     * @var string The persons first name must be no more than 50 characters and an alpha numeric string
     */
    private $name;

    /**
     * @var string The persons surname must be no more than 50 characters and an alpha numeric string
     */
    private $surname;

    /**
     * @var \DateTime The date of a persons DOB
     */
    private $dateOfBirth;

    /**
     * @var string The email of the person must be no more than 100 characters
     */
    private $email;

    /**
     * @var string Persons phone number must be no more than 30 character and a numeric string
     */
    private $phoneNumber;

    /**
     * @var string Personds fax number must be more than 30 character and a numeric string
     */
    private $faxNumber;

    /**
     * Set the salutation
     * @param $salutation
     * @return $this
     */
    public function setSalutation($salutation)
    {
        $this->salutation = $salutation;
        return $this;
    }

    /**
     * Return the salutation
     * @return string
     */
    public function getSalutation()
    {
        return $this->salutation;
    }

    /**
     * Set the name
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Return the name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the surname
     * @param string $surname
     * @return $this
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Return the surname
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the DOB
     * @param \DateTime $date
     * @return $this
     */
    public function setDateOfBirth(\DateTime $date)
    {
        $this->dateOfBirth = $date;
        return $this;
    }

    /**
     * Return the DOB
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Set the email address
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Return the email address
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the phone number
     * @param $phoneNumber
     * @return $this
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = preg_replace("/[^0-9]/", '', $phoneNumber);
        return $this;
    }

    /**
     * Get the phone number
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set the fax number
     * @param $faxNumber
     * @return $this
     */
    public function setFaxNumber($faxNumber)
    {
        $this->faxNumber = preg_replace("/[^0-9]/", '', $faxNumber);
        return $this;
    }

    /**
     * Return the fax number
     * @return string
     */
    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    /**
     * Return the array for validation
     * @return array
     */
    public function toArray()
    {
        $return = array(
            'salutation' => $this->getSalutation(),
            'name' => $this->getName(),
            'surname' => $this->getSurname(),
            'email' => $this->getEmail(),
        );

        if ($this->dateOfBirth) {
            $return['dateOfBirth'] = $this->getDateOfBirth()->format('Y-m-d');
        }

        if ($this->phoneNumber) {
            $return['phoneNumber'] = $this->getPhoneNumber();
        }

        if ($this->faxNumber) {
            $return['faxNumber'] = $this->getFaxNumber();
        }

        return $return;
    }

    /**
     * @return array
     */
    public function getValidationData()
    {
        $validationData = array();

        $validationData['salutation'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "Salutation is required"
        );

        $validationData['salutation'][] = array(
            'name' => 'Callback',
            'value' => get_class($this) . '::validateSalutation',
            'message' => "Salutation must be M or F"
        );

        $validationData['name'][] = array('name' => 'required', 'value' => null, 'message' => "Name is required");
        $validationData['name'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "Name must be less than 50 characters"
        );

        $validationData['surname'][] = array('name' => 'required', 'value' => null, 'message' => "Surname is required");
        $validationData['surname'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "Surname must be less than 50 characters"
        );

        $validationData['email'][] = array('name' => 'required', 'value' => null, 'message' => "Email is required");
        $validationData['email'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "Email must be less than 50 characters"
        );

        $validationData['phoneNumber'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "Phone Number must be less than 30 characters"
        );

        $validationData['faxNumber'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "Fax Number must be less than 30 characters"
        );

        return $validationData;
    }

    /**
     * Used to validate the salutation
     * @param string $value Which must be a single caracter string with M or F in it
     * @return bool
     */
    public static function validateSalutation($value)
    {
        return ($value == static::SALUTATIONMALE || $value == static::SALUTATIONFEMALE);
    }
}
