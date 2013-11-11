<h2><? if ($cancelled) echo "<span class=\"event_cancelled\">"?><?= $location ?> (<?= $area ?>) hike details<? if ($cancelled) echo "</span> &nbsp; <span class=\"cancelled_notice\">CANCELLED</span>"?></h2>
<p><strong><? if ($cancelled) echo "<span class=\"event_cancelled\">"?><? if ($notes) echo $notes . ", "?><?= $date ?><? if ($end_date) echo " to " . $end_date ?><? if ($cancelled) echo "</span>"?></strong></p>
<? if ($cancelled) echo "<p class=\"cancelled_notice\">We regret that due to unforseen circumstances this trip will no longer be going ahead.</p>"?>
<p>The details about this trip are below. You can find more general information about the club and how <? echo strtolower($type)."s" ?> operate in the <a href="/crucial-guide">crucial guide</a>. Make sure you're signed up to the <a href="/mailing-list">mailing list</a> to get this information sent straight to your inbox.</p>

<? 
if($email)
{ 
	echo "<div class=\"email\">";

	$url = "http://lists.umhc.org.uk/pipermail/umhc_club/$email";
	$headers = @get_headers($url);
	if(strpos($headers[0],'200')===false)
	{
		echo "<p>Sorry, something went wrong and the information couldn't be found.</p></div>";
		return;
	}
	
	$output = file_get_contents($url);        
		
		preg_match("@(?<=<H1>\[UMHC]\s:\s).*(?=</H1>)@", $output, $subject);
		#echo "Subject: " . $subject[0] . "<br/>";
		
		preg_match("@(?<=<B>).*(?=</B>)@", $output, $author);
		#echo "Author: " . $author[0] . "<br/>";
		
		preg_match("@([A-Z][a-z]{2}\s+){2}[0-9]{1,2}\s@", $output, $date);
		preg_match("@(?<=([0-9]{2}:){2}[0-9]{2}\s[A-Z]{3}\s)[0-9]{4}@", $output, $year);
		#echo "Date: " . $date[0] . $year[0] . "<br/>";
	
		preg_match("@[0-9]{2}:[0-9]{2}(?=:[0-9]{2})@", $output, $time);
		#echo "Time: " . $time[0] . "<br/>";
		
		preg_match("@(?<=<PRE>)[\s\S]*(?=</PRE>)@", $output, $message);
		
		/* the next line:
				* removes any reference to a "scrubbed" attachment
				* removes single line breaks to enable proper wrapping
				* replaces the remaining linebreaks with HTML <br> tags
				* replaces those <br> tags with a pair of </p><p> tags
				* appends a <p> to the start of the whole thing, and a </p> to the end
				
			 I think this is actually a bit more complicated than it needs to be, 
			 steps 2-4 can be condensed into 2 or perhaps even 1 step!
		*/
		echo "<p>".preg_replace("@<br\s*/>@","</p><p>",nl2br(preg_replace("@\n(?=\S)@"," ",
							 preg_replace("@-------------- next part [\s\S]*@","",$message[0]))))."</p>";
							 
	echo "</div>";
	
}