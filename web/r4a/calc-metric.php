<?php
/* ----------------------------------------------------------------------------- */
$AVG_DAILY_ELEC = 11000/365;  /* 11000 kWh ELECTRICITY per year */
$AVG_DAILY_PTRL = 1892/365;    /* 1892 liters GASOLINE/PETROL per year */
$AVG_DAILY_NGAS = 2778/365;   /* 2778 cubic meters NATURAL GAS per year */
$AVG_DAILY_PROP = 4163/365;   /* 4163 liters PROPANE per year */
$AVG_DAILY_HOIL = 2839/365;    /* 2839 liters HEATING OIL per year */
$AVG_DAILY_WOOD = 50/365;     /* 50 cords (non-sustainable) WOOD per year */
$AVG_DAILY_TRASH = 2;       /* 2 kg TRASH per person, per day */
$AVG_DAILY_WATER = 378;       /* 378 liters WATER per person, per day */
$AVG_DAILY_GOODS = 10000/365;  /* $10,000 CONSUMER GOODS per year */

$TARGET_FOOD_LOC = 70; /* 70% local organic */
$TARGET_FOOD_DRY = 25; /* 25% dry / bulk */
$TARGET_FOOD_WET = 5; /* 5% wet / other */

function debug () {
  echo "<div id='debug'><h3>Debugging Information</h3><table border=0>";
  foreach($_REQUEST as $key => $value) echo "<tr><td align=right>$key:</td><td> $value</td></tr>";
  echo "<tr><td colspan=2>file info:</td></tr>";
  foreach($_FILES['userfile'] as $key => $value) echo "<tr><td align=right>$key:</td><td> $value</td></tr>";
  foreach($_FILES['userfile1'] as $key => $value) echo "<tr><td align=right>$key:</td><td> $value</td></tr>";
  foreach($_FILES['userfile2'] as $key => $value) echo "<tr><td align=right>$key:</td><td> $value</td></tr>";
  echo "</table></div>";
}


$submitted = strip_tags($_REQUEST['submitted']);
$overall_pct = 0;

/* --- TRANSPORTATION ----------------------------------------------------------------------- */
$std_ptrl = strip_tags($_REQUEST['std_ptrl']); if (! is_numeric($std_ptrl)) { $std_ptrl = 0; }
$pt_ptrl = strip_tags($_REQUEST['pt_ptrl']); if (! is_numeric($pt_ptrl)) { $pt_ptrl = 0; }
$wvo_ptrl = strip_tags($_REQUEST['wvo_ptrl']); if (! is_numeric($wvo_ptrl)) { $wvo_ptrl = 0; }

$std_per = strip_tags($_REQUEST['std_per']);
if (("$std_per" != "day") and ($std_per != "week") and ($std_per != "month") and ($std_per != 'year')) { $std_per = 'month'; }
if ("$std_per" == "day") { $std_day="SELECTED"; $std_div = 1; } else { $std_day=""; }
if ("$std_per" == "week") { $std_week="SELECTED"; $std_div = 7; } else { $std_week=""; }
if ("$std_per" == "month") { $std_month="SELECTED"; $std_div = 30; } else { $std_month=""; }
if ("$std_per" == "year") { $std_year="SELECTED"; $std_div = 365; } else { $std_year=""; }

$pt_per = strip_tags($_REQUEST['pt_per']);
if (("$pt_per" != "day") and ($pt_per != "week") and ($pt_per != "month") and ($pt_per != 'year')) { $pt_per = 'month'; }
if ("$pt_per" == "day") { $pt_day="SELECTED"; $pt_div = 1; } else { $pt_day=""; }
if ("$pt_per" == "week") { $pt_week="SELECTED"; $pt_div = 7; } else { $pt_week=""; }
if ("$pt_per" == "month") { $pt_month="SELECTED"; $pt_div = 30; } else { $pt_month=""; }
if ("$pt_per" == "year") { $pt_year="SELECTED"; $pt_div = 365; } else { $pt_year=""; }

