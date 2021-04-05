# CoffeeDrop


## Setup

This app was built using Laravel 8 and PHP 8. Install using VM (e.g Homestead) configured to your download location of this repository. Use `coffeedrop.test` when mapping the url.
Once setup, you will need to run `php artisan migrate --seed` to create and seed the database.

## Front End

Once your VM is set up, navigate to the url you set up in your hosts file to access the front end of this app. Here, you can search for your nearest location, calculate cashback and add new locations.

## API

Using the Postman file located in this repository, you can see the 4 available endpoints:

1. Search Location - Include a postcode on the end of the url to search for the nearest location.
2. Create New Location - In the Body, provide a postcode and two arrays of opening and closing times in the format shown to add a new location to the database
3. Calculate Cashback - In the Body, provide an array of each coffee type and the amount to be recycled to be provided with a calculated cashback amount.
4. Get Cashback Calculation Requests - Returns the last 5 Cashback Calculation requests with some data about the user.

Each endpoint requires a token. This is included in the postman file. This is just a basic example of authentication as I was unsure about how to use oauth2 without users and logins and I didn't have the time to investigate further.

Thanks again for the opportunity!

