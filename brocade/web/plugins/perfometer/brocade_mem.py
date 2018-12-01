#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2018  Frank Fegert (fra.nospam.nk@gmx.de)
#
# Check_MK perf-o-meter script to display the current memory usage on
# Brocade Fibre Channel switches. This has currently been verified to
# work with the following Brocade Fibre Channel switch models and
# FabricOS versions:
#   Brocade G620 (v8.1.2a)
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

def perfometer_check_mk_brocade_mem(row, check_command, perf_data):
    # Uncommet to debug:
    #return repr(perf_data), ''
    color = { 0: '#00FF00', 1: '#FFFF00', 2: '#FF0000', 3: '#FFBF00' }[row["service_state"]]
    # Data sample:  
    # [(u'memory_used', u'18', u'', u'80', u'90', u'0', u'100')]
    if (perf_data[0][0] == "memory_used"):
        val = float(perf_data[0][1])
        return "%0.0f%%" % val, perfometer_linear(val, color)

perfometers["check_mk-brocade_mem"]  = perfometer_check_mk_brocade_mem
#
# EOF