$wvo_per = strip_tags($_REQUEST['wvo_per']);
if (("$wvo_per" != "day") and ($wvo_per != "week") and ($wvo_per != "month") and ($wvo_per != 'year')) { $wvo_per = 'month'; }
if ("$wvo_per" == "day") { $wvo_day="SELECTED"; $wvo_div = 1; } else { $wvo_day=""; }
if ("$wvo_per" == "week") { $wvo_week="SELECTED"; $wvo_div = 7; } else { $wvo_week=""; }
if ("$wvo_per" == "month") { $wvo_month="SELECTED"; $wvo_div = 30; } else { $wvo_month=""; }
if ("$wvo_per" == "year") { $wvo_year="SELECTED"; $wvo_div = 365; } else { $wvo_year=""; }

$std_pct = sprintf("%0.1f",($std_ptrl / $std_div) / $AVG_DAILY_PTRL * 100);
$pt_pct = sprintf("%0.1f",($pt_ptrl / $pt_div / 161) / $AVG_DAILY_PTRL * 100);
$wvo_pct = sprintf("%0.1f",($wvo_ptrl / $wvo_div / 161) / $AVG_DAILY_PTRL * 100);
$total_pct = $std_pct + $wvo_pct + $pt_pct;
$you_len = sprintf("%0.0f",$total_pct*2);
if ( $you_len > 250 ) { $you_len = 250; $dots = "&gt;&gt;&gt;"; } else { $dots = ""; }
$you_img = "yellowpix.gif";
if ( $total_pct > 100 ) { $you_img = "redpix.gif"; }
if ( $total_pct <= 10 ) { $you_img = "greenpix.gif"; }


$avg_bar = "<tr><td align=right>US Avg.</td><td><img src='graypix.gif' width=200 height=10></td></tr>";
$you_bar = "<tr><td align=right><i>Your Usage</i></td><td><img src='$you_img' width=$you_len height=10>$dots</td></tr>";
$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='graypix.gif' width=20 height=10></td></tr>";

if ( $submitted == "yes" ) {
  $ptrl_results="<td align=center>You have used<br><font color='#3333FF'><b>$total_pct %</b></font><br> of the national average<br>for transportation fuel</td>";
  $ptrl_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$avg_bar $you_bar $tgt_bar </table></td>";
}

$overall_pct += $total_pct;

/* --- ELECTRICITY -------------------------------------------------------------------------- */
$conv_kwh = strip_tags($_REQUEST['conv_kwh']); if (! is_numeric($conv_kwh)) { $conv_kwh = 0; }
$solar_kwh = strip_tags($_REQUEST['solar_kwh']); if (! is_numeric($solar_kwh)) { $solar_kwh = 0; }
$wind_kwh = strip_tags($_REQUEST['wind_kwh']); if (! is_numeric($wind_kwh)) { $wind_kwh = 0; }

$conv_per = strip_tags($_REQUEST['conv_per']);
if (("$conv_per" != "day") and ($conv_per != "week") and ($conv_per != "month") and ($conv_per != 'year')) { $conv_per = 'month'; }
if ("$conv_per" == "day") { $conv_day="SELECTED"; $conv_div = 1; } else { $conv_day=""; }
if ("$conv_per" == "week") { $conv_week="SELECTED"; $conv_div = 7; } else { $conv_week=""; }
if ("$conv_per" == "month") { $conv_month="SELECTED"; $conv_div = 30; } else { $conv_month=""; }
if ("$conv_per" == "year") { $conv_year="SELECTED"; $conv_div = 365; } else { $conv_year=""; }

$solar_per = strip_tags($_REQUEST['solar_per']);
if (("$solar_per" != "day") and ($solar_per != "week") and ($solar_per != "month") and ($solar_per != 'year')) { $solar_per = 'month'; }
if ("$solar_per" == "day") { $solar_day="SELECTED"; $solar_div = 1; } else { $solar_day=""; }
if ("$solar_per" == "week") { $solar_week="SELECTED"; $solar_div = 7; } else { $solar_week=""; }
if ("$solar_per" == "month") { $solar_month="SELECTED"; $solar_div = 30; } else { $solar_month=""; }
if ("$solar_per" == "year") { $solar_year="SELECTED"; $solar_div = 365; } else { $solar_year=""; }

