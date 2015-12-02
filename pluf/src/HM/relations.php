<?php
return array (
		'HM_Message' => array (
				'relate_to' => array (
						'SaaS_Application' 
				) 
		),
		'HM_Part' => array (
				'relate_to' => array (
						'SaaS_Application' 
				) 
		),
		'HM_Payment' => array (
				'relate_to' => array (
						'HM_Part' 
				),
				'relate_to_many' => array (
						'Bank_Receipt'
				)
		),
);
