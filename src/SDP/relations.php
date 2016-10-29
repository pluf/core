<?php
return array (
		'SDP_Link' => array (
				'relate_to' => array (
						'SaaS_Application',
						'SDP_Asset',
						'Pluf_User'
				) 
		),
		'SDP_Asset' => array (
				'relate_to' => array (
						'SaaS_Application' 
				) 
		),
		'SDP_Plan' => array (
				'relate_to' => array (
						'Pluf_User',
						'SaaS_Application'
				) 
		) 
)
;
