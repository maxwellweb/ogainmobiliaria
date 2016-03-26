jQuery(document).ready(function($) {

    /*
     |--------------------------------------------------------------------------
     | Ninja Forms THREE Upgrade App
     |--------------------------------------------------------------------------
     */

    var nfUpgradeApp = {

        forms: [],

        step: 'checking',

        container: '#nfUpgradeApp',

        tmpl: {
            test: wp.template( 'test' ),
            table: wp.template( 'table' ),
            legend: wp.template( 'legend' ),
        },

        updateTable: function(){

            var data = {
                title: '',
                headers: [ 'Status', 'Title' ],
                rows: this.forms,
                step: this.step,
                showSupportLink: 0,
            };

            if( 'checking' == this.step ) {

                data.title = 'Form Upgrade Compatibility';

                data.legend = this.tmpl.legend( {
                    no_issues_detected: 'No Issues Detected',
                    will_need_attention: 'Will Need Attention After Upgrade',
                }),

                data.next = 'Start Upgrade';

                data.readyToConvert = 1;
                _.each(this.forms, function (form) {
                    if ( ! form.checked ) data.readyToConvert = 0;
                }, this);
            }

            if( 'converting' == this.step ) {
                data.title = 'Converting Forms';

                var redirectToThree = 1;
                _.each(this.forms, function (form) {
                    if ( ! form.converted ) redirectToThree = 0;
                    if ( form.failed ) data.showSupportLink = 1;
                }, this);
                if( redirectToThree ) window.location.href = nfThreeUpgrade.redirectURL;
            }

            jQuery( this.container ).html( this.tmpl.table( data ) );
        },

        checkForm: function( form ) {

            var that = this;
            $.post( ajaxurl, { action: 'ninja_forms_upgrade_check', formID: form.id }, function( response ) {

                var icon = ( response.canUpgrade ) ? 'yes' : 'flag';
                var flagged = ( response.canUpgrade ) ? 0 : 1;
                that.updateForm( form.id, 'title', response.title );
                that.updateForm( form.id, 'icon', icon );
                that.updateForm( form.id, 'checked', true );
                that.updateForm( form.id, 'flagged', flagged );
                that.updateTable();
            }, 'json' );
        },

        updateForm: function( formID, property, value ) {
            _.each( this.forms, function( form ) {
                if( formID != form.id ) return;
                form[ property ] = value;
            });
        },

        start: function () {
            _.each( nfThreeUpgrade.forms, function( formID ) {
                this.forms.push({
                    id: formID,
                    title: '',
                    icon: 'update',
                    checked: false,
                    converted: false,
                    failed: false,
                });
            }, this );
            _.each( this.forms, this.checkForm, this );
            this.updateTable();

            var that = this;
            jQuery( '#nfUpgradeApp' ).on( 'click','.js-nfUpgrade-startConversion', function() {
                that.startConversion( that );
            } );
        },

        startConversion: function( app ) {
            console.log( 'HERE' );
            console.log( app );
            app.step = 'converting';

            $.post( ajaxurl, { nf2to3: 1, action: 'ninja_forms_ajax_migrate_database' }, function( response ) {

                $.post( ajaxurl, { action: 'nfThreeUpgrade_GetSerializedFields' }, function( fieldsExport ) {
                    $.post(ajaxurl, { nf2to3: 1, fields: fieldsExport.serialized, action: 'ninja_forms_ajax_import_fields' }, function ( fieldsImport ) {
                        _.each(app.forms, function (form) {
                            this.convertForm(form);
                        }, app );
                    }, 'json' );
                }, 'json' );
            });

            _.each(app.forms, function (form) {
                form.icon = 'update';
            }, app );
            app.updateTable();
        },


        convertForm: function( form ) {
            var app =  this;
            $.post(ajaxurl, {action: 'nfThreeUpgrade_GetSerializedForm', formID: form.id}, function ( formExport ) {
                $.post(ajaxurl, { nf2to3: 1, action: 'ninja_forms_ajax_import_form', formID: form.id, import: formExport.serialized, flagged: form.flagged }, function ( formImport ) {
                    form.converted = true;
                    form.icon = 'yes';
                    app.updateTable();
                }, 'json').fail( function() {
                    form.converted = false;
                    form.failed = true;
                    form.icon = 'no';
                    app.updateTable();
                });

            }, 'json' );
        }

    };

    nfUpgradeApp.start();

});
