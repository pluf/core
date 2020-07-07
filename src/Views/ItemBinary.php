<?php
namespace Pluf\Views;

use Pluf\HTTP\Request;
use Pluf\HTTP\Response;
use Pluf\ObjectStorage;
use Pluf\HTTP\ResponseFileBuilder;

class ItemBinary extends ItemView
{

    /**
     * Download a content of a ModelBinary object
     *
     * @param Request $request
     * @param array $match
     * @return Response\File
     */
    public function download(Request $request, array $match, array $p): Response
    {
        $item = $this->getItem($request, $match, $p);
        // Do
        $storage = ObjectStorage::getInstance();

        return ResponseFileBuilder::getInstance()->setAbsloutPath($storage->getAbsloutPath($item))
            ->setFilename($storage->getMimeType($item))
            ->setMimetype($storage->getMimeType($item))
            ->setResumable(true)
            ->deleteOnEnd(false)
            ->build();
    }

    /**
     * Upload a file as content
     *
     * @param Request $request
     * @param array $match
     * @return Object model
     */
    public function upload($request, $match, $p)
    {
        $item = $this->getItem($request, $match, $p);
        // // Do action
        // if (array_key_exists('file', $request->FILES)) {
        // $extra = array(
        // 'model' => $object
        // );
        // $form = new Pluf_Form_ModelBinaryUpdate(array_merge($request->REQUEST, $request->FILES), $extra);
        // $item = $form->save();
        // return $item;
        // } else {
        // $myfile = fopen($item->getAbsloutPath(), "w") or die("Unable to open file!");
        // $entityBody = file_get_contents('php://input', 'r');
        // fwrite($myfile, $entityBody);
        // fclose($myfile);
        // $item->update();
        // }
        return $item;
    }
}

