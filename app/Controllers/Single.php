<?php

namespace App\Controllers;

use Sober\Controller\Controller;

class Single extends Controller
{
    /**
     * Returns the Post Type of current query
     * @return string
     */
    public function postType(): string
    {
        $queried_object = get_queried_object();

        if ($queried_object instanceof \WP_Term) {
            $taxonomy  = get_taxonomy($queried_object->taxonomy);
            $post_type = head($taxonomy->object_type);
        } else {
            $post_type = !empty($queried_object->name) ? $queried_object->name : get_post_type();
        }

        return $post_type;
    }

    /**
     * Returns the Post Type Category taxonomy of current query
     * @return string
     */
    public function postTypeCategoryTaxonomy(): string
    {
        $post_type = $this->postType();
        $taxonomy  = '';

        if (!empty($post_type)) {
            $taxonomy = 'post' === $post_type ? 'category' : "{$post_type}_category";
        }

        return $taxonomy;
    }

    public function primaryTermId()
    {
        return App::getPrimaryTermID($this->data['post_type']);
    }

    public function termName()
    {
        $term_name = get_the_category_by_ID($this->data['primary_term_id']);

        return !is_wp_error($term_name) ? $term_name : '';
    }

    public function termLink()
    {
        $term_link = get_term_link($this->data['primary_term_id'], $this->data['post_type_category_taxonomy']);

        return !is_wp_error($term_link) ? $term_link : '';
    }
}
