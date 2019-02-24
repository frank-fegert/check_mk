#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2019  Frank Fegert (fra.nospam.nk@gmx.de)
#
# WATO plugin for the parametrization of the threshold values
# used by the "sapcc_performance_backends" Check_MK check script.
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
    subgroup_applications,
    "sapcc_performance_backends",
    _("SAP Cloud Connector Backend Performance"),
    Dictionary(
        elements = [
            ( "call_levels", 
              Tuple(
                  help = _("The backend calls runtime bucket definition in milliseconds and the percentage of backend calls allowed per bucket."),
                  title =_("Runtime bucket definition and calls per bucket in percent"),
                  elements = [
                      Float(title = _("Warning if percentage of calls in warning bucket equal or above"), unit = _("%"), default_value = 10),
                      Integer(title = _("Assign calls to warning bucket if runtime equal or above"), unit = _("milliseconds"), default_value = 500),
                      Float(title = _("Critical if percentage of calls in critical bucket equal or above"), unit = _("%"), default_value = 5),
                      Integer(title = _("Assign calls to critical bucket if runtime equal or above"), unit = _("milliseconds"), default_value = 1000),
                  ])
            ),
        ]
    ),
    TextAscii(
        title = _("backend name"),
        help = _("The name of the backend system connected to the SAP Cloud Connector without the leading \"SAP CC Perf Backend\" string."),
        allow_empty = False,
    ),
    "dict"
) 

#
## EOF
