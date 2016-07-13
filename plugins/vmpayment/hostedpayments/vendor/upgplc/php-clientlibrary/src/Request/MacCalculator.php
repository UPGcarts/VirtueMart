<?php


namespace Upg\Library\Request;

use Upg\Library\Mac\AbstractCalculator;
use Upg\Library\Serializer\SerializerFactory;

/**
 * Class MacCalculator
 * Mac calculator for the request objects
 * @package Upg\Library\Request
 */
class MacCalculator extends AbstractCalculator
{
    /**
     * Instance of the serializer for calculation
     * @var \Upg\Library\Serializer\Serializer
     */
    private $serializer;

    public function __construct()
    {
        $this->serializer = SerializerFactory::getSerializer();
    }

    /**
     * Set request that MAC needs to be calculated on
     * @param AbstractRequest $request
     * @return $this
     * @throws \Upg\Library\Serializer\Exception\VisitorCouldNotBeFound
     */
    public function setRequest(AbstractRequest $request)
    {
        $data = array();
        /**Serialize the any sub objects**/
        foreach ($request->getSerializerData() as $key => $value) {
            if ($this->needsToBeSerialized($value)) {
                $data[$key] = $this->serializer->serialize($value);
            } else {
                $data[$key] = $value;
            }
        }

        $this->setCalculationArray($data);

        return $this;
    }

    /**
     * Check if value needs to be serialized
     * @param $value
     * @return bool
     */
    private function needsToBeSerialized($value)
    {
        return $value instanceof RequestInterface;
    }
}
