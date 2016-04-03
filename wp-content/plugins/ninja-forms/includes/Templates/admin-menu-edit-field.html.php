<script>
jQuery(document).ready(function($){
    $(".nf-item-edit").click(function(){
        $("#nf-builder").removeClass("nf-drawer-closed");
        $("#nf-builder").addClass("nf-drawer-opened");
        $(".nf-field-wrap:nth-child(4)").addClass("active");
        $("#field-3 .nf-item-controls").addClass("nf-editing");
    });
    $(".nf-close-drawer").click(function(){
        $("#nf-builder").removeClass("nf-drawer-opened");
        $("#nf-builder").addClass("nf-drawer-closed");
        $(".nf-field-wrap:nth-child(4)").removeClass("active");
        $("#field-3 .nf-item-controls").removeClass("nf-editing");
        $(".before").hide();
        $(".after").show();
    });
    $(".nf-toggle-drawer").click(function(){
        $("#nf-drawer").toggleClass("nf-drawer-expand");
    });
    $(".nf-mobile").click(function(){
        $("#nf-builder").toggleClass("nf-menu-expand");
    });
});
</script>
<div id="nf-builder">
    <?php Ninja_Forms::template( 'ui-nf-header.html.php' ); ?>
    <div id="nf-main">
        <!-- main content area. Where fields and actions are rendered. -->
        <div id="nf-main-header">
            <input class="nf-button secondary" type="submit" value="Edit Emails and Actions" />
        </div>
        <div id="nf-main-content">

<?php
for ($i=0; $i < 5; $i++) {
    if ( 0 == $i ) {
        $field = 'First Name';
    } elseif ( 1 == $i ) {
        $field = 'Last Name';
    } elseif ( 2 == $i ) {
        $field = 'Email';
    } elseif ( 3 == $i ) {
        $field = '<span class="before">Textarea</span><span class="after">Message</span>';
    } else {
        $field = 'Submit';
    }
    echo '<div id="field-' . $i . '" class="nf-field-wrap">' . $field;
    Ninja_Forms::template( 'ui-item-controls.html.php' );
    echo '</div>';
}


?>

        </div>
    </div>
    <?php Ninja_Forms::template( 'ui-nf-menu-drawer.html.php' ); ?>
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
                <input type="text" value="Textarea" />
            </div>
            <div class="nf-one-half">
                <label>Placeholder Text</label>
                <input type="text" value="" />
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

        <?php Ninja_Forms::template( 'ui-nf-toggle-drawer.html.php' ); ?>
        <?php Ninja_Forms::template( 'ui-nf-drawer-buttons.html.php' ); ?>
    </div>

</div>