$wind_per = strip_tags($_REQUEST['wind_per']);
if (("$wind_per" != "day") and ($wind_per != "week") and ($wind_per != "month") and ($wind_per != 'year')) { $wind_per = 'month'; }
if ("$wind_per" == "day") { $wind_day="SELECTED"; $wind_div = 1; } else { $wind_day=""; }
if ("$wind_per" == "week") { $wind_week="SELECTED"; $wind_div = 7; } else { $wind_week=""; }
if ("$wind_per" == "month") { $wind_month="SELECTED"; $wind_div = 30; } else { $wind_month=""; }
if ("$wind_per" == "year") { $wind_year="SELECTED"; $wind_div = 365; } else { $wind_year=""; }

$conv_pct = sprintf("%0.1f",($conv_kwh / $conv_div) / $AVG_DAILY_ELEC * 100);
$solar_pct = sprintf("%0.1f",($solar_kwh / $solar_div / 2) / $AVG_DAILY_ELEC * 100);
$wind_pct = sprintf("%0.1f",($wind_kwh / $wind_div / 4) / $AVG_DAILY_ELEC * 100);
$total_pct = $conv_pct + $wind_pct + $solar_pct;
$you_len = sprintf("%0.0f",$total_pct*2);
if ( $you_len > 250 ) { $you_len = 250; $dots = "&gt;&gt;&gt;"; } else { $dots = ""; }
$you_img = "yellowpix.gif";
if ( $total_pct > 100 ) { $you_img = "redpix.gif"; }
if ( $total_pct <= 10 ) { $you_img = "greenpix.gif"; }


$avg_bar = "<tr><td align=right>US Avg.</td><td><img src='graypix.gif' width=200 height=10></td></tr>";
$you_bar = "<tr><td align=right><i>Your Usage</i></td><td><img src='$you_img' width=$you_len height=10>$dots</td></tr>";
$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='graypix.gif' width=20 height=10></td></tr>";

if ( $submitted == "yes" ) {
  $elec_results="<td align=center>You have used<br><font color='#3333FF'><b>$total_pct %</b></font><br> of the national average<br>for electricity</td>";
  $elec_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$avg_bar $you_bar $tgt_bar </table></td>";
}

$overall_pct += $total_pct;

/* --- HEATING AND COOKING ------------------------------------------------------------------ */
$ng_heat = strip_tags($_REQUEST['ng_heat']); if (! is_numeric($ng_heat)) { $ng_heat = 0; }
$prop_heat = strip_tags($_REQUEST['prop_heat']); if (! is_numeric($prop_heat)) { $prop_heat = 0; }
$hoil_heat = strip_tags($_REQUEST['hoil_heat']); if (! is_numeric($hoil_heat)) { $hoil_heat = 0; }
$wood_heat = strip_tags($_REQUEST['wood_heat']); if (! is_numeric($wood_heat)) { $wood_heat = 0; }

$ng_per = strip_tags($_REQUEST['ng_per']);
if (("$ng_per" != "day") and ($ng_per != "week") and ($ng_per != "month") and ($ng_per != 'year')) { $ng_per = 'year'; }
if ("$ng_per" == "day") { $ng_day="SELECTED"; $conv_div = 1; } else { $ng_day=""; }
if ("$ng_per" == "week") { $ng_week="SELECTED"; $ng_div = 7; } else { $ng_week=""; }
if ("$ng_per" == "month") { $ng_month="SELECTED"; $ng_div = 30; } else { $ng_month=""; }
if ("$ng_per" == "year") { $ng_year="SELECTED"; $ng_div = 365; } else { $ng_year=""; }

$prop_per = strip_tags($_REQUEST['prop_per']);
if (("$prop_per" != "day") and ($prop_per != "week") and ($prop_per != "month") and ($prop_per != 'year')) { $prop_per = 'year'; }
if ("$prop_per" == "day") { $prop_day="SELECTED"; $prop_div = 1; } else { $prop_day=""; }
if ("$prop_per" == "week") { $prop_week="SELECTED"; $prop_div = 7; } else { $prop_week=""; }
if ("$prop_per" == "month") { $prop_month="SELECTED"; $prop_div = 30; } else { $prop_month=""; }
if ("$prop_per" == "year") { $prop_year="SELECTED"; $prop_div = 365; } else { $prop_year=""; }

