<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2010 Loic d'Anterroches and contributors.
#
# Plume Framework is free software; you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as published by
# the Free Software Foundation; either version 2.1 of the License, or
# (at your option) any later version.
#
# Plume Framework is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Lesser General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
#
# ***** END LICENSE BLOCK ***** */

class Pluf_Test_TemplatetagsUnitTestCase extends UnitTestCase
{
    /**
     * Tag class name like 'Pluf_Template_Tag_Cfg'.
     *
     * @var string
     */
    protected $tag_class = null;

    /**
     * Tag identifier like 'cfg'.
     *
     * @var string
     */
    protected $tag_name = null;

    /**
     * 
     */
    protected $tpl_folders;

    function __construct($label = false)
    {
        $label = ($label) ? $label : 'Test the `'.$this->tag_name.'` template tag.';
        parent::__construct($label);

        if (null === $this->tag_name) {
            throw new LogicException('You must initialize the `$tag_name` property.');
        }
        if (null === $this->tag_class) {
            throw new LogicException('You must initialize the `$tag_class` property.');
        }

        $folder = Pluf::f('tmp_folder').'/templatetags';
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $this->tpl_folders = array($folder);

        Pluf_Signal::connect('Pluf_Template_Compiler::construct_template_tags_modifiers', 
                             array($this, 'addTemplatetag'));
    }

    public function addTemplatetag($signal, &$params)
    {
        $params['tags'] = array_merge($params['tags'],
                                      array($this->tag_name => $this->tag_class));
    }

    protected function writeTemplateFile($tpl_name, $content)
    {
        $file = $this->tpl_folders[0].'/'.$tpl_name;
        if (file_exists($file)) {
            unlink($file);
        }
        file_put_contents($file, $content);
    }

    protected function getNewTemplate($content = '')
    {
        $tpl_name = sprintf('%s-%s.html',
                            get_class($this),
                            md5($content.microtime(true)));
        $this->writeTemplateFile($tpl_name, $content);

        return new Pluf_Template($tpl_name, $this->tpl_folders);
    }
}
