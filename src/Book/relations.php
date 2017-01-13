<?php
return array(
        'Book_Page' => array(
                'relate_to' => array(
                        'Book_Book',
                        'Pluf_User'
                ),
                'relate_to_many' => array()
        ),
        'Book_Book' => array(
                'relate_to' => array(
                        'Pluf_User'
                ),
                'relate_to_many' => array()
        )
);
