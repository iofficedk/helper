# Fm helpers
Io/Helper are some tools use by other composer modules I made. Comment for my modules are the /.io.json config file in root folder, where I save configurations. Your can type your own if you like. 

1. Io/Helper/Config
    - ::get('mysql') - return the config section if any
    - ::get('mysql', 'production') - if you have some sub configurations

## Composer install
```sh
$ composer require iofficedk/helper
```




