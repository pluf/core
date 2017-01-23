<?php
return array(
        'SaaSKM_TagRow' => array(
                'relate_to' => array(
                        'SaaSKM_Tag'
                )
        ),
        'SaaSKM_Tag' => array(
                'relate_to' => array(
                        'Pluf_Tenant'
                )
        ),
        'SaaSKM_Vote' => array(
                'relate_to' => array(
                        'Pluf_Tenant',
                        'Pluf_User'
                )
        )
);
