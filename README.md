**Package is still in development!!!**

# Powerimage
Powerimage is a dynamic image handler for laravel. It uses the package [thephpleague/glide](https://github.com/thephpleague/glide) for convert the image. 

After the installation you can request any image that you have uploaded in laravel. Example:
```example.com/images/cat.jpg``` (Original)     
```example.com/images/cat_w=200&h=200.jpg``` (Convert)  

Structure:
```
{domain}/{path}/{fileimage name}_{resize parameter}.{extension}

Delimiter: _
```

## Installation

Add packages:
```bash
composer require alcodo/powerimage:dev-master
```

## Additional

- [Parameter reference](http://glide.thephpleague.com/1.0/api/quick-reference/)

## License

The MIT License (MIT). Please see [LICENSE](https://github.com/alcodo/powerimage/blob/master/LICENSE) for more information.