$hoil_per = strip_tags($_REQUEST['hoil_per']);
if (("$hoil_per" != "day") and ($hoil_per != "week") and ($hoil_per != "month") and ($hoil_per != 'year')) { $hoil_per = 'year'; }
if ("$hoil_per" == "day") { $hoil_day="SELECTED"; $hoil_div = 1; } else { $hoil_day=""; }
if ("$hoil_per" == "week") { $hoil_week="SELECTED"; $hoil_div = 7; } else { $hoil_week=""; }
if ("$hoil_per" == "month") { $hoil_month="SELECTED"; $hoil_div = 30; } else { $hoil_month=""; }
if ("$hoil_per" == "year") { $hoil_year="SELECTED"; $hoil_div = 365; } else { $hoil_year=""; }

$wood_per = strip_tags($_REQUEST['wood_per']);
if (("$wood_per" != "day") and ($wood_per != "week") and ($wood_per != "month") and ($wood_per != 'year')) { $wood_per = 'year'; }
if ("$wood_per" == "day") { $wood_day="SELECTED"; $wood_div = 1; } else { $wood_day=""; }
if ("$wood_per" == "week") { $wood_week="SELECTED"; $wood_div = 7; } else { $wood_week=""; }
if ("$wood_per" == "month") { $wood_month="SELECTED"; $wood_div = 30; } else { $wood_month=""; }
if ("$wood_per" == "year") { $wood_year="SELECTED"; $wood_div = 365; } else { $wood_year=""; }

$ng_pct = sprintf("%0.1f",($ng_heat / $ng_div) / $AVG_DAILY_NGAS * 100);
$prop_pct = sprintf("%0.1f",($prop_heat / $prop_div) / $AVG_DAILY_PROP * 100);
$hoil_pct = sprintf("%0.1f",($hoil_heat / $hoil_div) / $AVG_DAILY_HOIL * 100);
$wood_pct = sprintf("%0.1f",($wood_heat / $wood_div) / $AVG_DAILY_WOOD * 100);
$total_pct = $ng_pct + $wood_pct + $hoil_pct + $prop_pct;
$you_len = sprintf("%0.0f",$total_pct*2);
if ( $you_len > 250 ) { $you_len = 250; $dots = "&gt;&gt;&gt;"; } else { $dots = ""; }
$you_img = "yellowpix.gif";
if ( $total_pct > 100 ) { $you_img = "redpix.gif"; }
if ( $total_pct <= 10 ) { $you_img = "greenpix.gif"; }

$avg_bar = "<tr><td align=right>US Avg.</td><td><img src='graypix.gif' width=200 height=10></td></tr>";
$you_bar = "<tr><td align=right><i>Your Usage</i></td><td><img src='$you_img' width=$you_len height=10>$dots</td></tr>";
$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='graypix.gif' width=20 height=10></td></tr>";

if ( $submitted == "yes" ) {
  $heat_results="<td align=center>You have used<br><font color='#3333FF'><b>$total_pct %</b></font><br> of the national average<br>for heating &amp; cooking fuel</td>";
  $heat_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$avg_bar $you_bar $tgt_bar </table></td>";
}

$overall_pct += $total_pct;

/* --- TRASH -------------------------------------------------------------------------------- */
$trash_wt = strip_tags($_REQUEST['trash_wt']); if (! is_numeric($trash_wt)) { $trash_wt = 0; }

$trash_per = strip_tags($_REQUEST['trash_per']);
if (("$trash_per" != "day") and ($trash_per != "week") and ($trash_per != "month") and ($trash_per != 'year')) { $trash_per = 'day'; }
if ("$trash_per" == "day") { $trash_day="SELECTED"; $trash_div = 1; } else { $trash_day=""; }
if ("$trash_per" == "week") { $trash_week="SELECTED"; $trash_div = 7; } else { $trash_week=""; }
if ("$trash_per" == "month") { $trash_month="SELECTED"; $trash_div = 30; } else { $trash_month=""; }
if ("$trash_per" == "year") { $trash_year="SELECTED"; $trash_div = 365; } else { $trash_year=""; }

$trash_pct = sprintf("%0.1f",($trash_wt / $trash_div) / $AVG_DAILY_TRASH * 100);
$total_pct = $trash_pct;
$you_len = sprintf("%0.0f",$total_pct*2);
if ( $you_len > 250 ) { $you_len = 250; $dots = "&gt;&gt;&gt;"; } else { $dots = ""; }
$you_img = "yellowpix.gif";
if ( $total_pct > 100 ) { $you_img = "redpix.gif"; }
if ( $total_pct <= 10 ) { $you_img = "greenpix.gif"; }


