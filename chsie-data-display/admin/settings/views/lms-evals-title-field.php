<?php

/**
* Provides the custom Title field for the LMS Evaluations tab of the CDD settings page.
* This HTML depends upon logic from Settings.php.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/admin/settings/views
*/
?>
<fieldset>
    <ul>
        <li>
            <input id="no_title" name="chsie_data_display_lms_evals_section[title_radio]" type="radio" value="no_title" <?php echo $no_checked; ?> />
            <label for="no_title">No Title</label>
            <p>
                No title will display on any lesson evaluation.
            </p>
        </li>
        <li>
            <input id="form_title" name="chsie_data_display_lms_evals_section[title_radio]" type="radio" value="form_title" <?php echo $form_checked; ?> />
            <label for="form_title">Evaluation Title</label>
            <p>
                Each lesson evaluation form will display its title as shown in Formidable.
            </p>
        </li>
        <li>
            <input id="custom_title" name="chsie_data_display_lms_evals_section[title_radio]" type="radio" value="custom_title" <?php echo $custom_checked; ?> />
            <label for="custom_title">Custom Title</label>
            <input id="custom_title_text" class="custom-text" name="chsie_data_display_lms_evals_section[title_text]" type="text" value=<?php echo esc_html( $custom_title_text ); ?> placeholder="title goes here" />
            <p>
                Enter a custom title to display on all lesson evaluation forms (e.g., "You're done!").  No HTML.
            </p>
        </li>
    </ul>
</fieldset>
