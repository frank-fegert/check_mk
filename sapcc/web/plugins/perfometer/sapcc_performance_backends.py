#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2019  Frank Fegert (fra.nospam.nk@gmx.de)
#
# Check_MK perf-o-meter script to display the percent of calls
# for each subaccount on a SAP Cloud Connector where the runtime
# is OK, above the warning threshold and above the critical thres-
# hold.
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

def perfometer_check_mk_sapcc_performance_backends(row, check_command, perf_data):
    # Uncomment to debug:
    #return repr(check_command), 'TEST'
    #return repr(perf_data), 'TEST'
    #
    # Data sample:  
    # [(u'calls_total', 0, u'', None, None, None, None),
    #  (u'calls_min_10_ms', 0, u'', None, None, None, None),
    #  ...
    #  (u'calls_min_5000_ms', 0, u'', None, None, None, None),
    #  (u'calls_pct_ok', 98.5, u'%', 10, 5, 0, 100),
    #  (u'calls_pct_warn', 1.53, u'%', 10, 5, 0, 100),
    #  (u'calls_pct_crit', 0.0, u'%', 10, 5, 0, 100)]
    val_ok = 0.0
    val_warn = 0.0
    val_crit = 0.0
    tab = '<table>'
    if (perf_data[-3][0] == 'calls_pct_ok' and perf_data[-2][0] == 'calls_pct_warn' and perf_data[-1][0] == 'calls_pct_crit'):
        val_ok = float(perf_data[-3][1])
        val_warn = float(perf_data[-2][1])
        val_crit = float(perf_data[-1][1])
        tab += perfometer_td(round(val_ok), '#00FF00')
        tab += perfometer_td(round(val_warn), '#F8F800')
        tab += perfometer_td(round(val_crit), '#FF0000')
    tab += '</table>'
    return "%0.1f%% / %0.1f%% / %0.1f%%" % (val_ok, val_warn, val_crit), tab

perfometers['check_mk-sapcc_performance_backends'] = perfometer_check_mk_sapcc_performance_backends

#
## EOF