$avg_bar = "<tr><td align=right>US Avg.</td><td><img src='graypix.gif' width=200 height=10></td></tr>";
$you_bar = "<tr><td align=right><i>Your Usage</i></td><td><img src='$you_img' width=$you_len height=10>$dots</td></tr>";
$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='graypix.gif' width=20 height=10></td></tr>";

if ( $submitted == "yes" ) {
  $trash_results="<td align=center>You have used<br><font color='#3333FF'><b>$total_pct %</b></font><br> of the national average<br>for garbage</td>";
  $trash_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$avg_bar $you_bar $tgt_bar </table></td>";
}

$overall_pct += $total_pct;

/* --- WATER -------------------------------------------------------------------------------- */
$water_vol = strip_tags($_REQUEST['water_vol']); if (! is_numeric($water_vol)) { $water_vol = 0; }

$water_per = strip_tags($_REQUEST['water_per']);
if (("$water_per" != "day") and ($water_per != "week") and ($water_per != "month") and ($water_per != 'year')) { $water_per = 'day'; }
if ("$water_per" == "day") { $water_day="SELECTED"; $water_div = 1; } else { $water_day=""; }
if ("$water_per" == "week") { $water_week="SELECTED"; $water_div = 7; } else { $water_week=""; }
if ("$water_per" == "month") { $water_month="SELECTED"; $water_div = 30; } else { $water_month=""; }
if ("$water_per" == "year") { $water_year="SELECTED"; $water_div = 365; } else { $water_year=""; }

$water_pct = sprintf("%0.1f",($water_vol / $water_div) / $AVG_DAILY_WATER * 100);
$total_pct = $water_pct;
$you_len = sprintf("%0.0f",$total_pct*2);
if ( $you_len > 250 ) { $you_len = 250; $dots = "&gt;&gt;&gt;"; } else { $dots = ""; }
$you_img = "yellowpix.gif";
if ( $total_pct > 100 ) { $you_img = "redpix.gif"; }
if ( $total_pct <= 10 ) { $you_img = "greenpix.gif"; }


$avg_bar = "<tr><td align=right>US Avg.</td><td><img src='graypix.gif' width=200 height=10></td></tr>";
$you_bar = "<tr><td align=right><i>Your Usage</i></td><td><img src='$you_img' width=$you_len height=10>$dots</td></tr>";
$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='graypix.gif' width=20 height=10></td></tr>";

if ( $submitted == "yes" ) {
  $water_results="<td align=center>You have used<br><font color='#3333FF'><b>$total_pct %</b></font><br> of the national average<br>for water</td>"; 
  $water_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$avg_bar $you_bar $tgt_bar </table></td>";
}

$overall_pct += $total_pct;

/* --- CONSUMER GOODS ----------------------------------------------------------------------- */
$goods_new = strip_tags($_REQUEST['goods_new']); if (! is_numeric($goods_new)) { $goods_new = 0; }
$goods_used = strip_tags($_REQUEST['goods_used']); if (! is_numeric($goods_used)) { $goods_used = 0; }

$goods_per = strip_tags($_REQUEST['goods_per']);
if (("$goods_per" != "day") and ($goods_per != "week") and ($goods_per != "month") and ($goods_per != 'year')) { $goods_per = 'week'; }
if ("$goods_per" == "day") { $goods_day="SELECTED"; $goods_div = 1; } else { $goods_day=""; }
if ("$goods_per" == "week") { $goods_week="SELECTED"; $goods_div = 7; } else { $goods_week=""; }
if ("$goods_per" == "month") { $goods_month="SELECTED"; $goods_div = 30; } else { $goods_month=""; }
if ("$goods_per" == "year") { $goods_year="SELECTED"; $goods_div = 365; } else { $goods_year=""; }

$used_per = strip_tags($_REQUEST['used_per']);
if (("$used_per" != "day") and ($used_per != "week") and ($used_per != "month") and ($used_per != 'year')) { $used_per = 'week'; }
if ("$used_per" == "day") { $used_day="SELECTED"; $used_div = 1; } else { $used_day=""; }
if ("$used_per" == "week") { $used_week="SELECTED"; $used_div = 7; } else { $used_week=""; }
if ("$used_per" == "month") { $used_month="SELECTED"; $used_div = 30; } else { $used_month=""; }
if ("$used_per" == "year") { $used_year="SELECTED"; $used_div = 365; } else { $used_year=""; }

