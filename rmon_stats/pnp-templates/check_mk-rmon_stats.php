<?php
# +------------------------------------------------------------------+
# |             ____ _               _        __  __ _  __           |
# |            / ___| |__   ___  ___| | __   |  \/  | |/ /           |
# |           | |   | '_ \ / _ \/ __| |/ /   | |\/| | ' /            |
# |           | |___| | | |  __/ (__|   <    | |  | | . \            |
# |            \____|_| |_|\___|\___|_|\_\___|_|  |_|_|\_\           |
# |                                                                  |
# | Copyright Mathias Kettner 2014             mk@mathias-kettner.de |
# +------------------------------------------------------------------+
#
# This file is part of Check_MK.
# The official homepage is at http://mathias-kettner.de/check_mk.
#
# check_mk is free software;  you can redistribute it and/or modify it
# under the  terms of the  GNU General Public License  as published by
# the Free Software Foundation in version 2.  check_mk is  distributed
# in the hope that it will be useful, but WITHOUT ANY WARRANTY;  with-
# out even the implied warranty of  MERCHANTABILITY  or  FITNESS FOR A
# PARTICULAR PURPOSE. See the  GNU General Public License for more de-
# ails.  You should have  received  a copy of the  GNU  General Public
# License along with GNU Make; see the file  COPYING.  If  not,  write
# to the Free Software Foundation, Inc., 51 Franklin St,  Fifth Floor,
# Boston, MA 02110-1301 USA.

$opt[1] = "--width 650 --vertical-label 'pkts/sec' -u 10 -X0 --title \"$servicedesc on $hostname\" ";

$stats = array(
  array(1, "Broadcast", "      ", "#777777", "\\n"),
  array(2, "Multicast", "      ", "#a00000", "\\n"),
  array(3, "0-63 Bytes", "     ", "#ff0000", "\\n"),
  array(4, "64-127 Bytes", "   ", "#ffc000", "\\n"),
  array(5, "128-255 Bytes", "  ", "#f000f0", "\\n"),
  array(6, "256-511 Bytes", "  ", "#00b0b0", "\\n"),
  array(7, "512-1024 Bytes", " ", "#c060ff", "\\n"),
  array(8, "1024-1518 Bytes", "", "#00f040", "\\n")
);

$def[1] = "";

foreach ($stats as $entry) {
    list($i, $desc, $spaces, $color, $nl) = $entry;
    $stat = preg_replace('/ /', '_', $desc);
    $def[1] .= "DEF:$stat=$RRDFILE[$i]:$DS[$i]:MAX ";
    $def[1] .= "AREA:$stat$color:\"$desc\":STACK ";
    $def[1] .= "GPRINT:$stat:LAST:\"".$spaces."Current\: %10.2lf\" ";
    $def[1] .= "GPRINT:$stat:MIN:\"Minimum\: %10.2lf\" ";
    $def[1] .= "GPRINT:$stat:AVERAGE:\"Average\: %10.2lf\" ";
    $def[1] .= "GPRINT:$stat:MAX:\"Maximum\: %10.2lf$nl\" ";
}

