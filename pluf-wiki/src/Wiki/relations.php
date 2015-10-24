<?php
return array(
        'Wiki_Page' => array(
                'relate_to' => array(
                        'Pluf_User',
                        'Pluf_Book'
                )
        ),
        'Wiki_Book' => array(
                'relate_to' => array(
                        'Pluf_User'
                )
        )
);
