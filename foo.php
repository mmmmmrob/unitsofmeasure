<?php

define('MORIARTY_ARC_DIR', 'arc/');
require_once 'moriarty/moriarty.inc.php';
require_once 'moriarty/simplegraph.class.php';

$turtle_path = './all.ttl';
$turtle = file_get_contents($turtle_path);
$complete_graph = new SimpleGraph();
$complete_graph->from_turtle($turtle);
$new = new SimpleGraph();
$new->set_namespace_mapping('cc', 'http://web.resource.org/cc/');
$new->set_namespace_mapping('currencies', 'http://kilosandcups.info/currencies/');
$new->set_namespace_mapping('imperial', 'http://kilosandcups.info/imperial/');
$new->set_namespace_mapping('si', 'http://kilosandcups.info/si/');
$new->set_namespace_mapping('us_customary', 'http://kilosandcups.info/us_customary/');
$new->set_namespace_mapping('dct', 'http://purl.org/dc/terms/');
$new->set_namespace_mapping('measure', 'http://kilosandcups.info/schema/');
$new->set_namespace_mapping('rdf', 'http://www.w3.org/1999/02/22-rdf-syntax-ns#');
$new->set_namespace_mapping('rdfs', 'http://www.w3.org/2000/01/rdf-schema#');
$new->set_namespace_mapping('vann', 'http://purl.org/vocab/vann/');

$subjects = $complete_graph->get_subjects();
foreach ($subjects as $subject) {
	if ($complete_graph->subject_has_property($subject, RDFS_ISDEFINEDBY)) {
		$values = $complete_graph->get_subject_property_values($subject, RDFS_ISDEFINEDBY);
		foreach ($values as $value) {
			if ($value['type'] == uri) {
				$new->add_resource_triple($value['value'], OV_DEFINES, $subject);
			}
		}
	}
}
echo $new->to_turtle();