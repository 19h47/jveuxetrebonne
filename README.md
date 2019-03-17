# JVEB

The WordPress theme for JVEB blog.

The site has been designed by [__Les Indiens__](http://lesindiens.fr/)

![Screenshot](screenshot.png)

## Install

```bash
npm install
```

## Run Gulp

```bash
gulp
```

## PHPCS

### Install the WordPress rules

Add __PHP_CodeSniffer__ to the `composer.json` file

```json
{
    "require": {
        "squizlabs/php_codesniffer": "*"
    }
}
```

Then update dependencies

```bash
composer update
```

Create the project

```bash
Make create-project
```

### Add the Rules to PHP CodeSniffer

```bash
Make config-set
```


## Dependencies

- [ACF plugin](https://www.advancedcustomfields.com/)

## TODO

- [ ] Add a Ajax comment system (via a plugin maybe?)
