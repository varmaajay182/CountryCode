Step 1: Install the package

powershall command : composer require countrycodevendor/countrycode

step 2: set the serviceProvider
path: config.php/app.php
'providers' => ServiceProvider::defaultProviders()->merge([
      ....
        Countrycodevendor\Countrycode\countryCodeServiceProvider::class
       ... 
    ])->toArray(),
    

step 3: run this command 
php artisan vendor:publish --tag=public

step 4: check file exist or not 
path: public/veandor/countrycode/data and flags
In data Folder two json file exist: 1) countryData.json 2)language.json

step 5: If not issue in step 4 
run this url: localhost::8000/country
