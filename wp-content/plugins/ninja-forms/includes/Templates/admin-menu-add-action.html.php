<script>
jQuery(document).ready(function($){
    $(".nf-add-new").click(function(){
        $("#nf-builder").removeClass("nf-drawer-closed");
        $("#nf-builder").addClass("nf-drawer-opened");
        //$(".nf-field-wrap:first-child").addClass("active");
        //$("#action-0 .nf-item-controls").addClass("nf-editing");
    });
    $(".nf-close-drawer").click(function(){
        $("#nf-builder").removeClass("nf-drawer-opened");
        $("#nf-builder").addClass("nf-drawer-closed");
        //$(".nf-field-wrap:first-child").removeClass("active");
        //$("#action-0 .nf-item-controls").removeClass("nf-editing");
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
            <div class="nf-search">
                <input type="search" class="" value="" placeholder="Search" />
            </div>
            <input type="submit" class="nf-button primary nf-close-drawer" value="Done" />
        </header>

        <section class="nf-settings nf-action-items">
            <h3>Installed Actions</h3>
            <div class="nf-one-third">
                <div class="nf-item">Email</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Success Message</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Redirect</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Save Submissions</div>
            </div>
        </section>
        <section class="nf-settings nf-action-items">
            <h3>Available Actions</h3>
            <div class="nf-one-third">
                <div class="nf-item">MailChimp</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Insightly</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Constant Contact</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Campaign Monitor</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Slack</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Trello</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Create Post</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Salesforce</div>
            </div>
            <div class="nf-one-third">
                <div class="nf-item">Text Message</div>
            </div>
        </section>

        <?php Ninja_Forms::template( 'ui-nf-toggle-drawer.html.php' ); ?>
    </div>

</div>
