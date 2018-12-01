metric_info["sfp_rx_power"] = {
    "title" : _("Receive power"),
    "unit"  : "dbm",
    "color" : "#20c080",
}

metric_info["sfp_tx_power"] = {
    "title" : _("Transmit power"),
    "unit"  : "dbm",
    "color" : "#2080c0",
}

perfometer_info.append(("dual", [
    {
        "type"          : "logarithmic",
        "metric"        : "sfp_rx_power",
        "half_value"    : 5,
        "exponent"      : 10,
    },
    {
        "type"          : "logarithmic",
        "metric"        : "sfp_tx_power",
        "half_value"    : 5,
        "exponent"      : 10,
    }
]))

graph_info["sfp_power"] = {
    "title" : _("Receive and Transmit Signal Power"),
    "metrics" : [
        ( "sfp_rx_power", "area" ),
        ( "sfp_tx_power", "-area" )
    ]
}

check_metrics["check_mk-brocade_sfp"] = {
    "sfp_voltage"  : { "name" : "voltage" },
    "sfp_current"  : { "name" : "current"},
    "sfp_rx_power" : { "name" : "sfp_rx_power"},
    "sfp_tx_power" : { "name" : "sfp_tx_power"},
    "sfp_temp"     : { "name" : "temperature" }
}
