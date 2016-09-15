<?php

/**
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Engine_Zarinpal extends SaaSBank_Engine
{

    const MerchantID = 'MerchantID';

    var $client = false;

    /*
     *
     */
    public function getTitle ()
    {
        return 'Zarin Pal';
    }

    /*
     *
     */
    public function getDescription ()
    {
        return 'Zarin Pal Payment Service';
    }

    /*
     *
     */
    public function getExtraParam ()
    {
        return array(
                array(
                        'name' => 'MerchantID',
                        'type' => 'String',
                        'unit' => 'none',
                        'title' => 'Merchant ID',
                        'description' => 'MerchantID',
                        'editable' => true,
                        'visible' => true,
                        'priority' => 5,
                        'symbol' => 'id',
                        'defaultValue' => 'no title',
                        'validators' => [
                                'NotNull',
                                'NotEmpty'
                        ]
                )
        );
    }

    /*
     */
    public function create ($receipt)
    {
        $backend = $receipt->get_backend();
        $MerchantID = $backend->get(SaaSBank_Engine_Zarinpal::MerchantID);
        $Authority = $receipt->getMeta('Authority', null);
        // Check
        Pluf_Assert::assertNull($Authority, 'Receipt is created before');
        Pluf_Assert::assertNotNull($backend, 'Bakend is empty');
        Pluf_Assert::assertNotNull($MerchantID, 'MerchantID is not defined');
        
        if (Pluf::f('saas_bank_debug', false)) {
            $receipt->setMeta('Authority', 'back engine is in debug mode');
            $receipt->callURL = 'https://www.zarinpal.com/pg/StartPay/example';
            return;
        }
        
        // maso, 1395: ایجاد یک پرداخت
        $client = $this->getClient();
        $result = $client->PaymentRequest(
                [
                        'MerchantID' => $MerchantID,
                        'Amount' => $receipt->amount,
                        'Description' => $receipt->description,
                        'Email' => $receipt->email,
                        'Mobile' => $receipt->phone,
                        'CallbackURL' => $receipt->callbackURL
                ]);
        
        // Redirect to URL You can do it also by creating a form
        if ($result->Status != 100) {
            throw new SaaSBank_Exception_Engine('fail to create payment');
        }
        $receipt->putMeta('Authority', $result->Authority);
        $receipt->callUrl = 'https://www.zarinpal.com/pg/StartPay/' .
                 $result->Authority;
    }

    /**
     */
    public function update ($receipt)
    {
        $backend = $receipt->get_backend();
        $MerchantID = $backend->get(SaaSBank_Engine_Zarinpal::MerchantID);
        $Authority = $receipt->getMeta('Authority', null);
        // Check
        Pluf_Assert::assertNotNull($Authority, 'Receipt is created before');
        Pluf_Assert::assertNotNull($backend, 'Bakend is empty');
        Pluf_Assert::assertNotNull($MerchantID, 'MerchantID is not defined');
        
        if (Pluf::f('saas_bank_debug', false)) {
            $receipt->payRef = 'back engine is in debug mode';
            return true;
        }
        
        // maso, 1395: تایید یک پرداخت
        // $wsdlCheck = 'Location: https://sandbox.zarinpal.com/pg/StartPay/'
        // .$result->Authority
        $client = $this->getClient();
        $result = $client->PaymentVerification(
                [
                        'MerchantID' => $MerchantID,
                        'Authority' => $Authority,
                        'Amount' => $receipt->amount
                ]);
        if ($result->Status == 100) {
            throw new SaaSBank_Exception_Engine('fail to check payment');
        }
        $receipt->payRef = $result->RefID;
        return true;
    }

    private function getClient ()
    {
        if ($this->client) {
            return $this->client;
        }
        $wsdl = 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl';
        $this->client = new SoapClient($wsdl, 
                array(
                        'encoding' => 'UTF-8',
                        'soap_version' => SOAP_1_1,
                        'trace' => 0,
                        'exceptions' => 1,
                        'connection_timeout' => 180
                ));
        return $this->client;
    }
}