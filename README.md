# Powerimage


<p align="center">
<a href="https://travis-ci.org/alcodo/powerimage"><img src="https://travis-ci.org/alcodo/powerimage.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/alcodo/powerimage"><img src="https://poser.pugx.org/alcodo/powerimage/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/alcodo/powerimage/framework"><img src="https://poser.pugx.org/alcodo/powerimage/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/alcodo/powerimage/framework"><img src="https://poser.pugx.org/alcodo/powerimage/license.svg" alt="License"></a>
</p>

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

And by the way you can inlcude or exclude paths that you want use powerimage example:
```php
    public function render($request, Exception $exception)
    {
        if(PowerImage::include($request, ['/images/*', '/gallery/*'])) {
            PowerImage::check($request, $exception);
        }
        
        // or
        
        if(PowerImage::exclude($request, ['/user/*'])) {
            PowerImage::check($request, $exception);
        }

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
