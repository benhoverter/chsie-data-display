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
<ul>
    <li>
        <input id="no_cta" name="lms_evals_cta_field[radio]" type="radio" value="no_cta" <?php echo $no_checked; ?> />
        <label for="no_cta">No Call To Action</label>
        <p>
            Don't show a call to action after the lesson evaluation title.
        </p>
    </li>
    <li>
        <input id="custom_cta" name="lms_evals_cta_field[radio]" type="radio" value="custom_cta" <?php echo $custom_checked; ?> />
        <label for="custom_cta">Custom Call To Action</label>
        <input id="custom_cta_text" name="lms_evals_cta_field[custom_text]" type="text" value="<?php echo $custom_text; ?>" placeholder="title goes here" />
        <p>
            Use a custom call to action.
        </p>
    </li>
</ul>
