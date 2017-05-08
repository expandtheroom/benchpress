# BenchPress

BenchPress is a WordPress library that helps you develop better WordPress themes. 
It combines a number of helper functions and patterns that will help you increase 
your WordPress strength!

## Hooks

Often times you need to add a filter or action hook in order to change some behavior or data
within your WordPress theme. One option is to add the hook directly in your functions.php file.
While this works for simple themes, eventually it will lead to an unmaintainable functions.php.

Instead, you can leverage BenchPress’s `BenchPress\Hooks\Base_Action` and `BenchPress\Hooks\Base_Filter`
classes to create a single class file for a particular hook you are trying to implement.

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

And if you only want to add it to the titles for the book post type, you can implement the `should_run` method.
The `should_run` method will always be invoked the same arguments as the hook callback.

```php
use BenchPress\Hooks\Base_Filter;

class Add_Smiley_To_Book_Titles extends Base_Filter {

	protected function get_filter() {
		return ‘the_title’;
	}

	protected function should_run( $title, $post_id ) {
        // only run this hook if the post type is book
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

## Post Types

BenchPress provides a small abstraction for creating custom post types. Each post type can extend the `\BenchPress\Post_Type\Base_Post_Type` class. When extending this class, you must override a few abstract methods:

```php
use BenchPress\Post_Type\Base_Post_Type;

class Book extends Base_Post_Type {

    protected function get_post_type() {
        return 'book';
    }

    protected function get_singular_name() {
        return 'Book';
    }

    protected function get_plural_name() {
        return 'Books';
    }
}
```

To initialize your post type, add the following to your functions.php file:

```php
Book::init();
```

If you need to access your post type slug within other areas of your theme code, you can do the following:

```php
if ( is_singular( Book::post_type() ) {
    // do something if we are looking at single book post type template
}
```

If you need to provide more arguments for the post type registration, you can override `get_args`:

```php
use BenchPress\Post_Type\Base_Post_Type;

class Book extends Base_Post_Type {

    protected function get_post_type() {
        return 'book';
    }

    protected function get_singular_name() {
        return 'Book';
    }

    protected function get_plural_name() {
        return 'Books';
    }

    // set a custom post type slug
    protected function get_args() {
        return [
            'rewrite' => [
                'slug' => 'awesome-books'
            ]
        ]
    }
}
```

If you need to register a taxonomy for the post type, you can override `get_taxonomies`:

```php
use BenchPress\Post_Type\Base_Post_Type;

class Book extends Base_Post_Type {

    protected function get_post_type() {
        return 'book';
    }

    protected function get_singular_name() {
        return 'Book';
    }

    protected function get_plural_name() {
        return 'Books';
    }

    protected function get_taxonomies() {
        // If you registered your taxonomy using BenchPress's Base_Taxonomy class, you can access
        // the taxonomy name by calling the static `taxonomy` method.
        return [ Genre::taxonomy() ]
    }
}
```

By extending `Base_Post_Type`, all of the post type labels and updated messages will be set for you automatically based on the singular and plural name you provided.

## Taxonomies

BenchPress also provides a small abstraction for creating custom taxonomies. You can extend the `\BenchPress\Taxonomy\Base_Taxonomy` class. When extending this class, you must override a few abstract methods:

```php
use BenchPress\Taxonomy\Base_Taxonomy;

class Genre extends Base_Taxonomy {

    protected function get_taxonomy() {
        return 'genre';
    }

    protected function get_post_types() {
        // If you registered your post type using BenchPress's Base_Post_Type class, you can
        // access the post type slug by calling the static `post_type` method.
        return Book::post_type();
    }

    protected function get_singular_name() {
        return 'Genre';
    }

    protected function get_plural_name() {
        return 'Genres';
    }
}
```

To initialize your taxonomy, add the following to your functions.php file:

```php
Genre::init();
```

If you need to access your taxonomy name within other areas of your theme code, you can do the following:

```php
Genre::taxonomy();
```

If you need to provide more arguments for the taxonomy registration, you can override `get_args`:

```php
use BenchPress\Taxonomy\Base_Taxonomy;

class Genre extends Base_Taxonomy {

    protected function get_taxonomy() {
        return 'genre';
    }

    protected function get_post_types() {
        // If you registered your post type using BenchPress's Base_Post_Type class, you can
        // access the post type slug by calling the static `post_type` method.
        return Book::post_type();
    }

    protected function get_singular_name() {
        return 'Genre';
    }

    protected function get_plural_name() {
        return 'Genres';
    }

    protected function get_args() {
        // Don't show the taxonomy in the menu.
        return [
            'show_in_menu' => false
        ]
    }
}
```

By extending `Base_Taxonomy`, all of the taxonomy labels and updated messages will be set for you automatically based on the singular and plural name you provided.

## Shortcodes

BenchPress provides a convenient way to create shortcodes by extending the `\BenchPress\Shortcode\Shortcode` class.

```php
use BenchPress\Shortcode\Shortcode;

class Face_Emoji extends Shortcode {

    protected function get_name() {
        return 'face_emoji';
    }

    // These defaults will be merged into `$atts` for you automatically.
    protected function get_defaults() {
        return [
            'mood' => 'happy'
        ];
    }

    protected function get_content( $atts, $content, $tag ) {
        if ( $atts['mood'] == 'happy' ) {
            return ':)';
        } else {
            return ':(';
        }
    }
}
```

To initialize your shortcode, add the following to your functions.php file:

```php
Face_Emoji::init();
```