<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_temp
# Check_MK check script. This template handles the graph for the current
# temperature on Dell PowerConnect switches.
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
$vlabel='Degree Celcius';
$uom='degC';

$warn=$WARN[1];
$crit=$CRIT[1];

$min=$MIN[1];
$max=$MAX[1];

list($tag, $unit, $unit_no, $sensor, $sensor_no ) = explode('_', $NAME[1]);

# Graph options
$opt[1] = "--width 650 --vertical-label \"$vlabel\" -l $min -u $max --title \"Temperature - Unit $unit_no Sensor $sensor_no - $hostname\" ";

# Graph definitions
$def[1]  = "DEF:temp=$RRDFILE[1]:$DS[1]:AVERAGE " ;
$def[1] .= "LINE2:temp#40A018:\"Temperature  \" " ;
$def[1] .= "GPRINT:temp:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:temp:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:temp:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:temp:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
$def[1] .= "HRULE:$warn#FFFF00:\"Warning on   $warn $pre$uom \\n\" ";
$def[1] .= "HRULE:$crit#FF0000:\"Critical on  $crit $pre$uom \\n\" ";       

#
# EOF
?>
