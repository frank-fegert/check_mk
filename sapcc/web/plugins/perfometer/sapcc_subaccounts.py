#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2019  Frank Fegert (fra.nospam.nk@gmx.de)
#
# Check_MK perf-o-meter script to display the number of tunnel
# and application connections for each subaccount on a SAP Cloud
# Connector.
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
# 02110-1301, USA.#
#

def perfometer_check_mk_sapcc_subaccounts_conn(row, check_command, perf_data):
    # Uncomment to debug:
    #return repr(check_command), 'TEST'
    #return repr(perf_data), 'TEST'
    color = { 0: '#00FF00', 1: '#FFFF00', 2: '#FF0000', 3: '#FFBF00' }[row['service_state']]
    # Data sample:  
    # [(u'app_conn', 8, u'', None, None, None, None)]
    if (perf_data[0][0] == 'app_conn' or perf_data[0][0] == 'tunnel_conn'):
        val = int(perf_data[0][1])
        return '%0d Connections' % val, perfometer_logarithmic(val, 10, 10, color)

perfometers['check_mk-sapcc_subaccounts.app_conn'] = perfometer_check_mk_sapcc_subaccounts_conn
perfometers['check_mk-sapcc_subaccounts.tunnel'] = perfometer_check_mk_sapcc_subaccounts_conn

#
## EOF
