# Usage

The workflow of payments is as follows if autocapture **is not** enabled:

* Customer completes a purchase in the shop frontend and therefore creates an order
* Log into the administration backend and capture the total amount of products that will be shipped for this order. This amount may be smaller or equal to the initial order amount. Captures should be made on the same day the products are shipped, as they tell your payment provider at which date you claim the money. This is important for dunning procedures in case of bill/invoice payments.
* In case of cash in advance payments you can only capture an amount after the payment of the customer was confirmed by your payment provider
* If you only capture a part of the initial amount, you have to finish the transaction. This will automatically issue a refund for the remaining uncaptured amount to the customer.
* If you captured the full amount the order will be completed. A "PAID" notification will usually follow about 1-2 minutes after the full amount has been captured, which will result in a status change of the order.
* In case of direct debit and bill/invoice payments, the order will not immediately be paid after the capture, as the customer still has to pay the bill or the amount has to be debited from his bankaccount. When the money arrives at your payment provider, the shop will be notified resulting in a status change of the order.

If autocapture **is** enabled, the system of your payment provider will immediately capture the full amount of the order.
This should not be done when using the payment method bill/invoice, as the capture tells the payment provider at which point the products are shipped and therefore he will assume that they were shipped immediately. Therefore unless you ship the products on either the same or the next day as the order was created when offering bill payments **you should not enable autocapture**.

## Manual Capture

To capture transactions which are not set to autocapture or can not be auto-captured please follow these steps:
* Log into the administration backend and go to the order
* Scroll to the bottom of the order view below the 'Payment Method'
* Find the 'Amount to capture' box, enter an amount and then click the 'Submit capture' button to perform the capture

## Refunds

To issue a refund, at least one capture has to exist for an order.
If you issue a refund, the given amount will be transferred back to the customer by your payment provider, provided that there is money that can be refunded.
In case of bill/invoice payments you may also issue an refund, even if the customer has not paid anything yet, to reduce the amount of the capture.

To refund a order paid with our module please follow these steps.
* Log into the administration backend and go to the order
* Scroll to the bottom of the order view
* Under the capture you will see the refund section
* For the Capture Reference select a capture, enter a description for the refund then enter refund amount that is equal to or less than the amount captured
* Click the 'Submit refund' button to start the refund

## Cancelling a transaction

Transactions can only be cancelled if no capture has been made yet. If you cancel a transaction you forfeit any claim on money from the customer.

If autocapture is not enabled and you have not done any capture yet you are able to send through the cancel call. To do this follow the following steps:

* Log into the administration backend and go to the order
* Scroll to the bottom of the order view
* Select the action 'Cancel' in the select box labelled 'Please select an action'
* Then click on 'Submit API request'

## Finish a transaction

To finish a transaction at least one capture has to exist for the order. If you finish a transaction you tell the payment provider that you are satisfied with whatever amount you captured and that the order can be handled as if it was paid completely.
This is important in case you capture only a part of the initial amount, so that the remaining uncaptured amount can be refunded to the customer.

If autocapture is not enabled and you have done at least one capture but do not need to capture any more you are able to return the reserved amount that has not been captured.

* Log in to the administration backend and go to the order
* Scroll to the bottom of the order view
* Select the action 'Finish' in the select box labelled 'Please select an action'
* Then click on 'Submit API request'