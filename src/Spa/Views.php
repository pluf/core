<?php
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');
Pluf::loadFunction('Pluf_Form_Field_File_moveToUploadFolder');

/**
 * لایه نمایش مدیریت گروه‌ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Spa_Views extends Pluf_Views
{

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function find ($request, $match)
    { // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        $pag = new Pluf_Paginator(new SaaS_SPA());
        $pag->list_filters = array(
                'id',
                'title'
        );
        $list_display = array(
                'title' => __('title'),
                'summary' => __('summary')
        );
        $search_fields = array(
                'title',
                'summary'
        );
        $sort_fields = array(
                'id',
                'title',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * یک نر افزار را نصب می‌کند
     *
     * تنها پارامتری که برای نصب نرم افزار لازم است خود فایل نرم افزار هست. سایر
     * اطلاعات از توی فایل برداشته می‌شه. این فایل باید ساختار نرم افزارهای ما
     * رو داشته باشه.
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function create ($request, $match)
    {
        // 1- upload & extract
        $path = Pluf::f('saas_spa_repository') . '/tmp';
        Pluf_Form_Field_File_moveToUploadFolder($request->FILES['file'], 
                array(
                        'file_name' => 'spa.zip',
                        'upload_path' => $path,
                        'upload_path_create' => true,
                        'upload_overwrite' => true
                ));
        $zip = new ZipArchive();
        if ($zip->open($path . '/spa.zip') === TRUE) {
            $zip->extractTo($path);
            $zip->close();
        } else {
//             echo 'failed';
        }
        unlink($path . '/spa.zip');
        
        // 2- load infor
        $filename = $path . '/' . Pluf::f('saas_spa_config', "spa.json");
        $myfile = fopen($filename, "r") or die("Unable to open file!");
        $json = fread($myfile, filesize($filename));
        fclose($myfile);
        $package = json_decode($json, true);
        
        // 3- crate spa
        $spa = new SaaS_SPA();
        $spa->path = $path;
        $spa->setFromFormData($package);
        $spa->create();
        
        $spa->path = Pluf::f('saas_spa_repository') . '/' . $spa->id;
        $spa->update();
        
        rename($path, $spa->path);
        
        return new Pluf_HTTP_Response_Json($spa);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function get ($request, $match)
    {
        $spa = Pluf_Shortcuts_GetObjectOr404('SaaS_SPA', $match['spaId']);
        return new Pluf_HTTP_Response_Json($spa);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function update ($request, $match)
    {
        $spa = Pluf_Shortcuts_GetObjectOr404('SaaS_SPA', $match['spaId']);
        Spa_Views::remdir($spa->path);
        // 1- upload & extract
        Pluf_Form_Field_File_moveToUploadFolder($request->FILES['file'], 
                array(
                        'file_name' => 'spa.zip',
                        'upload_path' => $spa->path,
                        'upload_path_create' => true,
                        'upload_overwrite' => true
                ));
        $zip = new ZipArchive();
        if ($zip->open($spa->path . '/spa.zip') === TRUE) {
            $zip->extractTo($spa->path);
            $zip->close();
        } else {
            throw new Pluf_Exception('fail to extract zip package');
        }
        unlink($spa->path . '/spa.zip');
        
        // 2- load infor
        $filename = $spa->path . '/' . Pluf::f('saas_spa_config', "spa.json");
        $myfile = fopen($filename, "r") or die("Unable to open file!");
        $json = fread($myfile, filesize($filename));
        fclose($myfile);
        $package = json_decode($json, true);
        
        // 3- update spa
        $spa->setFromFormData($package);
        $spa->update();
        
        return new Pluf_HTTP_Response_Json($spa);
    }

    /**
     *
     * @param unknown_type $request            
     * @param unknown_type $match            
     */
    public function delete ($request, $match)
    {
        $spa = Pluf_Shortcuts_GetObjectOr404('SaaS_SPA', $match['spaId']);
        Spa_Views::rrmdir($spa->path);
        $spa->delete();
        return new Pluf_HTTP_Response_Json($spa);
    }

    static function rrmdir ($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        Spa_Views::rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    static function remdir ($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        Spa_Views::rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
        }
    }
}
