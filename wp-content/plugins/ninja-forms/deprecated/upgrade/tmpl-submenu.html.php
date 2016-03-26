<img class="nf-logo" src="<?php echo NF_PLUGIN_URL . 'images/nf-logo.png'; ?>">
<div class="nf-upgrade">
    <h1>Congratulations!</h1>
    <h2>You are elgible to update to Ninja Forms THREE!</h2>

    <p>You are only moments away from the biggest thing to ever happen to the WordPress form building experience.</p>

    <p>Ninja Forms THREE is the most <em>intuitive</em>, <em>powerful</em>, and <em>enjoyable</em> form builder you will ever use.</p>

    <h2>Before you update, we want to make you aware of a few <strong>very important points:</strong></h2>

    <ol>
        <li>
            <p>
                <span class="dashicons dashicons-warning"></span><strong>This is a pre-release.</strong>
                <br />We have tested everything we can and consider this release ready, but if you have any issues please report them via the "Get Help" item in the "Forms" menu.
            </p>
        </li>
        <li>
            <p>
                <span class="dashicons dashicons-flag"></span><strong>Calculations will not convert.</strong>
                <br />Any forms with calculations will be converted to Ninja Forms THREE, but calculations within those forms will need recreated as a result of our vastly improved calculations system.
            </p>
        </li>
        <li>
            <p>
                <span class="dashicons dashicons-warning"></span><strong>We have resources to help you with the transition to THREE.</strong>
                <br />The Ninja Forms THREE documentation, development process, FAQ, and more <a href='http://ninjaforms.com/three/'>can be found here.</a>
            </p>
        </li>
    </ol>

    <hr>

    <div id="nfUpgradeApp">

    </div>

    <script type="text/html" id="tmpl-test">
        Test
    </script>

    <script type="text/html" id="tmpl-table">

        <h2>{{ data.title }}</h2>

        {{{ data.legend }}}

        <table>
            <thead>
            <tr>
                <# _.each( data.headers, function( header ) { #>
                <th>{{header}}</th>
                <# }); #>
            </tr>
            </thead>
            <tbody>

                <# _.each( data.rows, function( row ) { #>
                    <# if( ! row.title ) { return; } #>
                <tr>
                    <td>{{row.title}}</td>
                    <td>
                        <span class="dashicons dashicons-{{row.icon}}"></span>
                    </td>
                </tr>
                <# }); #>

                <# if( 'checking' == data.step && ! data.readyToConvert ) { #>
                <tr>
                    <td colspan="2" style="text-align: center;"><span class="dashicons dashicons-update"></span></td>
                </tr>
                <# } #>

            </tbody>

        </table>

        <# if( 'undefined' != typeof data.showSupportLink && data.showSupportLink ) { #>
            <div style="text-align: center;">
                There was an error converting one or more of your forms.<br />
                Please contact <a href="http://ninjaforms.com/contact/">support</a>.
            </div>
        <# } #>

        <# if( 'checking' == data.step && data.readyToConvert ) { #>
            <p class="opt-in">
                <small>By upgrading to the Ninja Forms <a href="https://ninjaforms.com/three/">THREE Release Candidate</a>,<br /> you allow <a href="http://wpninjas.com/">The WP Ninjas, LLC</a> to track how Ninja Forms is used and help us make the plugin better.</small>
            </p>
            <button class="nf-upgrade-button js-nfUpgrade-startConversion">{{data.next}}</button>
        <# } #>
    </script>

    <script type="text/html" id="tmpl-legend">
        <span class="dashicons dashicons-yes"></span> = {{data.no_issues_detected}}
        &nbsp;
        <span class="dashicons dashicons-flag"></span> = {{data.will_need_attention}}
    </script>

<!--    <hr />-->
<!---->
<!--    <div id="nfThreeFormCheck">-->
<!---->
<!--        <h2>Form Upgrade Compatibility</h2>-->
<!---->
<!--        --><?php //$no_issues_detected  = __( 'No Issues Detected', 'ninja-forms' ); ?>
<!--        --><?php //$will_need_attention = __( 'Will Need Attention', 'ninja-forms' ); ?>
<!--        <span class="dashicons dashicons-yes"></span> = --><?php //echo $no_issues_detected; ?><!-- &nbsp; <span class="dashicons dashicons-flag"></span> = --><?php //echo $will_need_attention; ?>
<!---->
<!--        <table id="nfThreeFormCheckTable">-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th>ID#</th>-->
<!--                <th>Title</th>-->
<!--                <th>Status</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--            <tr id="nfThreeFormCheckEmptyRow">-->
<!--                <td colspan="3" style="text-align: center;">-->
<!--                    <span class="dashicons dashicons-update spin"></span>-->
<!--                    <div class="progress-bar--wrapper">-->
<!--                        <div class="progress-bar"></div>-->
<!--                    </div>-->
<!--                </td>-->
<!--            </tr>-->
<!--            </tbody>-->
<!--        </table>-->
<!---->
<!--        <button id="goNinjaGo" class="button go-ninja-go">--><?php //echo __( 'Upgrade Forms', 'ninja-forms' ); ?><!--</button>-->
<!---->
<!--    </div>-->
<!---->
<!--    <div id="nfThreeFormConvert">-->
<!---->
<!--        <h2>Upgrading Forms</h2>-->
<!---->
<!--        <table id="nfThreeFormConvertTable">-->
<!--            <thead>-->
<!--            <tr>-->
<!--                <th>ID#</th>-->
<!--                <th>Title</th>-->
<!--                <th>Status</th>-->
<!--            </tr>-->
<!--            </thead>-->
<!--            <tbody>-->
<!--                <tr class="js-tmp-row">-->
<!--                    <td colspan="3" style="text-align: center;">-->
<!--                        <span class="dashicons dashicons-update spin"></span>-->
<!--                    </td>-->
<!--                </tr>-->
<!--            </tbody>-->
<!--        </table>-->
<!---->
<!--        <a href="--><?php //echo admin_url( 'admin.php?page=ninja-forms&nf-switcher=upgrade' ); ?><!--" id="goToThree">Go To Three</a>-->
<!---->
<!--    </div>-->

</div>
