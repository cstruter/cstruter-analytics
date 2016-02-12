REM Fetch all dependencies and generate autoload information
CALL composer install

REM Perform unit testing
CALL vendor/bin/phpunit

REM Generate documentation
CALL vendor/bin/phpdoc

PAUSE