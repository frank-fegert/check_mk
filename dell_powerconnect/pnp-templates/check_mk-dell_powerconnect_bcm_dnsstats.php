<?php
#
# Copyright (C) 2015 - 2016  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   dell_powerconnect_bcm_dnsstats
# Check_MK check script. This template handles the graphs for the number
# of DNS queries (total and several error states defined by RFC-1035) on
# Dell PowerConnect switches.
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
$dsname = '';
$index = '';
$title = '';
$uom = '';
$vlabel = '';

# Graph definitions
foreach ($NAME as $idx => $dnsstat) {
    if (preg_match('/^cache_/', $dnsstat)) {
        # RRDtool Options
        $dsname = 'DNS Cache';
        $index = 'dns_cache';
        $title = 'DNS cache utilization';
        $vlabel = 'DNS cache entries';

        # Graph definitions
        if ( ! isset($def[$index]) ) {
            $def[$index] = "";
        }
        $def[$index] .= "DEF:$dnsstat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        if ( $dnsstat == 'cache_good' ) {
            $def[$index] .= "LINE:$dnsstat#40A018:\"RR cache successful\" " ;
        } else if ( $dnsstat == 'cache_bad' ) {
            $def[$index] .= "LINE:$dnsstat#FF0000:\"RR cache failed    \" " ;
        }
    } else if (preg_match('/^dns_rc_/', $dnsstat)) {
        # RRDtool Options
        $dsname = 'DNS Resolver RCODE';
        $index = 'dns_resolver_rcode';
        $title = 'DNS resolver RCODE statistics';
        $vlabel = 'DNS responses';

        # Graph definitions
        if ( ! isset($def[$index]) ) {
            $def[$index] = "";
        }
        $def[$index] .= "DEF:$dnsstat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        if ( $dnsstat == 'dns_rc_ok' ) {
            $def[$index] .= "LINE:$dnsstat#40A018:\"RCODE 0 - No error       \" " ;
        } else if ( $dnsstat == 'dns_rc_format' ) {
            $def[$index] .= "LINE:$dnsstat#FF0000:\"RCODE 1 - Format error   \" " ;
        } else if ( $dnsstat == 'dns_rc_server_failed' ) {
            $def[$index] .= "LINE:$dnsstat#FF00FF:\"RCODE 2 - Server failure \" " ;
        } else if ( $dnsstat == 'dns_rc_name_error' ) {
            $def[$index] .= "LINE:$dnsstat#000000:\"RCODE 3 - Name error     \" " ;
        } else if ( $dnsstat == 'dns_rc_not_supported' ) {
            $def[$index] .= "LINE:$dnsstat#2EFEF7:\"RCODE 4 - Not implemented\" " ;
        } else if ( $dnsstat == 'dns_rc_refused' ) {
            $def[$index] .= "LINE:$dnsstat#FF8000:\"RCODE 5 - Refused        \" " ;
        }
    } else {
        # RRDtool Options
        $dsname = 'DNS Resolver';
        $index = 'dns_resolver';
        $title = 'DNS resolver statistics';
        $vlabel = 'DNS responses';

        # Graph definitions
        if ( ! isset($def[$index]) ) {
            $def[$index] = "";
        }
        $def[$index] .= "DEF:$dnsstat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        if ( $dnsstat == 'dns_queries' ) {
            $def[$index] .= "LINE:$dnsstat#40A018:\"Total Queries           \" " ;
        } else if ( $dnsstat == 'dns_resp' ) {
            $def[$index] .= "LINE:$dnsstat#000000:\"Total Responses         \" " ;
        } else if ( $dnsstat == 'dns_na_data' ) {
            $def[$index] .= "LINE:$dnsstat#0040FF:\"Non-auth Answer         \" " ;
        } else if ( $dnsstat == 'dns_na_nodata' ) {
            $def[$index] .= "LINE:$dnsstat#A4A4A4:\"Non-auth No-answer      \" " ;
        } else if ( $dnsstat == 'dns_martians' ) {
            $def[$index] .= "LINE:$dnsstat#880000:\"Responses - Received    \" " ;
        } else if ( $dnsstat == 'dns_unparseable' ) {
            $def[$index] .= "LINE:$dnsstat#FF0000:\"Responses - Martians    \" " ;
        } else if ( $dnsstat == 'dns_records' ) {
            $def[$index] .= "LINE:$dnsstat#FF8000:\"Responses - Unparseable \" " ;
        } else if ( $dnsstat == 'dns_fallbacks' ) {
            $def[$index] .= "LINE:$dnsstat#2EFEF7:\"Fallbacks               \" " ;
        }
    }
    $def[$index] .= "GPRINT:$dnsstat:LAST:\"Current\: %6.2lf %s$uom\" ";
    $def[$index] .= "GPRINT:$dnsstat:MIN:\"Minimum\: %6.2lf %s$uom\" ";
    $def[$index] .= "GPRINT:$dnsstat:AVERAGE:\"Average\: %6.2lf %s$uom\" ";
    $def[$index] .= "GPRINT:$dnsstat:MAX:\"Maximum\: %6.2lf %s$uom\\n\" ";

    # Data source header name
    if ( ! isset($ds_name[$index]) ) {
        $ds_name[$index] = "$dsname";
    }

    # Graph options
    if ( ! isset($opt[$index]) ) {
        $opt[$index] = "--width 650 --slope-mode --vertical-label \"$vlabel\" -l 0 --title \"$title - $hostname\" ";
    }

}

#
## EOF
?>
