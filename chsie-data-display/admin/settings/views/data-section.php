<?php

/**
* Provides the HTML framework for the Data Dislpay tab of the CDD settings page.
*
* @link       https://github.com/benhoverter/chsie-data-display
* @since      1.0.0
*
* @package    chsie-data-display
* @subpackage chsie-data-display/admin/element/views
*/
?>

<div id="data-section" class="wrap" >

    <div class="settings-wrapper">
        <p>
            This plugin displays data relating to the learning management system, user demographic questions, and other site functions.
        </p>
        <p>
            To begin, just select a data set from the dropdown menu.
        </p>
        <p>
            <em>NOTE: To download all data for that set, you must show "ALL" entries before clicking the "Download CSV" button.</em>
        </p>
    </div>

    <div id="cdd-button-div">

        <?php $this->do_dropdown_select( $this->queries ); ?>

        <div class="spinner faded"></div>
        <a id='cdd-data-select-button' class='button button-primary'>Display</a>
        <a id='cdd-download-csv' class='button' style='display: none;'>Download CSV</a>

    </div>

    <div class="settings-wrapper">
        <p>
            <?php echo $this->check_conn_status( $this->conn ); ?>
        </p>
    </div>
    <div id="cdd-table-wrapper">
        <div id="cdd-data-table-mask"></div>
        <div id="cdd-data-table"></div>
    </div>

    <div id="cdd-credits">
        <?php // $this->do_end_messages_data_tables(); ?>
        <p>Developed by Ben Hoverter for the Center for Health Sciences Interprofessional Education, Research and Practice</p>
    </div>
</div>
