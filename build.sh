find . -name *.ttl | sed -e "s%\(.*\)\.ttl%rapper -i turtle -o rdfxml-abbrev \1.ttl > \1.rdf%" | bash
