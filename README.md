# About 
An addon which allows you to define image sizes per directory and pre-caches your images when you
run a command to make the accessing of websites faster and prevent time-out issues.

Inspired by https://github.com/spatie/statamic-responsive-images

## Installation
`composer require "pixney/statamic-image-generator"`

`php artisan vendor:publish` -> image-generator-configuration
`php artisan vendor:publish` -> image-generator-views

### Tag -
```
{{ picture:image 
    class="image another-image-class"  
    attributes='data-nameit="add value here"' 
    alt=image.alt
}}
```


```
<picture>
   <source media="(max-width: 414px)" srcset="http://statamic.test/img/containers/assets/news/news-test/GPD_36_OOH_6-compressor.jpg/47e3a18fe78dbec3a83256d4f5860726.webp" type="image/webp">
   <source media="(max-width: 414px)" srcset="http://statamic.test/img/containers/assets/news/news-test/GPD_36_OOH_6-compressor.jpg/435a59ed059b2661f5bf7350e023ac94.jpg">
   <source media="(max-width: 768px)" srcset="http://statamic.test/img/containers/assets/news/news-test/GPD_36_OOH_6-compressor.jpg/32642c722308ab03fb72ae20ae8dcaba.webp" type="image/webp">
   <source media="(max-width: 768px)" srcset="http://statamic.test/img/containers/assets/news/news-test/GPD_36_OOH_6-compressor.jpg/1ae31e8d381e20a6ae1cb259de15c166.jpg">
   <source media="(max-width: 1024px)" srcset="http://statamic.test/img/containers/assets/news/news-test/GPD_36_OOH_6-compressor.jpg/7fb8c7d4ba1304739e024e77de9f7b38.webp" type="image/webp">
   <source media="(max-width: 1024px)" srcset="http://statamic.test/img/containers/assets/news/news-test/GPD_36_OOH_6-compressor.jpg/a8e3ac2a831c911627ae758476fbf1b8.jpg">
   <img src="http://statamic.test/assets/news/news-test/GPD_36_OOH_6-compressor.jpg" alt="I am the alt text" class="image another-image-class" data-nameit="add value here">
</picture>
```
