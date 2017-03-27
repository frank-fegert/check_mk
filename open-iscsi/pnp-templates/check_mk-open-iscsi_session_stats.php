<?php
#
# Copyright (C) 2017  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   open-iscsi_session_stats
# Check_MK check script. This template handles the graphs for the
# current data transfer rate, errors as well as several protocol
# data units of Open-iSCSI sessions.
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

# RRDtool Options
$uom='';
list ($desc[0], $desc[1], $desc[2], $mac, $target) = explode(' ', $NAGIOS_AUTH_SERVICEDESC);
$desc = $desc[0]." ".$desc[1]." ".$desc[2];

$legend = array( "noptx_pdus"     => array("color" => "5f7a2f", "desc" => 'TX NOP       ', "dir" => 'out'),
                 "noprx_pdus"     => array("color" => "2f8077", "desc" => 'RX NOP       ', "dir" => 'in'),
                 "scsicmd_pdus"   => array("color" => "2f5580", "desc" => 'TX SCSI Cmd  ', "dir" => 'out'),
                 "scsirsp_pdus"   => array("color" => "662f80", "desc" => 'RX SCSI Cmd  ', "dir" => 'in'),
                 "tmfcmd_pdus"    => array("color" => "802f71", "desc" => 'TX TMF       ', "dir" => 'out'),
                 "tmfrsp_pdus"    => array("color" => "802f36", "desc" => 'RX TMF       ', "dir" => 'in'),
                 "text_pdus"      => array("color" => "804b2f", "desc" => 'TX Text      ', "dir" => 'out'),
                 "textrsp_pdus"   => array("color" => "80762f", "desc" => 'RX Text      ', "dir" => 'in'),
                 "dataout_pdus"   => array("color" => "ffec5f", "desc" => 'TX Data      ', "dir" => 'out'),
                 "datain_pdus"    => array("color" => "ff975f", "desc" => 'RX Data      ', "dir" => 'in'),
                 "login_pdus"     => array("color" => "ff5f6c", "desc" => 'TX Login     ', "dir" => 'out'),
                 "logout_pdus"    => array("color" => "ff5fe2", "desc" => 'TX Logout    ', "dir" => 'out'),
                 "logoutrsp_pdus" => array("color" => "cc5fff", "desc" => 'TX Logout    ', "dir" => 'out'),
                 "snack_pdus"     => array("color" => "5faaff", "desc" => 'TX SNACK     ', "dir" => 'out'),
                 "r2t_pdus"       => array("color" => "5fffef", "desc" => 'RX R2T       ', "dir" => 'in'),
                 "async_pdus"     => array("color" => "beff5f", "desc" => 'RX Async Mesg', "dir" => 'in'),
                 "rjt_pdus"       => array("color" => "00f0ff", "desc" => 'RX Reject    ', "dir" => 'in'),
             );

