# **Laravel Policies**


Policies are classes that organize authorization logic around a particular model . For example, if your application is a blog, you may have a App\Models\Post model and a corresponding App\Policies\PostPolicy to authorize user actions such as creating or updating posts.

You may generate a policy using the make:policy Artisan command. The generated policy will be placed in the app/Policies directory. If this directory does not exist in your application, Laravel will create it for you: