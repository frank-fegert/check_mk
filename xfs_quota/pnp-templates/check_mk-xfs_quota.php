<?php
#
# Copyright (C) 2017  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   xfs_quota
# Check_MK check script. This template handles the graph for the current
# block and inode quota usage on XFS filesystems.
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
# Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
# 02110-1301, USA.
#
#

# RRDtool Options
$uom='';

foreach ($NAME as $idx => $stat) {
    # Data source header name
    list ($type, $d1, $project) = explode('__', $stat);
    list ($d2, $fs, $d3) = explode('__', $LABEL[$idx]);

    $warn=$WARN[$idx];
    $crit=$CRIT[$idx];

    if ($type == 'b') {
        # RRDtool Options
        $vlabel='Blocks';
        # Data source header name
        $ds_name['blocks'] = "XFS Blocks";
        # Graph options
        $opt['blocks'] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 --title \"XFS Blocks - $hostname - $fs - $project\" ";

        # Graph definitions
        if ( ! isset($def['blocks']) ) {
            $def['blocks'] = "";
        }
        $def['blocks'] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        $def['blocks'] .= "AREA:$stat#40a018:\"Used blocks  \" " ;
        $def['blocks'] .= "GPRINT:$stat:LAST:\"Current\: %6.2lf %s$uom\" ";
        $def['blocks'] .= "GPRINT:$stat:MIN:\"Minimum\: %6.2lf %s$uom\" ";
        $def['blocks'] .= "GPRINT:$stat:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
        $def['blocks'] .= "GPRINT:$stat:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
        $def['blocks'] .= "HRULE:$warn#FFFF00:\"Warning on   $warn\\n\" ";
        $def['blocks'] .= "HRULE:$crit#FF0000:\"Critical on  $crit\\n\" ";
    } else if ($type == 'i') {
        # RRDtool Options
        $vlabel='Inodes';
        # Data source header name
        $ds_name['inodes'] = "XFS Inodes";
        # Graph options
        $opt['inodes'] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 --title \"XFS Inodes - $hostname - $fs - $project\" ";

        # Graph definitions
        if ( ! isset($def['inodes']) ) {
            $def['inodes'] = "";
        }
        $def['inodes'] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        $def['inodes'] .= "AREA:$stat#40a018:\"Used inodes  \" " ;
        $def['inodes'] .= "GPRINT:$stat:LAST:\"Current\: %6.2lf %s$uom\" ";
        $def['inodes'] .= "GPRINT:$stat:MIN:\"Minimum\: %6.2lf %s$uom\" ";
        $def['inodes'] .= "GPRINT:$stat:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
        $def['inodes'] .= "GPRINT:$stat:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";
        $def['inodes'] .= "HRULE:$warn#FFFF00:\"Warning on   $warn\\n\" ";
        $def['inodes'] .= "HRULE:$crit#FF0000:\"Critical on  $crit\\n\" ";
    }
}

#
## EOF
?>
