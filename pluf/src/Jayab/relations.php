<?php
return array (
		'Jayab_Vote' => array (
				'relate_to' => array (
						'Pluf_User',
						'Jayab_Location' 
				) 
		),
		'Jayab_Location' => array (
				'relate_to_many' => array (
						'KM_Label',
						'KM_Category',
				        'Jayab_Tag'
				) 
		) 
);