<script>
jQuery(document).ready(function($){
    $(".nf-setting-wrap").click(function(){
        $("#nf-builder").removeClass("nf-drawer-closed");
        $("#nf-builder").addClass("nf-drawer-opened");
        $(".nf-setting-wrap:nth-child(1)").addClass("active");
        $("#field-0 .nf-item-controls").addClass("nf-editing");
    });
    $(".nf-close-drawer").click(function(){
        $("#nf-builder").removeClass("nf-drawer-opened");
        $("#nf-builder").addClass("nf-drawer-closed");
        $(".nf-setting-wrap:nth-child(1)").removeClass("active");
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
                <li><a href="#">Form Fields</a></li>
                <li><a href="#">Emails & Actions</a></li>
                <li class="selected"><a class="active" href="#">Settings</a></li>
                <li><a class="preview" href="#">Preview Changes<span class="dashicons dashicons-visibility"></span></a></li>
            </ul>
            <input class="nf-button primary" type="submit" value="Publish Changes" />
            <a class="nf-cancel" href="#">Cancel</a>
        </div>

        <div id="nf-app-sub-header">
            <h2>Contact Form</h2>
        </div>

    </div>

    <div id="nf-main">
        <!-- main content area. Where fields and actions are rendered. -->
        <div id="nf-main-header">
        </div>
        <div id="nf-main-content">
            <div class="nf-setting-wrap"><span>Display Settings</span></div>
            <div class="nf-setting-wrap"><span>Restrictions</span></div>
            <div class="nf-setting-wrap"><span>Calculations</span></div>
            <div class="nf-setting-wrap"><span>PayPal</span></div>
            <div class="nf-setting-wrap"><span>Stripe</span></div>
        </div>
    </div>

    <div id="nf-drawer">
        <!-- drawer area. This is where settings and add fields are rendered. -->
        <!-- THIS IS THE CONTENT FOR EDITING FIELDS -->
        <header class="nf-drawer-header">
            <h2>Display Settings</h2>
            <input type="submit" class="nf-button primary nf-close-drawer" value="Close" />
        </header>
        <section class="nf-settings">
            <div class="nf-full toggle-row">
                <label>Display Form Title</label>
                <input type="checkbox" class="nf-toggle" />
            </div>
            <div class="nf-full toggle-row">
                <label>Clear form values after successful submission kjh hkja askh askjh jkasfhj kjhasf</label>
                <input type="checkbox" class="nf-toggle" />
            </div>
            <div class="nf-full toggle-row">
                <label>Hide form after successful submission</label>
                <input type="checkbox" class="nf-toggle" />
            </div>
        </section>

        <a class="nf-toggle-drawer">
            <span class="dashicons dashicons-admin-collapse"></span><span class="nf-expand-off">Full screen</span><span class="nf-expand-on">Half screen</span>
        </a>

    </div>

</div>
