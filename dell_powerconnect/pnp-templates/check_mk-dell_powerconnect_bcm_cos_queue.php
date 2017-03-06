<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_cos_queue
# Check_MK check script. This template handles the graphs the number of
# packets dropped at each Cos queue on Dell PowerConnect switches.
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
$vlabel='Packets dropped';
$uom='';
$min=0;

# Graph definitions
foreach ($NAME as $idx => $queue) {
    # Data source header name
    list ($d1, $d2, $num_q) = explode('_', $queue);
    $ds_name[$idx] = "$queue";

    # Graph options
    $opt[$idx] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l $min --title \"CPU Cos Queue $num_q - $hostname\" ";

    # Graph definitions
    $def[$idx]  = "DEF:$queue=$RRDFILE[$idx]:$DS[$idx]:AVERAGE " ;
    $def[$idx] .= "AREA:$queue#005CFF:\"Packets dropped     \" " ;
    $def[$idx] .= "GPRINT:$queue:LAST:\"Current\: %6.2lf %s$uom\" ";
    $def[$idx] .= "GPRINT:$queue:MIN:\"Minimum\: %6.2lf %s$uom\" ";
    $def[$idx] .= "GPRINT:$queue:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
    $def[$idx] .= "GPRINT:$queue:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
}

#
## EOF
?>
