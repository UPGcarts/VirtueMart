<?php
namespace Upg\Library\Request;

use Upg\Library\Request\Objects\Amount;
use Upg\Library\Request\Objects\Attributes\FileInterface;
use Upg\Library\Validation\Helper\Constants;

/**
 * Class UpdateTransaction
 * The updateTransactionData call allows the merchant to provide additional information to an existing
 * transaction - order.
 * This call gets relevant if a merchant uses the accredis dunning and collection interface where the announcement
 * of an open receivable to the collection agencies to initiate the dunning process requires detailed
 * information on the receivable.
 * @package Upg\Library\Request
 */
class UpdateTransaction extends AbstractRequest
{
    /**
     * This is the order number of the shop.
     * This id is created by the shop and is used as identifier for this transaction
     * @var string
     */
    private $orderID;

    /**
     * This is the unique reference of a capture or a partial capture (e.g. the invoice number)
     * @var string
     */
    private $captureID;

    /**
     * The invoice number
     * @var string
     */
    private $invoiceNumber;

    /**
     * the date when the in voice was created
     * @var \DateTime
     */
    private $invoiceDate;

    /**
     * the original amount of the invoice
     * @var Amount
     */
    private $originalInvoiceAmount;

    /**
     * The due date of the invoice
     * @var \DateTime
     */
    private $dueDate;

    /**
     * The original invoice as PDF
     * @var FileInterface
     */
    private $invoicePDF;

    /**
     * the sending date of the order
     * @var \DateTime
     */
    private $shippingDate;

    /**
     * The tracking ID of the sending
     * @var string
     */
    private $trackingID;

    /**
     * Set the Order ID
     * @see UpdateTransaction::orderID
     * @param string $orderID
     * @return $this
     */
    public function setOrderID($orderID)
    {
        $this->orderID = $orderID;
        return $this;
    }

    /**
     * Get the set order ID
     * @see UpdateTransaction::orderID
     * @return string
     */
    public function getOrderID()
    {
        return $this->orderID;
    }

    /**
     * Set the captureID field
     * @see UpdateTransaction::captureID
     * @param $captureID
     * @return $this
     */
    public function setCaptureID($captureID)
    {
        $this->captureID = $captureID;
        return $this;
    }

    /**
     * Get the captureID field
     * @see UpdateTransaction::captureID
     * @return string
     */
    public function getCaptureID()
    {
        return $this->captureID;
    }

    /**
     * Set the invoiceNumber field
     * @see UpdateTransaction::invoiceNumber
     * @param $invoiceNumber
     * @return $this
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this;
    }

    /**
     * Get the invoiceNumber field
     * @see UpdateTransaction::invoiceNumber
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set the invoiceDate field
     * @see UpdateTransaction::invoiceDate
     * @param \DateTime $invoiceDate
     * @return $this
     */
    public function setInvoiceDate(\DateTime $invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
        return $this;
    }

    /**
     * Get the invoiceDate field
     * @see UpdateTransaction::invoiceDate
     * @return \DateTime
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * Set the originalInvoiceAmount field
     * @see UpdateTransaction::originalInvoiceAmount
     * @param Amount $originalInvoiceAmount
     * @return $this
     */
    public function setOriginalInvoiceAmount(Amount $originalInvoiceAmount)
    {
        $this->originalInvoiceAmount = $originalInvoiceAmount;
        return $this;
    }

    /**
     * Get the originalInvoiceAmount field
     * @see UpdateTransaction::originalInvoiceAmount
     * @return Amount
     */
    public function getOriginalInvoiceAmount()
    {
        return $this->originalInvoiceAmount;
    }

    /**
     * Set the dueDate field
     * @see UpdateTransaction::dueDate
     * @param \DateTime $dueDate
     * @return $this
     */
    public function setDueDate(\DateTime $dueDate)
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * Get the dueDate field
     * @see UpdateTransaction::dueDate
     * @return \DateTime
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * Set the invoicePDF field
     * @see UpdateTransaction::invoicePDF
     * @param FileInterface $invoicePDF
     * @return $this
     */
    public function setInvoicePDF(FileInterface $invoicePDF)
    {
        $this->invoicePDF = $invoicePDF;
        return $this;
    }

    /**
     * Get the invoicePDF field
     * @see UpdateTransaction::invoicePDF
     * @return FileInterface
     */
    public function getInvoicePDF()
    {
        return $this->invoicePDF;
    }

    /**
     * Set the shippingDate field
     * @see UpdateTransaction::shippingDate
     * @param \DateTime $shippingDate
     * @return $this
     */
    public function setShippingDate(\DateTime $shippingDate)
    {
        $this->shippingDate = $shippingDate;
        return $this;
    }

    /**
     * Get the shippingDate field
     * @see UpdateTransaction::shippingDate
     * @return \DateTime
     */
    public function getShippingDate()
    {
        return $this->shippingDate;
    }

    /**
     * Set the trackingID field
     * @see UpdateTransaction::trackingID
     * @param $trackingID
     * @return $this
     */
    public function setTrackingID($trackingID)
    {
        $this->trackingID = $trackingID;
        return $this;
    }

    /**
     * Set the trackingID field
     * @see UpdateTransaction::trackingID
     * @return string
     */
    public function getTrackingID()
    {
        return $this->trackingID;
    }

    /**
     * Get the serializer data
     * @return array
     */
    public function getPreSerializerData()
    {
        $data = array(
            'orderID' => $this->getOrderID(),
            'captureID' => $this->getCaptureID(),
            'invoiceNumber' => $this->getInvoiceNumber(),
            'originalInvoiceAmount' => $this->getOriginalInvoiceAmount(),
        );

        if (!empty($this->invoiceDate)) {
            $data['invoiceDate'] = $this->getInvoiceDate()->format('Y-m-d');
        }

        if (!empty($this->dueDate)) {
            $data['dueDate'] = $this->getDueDate()->format('Y-m-d');
        }

        if (!empty($this->invoicePDF)) {
            $data['invoicePDF'] = $this->getInvoicePDF()->getFileBase64String();
        }

        if (!empty($this->shippingDate)) {
            $data['shippingDate'] = $this->getShippingDate()->format('Y-m-d');
        }

        if (!empty($this->trackingID)) {
            $data['trackingID'] = $this->getTrackingID();
        }

        return $data;
    }

    public function getClassValidationData()
    {
        $validationData = array();

        $validationData['orderID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "orderID is required"
        );

        $validationData['orderID'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "orderID must be between 1 and 30 characters"
        );

        $validationData['captureID'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "captureID is required"
        );

        $validationData['captureID'][] = array(
            'name' => 'MaxLength',
            'value' => '30',
            'message' => "captureID must be between 1 and 30 characters"
        );

        $validationData['invoiceNumber'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "invoiceNumber is required"
        );

        $validationData['invoiceNumber'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "invoiceNumber must be between 1 and 50 characters"
        );

        $validationData['invoiceDate'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "invoiceDate is required"
        );

        $validationData['originalInvoiceAmount'][] = array(
            'name' => 'required',
            'value' => null,
            'message' => "originalInvoiceAmount is required"
        );

        $validationData['trackingID'][] = array(
            'name' => 'MaxLength',
            'value' => '50',
            'message' => "trackingID must be between 1 and 50 characters"
        );

        return $validationData;
    }
}
