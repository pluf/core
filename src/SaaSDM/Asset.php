<?php
class SaaSDM_Asset extends Pluf_Model {
	
	/**
	 * @brief مدل داده‌ای را بارگذاری می‌کند.
	 *
	 * @see Pluf_Model::init()
	 */
	function init() {
		$this->_a ['table'] = 'saasdm_asset';
		$this->_a ['model'] = 'SaaSDM_Asset';
		$this->_model = 'SaaSDM_Asset';
		$this->_a ['cols'] = array (
				'id' => array (
						'type' => 'Pluf_DB_Field_Sequence',
						'blank' => false 
				),
				'name' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 250 
				),
				'path' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'readable' => false,
						'size' => 250 
				),
				'size' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250 
				),
				'download' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250 
				),
				'driver_type' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 250 
				),
				'driver_id' => array (
						'type' => 'Pluf_DB_Field_Integer',
						'blank' => false,
						'size' => 250 
				),
				'creation_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true 
				),
				'modif_dtime' => array (
						'type' => 'Pluf_DB_Field_Datetime',
						'blank' => true 
				),
				'parent' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'SaaSDM_Asset',
						'blank' => false,
						'relate_name' => 'parent' 
				),
				'type' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 250 
				),
				'content_name' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 2500 
				),
				'description' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 250 
				),
				'mime_type' => array (
						'type' => 'Pluf_DB_Field_Varchar',
						'blank' => false,
						'size' => 250 
				),
				// relations
				'tenant' => array (
						'type' => 'Pluf_DB_Field_Foreignkey',
						'model' => 'SaaS_Application',
						'blank' => false,
						'readable' => false,
						'relate_name' => 'tenant' 
				) 
		);
		
		$this->_a ['idx'] = array (
				'page_class_idx' => array (
						'col' => 'tenant, parent, name',
						'type' => 'unique', // normal, unique, fulltext, spatial
						'index_type' => '', // hash, btree
						'index_option' => '',
						'algorithm_option' => '',
						'lock_option' => '' 
				) 
		);
	}
	
	/**
	 * \brief پیش ذخیره را انجام می‌دهد
	 *
	 * @param $create حالت
	 *        	ساخت یا به روز رسانی را تعیین می‌کند
	 */
	function preSave($create = false) {
		if ($this->id == '') {
			$this->creation_dtime = gmdate ( 'Y-m-d H:i:s' );
		}
		$this->modif_dtime = gmdate ( 'Y-m-d H:i:s' );
	}
	
	/**
	 * حالت کار ایجاد شده را به روز می‌کند
	 *
	 * @see Pluf_Model::postSave()
	 */
	function postSave($create = false) {
		//
	}
	/**
	 * \brief عملیاتی که قبل از پاک شدن است انجام می‌شود
	 *
	 * عملیاتی که قبل از پاک شدن است انجام می‌شود
	 * در این متد فایل مربوط به است حذف می شود. این عملیات قابل بازگشت نیست
	 */
	function preDelete() {
		if (file_exists ( $this->path . '/' . $this->id )) {
			unlink ( $this->path . '/' . $this->id );
		}
	}
}