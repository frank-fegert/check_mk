#
# Copyright (C) 2019 Frank Fegert (fra.nospam.nk@gmx.de)
#

# Check_MK metric for the application connections for each subaccount on
# a SAP Cloud Connector.
metric_info["app_conn"] = {
    "title" : _("Connections"),
    "unit"  : "count",
    "color" : "#A080B0",
}

# Check_MK metric for the percent of calls for each subaccount on a SAP
# Cloud Connector where the runtime is OK.
metric_info["calls_pct_ok"] = {
    "title" : _("Percent calls within OK runtime"),
    "unit"  : "%",
    "color" : "#00FF00",
}

# Check_MK metric for the percent of calls for each subaccount on a SAP
# Cloud Connector where the runtime is above the warning threshold.
metric_info["calls_pct_warn"] = {
    "title" : _("Percent calls within WARNING runtime"),
    "unit"  : "%",
    "color" : "#F8F800",
}

# Check_MK metric for the percent of calls for each subaccount on a SAP
# Cloud Connector where the runtime is above the critical threshold.
metric_info["calls_pct_crit"] = {
    "title" : _("Percent calls within CRITICAL runtime"),
    "unit"  : "%",
    "color" : "#FF0000",
}

# Check_MK metric for the total number of calls per second for each
# subaccount on a SAP Cloud Connector.
metric_info["calls_total"] = {
    "title" : _("Total calls per second"),
    "unit"  : "1/s",
    "color" : "#00FF00",
}

# Check_MK metric for the number of calls per second for each of the 22
# buckets for each subaccount on a SAP Cloud Connector. The metrics are
# dynamically generated from a list of bucket time definitions.
buckets = [10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 300, 400, 500,
           750, 1000, 1250, 1500, 2000, 2500, 3000, 4000, 5000]
counter = 0
for i in buckets:
    metric_info["calls_min_%d_ms" % i] = {
        "title" : _("Calls with runtime >=%dms") % i,
        "unit"  : "1/s",
        "color" : indexed_color(counter, len(buckets)),
    }
    counter += 1

# Check_MK metric for the tunnel connections for each subaccount on a SAP
# Cloud Connector.
metric_info["tunnel_conn"] = {
    "title" : _("Connections"),
    "unit"  : "count",
    "color" : "#A080B0",
}

# Check_MK metric for the tunnel connection time for each subaccount on
# a SAP Cloud Connector.
metric_info["tunnel_time"] = {
    "title" : _("Connection time"),
    "unit"  : "s",
    "color" : "#94B65A",
}


## Check_MK perf-o-meter to display the number of application connections
# for each subaccount on a SAP Cloud Connector.
perfometer_info.append({
    "type"       : "logarithmic",
    "metric"     : "app_conn",
    "half_value" : 10,
    "exponent"   : 10,
})

# Check_MK perf-o-meter to display the number of tunnel connections for
# each subaccount on a SAP Cloud Connector.
perfometer_info.append({
    "type"       : "logarithmic",
    "metric"     : "tunnel_conn",
    "half_value" : 10,
    "exponent"   : 10,
})

# Check_MK perf-o-meter to display the percent of calls for each sub-
# account on a SAP Cloud Connector where the runtime is OK, above the
# warning threshold and above the critical threshold.
perfometer_info.append({
    "type"     : "linear",
    "segments" : [
        "calls_pct_ok,0.5,+",
        "calls_pct_warn,0.5,+",
        "calls_pct_crit,0.5,+",
    ],
    "total"    : 100,
    "label"    : ( "calls_pct_ok", "%" ),
})


# Check_MK graph for the number of calls per second for three combinations
# of buckets for each subaccount on a SAP Cloud Connector. The graphs are
# dynamically generated from a list of bucket time definitions.
buckets = [ [10, 20, 30, 40, 50, 75, 100],
            [125, 150, 200, 300, 400, 500, 750, 1000],
            [1250, 1500, 2000, 2500, 3000, 4000, 5000] ]
counter = 0
for combination in buckets:
    val_min = combination[0]
    val_max = combination[-1]
    graph_info["calls_comb_%d" % counter] = {
        "title"   : _("Calls per second - Runtime >=%dms - >=%dms") % (val_min, val_max),
        "metrics" : [
            ( "calls_min_%d_ms" % i, "line" )
            for i in sorted(combination, reverse=True)
        ],
    }
    counter += 1

# Check_MK graph for the number of calls per second for each of the 22
# buckets for each subaccount on a SAP Cloud Connector. The graphs are
# dynamically generated from a list of bucket time definitions.
buckets = [10, 20, 30, 40, 50, 75, 100, 125, 150, 200, 300, 400, 500,
           750, 1000, 1250, 1500, 2000, 2500, 3000, 4000, 5000]
for i in buckets:
    graph_info["calls_min_%d_ms" % i] = {
        "title"   : _("Calls per second - Runtime >=%dms") % i,
        "metrics" : [
            ( "calls_min_%d_ms" % i, "area" ),
        ],
    }

# Check_MK graph to display the percent of calls for each subaccount on
# a SAP Cloud Connector where the runtime is OK, above the warning thres-
# hold and above the critical threshold.
graph_info["calls_pct"] = {
    "title"   : _("Percent calls within Ok, Warning and Critical runtime"),
    "metrics" : [
        ( "calls_pct_ok", "stacked" ),
        ( "calls_pct_warn", "stacked" ),
        ( "calls_pct_crit", "stacked" ),
    ],
    "range"   : (0, 100),
}

# Check_MK graph to display the connection time of tunnel connections for
# each subaccount on a SAP Cloud Connector.
graph_info["tunnel_time"] = {
    "title"   : _("Connection time"),
    "metrics" : [
        ( "tunnel_time", "area" ),
    ],
}


#
# Uncomment the following definitions if the standard Check_MK definitions
# for graphs and perf-o-meters should be used.
#

# Mapping of the "app_conn" metric to the default Check_MK "connections"
# graph for the number of application connections for each subaccount on
# a SAP Cloud Connector.
#check_metrics["check_mk-sapcc_subaccounts.app_conn"] = {
#    "app_conn"    : { "name" : "connections" }
#}

# Mapping of the "tunnel_conn" metric to the default Check_MK "connections"
# graph for the number of tunnel connections for each subaccount on a SAP
# Cloud Connector.
#check_metrics["check_mk-sapcc_subaccounts.tunnel"] = {
#    "tunnel_conn" : { "name" : "connections" },
#}

#
## EOF
