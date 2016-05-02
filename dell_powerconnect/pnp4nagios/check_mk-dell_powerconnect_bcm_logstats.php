<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_logstats
# Check_MK check script. This template handles the graph for the number
# of log messages (total, dropped, relayed to syslog hosts) on Dell Power-
# Connect switches.
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
$vlabel='Messages';
$uom='';

# Data source header name
$ds_name['syslog'] = "Log messages";

foreach ($NAME as $idx => $stat) {
    # Graph options
    if ( ! isset($opt['syslog']) ) {
        $opt['syslog'] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 --title \"Log messages - $hostname\" ";
    }

    # Graph definitions
    if ( ! isset($def['syslog']) ) {
        $def['syslog'] = "";
    }
    $def['syslog'] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
    if ($stat == 'log_msg_total') {
        $def['syslog'] .= "LINE2:$stat#40a018:\"Total messages  \" " ;
    } elseif ($stat == 'log_msg_dropped') {
        $def['syslog'] .= "LINE:$stat#FF0000:\"Dropped messages\" " ;
    } elseif ($stat == 'log_msg_relayed') {
        $def['syslog'] .= "LINE:$stat#0040FF:\"Relayed messages\" " ;
    }
    $def['syslog'] .= "GPRINT:$stat:LAST:\"Current\: %6.2lf %s$uom\" ";
    $def['syslog'] .= "GPRINT:$stat:MIN:\"Minimum\: %6.2lf %s$uom\" ";
    $def['syslog'] .= "GPRINT:$stat:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
    $def['syslog'] .= "GPRINT:$stat:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
}

#
## EOF
?>
