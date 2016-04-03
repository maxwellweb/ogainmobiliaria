<script>
jQuery(document).ready(function($){
    $(".nf-item-edit").click(function(){
        $("#nf-builder").removeClass("nf-drawer-closed");
        $("#nf-builder").addClass("nf-drawer-opened");
        $(".nf-field-wrap:first-child").addClass("active");
        $("#action-3 .nf-item-controls").addClass("nf-editing");
    });
    $(".nf-close-drawer").click(function(){
        $("#nf-builder").removeClass("nf-drawer-opened");
        $("#nf-builder").addClass("nf-drawer-closed");
        $(".nf-field-wrap:first-child").removeClass("active");
        $("#action-3 .nf-item-controls").removeClass("nf-editing");
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
            <input class="nf-button secondary" type="submit" value="Manage Settings" />
        </div>

        <div id="nf-main-content">
            <table id="nf-table-display" class="nf-actions-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="nf-toggle" checked /></td>
                        <td>Save to Database</td>
                        <td>Save Submissions</td>
                        <td><?php Ninja_Forms::template( 'ui-item-controls.html.php' ); ?></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="nf-toggle" checked /></td>
                        <td>Email to Admin</td>
                        <td>Email</td>
                        <td><?php Ninja_Forms::template( 'ui-item-controls.html.php' ); ?></td>
                    </tr>
                    <tr class="nf-deactivated">
                        <td><input type="checkbox" class="nf-toggle" /></td>
                        <td>Thank You Message</td>
                        <td>Sucess Message</td>
                        <td><?php Ninja_Forms::template( 'ui-item-controls.html.php' ); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    <?php Ninja_Forms::template( 'ui-nf-menu-drawer.html.php' ); ?>
    <div id="nf-drawer">
        <!-- drawer area. This is where settings and add fields are rendered. -->
        <!-- THIS IS THE CONTENT FOR EDITING FIELDS -->
        <header class="nf-drawer-header">
            <h2>Email Action</h2>
            <input type="submit" class="nf-button primary nf-close-drawer" value="Close" />
        </header>
        <section class="nf-settings">
            <div class="nf-full">
                <label>Action Name</label>
                <input type="text" value="Email to Use" />
            </div>
            <div class="nf-one-half">
                <label>From Name</label>
                <input type="text" value="James Laws" />
            </div>
            <div class="nf-one-half">
                <label>From Email</label>
                <input type="text" value="james@wpninjas.com" />
            </div>
            <div class="nf-full">
                <label>To</label>
                <input type="text" value="" />
            </div>
            <div class="nf-full">
                <label>Subject</label>
                <input type="text" value="Email to Use" />
            </div>
            <fieldset class="nf-wp-editor">
                <legend>Email Message</legend>
                <div class="nf-full">

                </div>
                <div class="nf-full">
                    <?php wp_editor( 'Your Email Message', 2, $settings = array() ); ?>
                </div>
            </fieldset>
        </section>

        <?php Ninja_Forms::template( 'ui-nf-toggle-drawer.html.php' ); ?>
        <?php Ninja_Forms::template( 'ui-nf-drawer-buttons.html.php' ); ?>

    </div>

</div>
