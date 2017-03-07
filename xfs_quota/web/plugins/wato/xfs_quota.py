#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2017  Frank Fegert (fra.nospam.nk@gmx.de)
#
# WATO plugin for the parametrization of the threshold values used
# by the "xfs_quota" Check_MK check script.
#
# This program is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
# 02110-1301, USA.
#

register_check_parameters(
    subgroup_storage,
    "xfs_quota",
    _("XFS Quota Utilization"),
    Dictionary(
        help = _("The levels for the soft/hard block/inode quotas on XFS filesystems."),
        title = _("The levels for the soft/hard block/inode quotas on XFS filesystems"),
        elements = [
            ( "blocks_hard",
              Tuple(
                help = _("The levels for the hard block quotas on XFS filesystems."),
                title = _("The levels for the hard block quotas on XFS filesystems"),
                elements = [
                    Integer(title = _("Warning if below"), unit = _("Blocks"), default_value = 0),
                    Integer(title = _("Critical if below"), unit = _("Blocks"), default_value = 0),
                ]),
            ),
            ( "blocks_soft",
              Tuple(
                help = _("The levels for the soft block quotas on XFS filesystems."),
                title = _("The levels for the soft block quotas on XFS filesystems"),
                elements = [
                    Integer(title = _("Warning if below"), unit = _("Blocks"), default_value = 0),
                    Integer(title = _("Critical if below"), unit = _("Blocks"), default_value = 0),
                ]),
            ),
            ( "inodes_hard",
              Tuple(
                help = _("The levels for the hard inode quotas on XFS filesystems."),
                title = _("The levels for the hard inode quotas on XFS filesystems"),
                elements = [
                    Integer(title = _("Warning if below"), unit = _("Inodes"), default_value = 0),
                    Integer(title = _("Critical if below"), unit = _("Inodes"), default_value = 0),
                ]),
            ),
            ( "inodes_soft",
              Tuple(
                help = _("The levels for the soft inode quotas on XFS filesystems."),
                title = _("The levels for the soft inode quotas on XFS filesystems"),
                elements = [
                    Integer(title = _("Warning if below"), unit = _("Inodes"), default_value = 0),
                    Integer(title = _("Critical if below"), unit = _("Inodes"), default_value = 0),
                ]),
            ),
        ]
    ),
    None,
    None
) 

#
# EOF
