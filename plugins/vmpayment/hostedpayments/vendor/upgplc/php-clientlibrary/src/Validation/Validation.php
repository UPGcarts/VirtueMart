<?php

namespace Upg\Library\Validation;

use Upg\Library\Request\Attributes\ObjectArray;
use Upg\Library\Request\RequestInterface as RequestInterface;
use Sirius\Validation\Validator as Validator;

/**
 * Class Validation
 * Main validator class that is used internally
 * @package Upg\Library\Validation
 */
class Validation implements WrapperInterface
{
    private $validator;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Set the request object to be validated
     * @param RequestInterface $request
     */
    public function getValidator(RequestInterface $request)
    {

        $validator = new Validator();
        $requestData = $request->getValidationData();

        foreach ($request->getValidationData() as $propertyName => $rules) {
            foreach ($rules as $rule) {
                $ruleName = $rule['name'];
                $ruleOptions = (empty($rule['value']) ? null : $rule['value']);
                $ruleMessage = (empty($rule['message']) ? null : $rule['message']);

                if ($ruleName == "Regex") {
                    $ruleOptions = array('pattern' => $rule['value']);
                }

                if ($ruleName == "Callback") {
                    $ruleOptions = array('callback' => $rule['value']);
                }

                $validator->add($propertyName, $ruleName, $ruleOptions, $ruleMessage);
            }
        }

        $this->validator = $validator;
        $this->request = $request;

        return $this;
    }

    /**
     * Get data from the validator
     * @return array
     */
    public function performValidation()
    {
        /**
         * check if the request has children needing validation
         **/
        $validationMessages = array();

        foreach ($this->request->toArray() as $key => $value) {
            if ($value instanceof ObjectArray) {
                /**
                 * Loop through the array element validating any children
                 * That are objects which implement RequestInterface
                 */
                foreach ($value as $pos => $arrayValue) {
                    if ($arrayValue instanceof RequestInterface) {
                        $tmpData = $this->validateChild($arrayValue);
                        $validationMessages = array_merge($validationMessages, $tmpData);
                    }
                }
            } elseif ($value instanceof RequestInterface) {
                $tmpData = $this->validateChild($value);
                $validationMessages = array_merge($validationMessages, $tmpData);
            }
        }


        if (!$this->validator->validate($this->request)) {
            $parentValidation = $this->stripSiriusMessageObjects($this->validator->getMessages(), $this->request);
            $validationMessages = array_merge($validationMessages, $parentValidation);
        }

        $objectValidation = $this->request->customValidation();
        if (!empty($objectValidation)) {
            $validationMessages = array_merge($validationMessages, $objectValidation);
        }

        return $validationMessages;
    }

    /**
     * Format the message array from the validator library
     * @param array $data
     * @param RequestInterface $request
     * @return array
     */
    private function stripSiriusMessageObjects($data, RequestInterface $request)
    {
        $formattedReturn = array();
        $className = get_class($request);

        foreach ($data as $propertyName => $messages) {
            foreach ($messages as $message) {
                /**
                 * @var \Sirius\Validation\ErrorMessage $message
                 */
                $formattedReturn[$className][$propertyName][] = $message->__toString();
            }
        }

        return $formattedReturn;

    }

    /**
     * Validate child objects in the request
     *
     * @param RequestInterface $request
     * @return array
     */
    private function validateChild(RequestInterface $request)
    {
        $validationWrapper = new Validation();
        $validationWrapper->getValidator($request);
        return $validationWrapper->performValidation();
    }
}
