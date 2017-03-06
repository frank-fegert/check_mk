<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_cpu_proc
# Check_MK check script. This template handles the graph for the current
# status of the SNTP client on Dell PowerConnect switches.
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
$vlabel='Requests';
$uom='';
$min=0;

# Graph definitions
foreach ($NAME as $idx => $stat) {
    # Data source header name
    list($metric, $server) = explode('__', $stat);
    $server_display_name = preg_replace('/_/', '.', $server);
    $ds_name[$server] = "$server_display_name";

    # Graph options
    if ( ! isset($opt[$server]) ) {
        $opt[$server] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 --title \"SNTP Requests to Server $server_display_name - $hostname\" ";
    }

    # Graph definitions
    if ( ! isset($def[$server]) ) {
        $def[$server] = "";
    }
    $def[$server] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
    if ($metric == 'req_total') {
        $def[$server] .= "LINE2:$stat#40a018:\"Total requests \" " ;
    } elseif ($metric == 'req_failed') {
        $def[$server] .= "LINE:$stat#FF0000:\"Failed requests\" " ;
    }
    $def[$server] .= "GPRINT:$stat:LAST:\"Current\: %6.2lf %s$uom\" ";
    $def[$server] .= "GPRINT:$stat:MIN:\"Minimum\: %6.2lf %s$uom\" ";
    $def[$server] .= "GPRINT:$stat:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
    $def[$server] .= "GPRINT:$stat:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
}

#
## EOF
?>
