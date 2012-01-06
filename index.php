<?php
define('MORIARTY_ARC_DIR', 'arc/');
require_once 'moriarty/moriarty.inc.php';
require_once 'moriarty/simplegraph.class.php';

function sort_accept_values($header_list) {
	if (empty($header_list)) { return array(); }
	$entries = explode(',', $header_list);
	foreach ($entries as $entry) {
		$parts = explode(';', $entry);
		$values[$parts[0]] = isset($parts[1]) ? trim(str_replace('q=', '', $parts[1])) : 1;
	}
	arsort($values, SORT_NUMERIC);
	return array_keys($values);
}

$requested_mime_types = sort_accept_values($_SERVER['HTTP_ACCEPT']);
$acceptable_mime_types = array('text/html', 'application/rdf+xml', 'text/turtle', 'application/json');
$possible_mime_types = array_intersect($requested_mime_types, $acceptable_mime_types);
if (empty($possible_mime_types)) { require_once 'errors/406NotAcceptable.php'; exit; }
$chosen_mime_type = array_shift($possible_mime_types);

$turtle_path = './all.ttl';
$turtle = file_get_contents($turtle_path);
$complete_graph = new SimpleGraph();
$complete_graph->from_turtle($turtle);
$requested_uri = 'http://kilosandcups.info'.$_SERVER['REQUEST_URI'];
preg_match_all('%/[^/]+%', $_SERVER['REQUEST_URI'], $request_parts);
list($ontology_part, $datatype_part, $value_part) = $request_parts[0];
$is_home_request = $_SERVER['REQUEST_URI'] == '/';
$is_ontology_request = isset($ontology_part) && !isset($datatype_part) ? true : false ;
$is_datatype_request = isset($datatype_part) && !isset($value_part) ? true : false ;
$is_value_request = isset($value_part) ? true : false ;
$ontology_uri = isset($ontology_part) ? 'http://kilosandcups.info'.$ontology_part : null ;
$datatype_uri = isset($datatype_part) ? $ontology_uri.$datatype_part : null ;
$value_uri = isset($value_part) ? $datatype_uri.$value_part : null ;

if (!$is_home_request && !$complete_graph->has_triples_about($ontology_uri)) { require_once 'errors/404NotFound.php'; exit; }
if (!$is_home_request && !$is_ontology_request && !$complete_graph->has_triples_about($datatype_uri)) { require_once 'errors/404NotFound.php'; exit; }

if ($chosen_mime_type != 'text/html' && !$is_value_request) {
	$graph_to_serve = $complete_graph->get_subject_subgraph($requested_uri);
	$graph_to_serve->set_namespace_mapping('meaure', 'http://kilosandcups.info/schema/');
	$graph_to_serve->set_namespace_mapping('cc', 'http://web.resource.org/cc/');
	header("Content-type: ${chosen_mime_type}");
	switch ($chosen_mime_type) {
		case 'application/rdf+xml':
			echo $graph_to_serve->to_rdfxml(); break;
		case 'text/turtle':
			echo $graph_to_serve->to_turtle(); break;
		case 'application/json':
			echo $graph_to_serve->to_json(); break;
		default:
	}
}


//TODO
//http://open.vocab.org/terms/defines