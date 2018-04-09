# PageAmp worker
Iron.io worker

# Bon Voyage!
> Install composer dependencies

```php
$ composer install
```
> Test you're app if running fine by running

```php
$ php worker.php
```
> Install docker, download from here, https://www.docker.com/products/docker-toolbox, to do something more ambitious go here https://docs.docker.com/engine/installation/windows/

> Download Iron.io binary if on windows, https://github.com/iron-io/ironcli/releases then enter the path to this executable into my windows PATH environment variable.
> If you're on MAC your're in luck, https://hud.iron.io/

> On docker console, build current image and name as f4social

```php
$ docker build -t pageamp .
```
> On docker console, run your app under that image

```php
$ docker run --rm  -v "//$PWD":/worker pageamp php worker.php -payload payload.example.json
```

# When you're good to go, and feel like breaking some stuff
> Create a zip file of all the files inside your directory, say 'pageamp.zip'.
> Then upload task to Iron.io, named as pageamp in this sample

```php
$ iron worker upload --zip pageamp.zip --name f45social_fb iron/node node index.js fb
```
> This task is specific to run FB feed fetching

