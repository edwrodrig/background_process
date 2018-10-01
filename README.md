edwrodrig\background_process
========
A php library to launch background process and handle it

[![Latest Stable Version](https://poser.pugx.org/edwrodrig/background_process/v/stable)](https://packagist.org/packages/edwrodrig/background_process)
[![Total Downloads](https://poser.pugx.org/edwrodrig/background_process/downloads)](https://packagist.org/packages/edwrodrig/background_process)
[![License](https://poser.pugx.org/edwrodrig/background_process/license)](https://packagist.org/packages/edwrodrig/background_process)
[![Build Status](https://travis-ci.org/edwrodrig/background_process.svg?branch=master)](https://travis-ci.org/edwrodrig/background_process)
[![codecov.io Code Coverage](https://codecov.io/gh/edwrodrig/background_process/branch/master/graph/badge.svg)](https://codecov.io/github/edwrodrig/background_process?branch=master)
[![Code Climate](https://codeclimate.com/github/edwrodrig/background_process/badges/gpa.svg)](https://codeclimate.com/github/edwrodrig/background_process)

## My use cases

My infrastructure is targeted to __Ubuntu 16.04__ machines with last __php7.2__ installed from [ppa:ondrej/php](https://launchpad.net/~ondrej/+archive/ubuntu/php).
I use some unix commands for some process like __cp__ or __ln__.
I'm sure that there are way to make it compatible with windows but I don't have time to program it and testing,
but I'm open for pull requests to make it more compatible.

## Documentation
The source code is documented using [phpDocumentor](http://docs.phpdoc.org/references/phpdoc/basic-syntax.html) style,
so it should pop up nicely if you're using IDEs like [PhpStorm](https://www.jetbrains.com/phpstorm) or similar.

## Composer
```
composer require edwrodrig/background_process
```

## Testing
The test are built using PhpUnit. It generates images and compare the signature with expected ones. Maybe some test fails due metadata of some generated images, but at the moment I haven't any reported issue.

## License
MIT license. Use it as you want at your own risk.

## About language
I'm not a native english writer, so there may be a lot of grammar and orthographical errors on text, I'm just trying my best. But feel free to correct my language, any contribution is welcome and for me they are a learning instance.

