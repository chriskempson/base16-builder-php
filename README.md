# Base16 Builder PHP
A PHP implementation of a _Base16 Builder_ that follows the guidelines at [Base16](https://github.com/chriskempson/base16).

## Installation

    git clone https://github.com/chriskempson/base16-builder-php
    cd base16-builder-php
    composer install

You'll need to obtain some [scheme files](https://github.com/chriskempson/base16-schemes) and [template files](https://github.com/chriskempson/base16-templates) before you can run the Builder. For example:

    git clone https://github.com/chriskempson/base16-tomorrow-scheme schemes 
    git clone https://github.com/chriskempson/base16-vim-template schemes 
## Usage
    cat scheme.yaml | php base16-builder.php --template template.mustache > theme.file
Updates all scheme and template repositories as defined in `schemes.yaml` and `templates.yaml`.

    ./build-all.sh
Build everything using all schemes in `./schemes/` and all templates in `/.templates/`.