#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# WATO plugin for the parametrization of the threshold values used
# by the "dell_powerconnect_bcm_arp_cache" Check_MK check script.
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
    subgroup_os,
    "dell_powerconnect_bcm_arp_cache",
    _("Dell PowerConnect ARP cache"),
    Dictionary(
        help = _("The levels for the number of ARP cache entries on Dell PowerConnect switches, based on Broadcom FastPath silicon."),
        title = _("The levels for the number of ARP cache entries on Dell PowerConnect switches"),
        elements = [
            ( "levels",
            Tuple(
		help = _("The levels for the number of ARP cache entries on Dell PowerConnect switches, based on Broadcom FastPath silicon."),
	        title = _("The levels for the number of ARP cache entries on Dell PowerConnect switches"),
                elements = [
                    Integer(title = _("Warning if equal or above"), unit = _("Entries"), default_value = 3072),
                    Integer(title = _("Critical if equal or above"), unit = _("Entries"), default_value = 3584),
                ]),
            ),
        ]
    ),
    None,
    "dict"
) 

#
# EOF
