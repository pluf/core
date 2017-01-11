<?php
return array(
        'Wiki_Page' => array(
                'relate_to' => array(
                        'Wiki_Book',
                        'Pluf_User',
                        'Pluf_Tenant'
                ),
                'relate_to_many' => array(
                        'KM_Label',
                        'KM_Category'
                )
        ),
        'Wiki_Book' => array(
                'relate_to' => array(
                        'Pluf_User',
                        'Pluf_Tenant'
                ),
                'relate_to_many' => array(
                        'KM_Label',
                        'KM_Category'
                )
        )
);
