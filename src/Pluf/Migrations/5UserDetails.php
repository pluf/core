<?php
/* -*- tab-width: 4; indent-tabs-mode: nil; c-basic-offset: 4 -*- */
/*
# ***** BEGIN LICENSE BLOCK *****
# This file is part of Plume Framework, a simple PHP Application Framework.
# Copyright (C) 2001-2007 Loic d'Anterroches and contributors.
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

/**
 * Add lang and timezone in the user model.
 */

function Pluf_Migrations_5UserDetails_up($params=null)
{
    $db = Pluf::db();
    $db->begin(); // Start a transaction
    try {
        // Add 2 new fields.
        $user_model = Pluf::f('pluf_custom_user','Pluf_User');
        $guser = new $user_model();
        $table = $guser->getSqlTable();
        $sql = 'ALTER TABLE '.$table."\n"
            .'ADD COLUMN language VARCHAR(5) DEFAULT \'en\','."\n"
            .'ADD COLUMN timezone VARCHAR(50) DEFAULT \'Europe/Berlin\''."\n";
        $db->execute($sql);
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
    $db->commit(); 
}

function Pluf_Migrations_5UserDetails_down($params=null)
{
    $db = Pluf::db();
    $db->begin(); // Start a transaction
    try {
        $user_model = Pluf::f('pluf_custom_user','Pluf_User');
        $guser = new $user_model();
        $table = $guser->getSqlTable();
        $sql = 'ALTER TABLE '.$table."\n"
            .'DROP COLUMN language,'."\n"
            .'DROP COLUMN timezone'."\n";
        $db->execute($sql);
    } catch (Exception $e) {
        $db->rollback();
        throw $e;
    }
    $db->commit(); 
}