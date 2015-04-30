<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of ConfOrganizer, a conference manager application.
# Copyright (C) 2006 Loic d'Anterroches.
#
# ***** END LICENSE BLOCK ***** */

class Pluf_Template_Tag_RmediaUrl extends Pluf_Template_Tag
{
    function start($var, $file='')
    {
        $this->context->set($var,
                            Pluf_Template_Tag_MediaUrl::url($file));
    }
}

