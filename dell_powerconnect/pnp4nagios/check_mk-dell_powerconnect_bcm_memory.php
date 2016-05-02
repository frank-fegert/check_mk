<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_memory
# Check_MK check script. This template handles the graph for the current
# memory usage on Dell PowerConnect switches.
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
$pre='k';
$vlabel='Bytes';
$uom='B';

$kb_max=$ACT[2];
$kb_warn=$ACT[2]-$WARN[1];
$kb_crit=$ACT[2]-$CRIT[1];

$b_max=$kb_max*1024;
$b_warn=$kb_warn*1024;
$b_crit=$kb_crit*1024;

# Graph options
$opt[1] = "--width 650 --vertical-label $vlabel -l 0 -u $b_max --title \"Memory usage - $hostname\" ";

# Graph definitions
$def[1]  = "DEF:var2=$RRDFILE[2]:$DS[1]:AVERAGE " ;
$def[1] .= "CDEF:total=var2,1024,* ";
$def[1] .= "DEF:var1=$RRDFILE[1]:$DS[1]:AVERAGE " ;
$def[1] .= "CDEF:tmp1=var1,1024,* ";
$def[1] .= "CDEF:used=$b_max,tmp1,- ";
$def[1] .= "CDEF:free=total,used,- ";

$def[1] .= "AREA:total#40A018:\"Free Memory  \" " ;
$def[1] .= "GPRINT:free:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:free:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:free:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:free:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
$def[1] .= "AREA:used#005CFF:\"Used Memory  \" " ;
$def[1] .= "GPRINT:used:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:used:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:used:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:used:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
$def[1] .= "HRULE:$b_max#003300:\"Total Memory $kb_max $pre$uom \\n\" ";
$def[1] .= "HRULE:$b_warn#FFFF00:\"Warning on   $kb_warn $pre$uom \\n\" ";
$def[1] .= "HRULE:$b_crit#FF0000:\"Critical on  $kb_crit $pre$uom \\n\" ";       

#
# EOF
?>
