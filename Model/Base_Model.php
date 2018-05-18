<?php

namespace BenchPress\Model;

/**
 * Base class for Post Model. This class can be extended to create a custom post
 *     model handlers
 *
 * @package BenchPress\Model
 */
class Base_Model {

    /**
     * The post object that the model will use.
     *
     * @var \WP_Post
     */
    protected $post;

    /**
     * Aliases field key. Allows you to access property by alias
     * @var array
     */
    protected $aliases = [];

    /**
     * Sets the default value for a property. If the value of that property
     *     is falsey, the default is returned. If the key is aliased, use the
     *     alias.
     * @var array
     */
    protected $defaults = [];

    /**
     * Fields that should be returned from call to `get_public`
     * @var array
     */
    protected $public = [
    ];

    /**
     * Base_Model constructor.
     *
     * @param [\WP_Post, int, string]  either a post object or post ID
     */
    public function __construct( $post ) {
        if( is_a($post, 'WP_Post') ) {
            $this->post = $post;
        } else {
            $this->post = get_post($post);
        }
    }

    /**
     * Gets an array of properties
     * @param  array  $keys the properties to retrieve
     * @return array
     */
    public function get($keys = []){
        $data = [];
        foreach ( $keys as $key ) { $data[$key] = $this->get_value($key); }
        return $data;
    }

    /**
     * Gets the fields defined on classes `public` propery
     * @return array
     */
    public function get_public() {
        return $this->get($this->public);
    }

    /**
     * Sets propery on post or custom field
     * @param string $key post property or ACF field
     * @param mixed $value
     */
    public function set($key, $value) {
        $key = $this->get_aliased_key($key);
        if( $this->post_has_property($key) ) {
            wp_update_post([
                'ID' => $this->post->ID,
                $key => $value
            ]);
            // update local post
            $this->post = get_post($this->post->ID);
        } else  if( function_exists('update_field') ){
            update_field($key, $value, $this->post->ID);
        }
    }

    /**
     * Get the post for the model.
     *
     * @return \WP_Post
     */
    public function get_post() {
        return $this->post;
    }

    /**
     * Method missing function. Used to access ojbect properties.
     */
    public function __call($method, $args){
        return $this->get_value($method);
    }

    /**
     * Gets the model value. If the key exists on the post object, that is
     *     returned else, if the ACF field is defined, that is returned.
     * @param  string $key
     * @return [mixed, null] returns the model value if found else null
     */
    protected function get_value($key){

        $key = $this->get_aliased_key($key);
        $value = null;

        if( $this->post_has_property($key) ) {
            $value = $this->post->{$key};
        } else  if( function_exists('get_field') ){
            $value = get_field($key, $this->post->ID);
        }

        if( empty($value) ) {
            $value = $this->get_default($key, $value);
        }

        return $value;
    }

    /**
     * Checks if the property is aliased and returns the non-aliased value if it is
     * @param  string $key potentially aliased key
     * @return string
     */
    protected function get_aliased_key($key) {
        return isset( $this->aliases[$key] ) ? $this->aliases[$key] : $key;
    }

    /**
     * Checks if a default is set and returns it if it is
     * @param  string $key non-aliased class property
     * @param  mixed $value falsey value returne
     * @return mixed
     */
    protected function get_default($key, $value) {
        if( isset($this->defaults[$key]) ){
            return $this->defaults[$key];
        }
        return $value;
    }

    private function post_has_property($property){
        return property_exists($this->post, $property);
    }
}
