Step 1: Install the package

powershall command : composer require countrycodevendor/countrycode

step 2: set the serviceProvider
path: config.php/app.php
'providers' => ServiceProvider::defaultProviders()->merge([
      ....
        Countrycodevendor\Countrycode\countryCodeServiceProvider::class
       ... 
    ])->toArray(),
