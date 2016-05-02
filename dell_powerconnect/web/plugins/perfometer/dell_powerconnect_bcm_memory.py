#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# Check_MK perf-o-meter script to display the current memory usage of
# Dell PowerConnect switches. This has currently been verified to work
# with the following Broadcom FastPath silicon based switch models:
#   PowerConnect M8024-k
#   PowerConnect M6348 
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

def perfometer_check_mk_dell_powerconnect_bcm_memory(row, check_command, perf_data):
    # Uncommet to debug:
    #return repr(perf_data), ''
    color = { 0: '#00FF00', 1: '#FFFF00', 2: '#FF0000', 3: '#FFBF00' }[row["service_state"]]
    # Data sample:  
    # [(u'mem_free', u'26962', u'', u'25600', u'12800', u'', u''), (u'mem_total', u'524288', u'', u'', u'', u'', u'')]
    if (perf_data[0][0] == "mem_free" and perf_data[1][0] == "mem_total"):
        val = int(perf_data[0][1])
        maxval = int(perf_data[1][1])
        pctval = float(float(val)/float(maxval)*100)
        return "%0.0f KB free" % val, perfometer_linear(pctval, color)

perfometers["check_mk-dell_powerconnect_bcm_memory"]  = perfometer_check_mk_dell_powerconnect_bcm_memory
#
# EOF
