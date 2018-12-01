#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2018  Frank Fegert (fra.nospam.nk@gmx.de)
#
# WATO plugin for the parametrization of the threshold values used
# by the "brocade_mem" Check_MK check script.
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
    subgroup_os,
    "brocade_mem",
    _("Brocade Fibre Channel Memory Usage"),
    Transform(
        Dictionary(
            help = _("The levels for the percentage of used memory on Brocade Fibre Channel switches."),
            elements = [
                ( "levels", CascadingDropdown(
                    title = _("Levels for memory usage"),
                    choices = [
                        ( "perc_used",
                            _("Percentual levels for used memory"),
                                Tuple(
                                    elements = [
                                        Percentage(title = _("Warning if equal or above"), unit = _("% of RAM"), default_value = 80.0),
                                        Percentage(title = _("Critical if equal or above"), unit = _("% of RAM"), default_value = 90.0),
                                  ]
                            )),
                        ( "ignore", _("Do not impose levels")),
                    ])
                ),
            ]
        ),
        # Convert default levels from discovered checks
        forth = lambda v: type(v) != dict and { "levels" : ( "perc_used", v) } or v,
    ),
    None,
    "dict"
) 

#
# EOF
