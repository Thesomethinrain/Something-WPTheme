<?php


/* Ajout des emplacements des zones de widget */
function theme_widgets_init() {

// Colonne de droite principale
    register_sidebar( array(
        'name' =>'Colonne de droite principale',
        'id' => 'primary-right-column',
        'description' =>'La colonne de droite principale.',
        'before_widget' => '<li id="%1$s" class="widget_container %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>'
        ) );

}
add_action( 'widgets_init', 'theme_widgets_init' );

/* Ajout des styles */
function css_styles() {
    wp_register_style( 'style-theme',get_template_directory_uri() . '/dist/css/style-theme.css' );
    wp_enqueue_style( 'style-theme' );
}
add_action('wp_enqueue_scripts','css_styles');


/* Ajout des scripts */

//wp_enqueue_script('my-custom-script', get_template_directory_uri() .'/js/my-custom-script.js', array('jquery'), null, true);

// CDN jQuery Google API
function modify_jquery() {
    if (!is_admin()) {
        // comment out the next two lines to load the local copy of jQuery
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', false, '1.11.0');
        wp_enqueue_script('jquery');
    }
}
add_action('init', 'modify_jquery');



function theme_enqueue_scripts() { 
  $js_directory = get_template_directory_uri() . '/dist/js/'; 
  wp_register_script( 'isotope', $js_directory . 'isotope.js', 'jquery', '1.0' ); 
  wp_register_script( 'global', $js_directory . 'global.js', 'jquery', '1.0' ); 
		//wp_enqueue_script( 'jquery' ); 
  wp_enqueue_script( 'isotope' ); 
  wp_enqueue_script( 'global' ); 
} 

add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );


// Fonction Date

function date_fr() {

    $date = get_post_meta( get_the_ID(), 'evt_date', true);

// extract Y,M,D
    $y = substr($date, 0, 4);
    $m = substr($date, 5, 2);
    $d = substr($date, 8, 2);
    
// create UNIX
    $time = strtotime("{$d}-{$m}-{$y}");

// format date (23/11/1988)
    return date('d/m/Y', $time);
    
// format date (November 11th 1988)
//echo date('n F Y', $time);
}


/* Ajout CustomPost */

add_action('init', 'my_custom_post');

function my_custom_post() {
    register_post_type('evenement', array(
      'label' => __('Evenements'),
      'singular_label' => __('Evenements'),
      'public' => true,
      'show_ui' => true,
      'capability_type' => 'post',
      'hierarchical' => false,
  //'supports' => array('title')//'supports' => array('title', 'excerpt', 'thumbnail')
      ));
}

/* Ajout Metaboxes */


$prefix = 'evt_';

$meta_box = array(
    'id' => 'my-meta-box',
    'title' => 'Custom meta box',
    'page' => 'evenement',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Text box',
            'desc' => 'Enter something here',
            'id' => $prefix . 'text',
            'type' => 'text',
            'std' => 'Default value 1'
            ),
        array(
            'name' => 'Textarea',
            'desc' => 'Enter big text here',
            'id' => $prefix . 'textarea',
            'type' => 'textarea',
            'std' => 'Default value 2'
            ),
        array(
            'name' => 'Date',
            'desc' => 'Enter date here',
            'id' => $prefix . 'date',
            'type' => 'date'
            ),
        array(
            'name' => 'Select box',
            'id' => $prefix . 'select',
            'type' => 'select',
            'options' => array('Option 1', 'Option 2', 'Option 3')
            ),
        array(
            'name' => 'Radio',
            'id' => $prefix . 'radio',
            'type' => 'radio',
            'options' => array(
                array('name' => 'Name 1', 'value' => 'Value 1'),
                array('name' => 'Name 2', 'value' => 'Value 2')
                )
            ),
        array(
            'name' => 'Checkbox',
            'id' => $prefix . 'checkbox',
            'type' => 'checkbox'
            )
        )
    );

add_action('admin_menu', 'mytheme_add_box');

// Add meta box
function mytheme_add_box() {
    global $meta_box;

    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}

// Callback function to show fields in meta box
function mytheme_show_box() {
    global $meta_box, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

    echo '<table class="form-table">';

    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);

        echo '<tr>',
        '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
        '<td>';
        switch ($field['type']) {
            case 'text':
            echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
            break;
            case 'textarea':
            echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
            break;
            case 'date':
            echo '<input type="date" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
            break;
            case 'select':
            echo '<select name="', $field['id'], '" id="', $field['id'], '">';
            foreach ($field['options'] as $option) {
                echo '<option ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
            }
            echo '</select>';
            break;
            case 'radio':
            foreach ($field['options'] as $option) {
                echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
            }
            break;
            case 'checkbox':
            echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
            break;
        }
        echo     '</td><td>',
        '</td></tr>';
    }

    echo '</table>';
}



// Sauvegarder les données


add_action('save_post', 'mytheme_save_data');

// Save data from meta box
function mytheme_save_data($post_id) {
    global $meta_box;

    // verify nonce
    if (!wp_verify_nonce($_POST['mytheme_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    foreach ($meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}

