#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2018  Frank Fegert (fra.nospam.nk@gmx.de)
#
# WATO plugin for the parametrization of the threshold values used
# by the "brocade_sfp" Check_MK check script.
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
    subgroup_networking,
    "brocade_sfp",
    _("Brocade Fibre Channel SFP"),
    Dictionary(
        elements = [
            ( "levels", 
              Tuple(
                  help = _("The temperature levels in degrees celsius for a SFP on Brocade Fibre Channel switches."),
                  title =_("Temperature levels in degrees celsius"),
                  elements = [
                      Float(title = _("Warning if equal or above"), unit = u"°C", default_value = 50.0),
                      Float(title = _("Critical if equal or above"), unit = u"°C", default_value = 60.0),
                  ])
            ),
            ( "rx_power", 
              Tuple(
                  help = _("The power levels of the received signal for a SFP on Brocade Fibre Channel switches."),
                  title =_("Receive power levels in dBm"),
                  elements = [
                      Float(title = _("Warning if equal or below"), unit = _("dBm"), default_value = -7.0),
                      Float(title = _("Critical if equal or below"), unit = _("dBm"), default_value = -9.0),
                  ])
            ),
            ( "tx_power", 
              Tuple(
                  help = _("The power levels of the transmitted signal for a SFP on Brocade Fibre Channel switches."),
                  title =_("Transmit power levels in dBm"),
                  elements = [
                      Float(title = _("Warning if equal or below"), unit = _("dBm"), default_value = -2.0),
                      Float(title = _("Critical if equal or below"), unit = _("dBm"), default_value = -3.0),
                  ])
            ),
        ]
    ),
    TextAscii(
        title = _("port name"),
        help = _("The name of the switch port without the leading \"SFP Port\" string."),
        regex = "(^[0-9]*) (ISL |)(.*|$)",
        regex_error = _("The name of the switch port must begin with a number followed by the port description or the string \"ISL\" and the port description."),
        allow_empty = False,
    ),
    "dict"
) 

#
# EOF
