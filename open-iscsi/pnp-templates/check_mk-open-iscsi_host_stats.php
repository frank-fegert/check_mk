<?php
#
# Copyright (C) 2017  Frank Fegert (fra.nospam.nk@gmx.de)
#
# PNP4Nagios template for the:
#   open-iscsi_host_stats
# Check_MK check script. This template handles the graphs for the
# current data transfer rate, errors as well as several protocol
# specific counters on several OSI layers of iSOE hosts.
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
list ($desc[0], $desc[1], $desc[2], $mac, $host) = explode(' ', $NAGIOS_AUTH_SERVICEDESC);
# Shorten iSOE host name by stripping vendor prefix
$host = preg_replace('/^iqn.2000-04.com.qlogic:/', '', $host);
$desc = $desc[0]." ".$desc[1]." ".$desc[2];

$legend = array(
    # Graph 1 - Traffic in bytes on various layers of the OSI stack
    "traffic_bytes" => array(
        "mactx_bytes"           => array("color" => "005CFF", "desc" => 'MAC TX Bytes    ', "title" => 'Traffic Bytes', "dir" => 'out'),
        "macrx_bytes"           => array("color" => "40A018", "desc" => 'MAC RX Bytes    ', "title" => 'Traffic Bytes', "dir" => 'in'),
        "iptx_bytes"            => array("color" => "FFEC5F", "desc" => 'IP TX Bytes     ', "title" => 'Traffic Bytes', "dir" => 'out'),
        "iprx_bytes"            => array("color" => "BEFF5F", "desc" => 'IP RX Bytes     ', "title" => 'Traffic Bytes', "dir" => 'in'),
        "ipv6tx_bytes"          => array("color" => "FF5F6C", "desc" => 'IPv6 TX Bytes   ', "title" => 'Traffic Bytes', "dir" => 'out'),
        "ipv6rx_bytes"          => array("color" => "FF5FE2", "desc" => 'IPv6 RX Bytes   ', "title" => 'Traffic Bytes', "dir" => 'in'),
        "tcptx_bytes"           => array("color" => "5FAAFF", "desc" => 'TCP TX Bytes    ', "title" => 'Traffic Bytes', "dir" => 'out'),
        "tcprx_byte"            => array("color" => "CC5FFF", "desc" => 'TCP RX Bytes    ', "title" => 'Traffic Bytes', "dir" => 'in'),
        "iscsi_data_bytes_tx"   => array("color" => "5F7A2F", "desc" => 'iSCSI TX Bytes ', "title" => 'Traffic Bytes', "dir" => 'out'),
        "iscsi_data_bytes_rx"   => array("color" => "00F0FF", "desc" => 'iSCSI RX Bytes ', "title" => 'Traffic Bytes', "dir" => 'in'),
    ),
    # Graph 2 - Traffic in frames on the MAC layer
    "mac_frames" => array(
        "mactx_frames"                  => array("color" => "005CFF", "desc" => 'TX Frames           ', "title" => 'MAC Frames', "dir" => 'out'),
        "macrx_frames"                  => array("color" => "40A018", "desc" => 'RX Frames           ', "title" => 'MAC Frames', "dir" => 'in'),
        "mactx_multicast_frames"        => array("color" => "FFEC5F", "desc" => 'TX Mcast Frames     ', "title" => 'MAC Frames', "dir" => 'out'),
        "macrx_multicast_frames"        => array("color" => "BEFF5F", "desc" => 'RX Mcast Frames     ', "title" => 'MAC Frames', "dir" => 'in'),
        "mactx_broadcast_frames"        => array("color" => "FF5F6C", "desc" => 'TX Bcast Frames     ', "title" => 'MAC Frames', "dir" => 'out'),
        "macrx_broadcast_frames"        => array("color" => "FF5FE2", "desc" => 'RX Bcast Frames     ', "title" => 'MAC Frames', "dir" => 'in'),
        "mactx_pause_frames"            => array("color" => "5FAAFF", "desc" => 'TX Pause Frames     ', "title" => 'MAC Frames', "dir" => 'out'),
        "macrx_pause_frames"            => array("color" => "CC5FFF", "desc" => 'RX Pause Frames     ', "title" => 'MAC Frames', "dir" => 'in'),
        "mactx_control_frames"          => array("color" => "5F7A2F", "desc" => 'TX Ctrl Frames      ', "title" => 'MAC Frames', "dir" => 'out'),
        "macrx_control_frames"          => array("color" => "00F0FF", "desc" => 'RX Ctrl Frames      ', "title" => 'MAC Frames', "dir" => 'in'),
        "macrx_unknown_control_frames"  => array("color" => "FF975F", "desc" => 'RX Unkn Ctrl Frames ', "title" => 'MAC Frames', "dir" => 'in'),
        "mactx_jumbo_frames"            => array("color" => "80762F", "desc" => 'TX Jumbo Frames     ', "title" => 'MAC Frames', "dir" => 'out'),
        "mactx_frames_dropped"          => array("color" => "662F80", "desc" => 'TX Dropped Frames   ', "title" => 'MAC Frames', "dir" => 'out'),
        "macrx_frames_dropped"          => array("color" => "2F8077", "desc" => 'RX Dropped Frames   ', "title" => 'MAC Frames', "dir" => 'in'),
        "macrx_frame_discarded"         => array("color" => "2F5580", "desc" => 'RX Discarded Frames ', "title" => 'MAC Frames', "dir" => 'in'),
    ),
    # Graph 3 - Errors on the MAC layer
    "mac_errors" => array(
        "mactx_deferral"                => array("color" => "005CFF", "desc" => 'TX Deferral            ', "title" => 'MAC Errors', "dir" => 'out'),
        "mactx_excess_deferral"         => array("color" => "40A018", "desc" => 'TX Excess Deferral     ', "title" => 'MAC Errors', "dir" => 'out'),
        "mactx_late_collision"          => array("color" => "FFEC5F", "desc" => 'TX Late Collision      ', "title" => 'MAC Errors', "dir" => 'out'),
        "mactx_abort"                   => array("color" => "BEFF5F", "desc" => 'TX Abort               ', "title" => 'MAC Errors', "dir" => 'out'),
        "mactx_single_collision"        => array("color" => "FF5F6C", "desc" => 'TX Single Collision    ', "title" => 'MAC Errors', "dir" => 'out'),
        "mactx_multiple_collision"      => array("color" => "FF5FE2", "desc" => 'TX Multiple Collision  ', "title" => 'MAC Errors', "dir" => 'out'),
        "mactx_collision"               => array("color" => "5FAAFF", "desc" => 'TX Collision           ', "title" => 'MAC Errors', "dir" => 'out'),
        "macrx_dribble"                 => array("color" => "CC5FFF", "desc" => 'RX Dribble             ', "title" => 'MAC Errors', "dir" => 'in'),
        "macrx_frame_length_error"      => array("color" => "5F7A2F", "desc" => 'RX Frame Length Error  ', "title" => 'MAC Errors', "dir" => 'in'),
        "macrx_jabber"                  => array("color" => "00F0FF", "desc" => 'RX Jabber              ', "title" => 'MAC Errors', "dir" => 'in'),
        "macrx_carrier_sense_error"     => array("color" => "FF975F", "desc" => 'RX Carrier Sense Error ', "title" => 'MAC Errors', "dir" => 'in'),
        "mac_crc_error"                 => array("color" => "80762F", "desc" => 'CRC Error              ', "title" => 'MAC Errors', "dir" => 'out'),
        "mac_encoding_error"            => array("color" => "662F80", "desc" => 'Encoding Error         ', "title" => 'MAC Errors', "dir" => 'out'),
        "macrx_length_error_large"      => array("color" => "2F8077", "desc" => 'RX Length Error Large  ', "title" => 'MAC Errors', "dir" => 'in'),
        "macrx_length_error_small"      => array("color" => "2F5580", "desc" => 'RX Length Error Small  ', "title" => 'MAC Errors', "dir" => 'in'),
    ),
    # Graph 4 - Traffic in packets on the IP layer
    "ip_packets" => array(
        "iptx_packets"          => array("color" => "005CFF", "desc" => 'IP TX Packets     ', "title" => 'IP Packets and Fragments', "dir" => 'out'),
        "iprx_packets"          => array("color" => "40A018", "desc" => 'IP RX Packets     ', "title" => 'IP Packets and Fragments', "dir" => 'in'),
        "ipv6tx_packets"        => array("color" => "FF5F6C", "desc" => 'IPv6 TX Packets   ', "title" => 'IP Packets and Fragments', "dir" => 'out'),
        "ipv6rx_packets"        => array("color" => "FF5FE2", "desc" => 'IPv6 RX Packets   ', "title" => 'IP Packets and Fragments', "dir" => 'in'),
        "iptx_fragments"        => array("color" => "00F0FF", "desc" => 'IP TX Fragments   ', "title" => 'IP Packets and Fragments', "dir" => 'out'),
        "iprx_fragments"        => array("color" => "BEFF5F", "desc" => 'IP RX Fragments   ', "title" => 'IP Packets and Fragments', "dir" => 'in'),
        "ipv6tx_fragments"      => array("color" => "FFEC5F", "desc" => 'IPv6 TX Fragments ', "title" => 'IP Packets and Fragments', "dir" => 'out'),
        "ipv6rx_fragments"      => array("color" => "5FAAFF", "desc" => 'IPv6 RX Fragments ', "title" => 'IP Packets and Fragments', "dir" => 'in'),
    ),
    # Graph 5 - Errors on the IP layer
    "ip_errors" => array(
        "ip_datagram_reassembly"            => array("color" => "005CFF", "desc" => 'IP Datagram Assy          ', "title" => 'IP Errors', "dir" => 'out'),
        "ipv6_datagram_reassembly"          => array("color" => "40A018", "desc" => 'IPv6 Datagram Assy        ', "title" => 'IP Errors', "dir" => 'out'),
        "ip_invalid_address_error"          => array("color" => "FFEC5F", "desc" => 'IP Address Error          ', "title" => 'IP Errors', "dir" => 'out'),
        "ipv6_invalid_address_error"        => array("color" => "BEFF5F", "desc" => 'IPv6 Address Error        ', "title" => 'IP Errors', "dir" => 'out'),
        "ip_error_packets"                  => array("color" => "FF5F6C", "desc" => 'IP Error Packets          ', "title" => 'IP Errors', "dir" => 'out'),
        "ipv6_error_packets"                => array("color" => "FF5FE2", "desc" => 'IPv6 Error Packets        ', "title" => 'IP Errors', "dir" => 'out'),
        "ip_fragrx_overlap"                 => array("color" => "5FAAFF", "desc" => 'IP Frag Overlap           ', "title" => 'IP Errors', "dir" => 'out'),
        "ipv6_fragrx_overlap"               => array("color" => "CC5FFF", "desc" => 'IPv6 Frag Overlap         ', "title" => 'IP Errors', "dir" => 'out'),
        "ip_fragrx_outoforder"              => array("color" => "5F7A2F", "desc" => 'IP Frag Out-of-order      ', "title" => 'IP Errors', "dir" => 'out'),
        "ipv6_fragrx_outoforder"            => array("color" => "00F0FF", "desc" => 'IPv6 Frag Out-of-order    ', "title" => 'IP Errors', "dir" => 'out'),
        "ip_datagram_reassembly_timeout"    => array("color" => "FF975F", "desc" => 'IP Dgram Reassy Timeout   ', "title" => 'IP Errors', "dir" => 'out'),
        "ipv6_datagram_reassembly_timeout"  => array("color" => "80762F", "desc" => 'IPv6 Dgram Reassy Timeout ', "title" => 'IP Errors', "dir" => 'out'),
    ),
    # Graph 6 - Traffic in segments on the TCP layer
    "tcp_segements" => array(
        "tcptx_segments"        => array("color" => "005CFF", "desc" => 'TX Segments ', "title" => 'TCP Segments', "dir" => 'out'),
        "tcprx_segments"        => array("color" => "40A018", "desc" => 'RX Segments ', "title" => 'TCP Segments', "dir" => 'in'),
    ),
    # Graph 7 - Errors on the TCP layer
    "tcp_errors" => array(
        "tcp_duplicate_ack_retx"        => array("color" => "005CFF", "desc" => 'Dup ACK Retrans      ', "title" => 'TCP Errors', "dir" => 'out'),
        "tcp_retx_timer_expired"        => array("color" => "40A018", "desc" => 'Retrans Timer Exp    ', "title" => 'TCP Errors', "dir" => 'out'),
        "tcprx_duplicate_ack"           => array("color" => "FFEC5F", "desc" => 'RX Dup ACK           ', "title" => 'TCP Errors', "dir" => 'in'),
        "tcprx_pure_ackr"               => array("color" => "BEFF5F", "desc" => 'RX Pure ACK          ', "title" => 'TCP Errors', "dir" => 'in'),
        "tcptx_delayed_ack"             => array("color" => "FF5F6C", "desc" => 'TX Delayed ACK       ', "title" => 'TCP Errors', "dir" => 'out'),
        "tcptx_pure_ack"                => array("color" => "FF5FE2", "desc" => 'TX Pure ACK          ', "title" => 'TCP Errors', "dir" => 'out'),
        "tcprx_segment_error"           => array("color" => "5FAAFF", "desc" => 'RX Seg Error         ', "title" => 'TCP Errors', "dir" => 'in'),
        "tcprx_segment_outoforder"      => array("color" => "CC5FFF", "desc" => 'RX Seg Out-of-order  ', "title" => 'TCP Errors', "dir" => 'in'),
        "tcprx_window_probe"            => array("color" => "5F7A2F", "desc" => 'RX Win Probe         ', "title" => 'TCP Errors', "dir" => 'in'),
        "tcprx_window_update"           => array("color" => "00F0FF", "desc" => 'RX Win Update        ', "title" => 'TCP Errors', "dir" => 'in'),
        "tcptx_window_probe_persist"    => array("color" => "FF975F", "desc" => 'TX Win Probe Persist ', "title" => 'TCP Errors', "dir" => 'out'),
    ),
    # Graph 8 - Traffic in PDUs on the iSCSI layer
    "iscsi_pdus" => array(
        "iscsi_pdu_tx"              => array("color" => "005CFF", "desc" => 'TX PDUs ', "title" => 'iSCSI PDUs', "dir" => 'out'),
        "iscsi_pdu_rx"              => array("color" => "40A018", "desc" => 'RX PDUs ', "title" => 'iSCSI PDUs', "dir" => 'in'),
    ),
    # Graph 9 - Errors on the iSCSI layer
    "iscsi_errors" => array(
        "iscsi_io_completed"        => array("color" => "005CFF", "desc" => 'iSCSI I/O Completed             ', "title" => 'iSCSI Errors', "dir" => 'in'),
        "iscsi_unexpected_io_rx"    => array("color" => "40A018", "desc" => 'iSCSI I/O Unexpected            ', "title" => 'iSCSI Errors', "dir" => 'in'),
        "iscsi_format_error"        => array("color" => "FF5F6C", "desc" => 'iSCSI Format Error              ', "title" => 'iSCSI Errors', "dir" => 'in'),
        "iscsi_hdr_digest_error"    => array("color" => "FF5FE2", "desc" => 'iSCSI Header Digest (CRC) Error ', "title" => 'iSCSI Errors', "dir" => 'in'),
        "iscsi_data_digest_error"   => array("color" => "00F0FF", "desc" => 'iSCSI Data Digest (CRC) Error   ', "title" => 'iSCSI Errors', "dir" => 'in'),
        "iscsi_sequence_error"      => array("color" => "BEFF5F", "desc" => 'iSCSI Sequence Error            ', "title" => 'iSCSI Errors', "dir" => 'in'),
    ),
    # Graph 10 - ECC Errors on the iSOE
    "isoe" => array(
        "ecc_error_correction"      => array("color" => "005CFF", "desc" => 'Error Corrections', "title" => 'ECC Error Correction', "dir" => 'out')
    ),
);


