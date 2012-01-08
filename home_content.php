<?php
$parts = $complete_graph->get_resource_triple_values($requested_uri, DCT_HASPART);

if (!empty($parts)) { ?>
	<h2>Parts</h2>
	<ul>
		<?php
		foreach ($parts as $part) {
			$label = $complete_graph->get_first_literal($part, RDFS_LABEL, null, $preferred_languages);
			echo "				<li><a href=\"${part}\">${label}</a></li>\n";
		} ?>
	</ul>
<?php }
