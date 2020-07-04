<?php
return array(
    0 => array(
        'Pluf_Search_Occ' => array(
            'relate_to' => array(
                0 => 'Pluf_Search_Word'
            )
        ),
        'Test_ModelRecurse' => array(
            'relate_to' => array(
                0 => 'Test_ModelRecurse'
            )
        ),
        'Test_RelatedToTestModel' => array(
            'relate_to' => array(
                0 => 'Test_Model'
            )
        ),
        'Test_RelatedToTestModel2' => array(
            'relate_to' => array(
                0 => 'Test_Model'
            )
        ),
        'Test_ManyToManyOne' => array(
            'relate_to_many' => array(
                0 => 'Test_ManyToManyTwo'
            )
        )
    ),
    1 => array(
        'relate_to' => array(
            'Pluf_Search_Word' => array(
                0 => 'Pluf_Search_Occ'
            ),
            'Test_ModelRecurse' => array(
                0 => 'Test_ModelRecurse'
            ),
            'Test_Model' => array(
                0 => 'Test_RelatedToTestModel',
                1 => 'Test_RelatedToTestModel2'
            )
        ),
        'relate_to_many' => array(
            'Test_ManyToManyTwo' => array(
                0 => 'Test_ManyToManyOne'
            )
        ),
        'foreignkey' => array(
            'Pluf_Search_Word' => array(
                0 => 'Pluf_Search_Occ'
            ),
            'Test_ModelRecurse' => array(
                0 => 'Test_ModelRecurse'
            ),
            'Test_Model' => array(
                0 => 'Test_RelatedToTestModel',
                1 => 'Test_RelatedToTestModel2'
            )
        ),
        'manytomany' => array(
            'Test_ManyToManyTwo' => array(
                0 => 'Test_ManyToManyOne'
            )
        )
    ),
    2 => array(
        'Pluf_Dispatcher::postDispatch' => array(
            0 => array(
                0 => '\\Pluf\\Logger',
                1 => 'flushHandler',
                2 => 'Pluf_Dispatcher'
            )
        )
    )
);
