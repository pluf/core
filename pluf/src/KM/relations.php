<?php
return array(
        'KM_Label' => array(
                'relate_to' => array(
                        'Pluf_User'
                )
        ),
        'KM_Category' => array(
                'relate_to' => array(
                        'Pluf_User',
                        'KM_Category'
                )
        )
);
