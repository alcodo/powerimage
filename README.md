# Powerimage
Powerimage is a dynamic image handler for laravel. It uses the package [thephpleague/glide](https://github.com/thephpleague/glide) for convert the image. 

After the installation you can request any image that you have uploaded in laravel. Example:    
```example.com/images/cat.jpg``` (Original)     
```example.com/images/cat_w=200&h=200.jpg``` (Convert)

The workflow is very simple. If image isn't found, laravel throw a exception. 
This package creates only one time this image. And on the next request the image will
return form your webserver like nginx (fast response).

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

Add powerimage handler in `app/Exceptions/Handler.php`:
```php
    public function render($request, Exception $exception)
    {
        PowerImage::check($request, $exception);

        return parent::render($request, $exception);
    }
```
## Helper

Create powerimage path helper:
```php
    powerimage('images/video.png', ['w' => 200, 'h' => 350]);
    
    it returns:
    'images/video_w=200&h=350.png'
```

- [Parameter reference](http://glide.thephpleague.com/1.0/api/quick-reference/)

## License

The MIT License (MIT). Please see [LICENSE](https://github.com/alcodo/powerimage/blob/master/LICENSE) for more information.
