<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
 * # ***** BEGIN LICENSE BLOCK *****
 * # This file is part of Plume Framework, a simple PHP Application Framework.
 * # Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
 * #
 * # Plume Framework is free software; you can redistribute it and/or modify
 * # it under the terms of the GNU Lesser General Public License as published by
 * # the Free Software Foundation; either version 2.1 of the License, or
 * # (at your option) any later version.
 * #
 * # Plume Framework is distributed in the hope that it will be useful,
 * # but WITHOUT ANY WARRANTY; without even the implied warranty of
 * # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * # GNU Lesser General Public License for more details.
 * #
 * # You should have received a copy of the GNU Lesser General Public License
 * # along with this program; if not, write to the Free Software
 * # Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 * #
 * # ***** END LICENSE BLOCK *****
 */
namespace Pluf\AB;

use Pluf\AB;
use Pluf\HTTP\Response\Redirect;

/**
 * Manage and visualize the tests.
 *
 * It is possible to mark a test as inactive by picking a
 * winner.
 *
 * Check the urls.php file for the URL definition to integrate the
 * dashboard in your application/project.
 *
 * The permission used are:
 *
 * Pluf_AB.view-dashboard: The user can view the dasboard.
 * Pluf_AB.edit-test: The user can edit a test.
 */
class Pluf_AB_Views
{

    /**
     * Display the currently running tests.
     *
     * The name of the view in the urls must be 'pluf_ab_dashboard'.
     */
    public $dasboard_precond = array(
        array(
            'User_Precondition::hasPerm',
            'Pluf_AB.view-dashboard'
        )
    );

    public function dashboard($request, $match)
    {
        $url = Pluf_HTTP_URL_urlForView('pluf_ab_dashboard');
        $can_edit = $request->user->hasPerm('Pluf_AB.edit-test');
        if ($can_edit && $request->method == 'POST') {
            // We mark the winner.
            $form = new Form\MarkWinner($request->POST);
            if ($form->isValid()) {
                $form->save();
                $request->user->setMessage('The test has been updated.');
                return new Redirect($url);
            }
        } else {
            // To have it available for the control of the errors in
            // the template.
            $form = new Form\MarkWinner();
        }
        // Get the list of tests
        $db = AB::getDb();
        $active = array();
        $stopped = array();
        foreach ($db->tests->find() as $test) {
            $test['stats'] = AB::getTestStats($test);
            if ($test['active']) {
                $active[] = $test;
            } else {
                $stopped[] = $test;
            }
        }
        return Pluf_Shortcuts_RenderToResponse('pluf/ab/dashboard.html', array(
            'active' => $active,
            'stopped' => $stopped,
            'form' => $form,
            'can_edit' => $can_edit
        ), $request);
    }

    /**
     * Display the list of funnels.
     */
    public $funnels_precond = array(
        array(
            'Pluf_Precondition::hasPerm',
            'Pluf_AB.view-funnels'
        )
    );

    public function funnels($request, $match)
    {
        $funnels = Funnel::getFunnels();
        return Pluf_Shortcuts_RenderToResponse('pluf/ab/funnels.html', array(
            'funnels' => $funnels
        ), $request);
    }

    /**
     * Display a given funnel stats.
     */
    public $funnel_precond = array(
        array(
            'Pluf_Precondition::hasPerm',
            'Pluf_AB.view-funnels'
        )
    );

    public function funnel($request, $match)
    {
        $periods = array(
            'yesterday' => 'Yesterday',
            'today' => 'Today',
            '7days' => 'Last 7 days',
            'all' => 'All time'
        );
        $period = 'today';
        $nperiod = $periods[$period];
        if (isset($request->REQUEST['p']) and isset($periods[$request->REQUEST['p']])) {
            $period = $request->REQUEST['p'];
            $nperiod = $periods[$request->REQUEST['p']];
        }
        $props = Funnel::getFunnelProps($match[1], $period);
        $prop = null;
        if (isset($request->REQUEST['prop']) and in_array($request->REQUEST['prop'], array_keys($props))) {
            $prop = $request->REQUEST['prop'];
        }
        $stats = Funnel::getStats($match[1], $period, $prop);
        return Pluf_Shortcuts_RenderToResponse('pluf/ab/funnel.html', array(
            'stats' => $stats,
            'funnel' => $match[1],
            'nperiod' => $nperiod,
            'period' => $period,
            'props' => $props,
            'prop' => $prop
        ), $request);
    }

    /**
     * A simple view to redirect a user and convert it.
     *
     * To convert the user for the test 'my_test' and redirect it to
     * the URL 'http://www.example.com' add the following view in your
     * urls.php:
     *
     * <pre>
     * array('regex' => '#^/goto/example/$#',
     * 'base' => $base,
     * 'model' => 'Pluf_AB_Views',
     * 'method' => 'convRedirect',
     * 'name' => 'go_to_example',
     * 'params' => array('url' => 'http://www.example.com',
     * 'test' => 'my_test')
     * );
     * </pre>
     *
     * Try to put a url which reflects the final url after redirection
     * to minimize the confusion for the user. In this example, in
     * your code or template you use the named url 'go_to_example'.
     */
    public function convRedirect($request, $match, $p)
    {
        AB::convert($p['test'], $request);
        return new Redirect($p['url']);
    }
}






