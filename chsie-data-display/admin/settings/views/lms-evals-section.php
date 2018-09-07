<?php

/**
* Provides documentation for the LMS Evaluations tab of the CDD settings page.
* This HTML is combined with fields from the Settings API.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/admin/settings/views
*/
?>

<div class="documentation" >
    <p>
        We want users to give feedback on our LMS lessons, and LearnDash isn’t built for that, so we’ve turned to the free version of well-known form plugin Formidable.  It allows us to make drag-and-drop forms, then insert them via a copy-paste shortcode in the description text of a lesson’s landing page.  From there, this plugin handles some more database tweaks, UI functionality, and styling.  You can adjust the global title and call to action properties for the evaluations using the options on this page.
    </p>
    <p>
        <strong>Practically speaking, here’s how you do an eval form:</strong>
    </p>
    <ol>
        <li>
            Open Formidable.  You should see a list of our current forms.
        </li>
        <li>
            Find the one labeled “Module Evaluation Template.  DUPLICATE ME!”  Hover over the name, and you’ll see the Duplicate option.  Click it.
        </li>
        <li>
            Open the Lesson landing page for the module you want to evaluate and copy its title.
        </li>
        <li>
            Go back to your new (duplicated) form, click on the title, and type “Evaluation: “, then paste the Lesson title.  (If you don’t do this, nothing breaks, it’s just good practice).
        </li>
        <li>
            Change any of the question fields.  Add new ones by dragging field types from the right side of the page.  If you want a quick Formidable tutorial, try: <a style="white-space: nowrap;" href="https://formidableforms.com/knowledgebase/create-a-form/" target="_blank">https://formidableforms.com/knowledgebase/create-a-form/</a>
        </li>
        <li>
            Save the form with the Create button at the bottom. If it doesn’t take you to that form’s Settings tab, go there.  You’ll see a Shortcodes link near the top--click it, and copy the shorter of the two shortcodes.
        </li>
        <li>
            Go back to your Lesson.  Open the very top Divi module labeled “Edit: Description Text” (it has other notes in the name, too).
            In that module, paste the shortcode at the very top of the text.  It shouldn’t matter if you’re in the Visual or Text mode of the editor, but if you have problems, use the Text version.
        </li>
        <li>
            Save the Lesson, then go view it.
        </li>
        <li>
            If you haven’t completed the Lesson, do so now; the logic in this plugin won’t display the new form until the whole Lesson is complete. The first time (and every time) that you go to the Lesson landing page after completing the Lesson, you should see the form slide open.
        </li>
    </ol>
    <p>
        You can test it by filling it out and Submitting it, but be warned: once you have completed the form, you will no longer see it!  If you want to re-test, return to Formidable, select that form, and go to the Entries tab.
        You’ll see an entry with the timestamp of the form you just submitted.  Delete it, then return to the Lesson.
        Be warned: you’ll need to reload the page by clicking on the URL bar in your browser to highlight it, then hitting Enter, otherwise the form will re-submit and you won’t see it like a first-time user.
    </p>
</div>
