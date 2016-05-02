<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_ssh_sessions
# Check_MK check script. This template handles the graph for the number
# of currently active SSH sessions on Dell PowerConnect switches.
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
$vlabel='Sessions';
$uom='';
$min=0;

$warn=$WARN[1];
$crit=$CRIT[1];

# Graph options
$opt[1] = "--width 650 --vertical-label \"$vlabel\" -l $min --title \"SSH sessions - $hostname\" ";

# Graph definitions
$def[1]  = "DEF:sessions=$RRDFILE[1]:$DS[1]:AVERAGE " ;
$def[1] .= "LINE2:sessions#40A018:\"SSH sessions  \" " ;
$def[1] .= "GPRINT:sessions:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:sessions:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:sessions:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:sessions:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
$def[1] .= "HRULE:$warn#FFFF00:\"Warning on   $warn $pre$uom \\n\" ";
$def[1] .= "HRULE:$crit#FF0000:\"Critical on  $crit $pre$uom \\n\" ";       

#
# EOF
?>
