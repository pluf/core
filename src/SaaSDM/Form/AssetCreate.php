<?php

/**
 * ایجاد یک دارایی جدید
 *
 * با استفاده از این فرم می‌توان یک دارایی جدید را ایجاد کرد.
 * 
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *
 */
class SaaSDM_Form_AssetCreate extends Pluf_Form
{

    public $tenant = null;
    
    public function initFields($extra = array())
    {
        $this->tenant = Pluf_Tenant::current();
        
        $this->fields['name'] = new Pluf_Form_Field_Varchar(array(
            'required' => true,
            'label' => 'Name',
            'help_text' => 'Name of asset'
        ));
        $this->fields['parent'] = new Pluf_Form_Field_Integer(array(
            'required' => false,
            'label' => 'Parent',
            'help_text' => 'Parent of asset'
        ));
        $this->fields['content_name'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'content',
            'help_text' => 'Content related to this asset'
        ));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'description',
            'help_text' => 'description of asset'
        ));
    }

    function save($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the asset from an invalid form');
        }
        // Create the asset
        $asset = new SaaSDM_Asset();
        $asset->driver_type = 'local';
        $asset->type = 'file';
        $asset->driver_id = '0';
        $asset->setFromFormData($this->cleaned_data);
        $asset->path = Pluf::f('upload_path') . '/' . $this->tenant->id . '/dm';
        if (! is_dir($asset->path)) {
            if (false == @mkdir($asset->path, 0777, true)) {
                throw new Pluf_Form_Invalid('An error occured when creating the upload path. Please try to send the file again.');
            }
        }
        // To find out whether a file or folder being created and to check that folder exist or Not
        if (isset($_REQUEST['parent'])) {
            // Note: Hadi, 1395-09: It throw exception if asset dose not exist
            $assetFolder = SaaSDM_Shortcuts_GetAssetOr404($_REQUEST['parent']);
            if ($assetFolder->type != 'folder') {
                throw new Pluf_Form_Invalid('The specified folder does not exist.');
            }
        }
        // Note: Mahdi, 1395-09: For folders there is no path attribute
        if($_REQUEST['type'] == 'folder')
        	$asset->path = '';
        // $asset->user = $this->user;
        $asset->tenant = $this->tenant;
        if ($commit) {
            $asset->create();
        }
        return $asset;
    }
}