$goods_pct = sprintf("%0.1f",($goods_new / $goods_div) / $AVG_DAILY_GOODS * 100);
$used_pct = sprintf("%0.1f",($goods_used / $used_div) / 10 / $AVG_DAILY_GOODS * 100);
$total_pct = $goods_pct + $used_pct;
$you_len = sprintf("%0.0f",$total_pct*2);
if ( $you_len > 250 ) { $you_len = 250; $dots = "&gt;&gt;&gt;"; } else { $dots = ""; }
$you_img = "yellowpix.gif";
if ( $total_pct > 100 ) { $you_img = "redpix.gif"; }
if ( $total_pct <= 10 ) { $you_img = "greenpix.gif"; }


$avg_bar = "<tr><td align=right>US Avg.</td><td><img src='graypix.gif' width=200 height=10></td></tr>";
$you_bar = "<tr><td align=right><i>Your Usage</i></td><td><img src='$you_img' width=$you_len height=10>$dots</td></tr>";
$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='graypix.gif' width=20 height=10></td></tr>";

if ( $submitted == "yes" ) {
  $goods_results="<td align=center>You have used<br><font color='#3333FF'><b>$total_pct %</b></font><br> of the national average<br>for consumer goods</td>"; 
  $goods_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$avg_bar $you_bar $tgt_bar </table></td>";
}

$overall_pct += $total_pct;

/* --- FOOD --------------------------------------------------------------------------------- */

$local_food = strip_tags($_REQUEST['local_food']);
$dry_bulk = strip_tags($_REQUEST['dry_bulk']);
$wet_goods = strip_tags($_REQUEST['wet_goods']);

$local_len = sprintf("%0.0f",$local_food*2);
$dry_len = sprintf("%0.0f",$dry_bulk*2);
$wet_len = sprintf("%0.0f",$wet_goods*2);

$total_food = $local_food + $dry_bulk + $wet_goods;

$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='greenpix.gif' width=140 height=10> <img src='yellowpix.gif' width=50 height=10> <img src='redpix.gif' width=10 height=10></td></tr>";
$you_bar = "<tr><td align=right>Your Usage</td><td><img src='greenpix.gif' width=$local_len height=10> <img src='yellowpix.gif' width=$dry_len height=10> <img src='redpix.gif' width=$wet_len height=10></td></tr>";

if ( $submitted == "yes" ) {
  if ( $total_food != 0 ) {
    if ( $total_food != 100 ) {
      $you_bar = "<tr><td align=right>Your Usage</td><td> [<i>Your food categories don't total 100%</i>] </td></tr>";
    }
    $food_key="<td align=left><img src='greenpix.gif' height=10 width=10> Local &amp; sustainable<br><img src='yellowpix.gif' height=10 width=10> Dry bulk<br><img src='redpix.gif' height=10 width=10> Wet goods<br></td>";
    $food_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$tgt_bar $you_bar</table></td></tr>";
  }
}

/* --- OVERALL ------------------------------------------------------------------------------ */
$total_pct = sprintf("%0.1f",$overall_pct / 6);

$you_len = sprintf("%0.0f",$total_pct*2);
if ( $you_len > 250 ) { $you_len = 250; $dots = "&gt;&gt;&gt;"; } else { $dots = ""; }
$you_img = "yellowpix.gif";
if ( $total_pct > 100 ) { $you_img = "redpix.gif"; }
if ( $total_pct <= 10 ) { $you_img = "greenpix.gif"; }

$avg_bar = "<tr><td align=right>US Avg.</td><td><img src='graypix.gif' width=200 height=10></td></tr>";
$you_bar = "<tr><td align=right><i>Your Usage</i></td><td><img src='$you_img' width=$you_len height=10>$dots</td></tr>";
$tgt_bar = "<tr><td align=right>R4A Target</td><td><img src='graypix.gif' width=20 height=10></td></tr>";

if ( $submitted == "yes" ) {
  $overall_results="<td align=center>You have used approximately<br><font color='#3333FF'><b>$total_pct %</b></font><br> of the national average<br>for non-food categories</td>";
  $overall_chart = "<td><table border=0 cellpadding=3 cellspacing=1>$avg_bar $you_bar $tgt_bar </table></td>";
}

