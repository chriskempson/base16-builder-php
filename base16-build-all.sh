#!/bin/sh
# Base16 Build All
# Chris Kempson (https://github.com/chriskempson)

schemes_path="schemes"
templates_path="templates"
output_path="output"

# Loop over template files
find "$templates_path" -name "*.mustache" | sort | while read template_file; do
    [ -f "$template_file" ] || continue

    # Resolve filenames and directories
    template=$(echo $(basename ${template_file}) | sed "s/.mustache//")
    ouput_dirs=$(echo $(dirname ${template_file}) | sed "s/"$templates_path"\///")

    # Remove any old files
    rm -f "$output_path/$ouput_dirs/"*."$template"

    # Loop over scheme files
    find "$schemes_path" -name "*.yaml" | sort | while read scheme_file; do
        [ -f "$scheme_file" ] || continue

        # Resolve filenames and directories
        scheme=$(echo $(basename ${scheme_file}) | sed "s/.yaml//")
        output_file=$output_path/$ouput_dirs/$scheme.$template

        # Ensure directories exists or create them
        mkdir -p "$output_path/$ouput_dirs"

        # Parse scheme and template file and write theme
        cat "$scheme_file" | php base16-builder.php --template "$template_file" > "$output_file"
        echo "Built $output_file"
    done

done