# Installation

 1. Copy the contents of the plugins folder to <root virtuemart directory>/plugins/
 2. Log in to the admin backend and go to Extension -> Discover
 3. Click on the discover button and then select the hosted payment modules for installation
 4. Copy files in vmpayment/hostedpayments/language and vmcustom/riskclassproduct/language over to <root virtuemart directory>/administrator/<lanaguge>/ and to <root virtuemart directory>/<lanaguge>/
 5. Go to VirtueMart -> Payment Methods
 6. Click on the 'New' button to create a new payment method. Select 'Hosted Payment' for 'Payment Method' in the drop down menu and then click on 'Save'.
 7. Go back in the saved payment method and click on the configuration tab
 8. Fill in the appropriate details. See the configuration part of this documentation for further details.
 9. Provide your payment provider with the 'Callback URL' and 'MNS URL' shown in the 'Callback URLs' section
 10. Once your payment provider has confirmed that the URLs have been configured simply publish the method
 11. Set up a cronjob to call the Cron MNS URL in a regular interval. Calling it every five minutes is recommended.
 