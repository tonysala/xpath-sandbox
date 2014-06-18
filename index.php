<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);

function clean($xml){
	return htmlspecialchars($xml); 
}

if (isset($_POST['query']) && isset($_POST['xml'])){
	
	if (empty($_POST['xml'])){
		$xml = file_get_contents('test.xml');
		$doc = new SimpleXMLElement($xml);
	} else {
		$doc = new SimpleXMLElement($_POST['xml']);
	}
	$xmlraw = clean($doc->asXML());
	if ($elements = @$doc->xpath($_POST['query'])){
		print json_encode(array(
			"status" => count($elements), 
			"error" => false,
			"xml" => $xmlraw
		));
	} else {
		print json_encode(array(
			"status" => 0, 
			"error" => true,
			"xml" => $xmlraw
	));
	}
	
	return;

}
	
$xml = file_get_contents('test.xml');
	
?>

<html>
	<head>
		<script src='libs/prettify.js'></script>
		<link href='libs/prettify.css' rel='stylesheet'/>
		<link href='libs/desert.css' rel='stylesheet'/>
		<link href='http://fonts.googleapis.com/css?family=Source+Code+Pro:200,400,600' rel='stylesheet' type='text/css'>
		<link href='libs/bootstrap.min.css' rel='stylesheet'/>
		<link href='libs/styles.css' rel='stylesheet'/>
		<script src='libs/jquery-2.1.1-min.js'></script>
		<script src='libs/application.js'></script>
	</head>
	<body>
		<div class='window'>
			<div class='input-group'>
				<input type='text' placeholder='xpath query...' class='form-control' id='query_field' />
				<span class='input-group-btn'>
					<button class='btn btn-default toolbar-btn' disabled id='xml_status' type='button'>...</button>
					<button class='btn btn-default toolbar-btn' id='zoomout' type='button'>-</button>
					<button class='btn btn-default toolbar-btn' id='zoomin'  type='button'>+</button>
					<button class='btn btn-primary toolbar-btn' id='edit' type='button'>Edit XML!</button>
				</span>
			</div>
			<span id='xml_status'></span>
			<div id='xml_out' contenteditable class='full-width-editor form-control'><pre class='prettyprint'><?=clean($xml)?></pre></div>
		</div>
	</body>
</html>
