#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2018  Frank Fegert (fra.nospam.nk@gmx.de)
#
# Check_MK perf-o-meter script to display the current optical receive
# and transmit power levels on Brocade Fibre Channel switches. This
# has currently been verified to work with the following Brocade Fibre
# Channel switch models and FabricOS versions:
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

def perfometer_check_mk_brocade_sfp(row, check_command, perf_data):
    # Uncommet to debug:
    #return repr(perf_data), '<table><tr>' + perfometer_td(100, '#fff') + '</tr></table>'
    #
    # Data sample:  
    #  [(u'sfp_temp', 38, u'', 50, 60, None, None),
    #   (u'sfp_rx_power', -1.4, u'', None, None, None, None),
    #   (u'sfp_tx_power', -0.9, u'', None, None, None, None),
    #   (u'sfp_voltage', 3.362, u'', None, None, None, None),
    #   (u'sfp_current', 0.007872, u'', None, None, None, None)]
    #
    rx_power, tx_power = None, None
    unit = "dBm"
    for perf_item in perf_data:
        if "sfp_rx_power" in perf_item:
            rx_power = perf_item[1]
        if "sfp_tx_power" in perf_item:
            tx_power = perf_item[1]

    text = "%s&nbsp;&nbsp;&nbsp;%s" % ( number_human_readable(rx_power, 1, unit), number_human_readable(tx_power, 1, unit) )
    return text, perfometer_logarithmic_dual(abs(rx_power), "#0e6", abs(tx_power), "#2af", 5, 10)

perfometers["check_mk-brocade_sfp"]  = perfometer_check_mk_brocade_sfp
#
# EOF
