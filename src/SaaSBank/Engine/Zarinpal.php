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
    }

    /**
     */
    public function update ()
    {
        // XXX: maso, 1395: ایجاد یک پرداخت
    }
}