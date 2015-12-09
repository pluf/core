<?php

/**
 * For each model having a 'foreignkey' or a 'manytomany' colum, details
 * must be added here.
 * These details are used to generated the methods
 * to retrieve related models from each model.
 */
$user_model = Pluf::f('pluf_custom_user', 'Pluf_User');
$group_model = Pluf::f('pluf_custom_group', 'Pluf_Group');

return array(
        $user_model => array(
                'relate_to_many' => array(
                        $group_model,
                        'Pluf_Permission'
                )
        ),
        $group_model => array(
                'relate_to_many' => array(
                        'Pluf_Permission'
                )
        ),
        'Pluf_Message' => array(
                'relate_to' => array(
                        $user_model
                )
        ),
        'Pluf_RowPermission' => array(
                'relate_to' => array(
                        'Pluf_Permission'
                )
        ),
        'Pluf_Search_Occ' => array(
                'relate_to' => array(
                        'Pluf_Search_Word'
                )
        )
);
