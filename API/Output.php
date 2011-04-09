<?php
/***********************************************
DAVE PHP API
https://github.com/evantahler/PHP-DAVE-API
Evan Tahler | 2011

I handle formatting the $OUTPUT object into XML, JSON, etc
***********************************************/

if ($PARAMS["OutputType"] == "")
{
	$PARAMS["OutputType"] = $CONFIG['DefaultOutputType'];
}

if ($PARAMS["OutputType"] == "VAR")
{
	var_dump($OUTPUT);
}

elseif ($PARAMS["OutputType"] == "PHP")
{
	echo serialize($OUTPUT);
}

elseif ($PARAMS["OutputType"] == "XML")
{
	function _DepthArrayPrint($Array,$depth,$container=null)
	{
		if (strlen($container) > 0)
		{
			$j = 0;
			while ($j < ($depth-1)) { echo "\t"; $j++; }
			if (is_int($container)) { $container = "item"; }
			echo '<'.(string)$container.'>'."\r\n";
		}
		
		$i = 0;
		$keys = array_keys($Array);
		while ($i < count($Array))
		{
			if (is_array($Array[$keys[$i]]))
			{
				_DepthArrayPrint($Array[$keys[$i]],($depth+1),$keys[$i]);
			}
			else
			{
				$j = 0;
				while ($j < $depth) { echo "\t"; $j++; }
				if (strlen($keys[$i]) > 0)
				{
					if (is_int($keys[$i])) { $this_key = $container; } else { $this_key = $keys[$i];} 
					print "<".(string)$this_key.">".(string)$Array[$keys[$i]]."</".$this_key.">"."\r\n";
				}
			}
			$i++;
		}
		
		if (strlen($container) > 0)
		{
			$j = 0;
			while ($j < ($depth-1)) { echo "\t"; $j++; }
			if (is_int($container)) { $container = "item"; }
			echo '</'.(string)$container.'>'."\r\n";
		}
	}
	//
	echo '<?xml version="1.0" encoding="UTF-8"?>'."\r\n";
	echo '<'.$CONFIG['XML_ROOT_NODE'].'>'."\r\n";
	_DepthArrayPrint($OUTPUT,1);
	echo '</'.$CONFIG['XML_ROOT_NODE'].'>'."\r\n";
}

elseif ($PARAMS["OutputType"] == "JSON")
{
	$JSON = json_encode($OUTPUT);
	if (strlen($PARAMS['Callback']) > 0)
	{
		echo $PARAMS['Callback']."(".$JSON.");";
	}
	else
	{
		echo $JSON;
	}
}

else
{
	echo 'I am sorry, but I do not know that OutputType.  Leave OutputType blank for the default option.';
}

flush();

?>