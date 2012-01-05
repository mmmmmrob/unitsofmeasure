find . -name *.ttl | sed -e "s%\(.*\)\.ttl%rapper -i turtle -o rdfxml-abbrev \1.ttl > \1.rdf%" | bash

find . -name *.ttl | sed -e "s%\(.*\)\.ttl%rapper -i turtle -o json \1.ttl > \1.json%" | bash

find . -name *.ttl | sed -e "s%\(.*\)\.ttl%rapper -i turtle -o html \1.ttl > \1.html%" | bash

