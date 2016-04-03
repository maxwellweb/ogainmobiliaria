<script>
jQuery(document).ready(function($){
    $(".nf-add-new").click(function(){
        $("#nf-builder").removeClass("nf-drawer-closed");
        $("#nf-builder").addClass("nf-drawer-opened");
        //$(".nf-field-wrap:first-child").addClass("active");
    });
    $(".nf-close-drawer").click(function(){
        $("#nf-builder").removeClass("nf-drawer-opened");
        $("#nf-builder").addClass("nf-drawer-closed");
        //$(".nf-field-wrap:first-child").removeClass("active");
    });

    $(".nf-toggle-drawer").click(function(){
        $("#nf-drawer").toggleClass("nf-drawer-expand");
    });
    $(".nf-item-expand").click(function(){
        $(".nf-group-wrap").toggleClass("expanded");
    });
    $(".nf-mobile").click(function(){
        $("#nf-builder").toggleClass("nf-menu-expand");
    });
    $(".first-name-tr").click(function(){
        $(".first-name").show();
    });
    $(".last-name-tr").click(function(){
        $(".last-name").show();
    });
    $(".email-tr").click(function(){
        $(".email").show();
    });
    $(".textarea-tr").click(function(){
        $(".textarea").show();
    });
    $(".submit-tr").click(function(){
        $(".submit").show();
    });
});
</script>

<div id="nf-builder" class="grey">

    <?php Ninja_Forms::template( 'ui-nf-header' ); ?>

    <div id="nf-main">

        <!-- main content area. Where fields and actions are rendered. -->
        <div id="nf-main-header">

            <!-- <input class="nf-button secondary" type="submit" value="Edit Emails and Actions" /> -->

        </div>

        <div id="nf-main-content">

            <div class="nf-fields-empty">
                <h3>Add form fields</h3>
                <p>Get started by adding your first form field. Just click the plus and select the fields you want. It’s that easy.</p>
            </div>

        </div>
    </div>
    <?php Ninja_Forms::template( 'ui-nf-menu-drawer' ); ?>
    <div id="nf-drawer">
        <!-- drawer area. This is where settings and add fields are rendered. -->
        <!-- THIS IS THE CONTENT FOR EDITING FIELDS -->
        <header class="nf-drawer-header">
            <div class="nf-search">
                <input type="search" class="" value="" placeholder="Search" />
            </div>
            <a href="http://three.ninjaforms.com/wp-admin/admin.php?page=edit-field" class="nf-button primary nf-close-drawer">Done</a>
        </header>
        <section class="nf-settings">
            <div class="nf-reservoir">
                <span class="nf-item-dock first-name">First Name<span class="dashicons dashicons-dismiss"></span></span>
                <span class="nf-item-dock last-name">Last Name<span class="dashicons dashicons-dismiss"></span></span>
                <span class="nf-item-dock email">Email<span class="dashicons dashicons-dismiss"></span></span>
                <span class="nf-item-dock textarea">Textarea<span class="dashicons dashicons-dismiss"></span></span>
                <span class="nf-item-dock submit">Submit<span class="dashicons dashicons-dismiss"></span></span>
            </div>
        </section>
        <section class="nf-settings nf-favorites">
            <h3>Saved Fields</h3>
            <div class="nf-one-third">
                <div class="nf-item first-name-tr">First Name</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item last-name-tr">Last Name</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item email-tr">Email</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item submit-tr">Submit</div>
            </div>
        </section>
        <section class="nf-settings">
            <h3>Common Fields</h3>
            <div class="nf-one-third">
                <div class="nf-item">Textbox</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item textarea-tr">Textarea</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Checkbox</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Dropdown</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Checkbox List</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Radio List</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">File Upload</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Multi-Select</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Hidden Field</div>
            </div>
        </section>

        <?php Ninja_Forms::template( 'ui-nf-toggle-drawer' ); ?>

    </div>

</div>