foreach ($NAME as $idx => $stat) {
    $category='';
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

    # Graph 1 - Traffic in bytes on various layers of the OSI stack
    if ( preg_match('/^((mac|ip|ipv6|tcp)(rx|tx)_byte(|s)|iscsi_data_bytes_(rx|tx))$/', $stat) ) {
        $category='traffic_bytes';
        $uom='B/s';
        $vlabel='Bytes/s';
    # Graph 2 - Traffic in frames on the MAC layer
    } else if ( preg_match('/^mac(rx|tx)_(frames|((multicast|broadcast|pause|control|unknown_control|jumbo)_frames|)|frames_dropped|frame_discarded)$/', $stat) ) {
        $category='mac_frames';
        $uom='F/s';
        $vlabel='Frames/s';
    # Graph 3 - Errors on the MAC layer
    } else if ( preg_match('/^mac(rx|tx|)_(deferral|excess_deferral|abort|(late_|single_|multiple_|)collision|dribble|jabber|(frame_length|carrier_sense|crc|encoding)_error|length_error_(large|small))$/', $stat) ) {
        $category='mac_errors';
        $uom='F/s';
        $vlabel='Frames/s';
    # Graph 4 - Traffic in packets on the IP layer
    } else if ( preg_match('/^ip(|v6)(rx|tx)_(packets|fragments)$/', $stat) ) {
        $category='ip_packets';
        $uom='P/s';
        $vlabel='Packets/s';
    # Graph 5 - Errors on the IP layer
    } else if ( preg_match('/^ip(|v6)_(datagram_reassembly|invalid_address_error|error_packets|fragrx_overlap|fragrx_outoforder|datagram_reassembly_timeout)$/', $stat) ) {
        $category='ip_errors';
        $uom='E/s';
        $vlabel='Errors/s';
    # Graph 6 - Traffic in segments on the TCP layer
    } else if ( preg_match('/^tcp(r|t)x_segments$/', $stat) ) {
        $category='tcp_segements';
        $uom='S/s';
        $vlabel='Segments/s';
    # Graph 7 - Errors on the TCP layer
    } else if ( preg_match('/^tcp(rx|tx|)_(duplicate_ack_retx|retx_timer_expired|duplicate_ack|pure_ackr|delayed_ack|pure_ack|segment_error|segment_outoforder|window_(probe|update|probe_persist))$/', $stat) ) {
        $category='tcp_errors';
        $uom='E/s';
        $vlabel='Errors/s';
    # Graph 8 - Traffic in PDUs on the iSCSI layer
    } else if ( preg_match('/^iscsi_pdu_(r|t)x$/', $stat) ) {
        $category='iscsi_pdus';
        $uom='PDU/s';
        $vlabel='PDU/s';
    # Graph 9 - Errors on the iSCSI layer
    } else if ( preg_match('/^iscsi_(io_completed|unexpected_io_rx|(format|hdr_digest|data_digest|sequence)_error)$/', $stat) ) {
        $category='iscsi_errors';
        $uom='E/s';
        $vlabel='Errors/s';
    # Graph 10 - ECC Errors on the iSOE
    } else if ( $stat == 'ecc_error_correction' ) {
        $category='isoe';
        $uom='E/s';
        $vlabel='Errors/s';
    # Skip unknown metrics
    } else {
        continue;
    }

    # Data source header name
    $ds_name[$category] = "$desc - iSOE ".$legend[$category][$stat]['title'];
    # Graph options
    $opt[$category] = "--width 750 --slope-mode --vertical-label \"$vlabel\" --title \"$mac - $host - ".$legend[$category][$stat]['title']."\" ";

    # Graph definitions
    if ( ! isset($def[$category]) ) {
        $def[$category] = "";
    }
    $def[$category] .= "DEF:$stat=".$RRDFILE[$idx].":".$DS[$idx].":MAX " ;
    if ( $legend[$category][$stat]['dir'] == 'in' ) {
        $def[$category] .= "LINE1:$stat#".$legend[$category][$stat]['color'].":\"".$legend[$category][$stat]['desc']."\" " ;
    } else if ( $legend[$category][$stat]['dir'] == 'out' ) {
        $def[$category] .= "CDEF:neg_$stat=$stat,-1,* ";
        $def[$category] .= "LINE1:neg_$stat#".$legend[$category][$stat]['color'].":\"".$legend[$category][$stat]['desc']."\" " ;
    }
    $def[$category] .= "GPRINT:$stat:LAST:\"Current\: %7.2lf %s$uom\" ";
    $def[$category] .= "GPRINT:$stat:MIN:\"Minimum\: %7.2lf %s$uom\" ";
    $def[$category] .= "GPRINT:$stat:AVERAGE:\"Average\: %7.2lf %s$uom\" ";
    $def[$category] .= "GPRINT:$stat:MAX:\"Maximum\: %7.2lf %s$uom\\n\" ";
}

#
## EOF
?>