foreach ($NAME as $idx => $stat) {
    $max=0;
    $min=0;
    if ( isset($WARN[$idx]) and $WARN[$idx] != '' ) {
        $warn=$WARN[$idx];
    } else {
        $warn=0;
    }
    if ( isset($CRIT[$idx]) and $CRIT[$idx] != '' ) {
        $crit=$CRIT[$idx];
    } else {
        $crit=0;
    }

    if ( $stat == 'rxdata_octets' or $stat == 'txdata_octets' ) {
        # Unit
        $uom='B/s';
        # RRDtool Options
        $vlabel='Bytes/s';
        # Data source header name
        $ds_name['rate'] = "$desc - Data transfer rate";
        # Graph options
        $opt['rate'] = "--width 750 --slope-mode --vertical-label \"$vlabel\" --title \"$mac - $target\" ";

        # Graph definitions
        if ( ! isset($def['rate']) ) {
            $def['rate'] = "";
        }
        $def['rate'] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        if ( $stat == 'rxdata_octets' ) {
            $def['rate'] .= "AREA:$stat#00E060:\"RX  \" " ;
        } else if ( $stat == 'txdata_octets' ) {
            $def['rate'] .= "CDEF:neg_$stat=$stat,-1,* ";
            $def['rate'] .= "AREA:neg_$stat#0080E0:\"TX  \" " ;
        }
        $def['rate'] .= "GPRINT:$stat:LAST:\"Current\: %7.2lf %s$uom\" ";
        $def['rate'] .= "GPRINT:$stat:MIN:\"Minimum\: %7.2lf %s$uom\" ";
        $def['rate'] .= "GPRINT:$stat:AVERAGE:\"Average\: %7.2lf %s$uom\" ";
        $def['rate'] .= "GPRINT:$stat:MAX:\"Maximum\: %7.2lf %s$uom\\n\" ";
        if ( $stat == 'rxdata_octets' ) {
            $def['rate'] .= "VDEF:percent_$stat=$stat,95,PERCENTNAN ";
            $def['rate'] .= "LINE:percent_$stat#008F00:\"RX 95% percentile\" ";
        } else if ( $stat == 'txdata_octets' ) {
            $def['rate'] .= "VDEF:percent_$stat=neg_$stat,5,PERCENTNAN ";
            $def['rate'] .= "LINE:percent_$stat#00008F:\"TX 95% percentile\" ";
        }
        $def['rate'] .= "GPRINT:percent_$stat:\"%7.1lf %s$uom\\n\" ";
    } else if ( $stat == 'digest_err' or $stat == 'timeout_err' ) {
        # Unit
        $uom='1/s';
        # RRDtool Options
        $vlabel='Errors/s';
        # Data source header name
        $ds_name['errors'] = "$desc - Error rate";
        # Graph options
        $opt['errors'] = "--width 750 --slope-mode --vertical-label \"$vlabel\" --title \"$mac - $target\" ";

        # Graph definitions
        if ( ! isset($def['errors']) ) {
            $def['errors'] = "";
        }
        $def['errors'] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        if ( $stat == 'digest_err' ) {
            $def['errors'] .= "AREA:$stat#40A018:\"Digest (CRC) errors  \" " ;
        } else if ( $stat == 'timeout_err' ) {
            $def['errors'] .= "AREA:$stat#005CFF:\"Timeout errors       \":STACK " ;
        }
        $def['errors'] .= "GPRINT:$stat:LAST:\"Current\: %7.2lf %s$uom\" ";
        $def['errors'] .= "GPRINT:$stat:MIN:\"Minimum\: %7.2lf %s$uom\" ";
        $def['errors'] .= "GPRINT:$stat:AVERAGE:\"Average\: %7.2lf %s$uom\" ";
        $def['errors'] .= "GPRINT:$stat:MAX:\"Maximum\: %7.2lf %s$uom\\n\" ";
        if ( $stat == 'digest_err' ) {
            $def['errors'] .= "HRULE:$warn#FFFF00:\"Warning on     $warn\\n\" ";
            $def['errors'] .= "HRULE:$crit#FF0000:\"Critical on    $crit\\n\" ";
        } else if ( $stat == 'timeout_err' ) {
            $def['errors'] .= "HRULE:$warn#FFA000:\"Warning on     $warn\\n\" ";
            $def['errors'] .= "HRULE:$crit#FF00FF:\"Critical on    $crit\\n\" ";
        }
    } else {
        # Unit
        $uom='PDU/s';
        # RRDtool Options
        $vlabel='PDU/s';
        # Data source header name
        $ds_name['pdu'] = "$desc - Protocol Data Unit (PDU) rate";
        # Graph options
        $opt['pdu'] = "--width 750 --slope-mode --vertical-label \"$vlabel\" --title \"$mac - $target\" ";

        # Graph definitions
        if ( ! isset($def['pdu']) ) {
            $def['pdu'] = "";
        }
        $def['pdu'] .= "DEF:$stat=$RRDFILE[$idx]:$DS[$idx]:MAX " ;
        if ( $legend[$stat]['dir'] == 'in' ) {
            $def['pdu'] .= "LINE1:$stat#".$legend[$stat]['color'].":\"".$legend[$stat]['desc']."\" " ;
        } else if ( $legend[$stat]['dir'] == 'out' ) {
            $def['pdu'] .= "CDEF:neg_$stat=$stat,-1,* ";
            $def['pdu'] .= "LINE1:neg_$stat#".$legend[$stat]['color'].":\"".$legend[$stat]['desc']."\" " ;
        }
        $def['pdu'] .= "GPRINT:$stat:LAST:\"Current\: %7.2lf %s$uom\" ";
        $def['pdu'] .= "GPRINT:$stat:MIN:\"Minimum\: %7.2lf %s$uom\" ";
        $def['pdu'] .= "GPRINT:$stat:AVERAGE:\"Average\: %7.2lf %s$uom\" ";
        $def['pdu'] .= "GPRINT:$stat:MAX:\"Maximum\: %7.2lf %s$uom\\n\" ";
    }
}

#
## EOF
?>
