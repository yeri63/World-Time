<?php
    # Program: world.php
    #    Date: 02-Jul-2023
    #  Author: gerry.walterscheid@gmail.com
    # Descrip: Display time for cities around the world.

    session_start();

    header("Content-type: text/xml");
    header("Refresh:10");

    $urlPath = "http://192.168.3.250/cisco/world.php";

    # Recall prior variable state
    $id = $_SESSION['id'] ?? "";                # Index pointing to item.

    # Prevent data corruption from prior application.
    if ($id == "world") {
        $i = $_SESSION['i'];                    # 1st timezone on display.
        $update = $_SESSION['update']; }        # Time from last page update.
    else { 
        $i = 0;
        $update = 0; }

    $timestamp = time();                        # Record current time.

    $city = array(
        "Midway", "Honolulu", "Tahiti Island", 
        "Anchorage", "San Francisco", "Vancouver", 
        "San Jose, CR", "Easter Island", "Bogota", 
        "Chicago", "Guayaquil", "Mexico City", 
        "Panama", "New York City", "Toronto", 
        "Bermuda", "Buenos_Aires", "Rio de Janeiro", 
        "Cape_Verde", "Azores Is.", "Greenwich", 
        "Iceland", "Canary Is.", "Lisbon", 
        "London", "Berlin", "Madrid", 
        "Paris", "Rome", "Stockholm", 
        "Bagdad", "Istanbul", "Moscow", 
        "Dubai", "Tehran", "New Delhi", 
        "Bangkok", "Hong Kong", "Shanghai", 
        "Singapore", "Taipei", "Seoul", 
        "Tokyo", "Guam", "Sydney", 
        "Solomon Is.", "Fiji Island", "Auckland");

    $tz = array(
        "Pacific/Midway", "Pacific/Honolulu", "Pacific/Tahiti", 
        "America/Anchorage", "America/Los_Angeles", "America/Vancouver", 
        "America/Costa_Rica", "Pacific/Easter", "America/Bogota", 
        "America/Chicago", "America/Guayaquil", "America/Mexico_City", 
        "America/Panama", "America/New_York", "America/Toronto", 
        "Atlantic/Bermuda", "America/Argentina/Buenos_Aires", "America/Sao_Paulo",
        "Atlantic/Cape_Verde", "Atlantic/Azores", "GMT",
        "Atlantic/Reykjavik", "Atlantic/Canary", "Europe/Lisbon", 
        "Europe/London", "Europe/Berlin", "Europe/Madrid", 
        "Europe/Paris", "Europe/Rome", "Europe/Stockholm", 
        "Asia/Baghdad", "Europe/Istanbul", "Europe/Moscow",
        "Asia/Dubai", "Asia/Tehran", "Asia/Kolkata", 
        "Asia/Bangkok", "Asia/Hong_Kong", "Asia/Shanghai", 
        "Asia/Singapore", "Asia/Taipei", "Asia/Seoul", 
        "Asia/Tokyo", "Pacific/Guam", "Australia/Sydney",
        "Pacific/Kosrae", "Pacific/Fiji", "Pacific/Auckland");

	$count = 48;                                # Number of timezones.
	$list = 9;                                  # Number of lines to display.

    # Get SoftKey selection.
    $cmd = $_GET['key'] ?? 0;                   # User command.

    # Ignore old commands generated after page refresh delay.
    if (time() - $update < 5)
        switch($cmd) {
        case 1 : # prev
            $i = (--$i + $count) % $count;
            break;

        case 2 : # next
            $i = ++$i % $count;
            break;
        }

    echo "<CiscoIPPhoneText>\n";
    echo "  <Title>World Time</Title>\n";
    echo "  <Text>";

    # Set home timezone time difference from GMT.
    $offset = 5;

    # Display city and time zone to the user.
    for ($j = 0; $j < $list; $j++) {
        $ndx = ($i + $j) % 48;
        date_default_timezone_set("$tz[$ndx]");
        list($hr,$min) = explode(':', date("P", time()));

        $hr = strval($hr) + strval($min) / 60 + $offset;
        if ($hr > -1) 
            $hr = "+" . $hr;

        echo date('D, H:i  ') . $city[$ndx] . " ($hr hr)"; 

        if ($j < $list - 1) 
             echo "\n"; }

    echo "</Text>\n";
       
    echo "  <SoftKeyItem>\n";
    echo "    <Name>Prev</Name>\n";
    echo "    <URL>$urlPath?key=1</URL>\n";
    echo "    <Position>1</Position>\n";
    echo "  </SoftKeyItem>\n";
            
    echo "  <SoftKeyItem>\n";
    echo "    <Name>Next</Name>\n";
    echo "    <URL>$urlPath?key=2</URL>\n";
    echo "    <Position>2</Position>\n";
    echo "  </SoftKeyItem>\n";
            
    echo "  <SoftKeyItem>\n";
    echo "    <Name>Exit</Name>\n";
    echo "    <URL>SoftKey:Exit</URL>\n";
    echo "    <Position>4</Position>\n";
    echo "  </SoftKeyItem>\n";

    echo "</CiscoIPPhoneText>";

    # Save current variable state.
    $_SESSION['id'] = "world";
    $_SESSION['cmd'] = $cmd;
    $_SESSION['i'] = $i;
    $_SESSION['update'] = $timestamp;
?>
