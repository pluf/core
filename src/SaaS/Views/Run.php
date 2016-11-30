<?php 

/**
 * نمایش و اجرای spa
 * 
 * @author maso
 *
 */
class SaaS_Views_Run {

    /**
     * **************************************************************************************
     * XXX: Hadi, 1395: متدهای این قمست باید بررسی شوند
     */
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
        $tenant = $request->tenant;
        $path = $match['path'];
        if (! isset($path)) {
            throw new Pluf_Exception('Name for spa or resource is null!');
        }
        $spa = SaaS_SPA::getSpaByName($path, $tenant);
        $resource = null;
        if (! isset($spa)) {
            $spa = $tenant->get_spa();
            $resource = $path;
        }
        return SaaS_Views_Run::loadSpaResource($request, $tenant, $spa,
                $resource);
        // // TODO: Check access
        // SaaS_Precondition::userCanAccessApplication($request, $tenant);
        // // SaaS_Precondition::userCanAccessSpa($request, $spa);
    
        // // نمایش اصلی
        // return SaaS_Views_Run::loadSpa($request, $tenant, $spa);
    }
    
    public static function defaultSpa ($request, $match)
    {
        $tenant = $request->tenant;
        return SaaS_Views_Run::loadSpaResource($request, $tenant);
    }
    
    public static function getResource ($request, $match)
    {
        // Load data
        $resourcePath = $match['resource'];
        $tenant = $request->tenant;
        if ($match['spa']) {
            $spa = SaaS_SPA::getSpaByName($match['spa'], $tenant);
        }
        if (! isset($spa)) {
            $spa = $tenant->get_spa();
            $resourcePath = $match[0];
        }
        return SaaS_Views_Run::loadSpaResource($request, $tenant, $spa,
                $resourcePath);
        // // TODO: Check access
        // $resPath = $spa->getResourcePath($resourcePath);
        // if (! $resPath) {
        // // Try to load resource form assets directory of platform
        // $resPath = SaaS_SPA::getAssetsPath($resourcePath);
        // }
        // return new Pluf_HTTP_Response_File($resPath,
        // SaaS_FileUtil::getMimeType($resPath));
    }
    
    public static function getResourceOfDefault ($request, $match)
    {
        // Load data
        $tenant = $request->tenant;
        $spa = $tenant->get_spa();
    
        return SaaS_Views_Run::loadSpaResource($request, $tenant, $spa,
                $match['resource']);
        // // TODO: Check access
        // // Load resource form local resources of spa
        // $res = $spa->getResourcePath($match['resource']);
        // if (! $res) {
        // // Try to load resource form assets directory of platform
        // $res = SaaS_SPA::getAssetsPath($match['resource']);
        // }
        // return new Pluf_HTTP_Response_File($res,
        // SaaS_FileUtil::getMimeType($res));
    }
    
    protected static function loadSpa ($request, $app, $spa)
    {
        // نمایش اصلی
        $mainPage = $spa->getMainPagePath();
    
        return new Pluf_HTTP_Response_File($mainPage,
                SaaS_FileUtil::getMimeType($mainPage));
    }
    
    /**
     * Loads a resource from an SPA of a tenant.
     * Tenant could not be null.
     * If $spa is null default SPA of tenant is used. If $resource is null
     * default main page of
     * SPA is used.
     *
     * @param unknown $request
     * @param SaaS_Application $tenant
     * @param SaaS_SPA $spa
     * @param string $resource
     * @throws Pluf_EXception if tenant is null or spa could not be found.
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response|Pluf_HTTP_Response_File
     */
    protected static function loadSpaResource ($request, $tenant, $spa = null,
            $resource = null)
    {
        // Tenant
        if (! isset($tenant)) {
            throw new Pluf_EXception('Tenant is not set determined!');
        }
        // SPA
        if (! isset($spa)) {
            // Default spa of tenant
            $_spa = $tenant->get_spa();
            if (! isset($_spa)) {
                throw new Pluf_Exception('Spa could not be found!');
            }
        } else {
            $_spa = $spa;
        }
        // Resource
        if (! isset($resource)) {
            return SaaS_Views_Run::loadSpa($request, $tenant, $_spa);
        }
        // TODO: Check access
        $resPath = $_spa->getResourcePath($resource);
        if (! $resPath) {
            // Try to load resource form assets directory of platform
            $resPath = SaaS_SPA::getAssetsPath($resource);
        }
        return new Pluf_HTTP_Response_File($resPath,
                SaaS_FileUtil::getMimeType($resPath));
    }
    
    
}