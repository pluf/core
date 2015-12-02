<?php
Pluf::loadFunction('User_Shortcuts_UpdateLeveFor');
Pluf::loadFunction('Pluf_HTTP_URL_urlForView');
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');

Pluf::loadFunction('KM_Shortcuts_GetLabelOr404');

Pluf::loadFunction('Jayab_Shortcuts_locationBound');
Pluf::loadFunction('Jayab_Shortcuts_GetLocationOr404');

/**
 * لایه نمایش برای دستری به مکان‌ها را ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Jayab_Views_Location
{

    /**
     * مکان مورد نظر کاربر را جستجو می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function find ($request, $match)
    {
        /*
         * XXX: maso, 1394: استفاده از برچسب‌ها در جستجو
         *
         * هر مکان با استفاده از یک برچسب تعیین دسته بندی می‌شود. این برچسب‌ها
         * در حال
         * حاضر در جستجو استفاده نمی‌شود؟!
         */
        $count = 10;
        if (array_key_exists("_px_c", $request->REQUEST)) {
            $count = $request->REQUEST["count"];
        }
        $count = Jayab_Shortcuts_locationCount($request, $count);
        $distance = 1000;
        if (array_key_exists("radius", $request->REQUEST)) {
            $distance = $request->REQUEST["radius"];
        }
        $distance = Jayab_Shortcuts_locationRadios($request, $distance);
        
        if (! array_key_exists('latitude', $request->REQUEST)) {
            throw new Pluf_Exception("Latitude is not defined.", 4000, null, 405, 
                    "/");
        }
        $latitude = $request->REQUEST['latitude'];
        if (! array_key_exists('longitude', $request->REQUEST)) {
            throw new Pluf_Exception("Longitude is not defined.", 4000, null, 
                    405, "/");
        }
        $longitude = $request->REQUEST['longitude'];
        $bound = Jayab_Shortcuts_locationBound($request, $latitude, $longitude, 
                $distance);
        // maso, 1394: گرفتن فهرست مناسبی از پیام‌ها
        // Paginator to paginate messages
        $pag = new Pluf_Paginator(new Jayab_Location());
        $pag->list_filters = array(
                'reporter',
                'community'
        );
        $pag->forced_where = new Pluf_SQL(
                'latitude<%s AND latitude>%s AND longitude<%s AND longitude>%s', 
                array(
                        $bound['max']['lat'],
                        $bound['min']['lat'],
                        $bound['max']['long'],
                        $bound['min']['long']
                ));
        $tag = new Jayab_Tag();
        if (array_key_exists('tag_key', $request->REQUEST) &&
                 array_key_exists('tag_key', $request->REQUEST)) {
            $pag->model_view = 'with_tag';
            $sqlSelect = new Pluf_SQL('tag_key=%s AND tag_value=%s', 
                    array(
                            $request->REQUEST['tag_key'],
                            $request->REQUEST['tag_value']
                    ));
            $tag = Pluf::factory('Jayab_Tag')->getOne(
                    array(
                            'filter' => $sqlSelect->gen()
                    ));
            $pag->forced_where->SAnd(
                    new Pluf_SQL(
                            'jayab_location_jayab_tag_assoc.jayab_tag_id=%s', 
                            array(
                                    $tag->id
                            )));
        }
        $list_display = array(
                'title' => __('location title'),
                'description' => __('description')
        );
        $search_fields = array(
                'name',
                'description'
        );
        $sort_fields = array(
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $count;
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        
        // Add statistic
        $stat = new Jayab_SearchStatistic();
        $stat->user = $request->user;
        $stat->application = $request->application;
        $stat->tag = $tag;
        if (array_key_exists('spa', $request->REQUEST))
            $stat->spa = $request->REQUEST['spa'];
        if (array_key_exists('device', $request->REQUEST))
            $stat->spa = $request->REQUEST['device'];
        $stat->latitude = $latitude;
        $stat->longitude = $longitude;
        $stat->create();
        
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * فهرستی از تمام مکان‌های موجود
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function locations ($request, $match)
    {
        $count = 10;
        if (array_key_exists("_px_c", $request->REQUEST)) {
            $count = $request->REQUEST["count"];
        }
        $count = Jayab_Shortcuts_locationCount($request, $count);
        // Paginator to paginate messages
        $pag = new Pluf_Paginator(new Jayab_Location());
        $pag->list_filters = array(
                'reporter',
                'community'
        );
        // $pag->forced_where = new Pluf_SQL();
        $list_display = array(
                'title' => __('location title'),
                'description' => __('description')
        );
        $search_fields = array(
                'name',
                'description'
        );
        $sort_fields = array(
                'name',
                'creation_date',
                'modif_dtime'
        );
        $pag->configure($list_display, $search_fields, $sort_fields);
        $pag->items_per_page = $count;
        $pag->sort_order = array(
                'creation_dtime',
                'DESC'
        );
        $pag->setFromRequest($request);
        return new Pluf_HTTP_Response_Json($pag->render_object());
    }

    /**
     * یک مکان جدید را ایجاد می کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function create ($request, $match)
    {
        $extra = array(
                'user' => $request->user
        );
        $form = new Jayab_Form_Location(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $cuser = $form->save();
        $request->user->setMessage(
                sprintf(__('the location %s has been created.'), 
                        (string) $cuser->name));
        
        User_Shortcuts_UpdateLeveFor($request->user, "jahanjoo_location_add");
        // Return response
        return new Pluf_HTTP_Response_Json($cuser);
    }

    /**
     * به روز کردن اطلاعات یک مکان
     *
     * با استفاده از این فراخوانی اطلاعات یک مکان را به روز می‌کند. کابر باید
     * دسترسی‌های مجاز را داشته باشد.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function update ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        
        Jayab_Precondition::canEditLocation($request->user, $location);
        $extra = array(
                'user' => $request->user,
                'location' => $location
        );
        $form = new Jayab_Form_Location(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $new_location = $form->update();
        $request->user->setMessage(
                sprintf(__('the location %s has been updated.'), 
                        (string) $new_location->name));
        return new Pluf_HTTP_Response_Json($new_location);
    }

    /**
     * اطلاعات یک مکان را دریافت می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function get ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canAccessLocation($request->user, $location);
        return new Pluf_HTTP_Response_Json($location);
    }

    /**
     * اطلاعات یک مکان را حذف می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function delete ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        // بررسی دسترسی دستکاری داده‌ها
        Jayab_Precondition::canDeleteLocation($request->user, $location);
        $tl = new Jayab_Location($location->id);
        $tl->id = 0;
        $location->delete();
        $request->user->setMessage(
                sprintf(__('the location %s has been deleted.'), 
                        (string) $tl->name));
        return new Pluf_HTTP_Response_Json($tl);
    }

    /**
     * یک پرونده بار می‌شود.
     *
     * @param unknown $request            
     * @param unknown $match            
     */
    public function loadGsonFile ($request, $match)
    {
        foreach ($request->FILES as $file) {
            $TAG = new Jayab_Tag();
            if (is_readable($file['tmp_name'])) {
                $myfile = fopen($file['tmp_name'], "r") or
                         die("Unable to open file!");
                $json = fread($myfile, filesize($file['tmp_name']));
                fclose($myfile);
                $gosm = json_decode($json, true);
                if (array_key_exists('elements', $gosm)) {
                    foreach ($gosm['elements'] as $node) {
                        if (array_key_exists('type', $node) &&
                                 $node['type'] == 'node') {
                            // get tags
                            $tags = array();
                            if (array_key_exists('tags', $node)) {
                                $filter = array();
                                foreach ($node['tags'] as $tk => $tv) {
                                    try {
                                        $temp_tag = $TAG->getList(
                                                array(
                                                        'filter' => array(
                                                                'tag_key=\'' .
                                                                         $tk .
                                                                         '\'',
                                                                        'tag_value=\'' .
                                                                         $tv .
                                                                         '\''
                                                        )
                                                ));
                                    } catch (Exception $e) {
                                        // Log
                                        var_dump($e);
                                    }
                                    $tags = array_merge($tags, 
                                            $temp_tag->getArrayCopy());
                                }
                            }
                            // create location
                            $location = new Jayab_Location();
                            $location->reporter = $request->user;
                            $location->community = $request->user->administrator;
                            if (array_key_exists('tags', $node) &&
                                     array_key_exists('name', $node['tags']))
                                $location->name = $node['tags']['name'];
                            $location->description = '';
                            $location->latitude = $node['lat'];
                            $location->longitude = $node['lon'];
                            $location->create();
                            foreach ($tags as $tag) {
                                $location->setAssoc($tag);
                            }
                        }
                    }
                }
            }
        }
        return new Pluf_HTTP_Response_Json(new ArrayObject(array()));
    }

    public function addTag ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canEditLocation($request->user, $location);
        $label = Jayab_Shortcuts_GetTagOr404($match[2]);
        $location->setAssoc($label);
        return new Pluf_HTTP_Response_Json($location);
    }

    public function deleteTag ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canEditLocation($request->user, $location);
        $label = Jayab_Shortcuts_GetTagOr404($match[2]);
        $location->delAssoc($label);
        return new Pluf_HTTP_Response_Json($location);
    }

    public function addTagBykeyvalue ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canEditLocation($request->user, $location);
        $sqlSelect = new Pluf_SQL('tag_key=%s AND tag_value=%s', 
                array(
                        $request->REQUEST['tag_key'],
                        $request->REQUEST['tag_value']
                ));
        $tag = Pluf::factory('Jayab_Tag')->getOne(
                array(
                        'filter' => $sqlSelect->gen()
                ));
        // XXX: maso, 1394: بررسی وجود برچسب
        $location->setAssoc($tag);
        return new Pluf_HTTP_Response_Json($location);
    }

    public function deleteTagBykeyvalue ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canEditLocation($request->user, $location);
        $sqlSelect = new Pluf_SQL('tag_key=%s AND tag_value=%s', 
                array(
                        $request->REQUEST['tag_key'],
                        $request->REQUEST['tag_value']
                ));
        $tag = Pluf::factory('Jayab_Tag')->getOne(
                array(
                        'filter' => $sqlSelect->gen()
                ));
        // XXX: maso, 1394: بررسی وجود برچسب
        $location->delAssoc($tag);
        return new Pluf_HTTP_Response_Json($location);
    }

    public function addLabel ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canEditLocation($request->user, $location);
        $label = KM_Shortcuts_GetLabelOr404($match[2]);
        $location->setAssoc($label);
        return new Pluf_HTTP_Response_Json($location);
    }

    public function deleteLabel ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canEditLocation($request->user, $location);
        $label = KM_Shortcuts_GetLabelOr404($match[2]);
        $location->delAssoc($label);
        return new Pluf_HTTP_Response_Json($location);
    }

    /**
     * فهرست برچسب‌ها را تعیین می‌کند
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function labels ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        Jayab_Precondition::canAccessLocation($request->user, $location);
        return new Pluf_HTTP_Response_Json($location->get_label_list());
    }

    /**
     * رای کاربر به مکان مورد نظر را تعیین می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    function getVote ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        $vote = $location->getUserVote($request->user);
        if ($vote != null) {
            return new Pluf_HTTP_Response_Json($vote);
        }
        throw new Pluf_Exception_DoesNotExist();
    }

    /**
     * اطلاعات یک رای را به روز می‌کند.
     *
     * در صورتی که رای وجود نداشته باشد آن را ایجاد می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_NotImplemented
     */
    function updateVote ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        $vote = $location->getUserVote($request->user);
        $extra = array(
                'user' => $request->user,
                'location' => $location,
                'vote' => $vote
        );
        $form = new Jayab_Form_Vote(
                array_merge($request->REQUEST, $request->FILES), $extra);
        $old = $vote;
        if ($vote === null) {
            $vote = $form->save();
            $old = new Jayab_Vote();
            $old->like = ! $vote->like;
            User_Shortcuts_UpdateLeveFor($request->user, 
                    "jahanjoo_location_like");
        } else {
            $vote = $form->update();
        }
        
        if ($old->like != $vote->like) {
            User_Shortcuts_UpdateLeveFor($location->get_reporter(), 
                    "jahanjoo_location_like", $vote->like);
        }
        return new Pluf_HTTP_Response_Json($vote);
    }

    /**
     * رای مربوط به مکان را حذف می‌کند
     *
     * این فراخوانی رای کاربر جاری به مکان تعیین شده را حذف می‌کند.
     *
     * @param unknown $request            
     * @param unknown $match            
     * @throws Pluf_Exception_DoesNotExist
     * @return Pluf_HTTP_Response_Json
     */
    function deleteVote ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        $vote = $location->getUserVote($request->user);
        if ($vote != null) {
            User_Shortcuts_UpdateLeveFor($request->user, 
                    "jahanjoo_location_like", false);
            User_Shortcuts_UpdateLeveFor($location->get_reporter(), 
                    "jahanjoo_location_like", ! $vote->like);
            $vote->delete();
            return new Pluf_HTTP_Response_Json($location);
        }
        throw new Pluf_Exception_DoesNotExist();
    }

    /**
     * تعیین خلاصه از ارائه داده شده
     *
     * @param unknown $request            
     * @param unknown $match            
     * @return Pluf_HTTP_Response_Json
     */
    public function votes ($request, $match)
    {
        $location = Jayab_Shortcuts_GetLocationOr404($match[1]);
        $vote = $location->getVoteSummery();
        return new Pluf_HTTP_Response_Json($vote);
    }
}