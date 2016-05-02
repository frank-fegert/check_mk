#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# Check_MK perf-o-meter script to display the current total cpu usage of
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

def perfometer_check_mk_dell_powerconnect_bcm_cpu(row, check_command, perf_data):
    # Uncommet to debug:
    #return repr(perf_data), ''
    color5 = { 0: '#00FF00', 1: '#FFFF00', 2: '#FF0000', 3: '#FFBF00' }[row["service_state"]]
    color60 = { 0: '#00CF00', 1: '#CFCF00', 2: '#CF0000', 3: '#FFBF00' }[row["service_state"]]
    color300 = { 0: '#00AF00', 1: '#AFAF00', 2: '#AF0000', 3: '#FFBF00' }[row["service_state"]]
    # Data sample:  
    # [
    #   (u'cpu_total_5', u'5.50', u'%', u'80', u'90', u'', u''),
    #   (u'cpu_total_60', u'5.83', u'%', u'80', u'90', u'', u''),
    #   (u'cpu_total_300', u'6.45', u'%', u'80', u'90', u'', u'')
    # ]

    tab = '<table>'
    if (perf_data[0][0] == "cpu_total_5"):
        pctval5 = float(perf_data[0][1])
        tab += perfometer_td(pctval5 / 3, color5)
        tab += perfometer_td((100 - pctval5) / 3, '#FFFFFF')
    if (perf_data[1][0] == "cpu_total_60"):
        pctval60 = float(perf_data[1][1])
        tab += perfometer_td(pctval60 / 3, color60)
        tab += perfometer_td((100 - pctval60) / 3, '#FFFFFF')
    if (perf_data[2][0] == "cpu_total_300"):
        pctval300 = float(perf_data[2][1])
        tab += perfometer_td(pctval300 / 3, color300)
        tab += perfometer_td((100 - pctval300) / 3, '#FFFFFF')
    tab += '</table>'
    return "%0.2f / %0.2f / %0.2f" % (pctval5,pctval60,pctval300), tab

perfometers["check_mk-dell_powerconnect_bcm_cpu"]  = perfometer_check_mk_dell_powerconnect_bcm_cpu
#
# EOF
