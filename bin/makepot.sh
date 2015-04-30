#!/bin/sh
#
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
# 
# Create the pot file for Pluf.
#
xgettext -o pluf.pot -p ./Pluf/locale --force-po --from-code=UTF-8 --keyword --keyword=__ --keyword=_n:1,2 -L PHP *.php
sleep 5
find Pluf/ -iname "*.php" -exec xgettext  -o pluf.pot -p ./Pluf/locale --from-code=UTF-8 -j --keyword --keyword=__ --keyword=_n:1,2 -L PHP {} \;
