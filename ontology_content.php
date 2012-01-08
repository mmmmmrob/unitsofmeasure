<?php
define('CURRENCIES', 'http://kilosandcups.info/currencies/');
define('IMPERIAL', 'http://kilosandcups.info/imperial/');
define('SI', 'http://kilosandcups.info/si/');
define('US_CUSTOMARY', 'http://kilosandcups.info/us_customary/');
define('MEASURE', 'http://kilosandcups.info/schema/');

$defines = $complete_graph->get_resource_triple_values($requested_uri, OV_DEFINES);

$properties = array(MEASURE.'symbol', CURRENCIES.'code', CURRENCIES.'precision', CURRENCIES.'accepted_in');

echo "<ol>\n";
foreach ($defines as $defined) {
	$anchor = end(explode("/", $defined));
	echo "\t<li class=\"datatype\">";
	echo "<a name=\"${anchor}\"></a>";
	$label = $complete_graph->get_first_literal($defined, RDFS_LABEL, null, $preferred_language);
	if ($label)  { echo "<div class=\"datatype_label\">${label}</div>"; }
	echo "<div class=\"property\"><span class=\"property_label\">Datatype URI: </span><a href=\"${defined}\">${defined}</a></div>";
	foreach ($properties as $property) {
		$values = $complete_graph->get_subject_property_values($defined, $property);
		$property_label = $complete_graph->get_first_literal($property, RDFS_LABEL, null, $preferred_language);
		if (empty($values)) { continue; }
		echo "<div class=\"property\"><span class=\"property_label\">${property_label}: </span>";
		foreach ($values as $value) {
			if ($value['type'] == 'uri') {
				echo "<a href=\"${value['value']}\">";
			}
			echo "<span class=\"property_value\">${value['value']}</span>";
			if ($value['type'] == 'uri') {
				echo "</a>";
			}
		}
		echo "</div>";
	}
	echo "</li>\n";
}
echo "</ol>\n";