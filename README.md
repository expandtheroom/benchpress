# BenchPress

BenchPress is a WordPress library that helps you develop better WordPress themes. It combines a number of helper
functions and patterns that will help you increase your WordPress strength!

#### Hooks

Often times you need to add a filter or action in order to change some behavior or data
within your WordPress theme. One option is to add the hook directly in your functions.php file.
While this works, you will eventually be faced with with an unmaintainable functions.php file and
it will be hard for you to find where a specific hook was added.

Instead, you can leverage BenchPress’s `BenchPress\Hooks\Base_Action` and `BenchPress\Hooks\Base_Filter` classes to create a
single class file for a particular behavior you are trying to change.

For example, if you want to add a smiley face to the title of all your theme's posts, it's as simple as creating
a class `Add_Smiley_To_Post_Titles` and overriding a few methods.

```php
use BenchPress\Hooks\Base_Filter;

class Add_Smiley_To_Post_Titles extends Base_Filter {

	protected function get_filter() {
		return ‘the_title’;
	}

	protected function get_arg_count() {
		return 2;
	}
	
	protected function callback( $title, $post_id ) {
		return $title . ' :)';
	}

}
```

And if you only want to add it to the titles for book post types, you would override the `should_run` method.
The `should_run` method will always be passed the same arguments as the hook callback.

```php
use BenchPress\Hooks\Base_Filter;

class Add_Smiley_To_Book_Titles extends Base_Filter {

	protected function get_filter() {
		return ‘the_title’;
	}

	protected function should_run( $title, $post_id ) {
		return get_post_type( $post_id ) == ‘book’;
	}

	protected function get_arg_count() {
		return 2;
	}

	protected function callback( $title, $post_id ) {
		return $title . ‘ :)’;
	}

}
```

In order to initialize this behavior, you would add the following to your functions.php file:

```php
Add_Smiley_To_Book_Titles::init();
```

And if you wanted to change the number of posts returned for a Book post type archive page:

```php
use BenchPress\Hooks\Base_Action;

class Update_Book_Archive_Query extends Base_Action {

    protected function get_action() {
        return 'pre_get_posts';
    }

    protected function should_run( $query ) {
        if ( !is_admin() && $query->is_main_query() ) {
            if ( is_post_type_archive( 'book' ) ) {
                return true;
            }
        }

        return false;
    }

    protected function callback( $query ) {
        $query->set( 'posts_per_page', 30 );
    }
}
```

#### Post Types

BenchPress provides a small abstraction for creating custom post types. Each post type can extend the `\BenchPress\Post_Types\Base_Post_Type`
class. When extending this class, you must override a two abstract methods:

```php
use BenchPress\Post_Types\Base_Post_Type;

class Book extends Base_Post_Type {

    protected function get_post_type() {
        return 'book';
    }

    protected function register_post_type( $post_type ) {
        // call WordPress's register_post_type
        register_post_type( $post_type, [
            // labels, config etc.
        ] );
    }
}
```

And to initialize your post type, you would add the following to your functions.php file:

```php
Book::init();
```

If you need to access your post type slug within other areas of your theme code, you can do the following:

```php

if ( is_singular( Book::post_type() ) {
    // do something if we are looking at single book post type template
}

```
