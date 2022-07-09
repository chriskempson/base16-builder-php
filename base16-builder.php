<?php
# Base16 Builder PHP
# Chris Kempson (https://github.com/chriskempson)
#
# Example usage:
#     cat scheme.yaml | php base16-builder.php --template template.mustache > theme.file

require __DIR__ . '/vendor/autoload.php';
use Symfony\Component\Yaml\Yaml;
use Mexitek\PHPColors\Color;

// Read standard input
$scheme_file = ''; 
while (FALSE !== ($line = fgets(STDIN))) { $scheme_file .= $line; } 
fclose(STDIN);

// Handle command line arguments
$options = getopt('t:', ['template:']);
$template_file = $options['t'] ?? $options['template'] ?? null;
if (empty($template_file)) { fwrite(STDERR, 'Specify template with -t or --template='); exit; }

// Parse Scheme YAML
$scheme_data = Yaml::parse($scheme_file);

// Create scheme and template data
$template_data = buildTagsForTemplate($scheme_data);

// Render parsed template
fwrite(STDOUT, renderTemplate($template_file, $template_data));

/* 
 * Create variable tags to be used in mustache templates
 */
function buildTagsForTemplate($scheme_data)
{
    $tags['scheme-name'] = $scheme_data['scheme'];
	$tags['scheme-slug'] = str_replace(' ', '-', strtolower($tags['scheme-name']));
    $tags['scheme-author'] = $scheme_data['author'];

    $bases = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09',
        '0A', '0B', '0C', '0D', '0E', '0F');

    foreach ($bases as $base) {
        $base_key = 'base' . $base;
        $color = new Color(str_replace('#', '', $scheme_data[$base_key]));

        $tags[$base_key . '-hex'] = $color->getHex();
        $tags[$base_key . '-hex-bgr'] = substr($color->getHex(), 4, 2) .
            substr($color->getHex(), 2, 2) . substr($color->getHex(), 0, 2);
        $tags[$base_key . '-hex-r'] = substr($color->getHex(), 0, 2);
        $tags[$base_key . '-hex-g'] = substr($color->getHex(), 2, 2);
        $tags[$base_key . '-hex-b'] = substr($color->getHex(), 4, 2);
        $tags[$base_key . '-rgb-r'] = $color->getRgb()['R'];
        $tags[$base_key . '-rgb-g'] = $color->getRgb()['G'];
        $tags[$base_key . '-rgb-b'] = $color->getRgb()['B'];
        $tags[$base_key . '-dec-r'] = $color->getRgb()['R'] / 255;
        $tags[$base_key . '-dec-g'] = $color->getRgb()['G'] / 255;
        $tags[$base_key . '-dec-b'] = $color->getRgb()['B'] / 255;
        $tags[$base_key . '-hsl-h'] = $color->getHsl()['H'];
        $tags[$base_key . '-hsl-s'] = $color->getHsl()['S'];
        $tags[$base_key . '-hsl-l'] = $color->getHsl()['L'];
    }

    return $tags;
}

/* 
 * Renders a template using Mustache
 */
function renderTemplate($path, $template_tags)
{
    $mustache = new \Mustache_Engine();
    $mustache = $mustache->loadTemplate(file_get_contents($path));

    return $mustache->render($template_tags);
}