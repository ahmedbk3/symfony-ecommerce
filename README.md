*****all the work is in the branch dev*****

++++prject:e-commerce website using symfony framework.

++++u can find a demo video among the files.

++++Before starting the Symfony development server, make sure to set the required environment variables.
   Open PowerShell and run the following commands:
   
   1-php set-env.php

   2-copy output it should be like this:
    $env:DATABASE_URL="mysql://root:123456789@127.0.0.1:3306/ecommerce_db?serverVersion=8.0.32"
    $env:APP_SECRET="your_app_secret_key_here"
    > Replace "your_app_secret_key_here" with your actual secret key if different.

   3-Then, you can start the:   Symfony server:
     symfony server:start
