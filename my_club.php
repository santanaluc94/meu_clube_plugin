<?php
/**
 * Plugin Name: My Club Plugin
 * Plugin URI: https://github.com/santanaluc94/adclub
 * Description: Plugin para complementar o Tema my_clube do wordpress
 * Version: 1.0
 * Author: Author: Lucas Santana
 * Author URI: https://github.com/santanaluc94/
 */

/**
 * Does not allow executing this file, if it is not executed by Wordpress
 */
if (!defined('ABSPATH')) {
    die;
}

/**
 * Create club settings
 *
 * @return void
 */
function create_club_settings()
{
    add_theme_page(
        __('My Club Settings'),
        __('My Club'),
        'manage_options',
        'my_club',
        'create_my_club_configurations',
        'dashicons-admin-generic',
        2
    );
}

/**
 * Call function to create club settings
 */
add_action('admin_menu', 'create_club_settings');

/**
 * Function to renderize My Club page
 *
 * @return void
 */
function create_my_club_configurations()
{
?>
    <div>
        <h1><?php echo __('My Club') ?></h1>
    </div>

    <form method="post" action="options.php">
        <?php do_settings_sections('my_club') ?>

        <?php settings_fields('group_admin_settings') ?>
        <h2><?php echo __('Club Settings') ?></h2>
        <p><?php echo __('Set name, logo, stadium and colors of your club.') ?></p>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __('Club Name') ?></th>
                <td>
                    <input type="text" id="section_club_name" name="section_club_name" style="width: 40%;" value="<?php echo esc_attr(get_option('section_club_name')) ?>" required />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Club Abbreviation') ?></th>
                <td>
                    <input type="text" id="section_club_abbreviation" name="section_club_abbreviation" style="width: 40%;" value="<?php echo esc_attr(get_option('section_club_abbreviation')) ?>" maxlength="3" required />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Stadium Name') ?></th>
                <td>
                    <input type="text" id="section_club_stadium" name="section_club_stadium" style="width: 40%;" value="<?php echo esc_attr(get_option('section_club_stadium')) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('First Color') ?></th>
                <td>
                    <input type="color" id="section_club_first" name="section_club_first" value="<?php echo esc_attr(get_option('section_club_first')) ?: '#c70039' ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Second Color') ?></th>
                <td>
                    <input type="color" id="section_club_second" name="section_club_second" value="<?php echo esc_attr(get_option('section_club_second')) ?: '#FFC300' ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Third Color') ?></th>
                <td>
                    <input type="color" id="section_club_third" name="section_club_third" value="<?php echo esc_attr(get_option('section_club_third')) ?: '#FFFFFF' ?>" />
                </td>
            </tr>
        </table>

        <h2><?php echo __('Social Medias') ?></h2>
        <p><?php echo __('Set social medias of your club.') ?></p>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __('Store') ?></th>
                <td>
                    <input type="text" id="section_social_medias_store" name="section_social_medias_store" style="width: 40%;" value="<?php echo esc_attr(get_option('section_social_medias_store')) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Facebook') ?></th>
                <td>
                    <input type="text" id="section_social_medias_facebook" name="section_social_medias_facebook" style="width: 40%;" value="<?php echo esc_attr(get_option('section_social_medias_facebook')) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Twitter') ?></th>
                <td>
                    <input type="text" id="section_social_medias_twitter" name="section_social_medias_twitter" style="width: 40%;" value="<?php echo esc_attr(get_option('section_social_medias_twitter')) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Instagram') ?></th>
                <td>
                    <input type="text" id="section_social_medias_instagram" name="section_social_medias_instagram" style="width: 40%;" value="<?php echo esc_attr(get_option('section_social_medias_instagram')) ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Youtube') ?></th>
                <td>
                    <input type="text" id="section_social_medias_youtube" name="section_social_medias_youtube" style="width: 40%;" value="<?php echo esc_attr(get_option('section_social_medias_youtube')) ?>" />
                </td>
            </tr>
        </table>

        <h2><?php echo __('Footer') ?></h2>
        <p><?php echo __('Set the text in footter areas.') ?></p>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __('About CLub') ?></th>
                <td>
                    <textarea type="text" id="section_footer_about" name="section_footer_about" style="width:40%; height:100px;" maxlength="300" value="">
                        <?php echo esc_attr(get_option('section_footer_about')) ?: __('Insira o texto que irá no campo Sobre o Clube do rodapé.') ?>
                    </textarea>
                    <p><?php echo __('Limite máximo de 300 caracteres.') ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Contacts') ?></th>
                <td>
                    <p><?php echo __('Limite máximo de 300 caracteres.') ?></p>
                    <textarea type="text" id="section_footer_contact" name="section_footer_contact" style="width:40%; height:100px;" maxlength="300" value="">
                        <?php echo esc_attr(get_option('section_footer_contact')) ?: __('Insira o texto que irá no campo Entre em Contato do rodapé.') ?>
                    </textarea>
                    <p><?php echo __('Limite máximo de 300 caracteres.') ?></p>
                </td>
            </tr>
        </table>

        <h2><?php echo __('General settings') ?></h2>
        <p><?php echo __('Set the general settings to your theme.') ?></p>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php echo __('Enalbe Sponsors') ?></th>
                <td>
                    <select id="section_settings_sponsors" name="section_settings_sponsors">
                        <option <?php echo esc_attr(get_option('section_settings_sponsors')) === '1' ? 'selected' : '' ?> value="1"><?php echo __('Yes') ?></option>
                        <option <?php echo esc_attr(get_option('section_settings_sponsors')) === '0' ? 'selected' : '' ?> value="0"><?php echo __('No') ?></option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Enable Players') ?></th>
                <td>
                    <select id="section_settings_players" name="section_settings_players">
                        <option <?php echo esc_attr(get_option('section_settings_players')) === '1' ? 'selected' : '' ?> value="1"><?php echo __('Yes') ?></option>
                        <option <?php echo esc_attr(get_option('section_settings_players')) === '0' ? 'selected' : '' ?> value="0"><?php echo __('No') ?></option>
                    </select>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Enable Games') ?></th>
                <td>
                    <select id="section_settings_games" name="section_settings_games">
                        <option <?php echo esc_attr(get_option('section_settings_games')) === '1' ? 'selected' : '' ?> value="1"><?php echo __('Yes') ?></option>
                        <option <?php echo esc_attr(get_option('section_settings_games')) === '0' ? 'selected' : '' ?> value="0"><?php echo __('No') ?></option>
                    </select>
                </td>
            </tr>
        </table>

        <?php submit_button() ?>
    </form>
<?php
}

/**
 * Ceate Meu Clube section
 *
 * @return void
 */
function create_my_club_section()
{
    register_setting('group_admin_settings', 'section_club_name');
    register_setting('group_admin_settings', 'section_club_abbreviation');
    register_setting('group_admin_settings', 'section_club_stadium');
    register_setting('group_admin_settings', 'section_club_first');
    register_setting('group_admin_settings', 'section_club_second');
    register_setting('group_admin_settings', 'section_club_third');

    register_setting('group_admin_settings', 'section_social_medias_store');
    register_setting('group_admin_settings', 'section_social_medias_facebook');
    register_setting('group_admin_settings', 'section_social_medias_twitter');
    register_setting('group_admin_settings', 'section_social_medias_instagram');
    register_setting('group_admin_settings', 'section_social_medias_youtube');

    register_setting('group_admin_settings', 'section_footer_about');
    register_setting('group_admin_settings', 'section_footer_contact');

    register_setting('group_admin_settings', 'section_settings_sponsors');
    register_setting('group_admin_settings', 'section_settings_players');
    register_setting('group_admin_settings', 'section_settings_games');
}

/**
 * Call function to create Meu Clube section
 */
add_action('admin_menu', 'create_my_club_section');
