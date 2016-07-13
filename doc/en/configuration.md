# Configuration

The configuration is split into several sections, which are as follows:

* Hosted Payment configuration : Main plug-in configuration
* Order Statuses : Define the order status when the creation of an order failed/succeeded
* MNS status notifications : Define what happens when a transaction status change notification arrives.
* Callback URLs : Displays Callback URL, MNS URL and MNS Cron URL.
* Restriction: Restrict who the payment method is offered to

## Details to send to Payment Gateway Provider

Your gateway provider needs to configure 2 URLs in their system to completely set up your account.
Callback URL: This URL will be called to inform the shop about successful payments and payment selections in the checkout process. This URL is vital for the payment process.
MNS URL: This URL will be called for asynchronous status updates for orders.
Please provide these two URLs displayed at 'Callback URLs' to your payment provider. Otherwise the payment process will fail.

### Hosted Payment configuration

* Merchant ID : The Merchant ID provided by your payment provider
* Password : Password provided by your payment provider
* Store ID: Store ID provided to you by your payment provider. You will receive one Store ID for each currency supported by your shop. Please see section 'Multi currency support' for more details.
* Log Location : Absolute path to log data of the plug-in for debug purposes
* Log Level : Log level for the error log
* Integration Mode : Select whether you want to use the sandbox/test environment or the live environment.
* Risk Class : If not specified for users and/or products, your payment provider will use this risk class by default for all transactions.
	o	Trusted Risk Class: No solvency checks will be done. The customer will always be able to select every payment method.
	o	Default Risk Class: Solvency checks will be executed, depending on your contract with UPG. Depending on the outcome the customer may be classified as high risk user and will only be able to use secure payment methods.
	o	High Risk Class: All customers are treated as high risk users by default and will only be able to use secure payment methods.
	It is recommended to use 'Default Risk Class'.
* Enable Autocapture : Select whether transactions should be captured automatically.
  o	Autocapture disabled: Each payment by customers has to be captured manually. This is usually done when the order is getting shipped. This is important for certain payment methods that require UPG to know when exactly the products were shipped. Dunning procedures depend on this for example.
  o	Autocapture enabled: Each payment by customers will be captured automatically by UPG as soon as the funds are available.
* Default Locale : The module will attempt to use the language of the store the customer is currently in. If this language is not supported it will use the language configured here.


### Order Statuses

* Here you define what your order status will be set to right after a successful or failed purchase.

### MNS status notifications

You can define to what status an order will be set to after receiving certain status notifications from your payment provider.
Notifications can contain both an order status and a transaction status:
Order status: This is the status of an 'order', which is basically a payment created by doing a capture. This means an order status can only be set in transaction status INPROGRESS and DONE, as a capture had to be executed before.
Transaction status: Status of the whole transaction. A transaction can contain multiple 'orders', which are created by doing captures.
For an explanation of each order/transaction status, see below.

