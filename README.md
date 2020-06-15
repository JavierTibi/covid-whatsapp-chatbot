## Laravel 7 API with Passport


This is a Laravel Application that provide all the necessary endpoints based on the
specifications given

Use: 
- [Laravel Framework 7.0](https://laravel.com)
- [Twilio SDK](https://www.twilio.com/).
- [Covid API](https://covid19.mathdro.id/api)

As a extra I added an integration to Twilio where you can send a WhatsApp message to "+1 415 523 8886" with code <b>join grade-handsome.</b>

![alt text](https://github.com/JavierTibi/boalt-api/blob/master/img_examples/Screenshot_Join.png?raw=true)

You can then send the name of a country and the request will respond with the latest update of Covid cases in that country (Information provided by [Covid API](https://covid19.mathdro.id/api))

![alt text](https://github.com/JavierTibi/boalt-api/blob/master/img_examples/Screenshot_Country.png?raw=true)

or you can configure it locally following the instructions in the [Twilio Documentation](https://www.twilio.com/docs/whatsapp/api#overview) to create your Sandbox

## How to Install

1. Clone the repository
2. Run 
```bash
cd boalt-api
composer install
```
3. Set the database and the Twilio variables in the .env
4. Run
```bash
php artisan key:generate
php artisan optimize:clear
php artisan migrate
```
5. Run
```bash
php artisan passport:install
```

6. Run
```bash
php artisan serve
```
## Testing
Import the `API BOALT.postman_collection.json` file into your Postman application and then you can test the Endpoints in your local environment

## API Documentation

[Documentation](https://documenter.getpostman.com/view/5402529/SzzheyRz?version=latest)