/* --- END CALCULATIONS --------------------------------------------------------------------- */
?>

<html>
<head>
<title>90% Reduction Calculator</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<h1 align=center>Riot for Austerity<br>Resource Calculator</h1>
<form action="calc-metric.php" method=post>
<input name=submitted value="yes" type=hidden>
<table border=1 cellpadding=3 cellspacing=0 align=center bgcolor='#DDDDD'>
<tr><td colspan=3><b>Transportation</b></td></tr>
<tr>
<td align=right>Gas, diesel, biofuels: <input type=text size=6 name=std_ptrl value="<? echo $std_ptrl; ?>"> liters
per person per <select name=std_per>
<option value="day" <?php echo $std_day; ?> >day</option>
<option value="week" <?php echo $std_week; ?> >week</option>
<option value="month" <?php echo $std_month; ?> >month</option>
<option value="year" <?php echo $std_year; ?> >year</option>
</select>
<br>
Public Transportation: <input type=text size=6 name=pt_ptrl value="<?php echo $pt_ptrl; ?>"> km
per person per <select name=pt_per>
<option value="day" <?php echo $pt_day; ?> >day</option>
<option value="week" <?php echo $pt_week; ?> >week</option>
<option value="month" <?php echo $pt_month; ?> >month</option>
<option value="year"> <?php echo $pt_year; ?>year</option>
</select>
<br>
Waste veggie oil: <input type=text size=6 name=wvo_ptrl value="<?php echo $wvo_ptrl; ?>"> km
per person per <select name=wvo_per>
<option value="day" <? echo $wvo_day; ?> >day</option>
<option value="week" <? echo $wvo_week; ?> >week</option>
<option value="month" <? echo $wvo_month; ?> >month</option>
<option value="year" <? echo $wvo_year; ?> >year</option>
</select>
<br>
</td>
<?php echo $ptrl_results; ?>
<?php echo $ptrl_chart; ?>
</tr>

<tr><td  colspan=3><b>Electricity</b></td></tr> 
<tr>
<td align=right>Conventional: <input type=text size=6 name=conv_kwh value="<? echo $conv_kwh; ?>"> kWh
per household per <select name=conv_per>
<option value="day" <?php echo $conv_day; ?> >day</option>
<option value="week" <?php echo $conv_week; ?> >week</option> 
<option value="month" <?php echo $conv_month; ?> >month</option>
<option value="year" <?php echo $conv_year; ?> >year</option>
</select>
<br>
Solar: <input type=text size=6 name=solar_kwh value="<?php echo $solar_kwh; ?>"> kWh 
per household per <select name=solar_per>
<option value="day" <?php echo $solar_day; ?> >day</option>
<option value="week" <?php echo $solar_week; ?> >week</option>
<option value="month" <?php echo $solar_month; ?> >month</option>
<option value="year"> <?php echo $solar_year; ?>year</option> 
</select>
<br>
Wind/Hydro: <input type=text size=6 name=wind_kwh value="<?php echo $wind_kwh; ?>"> kWh
per household per <select name=wind_per>
<option value="day" <? echo $wind_day; ?> >day</option>
<option value="week" <? echo $wind_week; ?> >week</option>
<option value="month" <? echo $wind_month; ?> >month</option>
<option value="year" <? echo $wind_year; ?> >year</option>
</select>
<br>
</td>
<?php echo $elec_results; ?>
<?php echo $elec_chart; ?>
</tr>

