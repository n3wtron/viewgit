<?php
	include ("config.inc.php");
	$scriptPath=$_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_NAME'];
	$basePath=substr($scriptPath,0,strrpos($scriptPath,'/'));
	$graphPath="$basePath/graphs/";
	//cancello i file vecchi
	unlink ( "$graphPath/".$_REQUEST['project'].".png");
	unlink ( "$graphPath/".$_REQUEST['project'].".map");
	if (isset($_REQUEST['short'])){
		$month = date("m");
		$year = date ("Y");
		$delta=3;
		if ($month -$delta  < 0){
			$year--;
			$month=12+($month-$delta);
		}else{
			$month=$month-$delta;
		}
		$sixMonthAgo=$year."-".$month."-01";
		$res=exec("$basePath/git2dot.sh ".$_REQUEST['project']." ".$_REQUEST['path']." ".$sixMonthAgo."|".$conf['dotPath']." -Tcmapx -o$graphPath/".$_REQUEST['project'].".map -Tpng -o$graphPath/".$_REQUEST['project'].".png");	
	}else{
		$res=exec("$basePath/git2dot.sh ".$_REQUEST['project']." ".$_REQUEST['path']."|".$conf['dotPath']." -Tcmapx -o$graphPath/".$_REQUEST['project'].".map -Tpng -o$graphPath/".$_REQUEST['project'].".png");	
	}	
?>
<html>
<head>
	<script type="text/javascript">
		function scroll(){
			var obj = document.getElementById("graph");
			obj.scrollLeft = obj.scrollWidth - obj.clientWidth
			window.scrollBy(obj.scrollWidth - obj.clientWidth,0);
		}
	</script> 
</head>
<body onload="scroll();"> 
	<?php
	if (isset($_REQUEST['short'])){
		echo "Graph from ".$sixMonthAgo." to ".date("Y-m-d");
	}
	?>
	<div style="overflow:auto;" border="0" id="graph">
		<img src="graphs/<?php print($_REQUEST['project']);?>.png" usemap="#<?php print($_REQUEST['project']);?>" border="0px"/>
	</div>
		<?php
			$content=file_get_contents("graphs/".$_REQUEST['project'].".map");
			echo $content
		?>
</body>
</html>
