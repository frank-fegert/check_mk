#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2019  Frank Fegert (fra.nospam.nk@gmx.de)
#
# WATO plugin for the parametrization of the configuration values
# used by the "agent_sapcc" Check_MK special agent for the SAP
# Cloud Connector.
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
register_rulegroup("datasource_programs",
    _("Datasource Programs"),
    _("Specialized agents, e.g. check via SSH, ESX vSphere, SAP R/3"))

group = "datasource_programs"

register_rule(group,
    "special_agents:sapcc",
     Dictionary(
        title = _("SAP Cloud Connector systems"),
        help = _("This rule set selects the <tt>sapcc</tt> agent instead of the normal Check_MK Agent "
                 "and allows monitoring of SAP Cloud Connector systems by calling the monitoring API"
                 "of the SAP Cloud Connector over HTTP or HTTPS. "
                 "Make sure your monitoring user on the SAP Cloud Connector has the role <tt>sccmonitoring</tt>"
                 "or <tt>sccadmin</tt> assigned."
                 ),
        elements = [
            ( "user",
              TextAscii(
                  title = _("SAP Cloud Connector user name"),
                  allow_empty = False,
                  help = _("User name on the SAP Cloud Connector system."),
              )
            ),
            ( "password",
              Password(
                  title = _("SAP Cloud Connector password"),
                  allow_empty = False,
                  help = _("Password for the user on the SAP Cloud Connector system."),
              )
            ),
            ( "port",
              Integer(
                  title = _("SAP Cloud Connector TCP port"),
                  help = _("The TCP port for the monitoring API on a SAP Cloud Connector system."),
                  default_value = 8443,
              )
            ),
            ( "timeout",
              Integer(
                  title = _("Connect timeout to the SAP Cloud Connector monitoring API"),
                  help = _("The network timeout in seconds when communicating with the SAP Cloud Connector monitoring API via HTTP. "
                           "The default is 30 seconds."),
                  default_value = 30,
                  minvalue = 1,
                  unit = _("seconds"),
              )
            ),
        ],
        optional_keys = ['timeout'],
    ),
    factory_default = Rulespec.FACTORY_DEFAULT_UNUSED, # No default, do not use setting if no rule matches
    match = 'first')

#
## EOF
