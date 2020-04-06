The base style of the page were taken and change from: https://www.tutsmake.com/laravel-6-custom-login-registration-example-tutorial/

You can delete servers, but it is a soft delete (not actually removed from the DB but just marked as deleted)
Client Side error display is none existing, one must look at the console to know what the error might be. Server side could also improve the messages sent back to the client.

With more time:
- would have done a better handling of the error messages
- would have dockerized it (DB would have its own container and the app would have one too for example)

The DB does is not included in the repo. In order to create it you need:
- to have the proper PHP version () 
- create a mysql user with the enough premission to be able to create a DB
    you will also need to make sure the .env variable contains the proper user / password / port / host for the DB connection
- run this command at the root of the app:
    php artisan command:createdatabase
        this command will generate the DB if it does not exist and do an initial migration