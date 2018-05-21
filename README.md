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

For example, if you want to add a smiley face to the title of all your theme's posts, it's as simple as creating a class `Add_Smiley_To_Post_Titles` and overriding a few methods.

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

If you need to remove an action or filter, you can call the inherited static `remove` method:

```php
Update_Book_Archive_Query::remove();
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

## Models

Benchpress provides a model class to simplify accessing and updating post properties. `\BenchPress\Model\Base_Model` can be used as is to provide a simple and uniform way to access post properties or it can be extended to provide custom getters, field aliases, and defaults.

All of the following example model properties and methods are optional:

```php
use BenchPress\Model\Base_Model;

class Event_Model extends Base_Model {
    protected $aliases = [
        'subtitle' => 'field_5ad0b74498fad',
        'start' => 'field_5ad0b78798fae',
        'end' => 'field_5ae730079bb55',
        'name' => 'field_5ae730079bb56'
    ];

    protected $defaults = [
        'subtitle' => 'Upcoming Event'
    ];

    protected $public = ['ID', 'subtitle', 'date'];

    // define custom getters
    public function date_range(){
        return $this->start(). ' until '. $this->end();
    }

    // override getter
    public function name(){
        // access property directly
        $name = $this->get_value('name');
        return strtoupper($name);
    }
}
```

Model instances can be used like this:

```php
// create model (model can accept an ID or post object)
$event = new Event_Model($ID);

// get post property
$id = $event->ID();

// get ACF field
$custom_field = $event->some_custom_field();

// get field by alias
$subtitle = $event->subtitle();

// get by custom getter
$full_date = $event->date_range();

// get an array of fields
$fields = $event->get(['ID', 'start', 'end']); // ~> ['ID' => 123, 'start' => ...]

// get public fields
$public = $event->get_public(); // ~> ['ID' => 123, 'subtitle' => ...]

// set post field
$event->set('post_title', 'new title');

// set custom field
$event->set('start', '1970-01-01');
```


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

Sometimes you may wish to do a bit more with your shortcode, such as adding a button to the WYSIWYG to insert the shortcode. You can implement `register_hooks` in your shortcode class in order to do so:

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

    protected function register_hooks() {
        // add filters/actions to add shortcode button to WYSIWYG
    }
}
```


## Theme Support

BenchPress provides quite a few theme support functions to use within your theme.

To use them, you will first need to initialize BenchPress's theme support class:

```php
use \BenchPress\Theme_Support\Theme_Support;

Theme_Support::init();
```

**Clean Up**

This theme support function will remove a bunch of the WordPress generated tags that are output within the `<head>` tag of your theme. It also cleans up the output of `body_class()`.

```php
add_theme_support( Theme_Support::CLEAN_UP );
```

**Favicon**

This theme support function will add a favicon for your theme.

```php
add_theme_support( Theme_Support::FAVICON, get_template_directory_uri() . '/favicon.png' );
```

* `string $favicon_url` - The URL of your favicon file.

**Google Analytics**

This theme support function will add Google Analytics tracking code to your theme.

```php
add_theme_support( Theme_Support::GOOGLE_ANALYTICS, 'UA-1234567' );
```

* `string $ua_id` - Your Google Analytics UA identifier.

**Login Logo**

This theme support function will add a custom logo to the WordPress login screen.

```php
add_theme_support( Theme_Support::LOGIN_LOGO, [
    'url' => get_template_directory_uri() . '/login_logo.png',
    'width' => 200,
    'height' => 50
] );
```

* `string $url` - The URL of your custom login logo.
* `int $width` - The width of your logo.
* `int $height` - The height of your logo.

**Remove Admin Menus**

This theme support function removes admin menus from the WordPress admin.

```php
add_theme_support( Theme_Support::REMOVE_ADMIN_MENUS, [
    'edit-comments.php'
] );
```

* `array $menu_slugs` - An array of menu slugs to remove from the admin menu.

## Partials

BenchPress provides two functions for rendering partials within your theme. Similar to WordPress's `get_template_part` function, `\BenchPress\get_partial` and `\BenchPress\the_partial` allow you to render a partial while providing a data array to the partial to render. Similar to how most template engines work, you can pass the data array as the second argument:

In `my-theme/partials/greeter.php`:

```html
<h1>Hello <?php echo $name; ?></h1>
```

In another theme file:

```php
\BenchPress\the_partial( 'greeter', [ 'name' => 'Mrs. Smith' ] );
```

This would output:

```html
<h1>Hello Mrs. Smith</h1>
```

If want to return the contents of the partial instead of echoing the contents, use `\BenchPress\get_partial`.

By default, the partial functions will first look in a `partials` directory within your theme followed by looking your theme root. You can also provide an absolute path to the partial which is useful when using the partial functions within a plugin.

If you want to change the search path from `partials` to another directory, you can add the below filter:

```php
add_filter( 'benchpress/partials_directory', function() {
    return 'parts';
} );
```

## Other Helpers

### Admin Notice

Sometimes it is necessary to display a custom admin notice when working on admin functionality. WordPress doesn't have a simple way to create the different types of admin notices. However, BenchPress provides an easy way to create them when needed:

```php
use \BenchPress\Admin\Admin_Notice;

Admin_Notice::create( Admin_Notice::Success, 'Your book is safe', true );
```

You would typically invoke this function within the `admin_notices` action.

```php
add_action( 'admin_notices', function() {
    Admin_Notice::create( Admin_Notice::Success, 'Your book is safe', true );
} );
```

The static `\BenchPress\Admin_Notice\Admin_Notice::create` method accepts the following arguments:

- `int $type` - The type of notice to display. There are three different types of notices:

    ```php
    Admin_Notice::Success;
    Admin_Notice::Warning;
    Admin_Notice::Error;
    ```
- `string $message` - The message to display in the notice.
- `bool $dismissible` - Whether this notice should be dismissible or not

### User Related Functions

If you need to access the role of a user, you can call `\BenchPress\get_user_role( $user )`. It will return the user role if found, or false if one isn't found.
