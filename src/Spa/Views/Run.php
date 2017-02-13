<?php

/**
 * نمایش و اجرای spa
 * 
 * @author maso
 *
 */
class Spa_Views_Run
{

    /**
     * Loads SPA (by name) or resource (by name).
     * First search for SPA with specified name.
     * If such SPA is not found search for resource file with specified name in
     * default SPA of tenant.
     *
     * @param unknown $request            
     * @param array $match            
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function loadSpaOrResource ($request, $match)
    {
        $path = $match['path'];
        if (! isset($path)) {
            throw new Pluf_Exception('Name for spa or resource is null!');
        }
        $spa = Spa_SPA::getSpaByName($path);
        $resource = null;
        if (! isset($spa)) {
            $name = Setting_Service::get('spa.default', 'start');
            $spa = Spa_SPA::getSpaByName($name);
            $resource = $path;
        }
        return self::loadSpaResource($request, $spa, $resource);
    }

    public static function defaultSpa ($request, $match)
    {
        $name = Setting_Service::get('spa.default', 'start');
        $spa = Spa_SPA::getSpaByName($name);
        return self::loadSpaResource($request, $spa);
    }

    public static function getResource ($request, $match)
    {
        // Load data
        $resourcePath = $match['resource'];
        $spa = Spa_SPA::getSpaByName($match['spa']);
        if(!isset($spa)){
            $name = Setting_Service::get('spa.default', 'start');
            $spa = Spa_SPA::getSpaByName($name);
            $resourcePath = $match['spa'].'/'.$resourcePath;
        }
        return self::loadSpaResource($request, $spa, $resourcePath);
    }

    protected static function loadSpa ($request, $spa)
    {
        // نمایش اصلی
        $mainPage = $spa->getMainPagePath();
        return new Pluf_HTTP_Response_File($mainPage, 
                Pluf_FileUtil::getMimeType($mainPage));
    }

    /**
     * Loads a resource from an SPA of a tenant.
     * Tenant could not be null.
     * If $spa is null default SPA of tenant is used. If $resource is null
     * default main page of
     * SPA is used.
     *
     * @param unknown $request            
     * @param Pluf_Tenant $tenant            
     * @param SPA $spa            
     * @param string $resource            
     * @throws Pluf_EXception if tenant is null or spa could not be found.
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response|Pluf_HTTP_Response_File
     */
    protected static function loadSpaResource ($request, $spa = null, 
            $resource = null)
    {
        // Resource
        if (! isset($resource)) {
            return self::loadSpa($request, $spa);
        }
        // TODO: Check access
        $resPath = $spa->getResourcePath($resource);
        if (! $resPath) {
            // Try to load resource form assets directory of platform
            $resPath = Spa_SPA::getAssetsPath($resource);
        }
        return new Pluf_HTTP_Response_File($resPath, 
                Pluf_FileUtil::getMimeType($resPath));
    }
}