<tr><td  colspan=3><b>Heating & Cooking fuel</b></td></tr> 
<tr>
<td align=right>Natural Gas: <input type=text size=6 name=ng_heat value="<? echo $ng_heat; ?>"> cubic meters
per household per <select name=ng_per>
<option value="day" <?php echo $ng_day; ?> >day</option>
<option value="week" <?php echo $ng_week; ?> >week</option> 
<option value="month" <?php echo $ng_month; ?> >month</option>
<option value="year" <?php echo $ng_year; ?> >year</option>
</select>
<br>
Propane: <input type=text size=6 name=prop_heat value="<?php echo $prop_heat; ?>"> liters 
per household per <select name=prop_per>
<option value="day" <?php echo $prop_day; ?> >day</option>
<option value="week" <?php echo $prop_week; ?> >week</option>
<option value="month" <?php echo $prop_month; ?> >month</option>
<option value="year" <?php echo $prop_year; ?> >year</option> 
</select>
<br>
Heating Oil: <input type=text size=6 name=hoil_heat value="<?php echo $hoil_heat; ?>"> liters 
per household per <select name=hoil_per>
<option value="day" <?php echo $hoil_day; ?> >day</option>
<option value="week" <?php echo $hoil_week; ?> >week</option>
<option value="month" <?php echo $hoil_month; ?> >month</option>
<option value="year" <?php echo $hoil_year; ?> >year</option> 
</select>
<br>
Wood: <input type=text size=6 name=wood_heat value="<?php echo $wood_heat; ?>"> cords
per household per <select name=wood_per>
<option value="day" <? echo $wood_day; ?> >day</option>
<option value="week" <? echo $wood_week; ?> >week</option>
<option value="month" <? echo $wood_month; ?> >month</option>
<option value="year" <? echo $wood_year; ?> >year</option>
</select>
<br>
</td>
<?php echo $heat_results; ?>
<?php echo $heat_chart; ?>
</tr>

<tr><td  colspan=3><b>Garbage</b></td></tr> 
<tr>
<td align=right>Garbage: <input type=text size=6 name=trash_wt value="<? echo $trash_wt; ?>"> kg
per person per <select name=trash_per>
<option value="day" <?php echo $trash_day; ?> >day</option>
<option value="week" <?php echo $trash_week; ?> >week</option> 
<option value="month" <?php echo $trash_month; ?> >month</option>
<option value="year" <?php echo $trash_year; ?> >year</option>
</select>
<br>
</td>
<?php echo $trash_results; ?>
<?php echo $trash_chart; ?>
</tr>

<tr><td  colspan=3><b>Water</b></td></tr>
<tr>
<td align=right>Water: <input type=text size=6 name=water_vol value="<? echo $water_vol; ?>"> liters
per person per <select name=water_per>
<option value="day" <?php echo $water_day; ?> >day</option>
<option value="week" <?php echo $water_week; ?> >week</option>
<option value="month" <?php echo $water_month; ?> >month</option>
<option value="year" <?php echo $water_year; ?> >year</option>
</select>
<br>
</td>
<?php echo $water_results; ?>
<?php echo $water_chart; ?>
</tr>

<tr><td  colspan=3><b>Consumer Goods</b></td></tr>
<tr>
<td align=right>New stuff: <input type=text size=6 name=goods_new value="<? echo $goods_new; ?>"> dollars
per household per <select name=goods_per>
<option value="day" <?php echo $goods_day; ?> >day</option>
<option value="week" <?php echo $goods_week; ?> >week</option>
<option value="month" <?php echo $goods_month; ?> >month</option>
<option value="year" <?php echo $goods_year; ?> >year</option>
</select>
<br>
Used stuff: <input type=text size=6 name=goods_used value="<? echo $goods_used; ?>"> dollars
per household per <select name=used_per>
<option value="day" <?php echo $used_day; ?> >day</option>
<option value="week" <?php echo $used_week; ?> >week</option>
<option value="month" <?php echo $used_month; ?> >month</option>
<option value="year" <?php echo $used_year; ?> >year</option>
</select>
<br>
</td>
<?php echo $goods_results; ?>
<?php echo $goods_chart; ?>
</tr>

<tr><td colspan=3><b>Food</b></td></tr>
<tr>
<td align=right>Local, sustainably grown: <input type=text size=3 name=local_food value="<? echo $local_food; ?>"> %<br>
Dry, unprocessed bulk goods: <input type=text size=3 name=dry_bulk value="<? echo $dry_bulk; ?>"> %<br>
Wet goods & conventional: <input type=text size=3 name=wet_goods value="<? echo $wet_goods; ?>"> %<br>
</td>
<?php echo $food_key; ?>
<?php echo $food_chart; ?>
</tr>


<?php
if ( $submitted == "yes" ) {
echo "<tr><td colspan=3><b>Overall (excluding Food)</b></td></tr>";
echo "<tr> <td align=right>&nbsp;</td>";
echo $overall_results;
echo $overall_chart; 
echo "</tr>";
}
?>

<tr><td  colspan=3><input type=submit value='Calculate'></td></tr>
</table>
</form>
</body>
</html>
