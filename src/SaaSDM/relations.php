<?php
return array (
		'SaaSDM_Link' => array (
				'relate_to' => array (
						'SaaS_Application',
						'SaaSDM_Asset',
						'Pluf_User'
				) 
		),
		'SaaSDM_Asset' => array (
				'relate_to' => array (
						'SaaS_Application' 
				) 
		),
		'SaaSDM_Plan' => array (
				'relate_to' => array (
						'Pluf_User',
						'SaaS_Application'
				) 
		) 
)
;
