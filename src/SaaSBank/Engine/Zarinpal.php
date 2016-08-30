<?php

/**
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class SaaSBank_Engine_Zarinpal extends SaaSBank_Engine
{

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

    /**
     */
    public function create ()
    {
        // XXX: maso, 1395: ایجاد یک پرداخت
        $MerchantID = 'test'; // Required
        $Amount = 1000; // Amount will be based on Toman - Required
        $Description = 'توضیحات تراکنش تستی'; // Required
        $Email = 'UserEmail@Mail.Com'; // Optional
        $Mobile = '09123456789'; // Optional
        $CallbackURL = 'http://www.yoursoteaddress.ir/verify.php'; // Required
        
        $client = new SoapClient(
                'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl', 
                array(
                        'encoding' => 'UTF-8','verifypeer' => false, 'verifyhost' => false,
                ));
        
        $result = $client->PaymentRequest(
                [
                        'MerchantID' => $MerchantID,
                        'Amount' => $Amount,
                        'Description' => $Description,
                        'Email' => $Email,
                        'Mobile' => $Mobile,
                        'CallbackURL' => $CallbackURL
                ]);
        
        // Redirect to URL You can do it also by creating a form
        if ($result->Status == 100) {
            Header(
                    'Location: https://sandbox.zarinpal.com/pg/StartPay/' .
                             $result->Authority);
        } else {
            echo 'ERR: ' . $result->Status;
        }
    }

    /**
     */
    public function update ()
    {
        // XXX: maso, 1395: ایجاد یک پرداخت
    }
}