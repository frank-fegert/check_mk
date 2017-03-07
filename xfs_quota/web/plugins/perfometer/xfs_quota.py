#!/usr/bin/python
# -*- encoding: utf-8; py-indent-offset: 4 -*-
#
# Copyright (C) 2017  Frank Fegert (fra.nospam.nk@gmx.de)
#
# Check_MK perf-o-meter script to display the current quota usage on
# XFS filesystems.
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

def perfometer_check_mk_xfs_quota(row, check_command, perf_data):
    # Uncomment and restart Apache to debug:
    #return repr(perf_data), ''
    # Data sample:  
    # [
    #    (u'b__/srv/xfstest__test1', u'2097152', u'', u'100', u'50', u'', u''),
    #    (u'i__/srv/xfstest__test1', u'2', u'', u'0', u'0', u'', u'')
    # ]
    color_b = { 0: '#60E0A0', 1: '#FFFF00', 2: '#FF0000', 3: '#FFBF00' }[row["service_state"]]
    color_i = { 0: '#60A0E0', 1: '#FFFF80', 2: '#FF0080', 3: '#FFBF80' }[row["service_state"]]
    half_b = 100000000
    half_i = 1000000

    if (perf_data[0][0].startswith("b__")):
        blocks = float(perf_data[0][1])
    elif (perf_data[1][0].startswith("b__")):
        blocks = float(perf_data[1][1])
    if (perf_data[0][0].startswith("i__")):
        inodes = float(perf_data[0][1])
    elif (perf_data[1][0].startswith("i__")):
        inodes = float(perf_data[1][1])

    text = ""
    if (blocks >= 0):
        human_b = number_human_readable(blocks, 0, "Blk")
        text += "%s" % human_b
    if (inodes >= 0):
        human_i = number_human_readable(inodes, 0, "Inode")
        if (text):
            text += " / "
        text += "%s" % human_i
    return text, perfometer_logarithmic_dual_independent(
            blocks, color_b, half_b, 10, inodes, color_i, half_i, 10)

perfometers["check_mk-xfs_quota"] = perfometer_check_mk_xfs_quota

#
# EOF
