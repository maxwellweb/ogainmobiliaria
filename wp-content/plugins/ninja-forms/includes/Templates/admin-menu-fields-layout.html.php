<script>
jQuery(document).ready(function($){
    $(".nf-item-edit").click(function(){
        $("#nf-builder").removeClass("nf-drawer-closed");
        $("#nf-builder").addClass("nf-drawer-opened");
        $(".nf-field-wrap:first-child").addClass("active");
        $("#field-0 .nf-item-controls").addClass("nf-editing");
    });
    $(".nf-close-drawer").click(function(){
        $("#nf-builder").removeClass("nf-drawer-opened");
        $("#nf-builder").addClass("nf-drawer-closed");
        $(".nf-field-wrap:first-child").removeClass("active");
        $("#field-0 .nf-item-controls").removeClass("nf-editing");
    });
    $(".nf-toggle-drawer").click(function(){
        $("#nf-drawer").toggleClass("nf-drawer-expand");
    });
});
</script>
<div id="nf-builder">
    <div id="nf-header">
        <div id="nf-app-header">
            <div id="nf-logo"></div>
            <ul>
                <li class="selected"><a class="active" href="#">Form Fields</a></li>
                <li><a href="#">Emails & Actions</a></li>
                <li><a href="#">Settings</a></li>
                <li><a class="preview" href="#">Preview Changes<span class="dashicons dashicons-visibility"></span></a></li>
            </ul>
            <input class="nf-button primary" type="submit" value="Publish Changes" />
            <a class="nf-cancel" href="#">Cancel</a>
        </div>

        <div id="nf-app-sub-header">
            <h2>Contact Form</h2>

            <!-- <input class="nf-button secondary" type="submit" value="Edit Emails and Actions" /> -->

        </div>

    </div>

    <div id="nf-main">
        <!-- main content area. Where fields and actions are rendered. -->
        <div id="nf-main-header">
            <input class="nf-button secondary" type="submit" value="Edit Emails and Actions" />
        </div>
        <div id="nf-main-content">
<a class="nf-add-new" href="#">Add new field</a>
<?php
for ($i=0; $i < 25; $i++) {
    if ( 0 == $i ) {
        $field = 'First Name *';
    } else {
        $field = 'Textbox';
    }
    echo '<div id="field-' . $i . '" class="nf-field-wrap">' . $field . '
        <ul class="nf-item-controls">
            <li class="nf-item-delete"><a href="#"><span class="dashicons dashicons-dismiss"></span><span class="nf-tooltip">Delete</span></a></li>
            <li class="nf-item-duplicate"><a href="#"><span class="dashicons dashicons-admin-page"></span><span class="nf-tooltip">Duplicate</span></a></li>
            <li class="nf-item-edit"><a href="#"><span class="dashicons dashicons-admin-generic"></span><span class="nf-tooltip">Edit</span><span class="nf-item-editing">Editing field</span></a></li>
        </ul>
    </div>';
}
?>
        </div>
    </div>

    <div id="nf-drawer">
        <!-- drawer area. This is where settings and add fields are rendered. -->
        <!-- THIS IS THE CONTENT FOR EDITING FIELDS -->
        <header class="nf-drawer-header">
            <h2><span class="dashicons dashicons-star-empty"></span>Single Line Textbox</h2>
            <input type="submit" class="nf-button primary nf-close-drawer" value="Close" />
        </header>
        <section class="nf-settings">
            <div class="nf-one-half">
                <label>Label</label>
                <input type="text" value="First Name" />
            </div>
            <div class="nf-one-half">
                <label>Placeholder Text</label>
                <input type="text" value="Enter your first name" />
            </div>
            <div class="nf-one-half">
                <label>Label Position</label>
                <div class="nf-select">
                    <select>
                        <option>Above Field</option>
                        <option>Below Field</option>
                        <option>Left of Field</option>
                        <option>Right of Field</option>
                        <option>Hide Label</option>
                    </select>
                </div>
            </div>
            <div class="nf-one-half">
                <label>Required Field</label>
                <input type="checkbox" class="nf-toggle" />
            </div>
        </section>
        <section class="nf-settings">
            <h3><span class="dashicons dashicons-arrow-down"></span>Restriction Settings</h3>
            <div class="nf-settings-sub">
                <div class="nf-one-half">
                    <label>Input Mask</label>
                    <div class="nf-select">
                        <select>
                            <option>None</option>
                            <option>US Phone Number</option>
                            <option>Date</option>
                        </select>
                    </div>
                </div>
                <fieldset>
                    <legend>Limit input to this number</legend>
                    <div class="nf-one-half">
                        <input type="text" value="140" />
                    </div>
                    <div class="nf-one-half">
                        <div class="nf-select">
                            <select>
                                <option>Character</option>
                                <option>Words</option>
                            </select>
                        </div>
                    </div>
                    <div class="nf-full">
                        <label>Text to appear after counter</label>
                        <input type="text" value="character(s) left" />
                    </div>
                </fieldset>
            </div>
        </section>
        <section class="nf-settings">
            <h3><span class="dashicons dashicons-arrow-right"></span>Advanced Settings</h3>
        </section>
        <section class="nf-settings">
            <h3><span class="dashicons dashicons-arrow-right"></span>Conditional Settings</h3>
        </section>

        <a class="nf-toggle-drawer">
            <span class="dashicons dashicons-admin-collapse"></span><span class="nf-expand-off">Full screen</span><span class="nf-expand-on">Half screen</span>
        </a>
    </div>

</div>
