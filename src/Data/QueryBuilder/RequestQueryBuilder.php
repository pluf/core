<?php
namespace Pluf\Data\QueryBuilder;

use Pluf\HTTP\Request;

class RequestQueryBuilder extends \Pluf\Data\QueryBuilder
{

    const SEARCH_QUERY_KEY = '_px_q';

    const CURRENT_PAGE_KEY = '_px_p';

    const PAGE_SIZE_KEY = '_px_ps';

    const SORT_KEY_KEY = '_px_sk';

    const SORT_ORDER_KEY = '_px_so';

    const FILTER_KEY_KEY = '_px_fk';

    const FILTER_VALUE_KEY = '_px_fv';

    /**
     * Load options from user request
     *
     * Here is list of all possible values
     *
     * <ul>
     * <li>_px_q : Query string to search.</li>
     * <li>_px_p : Current page.</li>
     * <li>_px_sk : Sort key.</li>
     * <li>_px_so : Sort order.</li>
     * <li>_px_fk : Filter key.</li>
     * <li>_px_fv : Filter value.</li>
     * <ul>
     *
     * @param
     *            \Pluf\HTTP\Request The request
     */
    function __construct(Request $request)
    {
        $this->loadPage($request)
            ->loadSorts($request)
            ->loadFilters($request)
            ->loadQuery($request);
    }

    /*
     * load current page
     */
    private function loadPage(Request $request): RequestQueryBuilder
    {
        $page = 0;
        $pageSize = 30;
        if (isset($request->REQUEST[self::CURRENT_PAGE_KEY])) {
            // >> Page number
            $page = $request->REQUEST[self::CURRENT_PAGE_KEY];
            if (! isset($page)) {
                $page = 0;
            }
            $page = (int) $page;
        }
        if (isset($request->REQUEST[self::PAGE_SIZE_KEY])) {
            // Page size
            $pageSize = $request->REQUEST[self::PAGE_SIZE_KEY];
            if (! isset($pageSize)) {
                $pageSize = 30;
            }
            $pageSize = (int) $pageSize;
        }
        // >> set query
        $this->setStart($page * $pageSize)->setLimit($pageSize);
        return $this;
    }

    /*
     * load current page
     */
    private function loadSorts(Request $request): RequestQueryBuilder
    {
        if (! isset($request->REQUEST[self::SORT_KEY_KEY])) {
            $this->sort_order = [];
            return $this;
        }
        // Sort orders
        $keys = $request->REQUEST[self::SORT_KEY_KEY];
        $vals = $request->REQUEST[self::SORT_ORDER_KEY];

        if (! is_array($keys)) {
            $keys = [
                $keys
            ];
        }
        if (! is_array($vals)) {
            $vals = [
                $vals
            ];
        }

        for ($i = 0; $i < sizeof($keys); $i ++) {
            $key = $keys[$i];
            $order = 'ASC';
            if ($vals[$i] === 'd') {
                $order = 'DESC';
            }
            $this->setOrder($key, $order);
        }

        return $this;
    }

    /*
     * load current page
     */
    private function loadFilters(Request $request): RequestQueryBuilder
    {

        // check filter option
        if (! array_key_exists(self::FILTER_KEY_KEY, $request->REQUEST)) {
            return $this;
        }

        $keys = $request->REQUEST[self::FILTER_KEY_KEY];
        $vals = $request->REQUEST[self::FILTER_VALUE_KEY];
        if (! is_array($keys)) {
            $keys = [
                $keys
            ];
        }
        if (! is_array($vals)) {
            $vals = [
                $vals
            ];
        }

        // categorize filters
        for ($i = 0; $i < sizeof($keys); $i ++) {
            $key = $keys[$i];
            $value = $vals[$i];
            $this->addFilter($key, $value);
        }

        return $this;
    }

    /*
     * load current page
     */
    private function loadQuery(Request $request): RequestQueryBuilder
    {
        // load query
        $query = null;
        if (isset($request->REQUEST[self::SEARCH_QUERY_KEY])) {
            $query = $request->REQUEST[self::SEARCH_QUERY_KEY];
        }
        $this->setSelect((string) $query);
        return $this;
    }
}

