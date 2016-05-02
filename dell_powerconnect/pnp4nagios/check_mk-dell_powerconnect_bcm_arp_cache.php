<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_arp_cache
# Check_MK check script. This template handles the graph for the current
# number of entries in the ARP cache on Dell PowerConnect switches.
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
$vlabel='Count';
$uom='';

$min=$MIN[1];
$max=$MAX[1];
$warn=$WARN[1];
$crit=$CRIT[1];

# Data source header name
$ds_name[1] = "ARP cache entries";

# Graph options
$opt[1] = "--width 650 --slope-mode --vertical-label $vlabel -l $min --title \"ARP cache entries - $hostname\" ";

# Graph definitions
$def[1]  = "DEF:arp_current_total=$RRDFILE[1]:$DS[1]:AVERAGE " ;
$def[1] .= "DEF:arp_max_total=$RRDFILE[2]:$DS[2]:AVERAGE " ;
$def[1] .= "DEF:arp_current_static=$RRDFILE[3]:$DS[3]:AVERAGE " ;
$def[1] .= "DEF:arp_max_static=$RRDFILE[4]:$DS[4]:AVERAGE " ;

$def[1] .= "LINE:arp_current_total#40A018:\"Total ARP Current  \" " ;
$def[1] .= "GPRINT:arp_current_total:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_current_total:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_current_total:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_current_total:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";

$def[1] .= "LINE:arp_max_total#005CFF:\"Total ARP Maximum  \" " ;
$def[1] .= "GPRINT:arp_max_total:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_max_total:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_max_total:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_max_total:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";

$def[1] .= "LINE:arp_current_static#00FFFF:\"Static ARP Current \" " ;
$def[1] .= "GPRINT:arp_current_static:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_current_static:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_current_static:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_current_static:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";

$def[1] .= "LINE:arp_max_static#FF00FF:\"Static ARP Maximum \" " ;
$def[1] .= "GPRINT:arp_max_static:LAST:\"Current\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_max_static:MIN:\"Minimum\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_max_static:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
$def[1] .= "GPRINT:arp_max_static:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";

$def[1] .= "HRULE:$warn#FFFF00:\"Warning on   $warn $pre$uom \\n\" ";
$def[1] .= "HRULE:$crit#FF0000:\"Critical on  $crit $pre$uom \\n\" ";

$def[1] .= "COMMENT:\" \\n\" ";
$def[1] .= "COMMENT:\"ARP cache size\: $max\" ";

#
## EOF
?>
