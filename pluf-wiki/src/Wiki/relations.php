<?php
return array(
        'Wiki_Page' => array(
                'relate_to' => array(
                        'Pluf_User',
                        'Pluf_Book',
                        'KM_Label',
                        'KM_Category',
                        'KM_Label'
                )
        ),
        'Wiki_Book' => array(
                'relate_to' => array(
                        'Pluf_User',
                        'KM_Label',
                        'KM_Category',
                        'KM_Label'
                )
        )
);
