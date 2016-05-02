#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# WATO plugin for the parametrization of the threshold values used
# by the "dell_powerconnect_bcm_temp" Check_MK check script.
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
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.#
#

register_check_parameters(
    subgroup_environment,
    "dell_powerconnect_bcm_temp",
    _("Dell PowerConnect temperature"),
    Dictionary(
        help = _("Temperature levels for Dell PowerConnect switches, based on Broadcom FastPath silicon."),
        title = _("Temperature levels for Dell PowerConnect switches"),
        elements = [
            ( "levels",
            Tuple(
                help = _("Temperature levels for Dell PowerConnect switches, based on Broadcom FastPath silicon."),
                title = _("Temperature levels for Dell PowerConnect switches"),
                elements = [
                    Integer(title = _("Warning if above"), unit = u"°C", default_value = 45),
                    Integer(title = _("Critical if above"), unit = u"°C", default_value = 50),
                ]),
            ),
        ]
    ),
    TextAscii(
        title = _("Temperature sensor"), 
        help = _("The identifier of the temperature sensor")),
    None
) 

#
# EOF
