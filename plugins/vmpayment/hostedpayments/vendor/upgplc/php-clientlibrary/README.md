# UPG Client Library for PHP #

[![Build Status](https://travis-ci.org/UPGcarts/clientlibrary.svg?branch=master)](https://travis-ci.org/UPGcarts/clientlibrary)
[![Codacy Badge](https://api.codacy.com/project/badge/grade/7710d14f3d9f4f54bc4cb1892f19cce3)](https://www.codacy.com/app/christine-jamieson/clientlibrary)

PHP Client Library for the UPG API.
Based on the API Documentation found here: https://www.manula.com/manuals/payco/payment-api/hostedpagesdraft/en/topic/introduction

## Current Issues ##
Currently there are issues with some of the calls these are:
 * CreateTransaction : The url field was renamed from the API documentation
 * UpdateTransaction : Currently Incomplete

## Using the Library ##

### Config Object ###
The API requires the configuration to work correctly.
All classes and methods that require cofiguration can be passed a populated instance of
Upg\Library\Config.

The config object should be fully populated at instantiation by providing an associative array.
```php
$configData = array('merchantID' => 1, 'storeID' => 1);
$config = new Upg\Library\Config($configData);
```
The fields for the config that must be provided are:

 * ['merchantPassword'] *string* This is the merchant password for mac calculation
 * ['merchantID'] *string* This is the merchantID assigned by UPG.
 * ['storeID'] *string* This is the store ID of a merchant.
 * ['logEnabled'] *bool* Should logging be enabled
 * ['logLevel'] *int* Log level See class constants for possible values
 * ['logLocationMain'] *string* Main log location file path
 * ['logLocationRequest'] *string* Log location file path for API requests
 * ['defaultRiskClass'] *string* Default risk class
 * ['defaultLocale'] *string* Default locale (see [Supported Languages](http://documentation.upgplc.com/hostedpagesdraft/en/topic/supported-languages))
 * ['sendRequestsWithSalt'] *bool* Automatically add salt to requests. In live this should be set to true and not false. However, for testing this can be false. By default this will be true if not specified.
 * ['baseUrl'] *string* Base URL of requests that should contain either https://sandbox.upgplc.com/2.0 or https://www.pay-co.net/2.0

#### Log Levels
When referencing log levels be sure to use the Psr\Log\LogLevel static constants
E.g. `\Psr\Log\LogLevel::ALERT`

### Starting an API request ###
The Library for requests is split in three parts:
**Upg\Library\Request** contains the request classes.
**Upg\Library\Request\Objects** contains classes for the JSON objects that are documented in the API docs.
If a request has a property that requires a JSON object please pass in the appropriately populated **Upg\Library\Request\Objects** class for that property.

All properties in the request and JSON objects have getters and setters. For example, to set a field called userType on
the request or object you would call `setUserType` and to get it you would call `getUserType`.

#### Notes on Date fields ####
Any field in the Requests and JSON Objects that requires a Date should be passed a PHP DateTime object - even if the field only requires month and year. For fields that require only a month and year such as the validity of a payment instrument, please look at [DateTime::setDate](http://php.net/manual/en/datetime.setdate.php).
Simply running the code like this would give you a DateTime object to populate the field
```php
$expiryMonth = 2
$expiryYear = 2020
$date = new \DateTime();
$date->setDate($expiryYear, $expiryMonth, 1);
```
The serializer will serialize the date to a correctly formatted string for the request.

#### Notes on Amount Fields ####
Any field that requires a JSON amount fields should be provided the **Upg\Library\Request\Objects\Amount** object.
This object has three properties:

 * amount: Full amount (subtotal+tax) in the lowest currency unit.
 * vatAmount: The amount of VAT if available in the lowest currency unit
 * vatRate: If a vatAmount is provided please provide details of the VAT rate up to 2 decimal places.

All amount values must be in the lowest currency unit (i.e. Cents, Pence, etc). So for example a 10 Euro transaction with 20% VAT would need to be populated:

 * amount: 1200
 * vatAmount: 200
 * vatRate: 20

#### Sending the request ####

Once you have populated a request object with the appropriate values simply instantiate an instance of a **Upg\Library\Api**
class for the appropriate method call. Pass in a config object and the request you want to send. Then, calling the
`sendRequest()` method will send the response. Raise any exception or provide a success response.

The exceptions which can be raised are in **Upg\Library\Api\Exception**. For any parsed responses you will have access to
an **Upg\Library\Response\SuccessResponse** or **Upg\Library\Response\FailureResponse** object. The success object is returned if no exception is thrown.
The failure object is returned in **Upg\Library\Api\Exception\ApiError** exception.

The response object has two types of properties:
Fixed properties such as the resultCode which are in every request, and Dynamic properties such as allowedPaymentMethods which are not in every request. To access a property that is Fixed or Dynamic, simply use the following:
```php
$response->getData('resultCode');
$response->getData('allowedPaymentMethods');
```

If any response contains JSON objects or an array of objects then the library will attempt to serialize them into **Upg\Library\Request\Objects** classes.
The properties names in responses that will be serialized are as follows:
 * allowedPaymentInstruments, paymentInstruments : Array of Upg\Library\Request\Objects\PaymentInstrument
 * billingAddress, shippingAddress : Upg\Library\Request\Objects\Address
 * amount : Upg\Library\Request\Objects\Amount
 * companyData : Upg\Library\Request\Objects\Company
 * paymentInstrument : Upg\Library\Request\Objects\PaymentInstrument
 * userData : Upg\Library\Request\Objects\Person

For example the response on the getUser API call contains the following properties that will be serialized to the following objects
 * companyData field would be an Upg\Library\Request\Objects\Company object
 * userData field would be an Upg\Library\Request\Objects\Person object
 * billingAddress, shippingAddress would be Upg\Library\Request\Objects\Address objects

The MAC validation/calculation for requests and responses is handled by the library and if these fail an exception will be raised.

All variables that are not ISO values are defined in classes as constants for you to use in the request.
For possible fixed field values please see the following constants:

 * locale: Upg\Library\Locale\Codes
 * riskClasses: Upg\Library\Risk\RiskClass
 * userType: Upg\Library\User\Type
 * salutation: Upg\Library\Request\Objects\Person::SALUTATIONFEMALE Upg\Library\Request\Objects\Person::SALUTATIONMALE
 * companyRegisterType: Upg\Library\Request\Objects\Company
 * paymentInstrumentType: Upg\Library\Request\Objects\PaymentInstrument
 * issuer: Upg\Library\Request\Objects\PaymentInstrument

The library implements stubs for all the methods except for registerMerchant as at this time UPG is restricting this to authorised parties only.

### Handling Callback ###

For the reserve API call you may be provided a callback from the API as a POST/GET request. For this the client library implements a helper for you to use: **Upg\Library\Callback\Handler**.

This takes in the following for the constructor:
 * $config: The config object for the integration
 * $data: The data from the post\get variables which should be an associated array containing contain the following:
   * merchantID
   * storeID
   * orderID
   * resultCode
   * merchantReference : Optional field
   * message : Optional field
   * salt
   * mac
   * $processor: An instance of an object that implements the **Upg\Library\Callback\ProcessorInterface** interface, which the method will invoke after validation

The processor should implement two methods:
`sendData` which the handler uses to pass data to the processor to use and another method called `run`, which will get invoked to handle call back processing.
This processor should return a string which contains a URL where the user should be redirected to after UPG has processed the transaction.

To run the handler simply call the `run` method on the object. Please note the following exceptions can be raised in which case the store must still send a URL, but respond with a non 200 HTTP result code to indicate there has been an issue. The following exceptions may be raised:
 * Upg\Library\Callback\Exception\ParamNotProvided : If a required parameter is not provided
 * Upg\Library\Callback\Exception\MacValidation : If there is a MAC validation issue with the callback parameters

### Handling MNS notifications ###

For the MNS notification the API provides a helper class to validate MNS notification. This class is **Upg\Library\Mns\Handler**. It takes the following as a constructor:

  * $config: The config object for the integration
  * $data: The data from the post\get variables which should be an associated array of the MNS callback
  * $processor: An instance of an object which implements the Upg\Library\Mns\ProcessorInterface interface which the method will invoke after validation.

The processor object should implement `sendData` to get data from the handler and a `run` method which executes your callback after successful validation.

The processor callback should avoid processing the request, instead it should save it to a database for asynchronous processing via a cron script.

Please note the MNS call must always return a 200 response to UPG otherwise no other MNS would be sent until a given MNS notification is accepted with a HTTP 200 response.

### Working with PayCoBridge.js ###
Please note this plugin does not provide any javascript libraries for the paybridge. Integrations using paybridge are expected to implement the javascript library. However, this library can be used to implement the server side functionality for any paybridge integrations, using PHP on the backend.

If you use the handler class to save a MNS to the database for later processing you can assume the MNS is perfectly valid with out checking the MAC.

## Working on the plugin ##

If you want to contribute o the library, please note that all code should be written according to the **PSR2 standard**.
This is very easy to set up in PHPStorm by using PHP-Codesniffer. To configure PHP-Codesniffer for PHPStorm follow these steps:

1. Run the following.
    ``` sh
    composer global require 'squizlabs/php_codesniffer=*'
    ```
2. In PHPStorm open the settings dialog and navigate to Languages & Framework -> PHP -> Code Sniffer
3. On configuration option click the ... button and in that prompt point PHPStorm to the path of Code Sniffer
4. To set the code style navigate to Editor -> Code Style -> PHP
5. In the setting click on Set From and go to Predefined Style -> PSR1/PSR2
6. Click on the OK button