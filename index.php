<?php
//ini_set('display_errors',1); 
//error_reporting(E_ALL);

function clean($xml){
	$xml = htmlspecialchars($xml);
	
	// Syntax highlight tags and attributes
	$pattern = '/(&lt;\s?\??\w*\s+)((\w++\s*=)\s*(&quot;.*?&quot;))+(\s?\??&gt;)/im';
	$replace = '<span style="color:blue;"><b>$1</b></span><span style="color:darkblue"><b>$3</b><span style="color:green">$4</span><span style="color:blue;"><b>$5</b></span>';
	$xml = preg_replace($pattern,$replace,$xml);
	
	// Syntax highlight text
	$pattern = '/&gt;(.*?)&lt;/im';
	$replace = '&gt;<span style="color:black;">$1</span>&lt;';
	$xml = preg_replace($pattern,$replace,$xml);
	$xml = preg_replace('/&lt;\s?\w*\s+((\w+)\s*(=)\s*\'(\w+)\'+)+\s?&gt;/im',
		'<span style="color:red">$2</span><span style="color:yellow">$3</span><span style="color:brown">$4</span>',
		$xml);
	$xml = preg_replace('/    /im','&nbsp;&nbsp;&nbsp;&nbsp;',$xml);
	$xml = nl2br($xml);
	
	return $xml;
}

if (isset($_POST['query']) && isset($_POST['xml'])){
	
	$doc = new DOMDocument;

	// We don't want to bother with white spaces
	$doc->preserveWhiteSpace = false;

	if (empty($_POST['xml'])){
		$doc->Load('test.xml');
	} else {
		$doc->LoadXML($_POST['xml']);
	}

	$xml = new DOMXPath($doc);
	
	if ($elements = @$xml->query($_POST['query'])){
		print json_encode(array(
			"status" => $elements->length, 
			"error" => false,
			"xml" => clean(file_get_contents('test.xml'))
		));
	} else {
		print json_encode(array(
			"status" => 0, 
			"error" => true,
			"xml" => clean(file_get_contents('test.xml'))
	));
	}
	
	return;

}
	
$xml = file_get_contents('test.xml');
	
?>

<html>
	<head>
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
					<button class='btn btn-primary toolbar-btn' type='button'>Upload XML!</button>
				</span>
			</div>
			<span id='xml_status'></span>
			<div id='xml_out' contenteditable class='full-width-editor form-control'><?=clean($xml)?></div>
		</div>
	</body>
</html>