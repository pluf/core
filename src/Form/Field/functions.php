<?php
namespace Pluf\Form\Field;

use Pluf;
use Pluf\Utils;
use Pluf\FormInvalidException;

/**
 * Default move function.
 * The file name is sanitized.
 *
 * In the extra parameters, options can be used so that this function is
 * matching most of the needs:
 *
 * * 'upload_path': The path in which the uploaded file will be
 * stored.
 * * 'upload_path_create': If set to true, try to create the
 * upload path if not existing.
 *
 * * 'upload_overwrite': Set it to true if you want to allow overwritting.
 *
 * * 'file_name': Force the file name to this name and do not use the
 * original file name. If this name contains '%s' for
 * example 'myid-%s', '%s' will be replaced by the
 * original filename. This can be used when for
 * example, you want to prefix with the id of an
 * article all the files attached to this article.
 *
 * If you combine those options, you can dynamically generate the path
 * name in your form (for example date base) and let this upload
 * function create it on demand.
 *
 * @param
 *            array Upload value of the form.
 * @param
 *            array Extra parameters. If upload_path key is set, use it. (array())
 * @return string Name relative to the upload path.
 */
function moveToUploadFolder($value, $params = array())
{
    $name = Utils::cleanFileName($value['name']);
    $upload_path = Pluf::f('upload_path', '/tmp');
    if (isset($params['file_name'])) {
        if (false !== strpos($params['file_name'], '%s')) {
            $name = sprintf($params['file_name'], $name);
        } else {
            $name = $params['file_name'];
        }
    }
    if (isset($params['upload_path'])) {
        $upload_path = $params['upload_path'];
    }
    $dest = $upload_path . '/' . $name;
    if (isset($params['upload_path_create']) and ! is_dir(dirname($dest))) {
        if (false == @mkdir(dirname($dest), 0777, true)) {
            throw new FormInvalidException('An error occured when creating the upload path. Please try to send the file again.');
        }
    }
    if ((! isset($params['upload_overwrite']) or $params['upload_overwrite'] == false) and file_exists($dest)) {
        throw new FormInvalidException(sprintf('A file with the name "%s" has already been uploaded.', $name));
    }
    if (@! move_uploaded_file($value['tmp_name'], $dest)) {
        throw new FormInvalidException('An error occured when uploading the file. Please try to send the file again.');
    }
    @chmod($dest, 0666);
    return $name;
}
