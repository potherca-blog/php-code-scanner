# Code Scanner

## Introduction

It can be difficult to understand what code does _exactly_ when working
with code-bases that are large, legacy, or low quality.

Sometimes you just want to know if the code writes to disk or reads from
a DB.

The purpose of this project is to give insight into which parts of code
have certain behaviour. Such behaviour is called an "identity".

By default the scanner can identify code that:

- Accesses a database
- Accesses a network
- Accesses the environment (ini/env/apache/etc.)
- Accesses the filesystem
- Sends emails
- Uses [native PHP global variables][reserved-variables]
- Writes output (STDOUT/print/echo/etc.)

The scanner is smart enough to distinguish internal/native PHP functions
and classes from user-land and vendor classes and functions.

The scanner is not meant to cover 100% of all cases, it is intended to
be "good enough". If there are cases the scanner does not support,
please open an issue to gain support.

## Installation

Use composer to install the tool in a project:

```bash
composer require 'potherca/php-scanner'
```

or globally:

```bash
composer global require 'potherca/php-scanner'
```

## Usage

Call `php-scanner --help` to see the most up-to-date overview iof supported options:

```bash
 ./bin/php-scanner --help

Usage: php-scanner --subject <path-to-scan> [--help] [--identifier=<path-to-identifier>] [--ignore=<path-to-ignore>]

    --subject <path-to-scan>            Path to directory or file to scan. Recurses into directories
    [--help]                            Display this information
    [--identifier=<path-to-identifier>] Path to directory or file declaring custom identifiers. Does not recurse into directories
    [--ignore=<path-to-ignore>]         Path to directory or file to exclude from scanning

```

### Simple usage

Call `php-scanner` with a subject that should be scanned.

```bash
php-scanner --subject /path/to/file/or/folder
```

The subject can be a file or directory.
If it is a directory it will be recursively scanned.

### Ignore files and folders

Specific files and folders can be ignored by adding `ignore` flag(s).

```bash
php-scanner --subject /path/to/file/or/folder --ignore=path/to/ignore
```

Multiple flags can be added:

```bash
php-scanner --subject /path/to/file/or/folder --ignore=path/to/ignore --ignore=path/to/ignore.file
```

Note the use of the "equals" sign `=`. Without it the flag does not work.

The ignore path should be relative from the root of the directory to scan.

If the ignore flag points to a directory, make sure to add a slash `/` at the
end to avoid unexpected behaviour. All files and folders in that directory
will be ignored.

### Custom scanning

The scanner supports custom scanners so users can expand the identities the
scanner can identify.

All a custom Identifier has to do is implement the `Potherca\Scanner\Identifier\IdentifierInterface`

The file (or folder) containing custom Identifier(s) can be passed to the scanner
using the `--identifier` flag.

- Multiple identifier flags can be added
- An "equals" sign `=` must be used between the falg and the path. Without it the flag does not work.
- Directories will _not_ be recursed into.

## License

This project has been licensed under GPL-3.0 License (GNU General Public License
v3.0).

Created by [Potherca](https://pother.ca/).

[reserved-variables]: http://php.net/manual/en/reserved.variables.php
