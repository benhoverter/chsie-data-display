<?php

/**
* Provides the custom Call To Action field for the LMS Evaluations tab of the
* CDD settings page.
* This HTML depends upon logic in Settings.php.
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
            <input id="no_cta" name="chsie_data_display_lms_evals_section[cta_radio]" type="radio" value="no_cta" <?php echo $no_checked; ?> />
            <label for="no_cta">No Call To Action</label>
            <p>
                Don't show a call to action after the lesson evaluation title.
            </p>
        </li>
        <li>
            <input id="custom_cta" name="chsie_data_display_lms_evals_section[cta_radio]" type="radio" value="custom_cta" <?php echo $custom_checked; ?> />
            <label for="custom_cta">Custom Call To Action</label>
            <input id="custom_cta_text" class="custom-text" name="chsie_data_display_lms_evals_section[cta_text]" type="text" value="<?php echo $custom_cta_text; ?>" placeholder="CTA goes here" />
            <p>
                Use a custom call to action.
            </p>
        </li>
    </ul>
</fieldset>
