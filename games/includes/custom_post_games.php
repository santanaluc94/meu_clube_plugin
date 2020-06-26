<?php

if ((bool) esc_attr(get_option('section_settings_games')) === true) {

    /**
     * Create custom post Games
     *
     * @return void
     */
    function create_post_games()
    {
        register_post_type(
            'games',
            [
                'labels' => [
                    'name' => __('Games'),
                    'singular_name' => __('Game'),
                    'add_new' => __('Add Game'),
                    'add_new_item' => __('Add new Game'),
                    'edit_item' => __('Edit Game'),
                    'all_items' => __('All Games'),
                    'view_item' => __('View Game'),
                    'search_item' => __('Search Game'),
                ],
                'public' => true,
                'has_archive' => false,
                'rewrite' => ['slug' => 'games'],
                'supports' => [
                    'thumbnail',
                    'title'
                ],
                'menu_icon' => 'dashicons-tickets-alt'
            ]
        );
    }

    /**
     * Call function to create custom posts type
     */
    add_action('init', 'create_post_games');

    /**
     * Create form to Games in SQL
     */
    function register_post_games()
    {
        add_meta_box(
            'games-info',
            __('Games Information'),
            'form_games',
            'games',
            'normal',
            'high'
        );
    }

    /**
     * Call function to create form to Games
     */
    add_action('add_meta_boxes', 'register_post_games');


    /**
     * Create form to Games post
     *
     * @param array $post
     * @return void
     */
    function form_games($post)
    {
        $games = get_post_meta($post->ID);
        $clube = esc_attr(get_option('section_club_abbreviation'));
        $estadio = esc_attr(get_option('section_club_stadium'));
?>
        <link rel="stylesheet" type="text/css" href="<?php esc_url(get_template_directory_uri()); ?>/plugins/games/css/form_games.css">

        <?php if (!empty($_SESSION['my_admin_errors_games'])) : ?>
            <div class="error">
                <?php foreach ($_SESSION['my_admin_errors_games'] as $error) : ?>
                    <p><?php echo $error ?></p>
                    <?php unset($error); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post">
            <fieldset>
                <input type="text" value="<?php echo $clube ?>" id="club_name" hidden readonly />
                <input type="text" value="<?php echo $estadio ?>" id="stadium_club" hidden readonly />
                <section class="first-row">
                    <div class="box-first-row">
                        <label>
                            <span><?php echo __('Date') ?></span>
                        </label>
                        <input type="date" name="date" id="date" value="<?php echo $games['date'][0] ?>" required />
                    </div>
                    <div class="box-first-row">
                        <label>
                            <span><?php echo __('Hour') ?></span>
                        </label>
                        <input type="time" name="hour" id="hour" value="<?php echo $games['hour'][0] ?>" required />
                    </div>
                    <div class="box-first-row">
                        <label>
                            <span><?php echo __('Stadium') ?></span>
                        </label>
                        <input type="text" name="stadium" id="stadium" value="<?php echo $games['stadium'][0] ?>" />
                    </div>
                </section>

                <section class="second-row">
                    <div class="box-second-row">
                        <input class="rad-home" type="radio" name="home_guest" id="rad-home" value="home" />
                        <label for="home">
                            <span><?php echo __('Home Club') ?></span>
                        </label>
                    </div>
                    <div class="box-second-row">
                        <input class="rad-guest" type="radio" name="home_guest" id="rad-guest" value="guest" />
                        <label for="guest">
                            <span><?php echo __('Guest Club') ?></span>
                        </label>
                    </div>
                </section>

                <section class="third-row">
                    <div class="box-third-row">
                        <label>
                            <span><?php echo __('Home Club') ?></span>
                        </label>
                        <input type="text" class="box-club-name" name="home" id="home" value="<?php echo $games['home'][0] ?>" maxlength="3" required />
                        <input type="text" class="box-score" name="score_home" id="score_home" value="<?php echo $games['score_home'][0] ?>" />
                        <label>
                            <span class="hide-mobile">X</span>
                        </label>
                        <label class="box-guest-mobile">
                            <span><?php echo __('Guest Club') ?></span>
                        </label>
                        <input type="text" class="box-score" name="score_guest" id="score_guest" value="<?php echo $games['score_guest'][0] ?>" />
                        <input type="text" class="box-club-name" name="guest" id="guest" value="<?php echo $games['guest'][0] ?>" maxlength="3" />
                    </div>
                </section>
            </fieldset>
        </form>

        <script type="text/javascript" src="<?php esc_url(get_template_directory_uri()); ?>/plugins/games/js/form_games.js">
        </script>
    <?php
    }

    /**
     * Save post Games in SQL
     *
     * @param int $post_id
     * @return void
     */
    function save_games($post_id)
    {
        $_SESSION['my_admin_errors_games'] = [];

        if (isset($_POST['home_guest'])) {
            if ($_POST['home_guest'] === 'home') {
                $guest_club = substr(sanitize_text_field($_POST['guest']), 0, 3);
                update_post_meta($post_id, 'home', esc_attr(get_option('section_club_abbreviation')));
                update_post_meta($post_id, 'guest', $guest_club);
                update_post_meta($post_id, 'stadium', esc_attr(get_option('section_club_stadium')));
            }

            if ($_POST['home_guest'] === 'guest') {
                $home_club = substr(sanitize_text_field($_POST['home']), 0, 3);
                update_post_meta($post_id, 'guest', esc_attr(get_option('section_club_abbreviation')));
                update_post_meta($post_id, 'home', $home_club);
                update_post_meta($post_id, 'stadium', sanitize_text_field($_POST['stadium']));
            }
        } else {
            update_post_meta($post_id, 'home', sanitize_text_field($_POST['home']));
            update_post_meta($post_id, 'guest', sanitize_text_field($_POST['guest']));
            update_post_meta($post_id, 'stadium', sanitize_text_field($_POST['stadium']));
        }

        if (isset($_POST['home']) && isset($_POST['guest'])) {
            $post_title = sanitize_text_field($_POST['home']) . ' x ' . sanitize_text_field($_POST['guest']);
            global $wpdb;
            $wpdb->update($wpdb->posts, ['post_title' => $post_title], ['ID' => $post_id]);
        }

        if (isset($_POST['hour'])) {
            update_post_meta($post_id, 'hour', sanitize_text_field($_POST['hour']));
        }

        if (isset($_POST['date'])) {
            $date = explode('-', $_POST['date']);

            if (checkdate($date[1], $date[2], $date[0])) {

                update_post_meta($post_id, 'date', sanitize_text_field($_POST['date']));

                if (date("Y-m-d") > $_POST['date']) {

                    if (isset($_POST['score_home'])) {
                        if (is_numeric($_POST['score_home'])) {
                            if ($_POST['score_home'] >= 0) {
                                update_post_meta($post_id, 'score_home', (int) sanitize_text_field($_POST['score_home']));
                            } else {
                                $_SESSION['my_admin_errors_games'][] = __('The score can not be a negative number.');
                            }
                        } else {
                            $_SESSION['my_admin_errors_games'][] = __('The score must be a number.');
                        }
                    }

                    if (isset($_POST['score_guest'])) {
                        if (is_numeric($_POST['score_guest'])) {
                            if ($_POST['score_guest'] >= 0) {
                                update_post_meta($post_id, 'score_guest', (int) sanitize_text_field($_POST['score_guest']));
                            } else {
                                $_SESSION['my_admin_errors_games'][] = __('The score can not be a negative number.');
                            }
                        } else {
                            $_SESSION['my_admin_errors_games'][] = __('The score must be a number.');
                        }
                    } else {
                        update_post_meta($post_id, 'score_home', null);
                        update_post_meta($post_id, 'score_guest', null);
                    }
                }
            } else {
                $_SESSION['my_admin_errors_games'][] = __('The date is invalid.');
            }
        } else {
            $_SESSION['my_admin_errors_games'][] = __('The date is required field.');
        }
    }

    /**
     * Call function to save post Games in SQL
     */
    add_action('save_post', 'save_games');

    /**
     * Create default Pages
     *
     * @return void
     */
    function create_games_championships_pages()
    {
        if (get_option('function_execute_once_01') !== 'completed') {
            $page_games = [
                'post_title' => 'Games',
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'page'
            ];

            $page_championships = [
                'post_title' => 'Championships',
                'post_content' => '',
                'post_status' => 'publish',
                'post_author' => 1,
                'post_type' => 'page'
            ];

            wp_insert_post($page_games);
            wp_insert_post($page_championships);
        }
        update_option('function_execute_once_01', 'completed');

        /**
         * Run create_games_championships_pages only once
         */
        add_action('admin_init', 'function_execute_once');
    }

    /**
     * Call function to create static page
     */
    add_action('admin_init', 'create_games_championships_pages');


    /**
     * Create Championships taxonomy
     *
     * @return void
     */
    function create_championships()
    {
        $singular_name = __('Championship');
        $plural_name = __('Championships');

        $args = [
            'labels' => [
                'name' => $plural_name,
                'singular_name' => $singular_name,
                'singular_name' => $singular_name,
                'edit_item' => $singular_name,
                'add_new_item' => __("Add new ") . $singular_name,
                'add_new' => __("Add", $singular_name),
                'new_item' => __("New ") . $singular_name,
                'all_items' => __("All ") . $plural_name,
                'view_item' => __("View ") . $singular_name,
                'search_items' => __("Search ") . $singular_name,
                'not_found' =>  __("Not found any ") . $plural_name,
                'not_found_in_trash' => __("Not found any ") . $singular_name . __(" in trash"),
                'parent_item_colon' => '',
                'menu_name' => $plural_name
            ],
            'public' => true,
            'hierarchical' => true,
            'has_archive' => true,
            'supports' => ['thumbnail'],
            'show_ui' => true,
            'show_admin_column' => true,
        ];

        register_taxonomy('championships', 'games', $args);
    }


    /**
     * Call function to create Championships taxonomy
     */
    add_action('init', 'create_championships');

    /**
     * Create field to Championships taxonomy
     *
     * @return void
     */
    function add_field_to_taxonomy_championships()
    {
        $currently_selected = date('Y');
        $earliest_year = $currently_selected - 10;
        $latest_year = date('Y') + 1;

    ?>
        <div class="form-field">
            <label for="term_meta[year]"><?php echo __('Championship Year'); ?></label>
            <select name="term_meta[year]" id="term_meta[year]">
                <?php foreach (range($latest_year, $earliest_year) as $i) : ?>
                    <option value="<?php echo $i ?>" <?php echo $currently_selected == $i ? 'selected' : '' ?>>
                        <?php echo $i ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description"><?php echo __('Year the championship will be shown.'); ?></p>
        </div>
    <?php
    }


    /**
     * Call function to create Championships taxonomy
     */
    add_action('championships_add_form_fields', 'add_field_to_taxonomy_championships', 10, 2);


    /**
     * Create field to page edit Championships taxonomy
     *
     * @param array $term
     * @return void
     */
    function page_edit_championships_taxonomy($term)
    {
        $term_meta = get_option('taxonomy_' . $term->term_id);

        $currently_selected = date('Y');
        $earliest_year = $currently_selected - 10;
        $latest_year = date('Y') + 1;

    ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="term_meta[year]"><?php echo __('Championship Year'); ?></label>
            </th>
            <td>
                <select name="term_meta[year]" id="term_meta[year]">
                    <?php foreach (range($latest_year, $earliest_year) as $i) : ?>
                        <option value="<?php echo $i; ?>" <?php echo $i == esc_attr($term_meta['year']) ? 'selected' : '' ?>>
                            <?php echo $i ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="description"><?php echo __('Year the championship will be shown.'); ?></p>
            </td>
        </tr>
<?php
    }

    /**
     * Call function to create field Championships taxonomy in edit page
     */
    add_action('championships_edit_form_fields', 'page_edit_championships_taxonomy', 10, 2);

    /**
     * Save Championships taxonomy
     *
     * @param int $term_id
     * @return void
     */
    function save_taxonomy_championships($term_id)
    {
        if (isset($_POST['term_meta'])) {
            $term_meta = get_option('taxonomy_' . $term_id);
            $cat_keys = array_keys($_POST['term_meta']);

            foreach ($cat_keys as $key) {
                if (isset($_POST['term_meta'][$key])) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }

            // Save the option array.
            update_option('taxonomy_' . $term_id, $term_meta);
        }
    }

    /**
     * Call function to save taxonomy in edit page
     */
    add_action('edited_championships', 'save_taxonomy_championships', 10, 2);

    /**
     * Call function to save taxonomy in new page
     */
    add_action('create_championships', 'save_taxonomy_championships', 10, 2);

    /**
     * Use radio inputs instead of checkboxes for term checklists in Campeonatos taxonomy.
     *
     * @param array $args
     * @return array
     */
    function change_radio_in_championships_taxonomy($args)
    {
        if (!empty($args['taxonomy']) && $args['taxonomy'] === 'championships') {
            if (empty($args['walker']) || is_a($args['walker'], 'Walker')) {
                if (!class_exists('WPSE_139269_Walker_Category_Radio_Checklist')) {
                    /**
                     * Custom walker for switching checkbox inputs to radio.
                     *
                     * @see Walker_Category_Checklist
                     */
                    class WPSE_139269_Walker_Category_Radio_Checklist extends Walker_Category_Checklist
                    {
                        function walk($elements, $max_depth, ...$args)
                        {
                            $output = parent::walk($elements, $max_depth, ...$args);
                            $output = str_replace(
                                ['type="checkbox"', "type='checkbox'"],
                                ['type="radio"', "type='radio'"],
                                $output
                            );

                            return $output;
                        }
                    }
                }

                $args['walker'] = new WPSE_139269_Walker_Category_Radio_Checklist;
            }
        }

        return $args;
    }

    add_filter('wp_terms_checklist_args', 'change_radio_in_championships_taxonomy');
}
