<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_cpu
# Check_MK check script. This template handles the graph for the current
# total cpu usage on Dell PowerConnect switches.
# This has currently been verified to work with the following Broadcom
# FastPath silicon based switch models:
#   PowerConnect M8024-k
#   PowerConnect M6348 
#
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
#

# RRDtool Options
$pre='';
$vlabel='CPU utilization (%)';
$uom_5=$UNIT[1];
$uom_60=$UNIT[2];
$uom_300=$UNIT[3];

$warn=$WARN[1];
$crit=$CRIT[1];

# Data source header name
$ds_name[1] = "CPU Utilization Averages";

# Graph options
$opt[1] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l0 -u 100 --title \"CPU Utilization - $hostname\"";

# Graph definitions
$def[1]  = "DEF:util_5=$RRDFILE[1]:$DS[1]:MAX " ;
$def[1] .= "DEF:util_60=$RRDFILE[2]:$DS[2]:MAX " ;
$def[1] .= "DEF:util_300=$RRDFILE[3]:$DS[3]:MAX " ;
$def[1] .= "AREA:util_5#40a018:\"CPU utilization (5s)\" " ;
$def[1] .= "GPRINT:util_5:LAST:\"Cur\: %.0lf %s$uom_5 \" " ;
$def[1] .= "GPRINT:util_5:AVERAGE:\"Avg\: %.0lf %s$uom_5 \" " ;
$def[1] .= "GPRINT:util_5:MIN:\"Min\: %.0lf %s$uom_5 \" " ;
$def[1] .= "GPRINT:util_5:MAX:\"Max\: %.0lf %s$uom_5 \\n\" " ;
$def[1] .= "LINE:util_60#0011FF:\"CPU utilization (1m)\" " ;
$def[1] .= "GPRINT:util_60:LAST:\"Cur\: %.0lf %s$uom_60 \" " ;
$def[1] .= "GPRINT:util_60:AVERAGE:\"Avg\: %.0lf %s$uom_60 \" " ;
$def[1] .= "GPRINT:util_60:MIN:\"Min\: %.0lf %s$uom_60 \" " ;
$def[1] .= "GPRINT:util_60:MAX:\"Max\: %.0lf %s$uom_60 \\n\" " ;
$def[1] .= "LINE:util_300#00AAFF:\"CPU utilization (5m)\" " ;
$def[1] .= "GPRINT:util_300:LAST:\"Cur\: %.0lf %s$uom_300 \" " ;
$def[1] .= "GPRINT:util_300:AVERAGE:\"Avg\: %.0lf %s$uom_300 \" " ;
$def[1] .= "GPRINT:util_300:MIN:\"Min\: %.0lf %s$uom_300 \" " ;
$def[1] .= "GPRINT:util_300:MAX:\"Max\: %.0lf %s$uom_300 \\n\" " ;
$def[1] .= "HRULE:$warn#FFFF00:\"Warning on   $warn $pre".preg_replace('/%%/', '%', $uom_5)." \\n\" ";
$def[1] .= "HRULE:$crit#FF0000:\"Critical on  $crit $pre".preg_replace('/%%/', '%', $uom_5)." \\n\" ";

#
## EOF
?>
