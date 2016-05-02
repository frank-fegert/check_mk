<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_cpu_proc
# Check_MK check script. This template handles the graphs for the current
# per process cpu usage on Dell PowerConnect switches.
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
$uom='';
$min=0;

# Graph definitions
foreach ($NAME as $idx => $proc) {
    # Data source header name
    $proc_display_name = preg_replace('/__(?:5|60|300)$/', '', $proc);
    $proc_ds = preg_replace('/\./', '_', $proc);
    $proc_name = preg_replace('/\./', '_', $proc_display_name);
    $ds_name[$proc_name] = "$proc_name";

    # RRDtool Options
    $uom[$proc_name]=$UNIT[$idx];
    $warn[$proc_name]=$WARN[$idx];
    $crit[$proc_name]=$CRIT[$idx];

    # Graph options
    if ( ! isset($opt[$proc_name]) ) {
        $opt[$proc_name] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 -u 100 --title \"CPU Utilization - $proc_display_name - $hostname\" ";
    }

    # Graph definitions
    if ( ! isset($def[$proc_name]) ) {
        $def[$proc_name] = "";
    }
    $def[$proc_name] .= "DEF:$proc_ds=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
    if (preg_match('/__5$/', $proc)) {
        $def[$proc_name] .= "AREA:$proc_ds#40a018:\"CPU utilization (5s)\" " ;
    } else if (preg_match('/__60$/', $proc)) {
        $def[$proc_name] .= "LINE:$proc_ds#0011FF:\"CPU utilization (1m)\" " ;
    } else if (preg_match('/__300$/', $proc)) {
        $def[$proc_name] .= "LINE:$proc_ds#00AAFF:\"CPU utilization (5m)\" " ;
    }
    $def[$proc_name] .= "GPRINT:$proc_ds:LAST:\"Current\: %6.2lf %s$uom[$proc_name]\" ";
    $def[$proc_name] .= "GPRINT:$proc_ds:MIN:\"Minimum\: %6.2lf %s$uom[$proc_name]\" ";
    $def[$proc_name] .= "GPRINT:$proc_ds:AVERAGE:\"Average\: %6.2lf %s$uom[$proc_name]\" ";
    $def[$proc_name] .= "GPRINT:$proc_ds:MAX:\"Maximum\: %6.2lf %s$uom[$proc_name]\\n\" ";
}

# Add warning and critical values to the end of each array member
foreach ($def as $idx => $rrd_def) {
    $def[$idx] .= "HRULE:$warn[$idx]#FFFF00:\"Warning on   $warn[$idx] $pre".preg_replace('/%%/', '%', $uom[$idx])." \\n\" ";
    $def[$idx] .= "HRULE:$crit[$idx]#FF0000:\"Critical on  $crit[$idx] $pre".preg_replace('/%%/', '%', $uom[$idx])." \\n\" ";
}

#
## EOF
?>
