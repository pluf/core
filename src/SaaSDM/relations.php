<?php
return array (
		'SaaSDM_Link' => array (
				'relate_to' => array (
						'Pluf_Tenant',
						'SaaSDM_Asset',
						'Pluf_User'
				) 
		),
		'SaaSDM_Asset' => array (
				'relate_to' => array (
						'Pluf_Tenant' 
				) 
		),
		'SaaSDM_Plan' => array (
				'relate_to' => array (
						'Pluf_User',
						'Pluf_Tenant'
				) 
		) 
)
;
