<?php
return array(
        'SaaSKM_TagRow' => array(
                'relate_to' => array(
                        'SaaSKM_Tag'
                )
        ),
        'SaaSKM_Tag' => array(
                'relate_to' => array(
                        'SaaS_Application'
                )
        ),
        'SaaSKM_Vote' => array(
                'relate_to' => array(
                        'SaaS_Application',
                        'Pluf_User'
                )
        )
);
