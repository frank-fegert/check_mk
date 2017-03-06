<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_mbuf
# Check_MK check script. This template handles the graph for the number
# of memory buffer allocations for packets arriving at the CPU on Dell
# PowerConnect switches.
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
$uom='';

foreach ($NAME as $idx => $stat) {
    if ($stat == 'mbuf_free') {
        # RRDtool Options
        $vlabel='Free Buffers';
        # Data source header name
        $ds_name['mbuf_free'] = "Memory Buffers Free";
        # Graph options
        $opt['mbuf_free'] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 --title \"Free Memory Buffers - $hostname\" ";

        # Graph definitions
        if ( ! isset($def['mbuf_free']) ) {
            $def['mbuf_free'] = "";
        }
        $def['mbuf_free'] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        $def['mbuf_free'] .= "AREA:$stat#40a018:\"Free buffers  \" " ;
        $def['mbuf_free'] .= "GPRINT:$stat:LAST:\"Current\: %6.2lf %s$uom\" ";
        $def['mbuf_free'] .= "GPRINT:$stat:MIN:\"Minimum\: %6.2lf %s$uom\" ";
        $def['mbuf_free'] .= "GPRINT:$stat:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
        $def['mbuf_free'] .= "GPRINT:$stat:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
    } else if (preg_match('/^mbuf_(?:alloc|failed)_/', $stat)) {
        # RRDtool Options
        $vlabel='Allocations';
        # Data source header name
        $metric = preg_replace('/^mbuf_(?:alloc|failed)_/', '', $stat);
        $class = ucwords(preg_replace('/_/', ' ', $metric));
        
        $ds_name[$metric] = "Memory Buffer $metric";

        # Graph options
        if ( ! isset($opt[$metric]) ) {
            $opt[$metric] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 --title \"Memory Buffer allocations - $class - $hostname\" ";
        }

        # Graph definitions
        if ( ! isset($def[$metric]) ) {
            $def[$metric] = "";
        }
        $def[$metric] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        if (preg_match('/^mbuf_alloc_/', $stat)) {
            $def[$metric] .= "LINE2:$stat#40a018:\"Total allocations  \" " ;
        } else if (preg_match('/^mbuf_failed_/', $stat)) {
            $def[$metric] .= "LINE:$stat#FF0000:\"Failed allocations \" " ;
        }
        $def[$metric] .= "GPRINT:$stat:LAST:\"Current\: %6.2lf %s$uom\" ";
        $def[$metric] .= "GPRINT:$stat:MIN:\"Minimum\: %6.2lf %s$uom\" ";
        $def[$metric] .= "GPRINT:$stat:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
        $def[$metric] .= "GPRINT:$stat:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
    }
}

#
## EOF
?>
