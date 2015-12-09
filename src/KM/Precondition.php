<?php

class KM_Precondition
{

    static public function fake ($request)
    {
        throw new Pluf_Exception('fake');
    }
}