* MNS PAID : Order status: You will receive this notification, if a capture was paid completely. Keep in mind that an order in your shop can have multiple captures, meaning this status notification does not necessarily mean the whole order is paid completely.
* MNS PAYPENDING : Order status: You will receive this notification, if a partial payment for a capture was made.
* MNS PAYMENTFAILED : Order status: You will receive this notification, if the payment for a capture failed.
* MNS CHARGEBACK : Order status: You will receive this notification, if the customer issued a chargeback on a payment made by him.
* MNS CLEARED : Order status: You will receive this notification, if the payment for a capture is cleared. This basically means it will show up in your next clearing file.
* MNS INDUNNING : Order status: You will receive this notification, if the customer did not pay his bill in time and the dunning process has started.
* MNS ACKNOWLEDGEPENDING : Transaction status: You will receive this notification, if a transaction was successfully started. If the order in your shop stays in this status with no follow-up notification, it is advised to contact your payment provider, as their system was unable to reach your shop in the checkout process.
* MNS FRAUDPENDING : Transaction status: You will receive this notification, if a transaction was created and our system recognises it to be a potential fraud case. Your payment provider will manually check this transaction.
* MNS FRAUDCANCELLED : Transaction status: You will receive this notification, if a transaction was cancelled due to fraud.
* MNS CIAPENDING : Transaction status: You will receive this notification, if your payment provider is waiting for the customers money to arrive on their bank account. This only applies to cash in advance payments.
* MNS CANCELLED / EXPIRED : Transaction status: You will one of these notifications, if the transaction was either manually cancelled by the merchant, or the transaction expired. A transaction can expire, when either the customer does not finish his payment selection, or the transaction stays in status 'ACKNOWLEDGEPENDING' for a certain amount of time.
* MNS MERCHANTPENDING : Transaction status: You will receive this notification, if your payment provider is waiting for you to create a capture for this transaction.
* MNS INPROGRESS : Transaction status: You will receive this notification, if a payment by the customer arrived, but not all captures of the transaction are completely paid.
* MNS DONE : Transaction status: You will receive this notification, if all captures of the transaction are completely paid.

### Callback URLs

This section is for display and information purposes

### Restriction

* Countries : Define for which countries this payment method is available
* Currency Restriction : Define for which currencies this payment method is available. Usually the store ID contains the currency code for which this payment method should be restricted to.
* Minimum Amount and Maximum Amount : Define for which basket amounts this payment method ia available.

## Multi currency support

To support multiple currencies you will need to set up multiple payment methods.
Each payment method should then be restricted to the currency support by its configured Store ID. This the configuration of each payment method should be identical except for the Store ID and currency restriction.

## B2B
Enable or disable the transfer of business related data to your gateway provider. This data is used in solvency checks if agreed upon in your contract. You can display these additional fields for the billing address in the checkout process.

The fields to collect business relevant data are not enabled by default. The fields are as follows:
 * upg_company_registration_id
 * upg_company_vat_id
 * upg_company_tax_id
 * upg_company_registration_type

The field name can be set by the merchant however the options must be left in place.
 
To enable the transfer of business data at least the field upg_company_registration_id must be published.
To do this go to VirtueMart -> Configuration -> Shopper Fields and then publish upg_company_registration_id and make sure that 'Show in account maintenance' is enabled as well.
This will display the field in the customer registration and the checkout process. If a customer provides both company name and registration ID he will be treated as a business customer by your payment provider.

## Product Risk Class

You can associate specific products with a risk class different to the default risk class defined in the payment method configuration.
To do so please follow these steps:

 1. The plug-in 'Hosted Payment Riskclass Product Field' must be enabled. You can find it at Extensions -> Plugins.
 2. Go to Components -> VirtueMart -> Products -> Custom Fields
 3. Create a new field ensuring the following settings have the following values:
    * Custom Field Type : Plug-ins
    * Cart Attribute : Yes
    * Admin only : Yes
    * Additional Parameters : Hosted Payment Riskclass Product Field
 4. Save and publish that field
 5. Go to Components -> VirtueMart -> Products, select a product and open the tab 'Custom Fields'
 6. Open
 6. At 'Custom Field Type' select your newly created field from the drop down menu
 7. This will create a field in which you can select the risk class for this product
 8. Save the product
 
## Customer Risk Class

You can assign specific customer groups different risk classes.
This allows you to mark customers as 'Trusted', meaning they will be able to use all available payment methods, independent of the product risk class.
You may also mark customers as high risk customers, allowing them to only pay with secure payment methods by default.

Please ensure the module overrides are in place by doing the following.

  1. Copy the folder 'administrator' and its contents from the plug-in files into the VirtueMart root directory
  2. Go to Components -> VirtueMart -> Orders & Shoppers -> Shopper Groups
  3. Select a customer group
  4. Select a risk class from the drop down menu
  5. Save the customer group
  