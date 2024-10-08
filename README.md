# Snappy Shopper Code Task

This repository contains the work I've done to complete the code task provided by Snappy Shopper.

I've used Laravel Breeze as the Authentication scaffolding for this project.

The API endpoints are secured using sanctum, so keys will need to be generated for the user using the terminal. The URLs can then be tested via Insomnia/Postman.

## Console command to download and import UK Postcodes

```php artisan postcodes``` can be run from the terminal, which will connect to doogal.co.uk to download a zip of all of the UK postcodes, unzip the file and then read through the CSV file, creating the entities in the database.

After the code has completed, the downloaded files are deleted from the system.

This has a corresponding test located at ```tests/Feature/Console/Commands/DownloadAndImportPostcodesCommandTest.php```.

## Controller action to add a new store

```app/Http/Controllers/StoreController.php``` has been created as a resource controller to allow the creation and updating of Stores held in the system.

Creation and updates are handled via Form Requests to validate the incoming data before being commited to the database.

This has a corresponding test located at ```tests/Feature/Http/Controllers/StoreControllerTest.php```.

## Controller action to return stores near to a postcode

```app/Http/Controllers/StoresNearPostcodeController.php``` is an invocable controller expecting a latitude, longitude and distance parameters and will return stores within the distance of the provided values. This is accessible via ```api/stores-near-postcode/{latitude}/{longitude}/{distance}``` eg ```api/stores-near-postcode/60.151426/-1.149862/40```.

This query is performed using the query scope ```scopeCloseTo``` on the Store model.

This has a corresponding test located at ```tests/Feature/Http/Controllers/StoresNearPostcodeControllerTest.php```.

## Controller action to return stores that can deliver to a certain postcode

```app/Http/Controllers/StoresThatDeliverToPostcode.php``` is an invocable controller expecting a postcode which will converted it to a latitude and longitude and return stores that offer deliveries within distance of the provided postcode. This is accessible via ```api/stores-that-deliver-to-postcode/{postcode}``` eg ```http://snappy-shopper-code-task.test/api/stores-that-deliver-to-postcode/DH3 3JF```.

This query is performed using the query scope ```scopeWillDeliverTo``` on the Store model.

This has a corresponding test located at ```tests/Feature/Http/Controllers/StoresThatDeliverToPostcodeControllerTest.php```.

## Further steps

I'd like to have investigated creating the select dropdown as a Blade Component rather than writing out the options manually on the create/edit screens for Stores but didn't want to spent too much time on it over the other tasks.
