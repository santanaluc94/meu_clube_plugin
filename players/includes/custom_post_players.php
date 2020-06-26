<?php

/**
 * Enable setting if Players is enable
 */
if ((bool) esc_attr(get_option('section_settings_players')) === true) {

    /**
     * Create custom post Player
     */
    function create_post_player()
    {
        register_post_type(
            'players',
            [
                'labels' => [
                    'name' => __('Players'),
                    'singular_name' => __('Player'),
                    'add_new' => __('Add Player'),
                    'add_new_item' => __('Add new Player'),
                    'edit_item' => __('Edit Player'),
                    'all_items' => __('All Players'),
                    'view_item' => __('View Player'),
                    'search_item' => __('Search Player'),
                ],
                'public' => true,
                'has_archive' => false,
                'rewrite' => ['slug' => 'player'],
                'supports' => [
                    'thumbnail'
                ],
                'menu_icon' => 'dashicons-groups'
            ]
        );
    }

    /**
     * Call function to create post Players
     */
    add_action('init', 'create_post_player');


    /**
     * Create form to Players in SQL
     */
    function register_custom_posts_player()
    {
        add_meta_box(
            'info-player',
            __('Players Information'),
            'form_players',
            'players',
            'normal',
            'high'
        );
    }

    /**
     * Call function to create form to Players
     */
    add_action('add_meta_boxes', 'register_custom_posts_player');


    /**
     * Create form to Players in custom post
     *
     * @param array $post
     * @return void
     */
    function form_players($post)
    {
        $players = get_post_meta($post->ID);
?>
        <link rel="stylesheet" type="text/css" href="<?php echo esc_url(get_template_directory_uri()) . '/plugins/players/css/form_players.css' ?>">

        <?php if ($_SESSION['my_admin_errors_players']) : ?>
            <div class="error">
                <p><?php echo $_SESSION['my_admin_errors_players'] ?></p>
                <?php unset($_SESSION['my_admin_errors_players']); ?>
            </div>
        <?php endif; ?>
        <div class="container">
            <form method="post">
                <fieldset>
                    <div class="row form-box">
                        <label class="wp-form-label" for="name"><?php echo __('Name:') ?></label>
                        <input class="wp-form-field" name="name" id="name" type="text" value="<?php echo $players['name'][0] ?>" required />
                    </div>

                    <div class="row form-box">
                        <label class="wp-form-label" for="full_name"><?php echo __('Full Name:') ?></label>
                        <input class="wp-form-field" name="full_name" id="full_name" type="text" value="<?php echo $players['full_name'][0] ?>" required />
                    </div>

                    <div class="row form-box">
                        <label class="wp-form-label" for="height"><?php echo __('Height: (cm)') ?></label>
                        <input class="wp-form-field" name="height" id="height" type="text" value="<?php echo $players['height'][0] ?>" />
                    </div>

                    <div class="row form-box">
                        <label class="wp-form-label" for="weight"><?php echo __('Weight: (kg)') ?></label>
                        <input class="wp-form-field" name="weight" id="weight" type="text" value="<?php echo $players['weight'][0] ?>" />
                    </div>

                    <div class="row form-box">
                        <label class="wp-form-label" for="function"><?php echo __('Function:') ?></label>
                        <input class="wp-form-field" name="function" id="function" type="text" value="<?php echo $players['function'][0] ?>" />
                    </div>

                    <div class="row form-box">
                        <label class="wp-form-label" for="birthday"><?php echo __('Birthday:') ?></label>
                        <input class="wp-form-field" name="birthday" id="birthday" type="date" value="<?php echo $players['birthday'][0] ?>" />
                    </div>
                </fieldset>
            </form>
        </div>
<?php
    }

    /**
     * Save custom post Players in SQL
     *
     * @param int $post_id
     * @return void
     */
    function save_players($post_id)
    {
        $_SESSION['my_admin_errors_players'] = '';

        if (isset($_POST['name'])) {
            if (!empty($_POST['name'])) {
                update_post_meta($post_id, 'name', sanitize_text_field($_POST['name']));

                global $wpdb;
                $wpdb->update($wpdb->posts, ['post_title' => sanitize_text_field($_POST['name'])], ['ID' => $post_id]);
            } else {
                $_SESSION['my_admin_errors_players'] .= __('Name can not be empty.');
            }
        }

        if (isset($_POST['full_name'])) {
            update_post_meta($post_id, 'full_name', sanitize_text_field($_POST['full_name']));
        }

        if (isset($_POST['height'])) {
            if (!empty($_POST['height'] && $_POST['height'] < 285 && $_POST['height'] > 50)) {
                update_post_meta($post_id, 'height', sanitize_text_field($_POST['height']));
            } else {
                $_SESSION['my_admin_errors_players'] .= __('Height must be in centimeter, check value.');
            }
        }

        if (isset($_POST['weight'])) {
            if (!empty($_POST['weight'] && $_POST['weight'] < 285 && $_POST['weight'] > 30)) {
                update_post_meta($post_id, 'weight', sanitize_text_field($_POST['weight']));
            } else {
                $_SESSION['my_admin_errors_players'] .= __('Weight must be in kilograms, check value.');
            }
        }

        if (isset($_POST['function'])) {
            update_post_meta($post_id, 'function', sanitize_text_field($_POST['function']));
        }

        if (isset($_POST['birthday'])) {
            if (date("Y-m-d") > $_POST['birthday']) {
                update_post_meta($post_id, 'birthday', sanitize_text_field($_POST['birthday']));
            } else {
                $_SESSION['my_admin_errors_players'] .= __('Birthday can not be greater than today.');
            }
        }

        if (!empty($_SESSION['my_admin_errors_players'])) {
            return;
        }
    }

    /**
     * Call function to save cutom post Players in SQL
     */
    add_action('save_post', 'save_players');


    /**
     * Create Players page
     *
     * @return void
     */
    function create_players_page()
    {
        if (get_option('function_execute_once_01') !== 'completed') {
            $page_players = [
                'post_title' => __('Players'),
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'page'
            ];
            wp_insert_post($page_players);
        }

        update_option('function_execute_once_01', 'completed');

        /**
         * Run create_players_page only once
         */
        add_action('admin_init', 'function_execute_once');
    }

    /**
     * Call function to create Players page
     */
    add_action('admin_init', 'create_players_page');
}
