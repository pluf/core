<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

/**
 * ایجاد یک دارایی جدید
 *
 * با استفاده از این فرم می‌توان یک دارایی جدید را ایجاد کرد.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class SDP_Form_AssetCreate extends Pluf_Form
{

    public function initFields($extra = array())
    {
        $this->fields['name'] = new Pluf_Form_Field_Varchar(array(
            'required' => true,
            'label' => 'Name',
            'help_text' => 'Name of asset'
        ));
        $this->fields['parent'] = new Pluf_Form_Field_Integer(array(
            'required' => false,
            'label' => 'Folder',
            'help_text' => 'Folder of asset'
        ));
        $this->fields['description'] = new Pluf_Form_Field_Varchar(array(
            'required' => false,
            'label' => 'Description',
            'help_text' => 'Description of asset'
        ));
        $this->fields['price'] = new Pluf_Form_Field_Integer(array(
            'required' => false,
            'label' => 'Price',
            'help_text' => 'Price of asset'
        ));
        $this->fields['content'] = new Pluf_Form_Field_Integer(array(
            'required' => false,
            'label' => 'Content',
            'help_text' => 'Content related to asset'
        ));
        $this->fields['thumbnail'] = new Pluf_Form_Field_Integer(array(
            'required' => false,
            'label' => 'Content',
            'help_text' => 'Content related to asset'
        ));
    }

    function save($commit = true)
    {
        if (! $this->isValid()) {
            throw new Pluf_Exception('cannot save the asset from an invalid form');
        }
        // Create the asset
        $asset = new SDP_Asset();
        $asset->driver_type = 'local';
        $asset->driver_id = '0';
        $asset->type = 'file';
        $asset->mime_type = $_FILES['file']['type'];
        $asset->setFromFormData($this->cleaned_data);
        $asset->path = Pluf::f('upload_path') . '/' . Pluf_Tenant::current()->id . '/sdp';
        if (! is_dir($asset->path)) {
            if (false == @mkdir($asset->path, 0777, true)) {
                throw new Pluf_Form_Invalid('An error occured when creating the upload path. Please try to send the file again.');
            }
        }
        // To find out whether a file or folder being created and to check that folder exist or Not
        if (isset($_REQUEST['parent'])) {
            // Note: Hadi, 1395-09: It throw exception if asset dose not exist
            $assetFolder = Pluf_Shortcuts_GetObjectOr404('SDP_Asset', $_REQUEST['parent']);
            if ($assetFolder->type != 'folder') {
                throw new Pluf_Form_Invalid('The specified folder does not exist.');
            }
        }
        // Note: Mahdi, 1395-09: For folders there is no path attribute
        if (isset($_REQUEST['type'])) {
            if ($_REQUEST['type'] == 'folder')
                $asset->path = '';
        }
        if ($commit) {
            $asset->create();
        }
        return $asset;
    }

    public function clean_name()
    {
        $fullname = trim($this->cleaned_data['name']);
        return $fullname;
    }
}
