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


						
echo "DATE: ".$datenow."<BR><br>";

//IT
$serv= mysql_query("SELECT server_ip from servers");
	if(mysql_num_rows($serv) > 0){
		while ($rowa=mysql_fetch_assoc($serv)){
			$server=$rowa['server_ip'];
			$totalagents=mysql_query("SELECT count(*) as totalagents from vicidial_live_agents where server_ip='$server'");
			if(mysql_num_rows($totalagents) > 0){
				while ($rowaaa=mysql_fetch_assoc($totalagents)){
					echo "<b>SERVER/AGENTS: </b>".$server." / ".$rowaaa['totalagents']."<br>";
				}
			}
		}
	}
$totalagentss=mysql_query("SELECT count(*) as totalagents from vicidial_live_agents");
	if(mysql_num_rows($totalagentss) > 0){
		while ($rowaaaa=mysql_fetch_assoc($totalagentss)){
			echo "<b>TOTAL AGENTS:</b> ".$rowaaaa['totalagents']."<br>";
		}
	}
					
$carrier = mysql_query("SELECT dialstatus from vicidial_carrier_log where call_date>='$minusone' AND call_date<='$datenow'");
					if(mysql_num_rows($carrier) > 0){
						while ($row5=mysql_fetch_assoc($carrier)){
							
							if($row5['dialstatus']=='ANSWER'){
								$answer++;
							}
							else if($row5['dialstatus']=='CANCEL'){
								$cancel++;
							}
							else if($row5['dialstatus']=='CHANUNAVAIL'){
								$chanunavail++;
							}
							else if($row5['dialstatus']=='CONGESTION'){
								$congestion++;
							}
							else if($row5['dialstatus']=='NOANSWER'){
								$noanswer++;
							}
							
						}
						
					}
							$total=$chanunavail + $cancel + $answer + $congestion + $noanswer;
							if ($total == 0){
								$ca_avg=0;
								$cancel_avg=0;
								$answer_avg=0;
								$congestion_avg=0;
								$noanswer_avg=0;
							}
							else {
								$ca_avg=$chanunavail/$total*100;
								$cancel_avg=$cancel/$total*100;
								$answer_avg=$answer/$total*100;
								$congestion_avg=$congestion/$total*100;
								$noanswer_avg=$noanswer/$total*100;
							}
							
							$noans = number_format($noanswer, 2, '.', '');
							$chan = number_format($ca_avg, 2, '.', '');
							$can =  number_format($cancel_avg, 2, '.', '');
							$cong =  number_format($congestion_avg, 2, '.', '');
							$ans =  number_format($answer_avg, 2, '.', '');
							
							echo "<b>TOTAL CALLS:</b> ".$total."<br>";
							echo "<b>ANSWER:</b> ".$ans."<br>";
							echo "<b>CANCEL:</b> ".$can."<br>";
							echo "<b>CHANUNAVAIL:</b> ".$chan."<br>";
							echo "<b>CONGESTION:</b> ".$cong."<br>";
							echo "<b>NOANSWER:</b> ".$noans."<br>";
							$carrier = mysql_query("SELECT count(*) as isip from vicidial_auto_calls where stage='START'");
							if(mysql_num_rows($carrier) > 0){
								while ($row7=mysql_fetch_assoc($carrier)){
									echo "<b>CALL RINGING:</b> ".$row7['isip']."<br>";
								}							
							}
							echo "<br><br>";
							
							
							
							

//Overall
echo "SERVER";
$actcamps= mysql_query("SELECT campaign_id,campaign_name,hopper_level,auto_dial_level,lead_filter_id from vicidial_campaigns where active='Y' order by campaign_id;");
if(mysql_num_rows($actcamps) > 0){
			while ($row=mysql_fetch_assoc($actcamps)){
				echo "<br>";
				$campid=$row['campaign_id'];
				echo "Campaign: ".$row['campaign_id']." - ".$row['campaign_name']."<br>";
				echo "Dial Level: ".$row['auto_dial_level']."<br>";
				echo "Hopper Level :".$row['hopper_level']."<br>";
				echo "Lead Filter: ".$row['lead_filter_id']."<br>";
				$actlists= mysql_query("SELECT count(*) as counter from vicidial_lists where active='Y' and campaign_id='$campid'");
					if(mysql_num_rows($actlists) > 0){
						while ($row1=mysql_fetch_assoc($actlists)){
							echo "Total Active Lists: ".$row1['counter']."<br>";
						}
					}
				$lists= mysql_query("SELECT count(*) as counter from vicidial_lists where campaign_id='$campid'");
					if(mysql_num_rows($lists) > 0){
						while ($row2=mysql_fetch_assoc($lists)){
							echo "Total Lists: ".$row2['counter']."<br>";
						}
					}
				$lists= mysql_query("SELECT dialable_leads, drops_answers_today_pct from vicidial_campaign_stats where campaign_id='$campid'");
					if(mysql_num_rows($lists) > 0){
						while ($row3=mysql_fetch_assoc($lists)){
							echo "Dialable Leads: ".$row3['dialable_leads']."<br>";
							echo "Drop Percentage: ".$row3['drops_answers_today_pct']."<br>";
							//drops_today_pct
						}
					}
					
				$hopper=mysql_query("SELECT count(*) as hopper_count from vicidial_hopper where campaign_id='$campid'");
					if(mysql_num_rows($hopper) > 0){
						while ($row4=mysql_fetch_assoc($hopper)){
							echo "Leads in Hopper: ".$row4['hopper_count']."<br>";
							//drops_today_pct
						}
					}			
				
				//LIVE AGENTS
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
					echo "QUEUE: ".$queue."<br>";
					echo "INCALL: ".$incall."<br>";
					echo "READY: ".$ready."<br>";
					echo "PAUSED: ".$paused."<br>";
					echo "DISPO: ".$dispo."<br>";
					echo "DEAD: ".$deadcl."<br>";
					
					echo "<br><br>";
			}
		}


//$vicidial_campaign_stats="SELECT calls_today,drops_today,$answers_singleSQL,status_category_1,status_category_count_1,status_category_2,status_category_count_2,status_category_3,status_category_count_3,status_category_4,status_category_count_4,hold_sec_stat_one,hold_sec_stat_two,hold_sec_answer_calls,hold_sec_drop_calls,hold_sec_queue_calls,campaign_id from vicidial_campaign_stats where campaign_id IN ($closer_campaignsSQL) order by campaign_id;";

?>