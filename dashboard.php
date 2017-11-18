<head>
<style>
	#board {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#board td, #board th {
    border: 1px solid #ddd;
    padding: 8px;
}

#board tr:nth-child(even){background-color: #f2f2f2;}

#board tr:hover {background-color: #ddd;}

#board th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
</style>
</head>
<?php

include ('inc/connect.php');
date_default_timezone_set('America/New_York');
$minusone =  date ('Y-m-d H:i:s', strtotime('-1 minutes'));
$datenow =  date ('Y-m-d H:i:s');
$chanunavail=0;
$cancel=0;
$answer=0;
$congestion=0;
$noanswer=0;
$server='';

echo "<table id='board' width='100%'>"; ?>
<tr>
<th>CAMP</th>
<th>DIAL<br>LEVEL</th>
<th>HOPPER<br>LEVEL</th>
<th>LEAD<BR>FILTER</th>
<th>NUM OF<br>ACTIVE LIST</th>
<th>TOTAL<br>LIST</th>
<th>DIALABLE<br>LEADS</th>
<th>DROP %</th>
<th>LEAD IN<BR>HOPPER</th>
<th>QUEUE</th>
<th>INCALL</th>
<th>READY</th>
<th>PAUSED</th>
<th>DISPO</th>
<th>DEAD</th>
<th>CALL<br>RINGING</th>
<th>NUMBER<br>OF AGENTS</th>
</tr>
<?php
			
				$actcamps= mysql_query("SELECT campaign_id,campaign_name,hopper_level,auto_dial_level,lead_filter_id from vicidial_campaigns where active='Y' order by campaign_id;");
				if(mysql_num_rows($actcamps) > 0){
					while ($row=mysql_fetch_assoc($actcamps)){
						echo "<tr>";
						$campid=$row['campaign_id'];
						echo "<td>".$row['campaign_id']." - ".$row['campaign_name']."</td>";
						echo "<td> ".$row['auto_dial_level']."</td>";
						echo "<td>".$row['hopper_level']."</td>";
						echo "<td>".$row['lead_filter_id']."</td>";
						$actlists= mysql_query("SELECT count(*) as counter from vicidial_lists where active='Y' and campaign_id='$campid'");
						if(mysql_num_rows($actlists) > 0){
							while ($row1=mysql_fetch_assoc($actlists)){
								echo "<td>".$row1['counter']."</td>";
							}
						}
						$lists= mysql_query("SELECT count(*) as counter from vicidial_lists where campaign_id='$campid'");
						if(mysql_num_rows($lists) > 0){
							while ($row2=mysql_fetch_assoc($lists)){
								echo "<td>".$row2['counter']."</td>";
							}
						}
						$lists= mysql_query("SELECT dialable_leads, drops_answers_today_pct from vicidial_campaign_stats where campaign_id='$campid'");
						if(mysql_num_rows($lists) > 0){
						while ($row3=mysql_fetch_assoc($lists)){
							echo "<td>".$row3['dialable_leads']."</td>";
							echo "<td>".$row3['drops_answers_today_pct']."</td>";
							//drops_today_pct
							}
						}
					
						$hopper=mysql_query("SELECT count(*) as hopper_count from vicidial_hopper where campaign_id='$campid'");
						if(mysql_num_rows($hopper) > 0){
							while ($row4=mysql_fetch_assoc($hopper)){
								echo "<td>".$row4['hopper_count']."</td>";
								//drops_today_pct
							}
						}
						$liveagent=mysql_query("SELECT * from vicidial_live_agents where campaign_id='$campid'");
						$incall=0;
						$deadcl=0;
						$ready=0;
						$paused=0;
						$queue=0;
						$dispo=0;
						if(mysql_num_rows($liveagent) > 0){
							while ($row6=mysql_fetch_assoc($liveagent)){
							
								if($row6['status']=='INCALL'){
								$incall++;
								}
								else if($row6['status']=='PAUSED'){
									$paused++;
								}
								else if($row6['status']=='READY'){
									$ready++;
								}
								else if($row6['status']=='QUEUE'){
									$queue++;
								}
								else if($row6['status']=='DISPO'){
									$dispo++;
								}
								else if($row6['status']=='DEAD'){
									$deadcl++;
								}
							}
						}
						echo "<td>".$queue."</td>";
						echo "<td>".$incall."</td>";
						echo "<td>".$ready."</td>";
						echo "<td>".$paused."</td>";
						echo "<td>".$dispo."</td>";
						echo "<td>".$deadcl."</td>";
						$serv= mysql_query("SELECT server_ip from servers");
							if(mysql_num_rows($serv) > 0){
								echo "<td>";
								while ($rowa=mysql_fetch_assoc($serv)){
									$server=$rowa['server_ip'];
										$carrier = mysql_query("SELECT count(*) as isip from vicidial_auto_calls where stage='START' and server_ip='$server' and campaign_id='$campid'");
										if(mysql_num_rows($carrier) > 0){
											while ($row7=mysql_fetch_assoc($carrier)){
												echo $server." - ".$row7['isip']."<br>";
											}	
										}
								}
								echo "</td>";
							}
							$serv= mysql_query("SELECT server_ip from servers");
							if(mysql_num_rows($serv) > 0){
								echo "<td>";
								while ($rowa=mysql_fetch_assoc($serv)){
									$server=$rowa['server_ip'];
									$totalagentss=mysql_query("SELECT count(*) as totalagents from vicidial_live_agents where server_ip='$server'and campaign_id='$campid'");
										if(mysql_num_rows($totalagentss) > 0){
											while ($rowaaaa=mysql_fetch_assoc($totalagentss)){
												echo $rowaaaa['totalagents']."<br>";
											}
										}
								}
								echo "</td>";
							}
					echo "</tr>";
					}
				}
			
	

echo "</table>";
?>