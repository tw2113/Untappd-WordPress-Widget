(function (blocks, i18n, element) {

    var __ = wp.i18n.__; // The __() for internationalization.
    var el = wp.element.createElement; // The wp.element.createElement() function to create elements.

    var InspectorControls = wp.blocks.InspectorControls;
    var textControl = wp.blocks.InspectorControls.textControl;
    var ToggleControl = wp.blocks.InspectorControls.ToggleControl;


    var blockStyle = {
        backgroundColor: '#900',
        color: '#fff',
        padding: '20px'
    };

    blocks.registerBlockType('untappd-mb-gutenberg/latest-badge', {
        title: 'Untappd Latest User Badge',
        category: 'layout',

        attributes: {
            username: {
                type: 'string'
            },
        },

        edit: function (props) {
            console.log( 'edit:',props);
            var focus = props.focus;

            props.attributes.form_id =  props.attributes.form_id &&  props.attributes.form_id != '0' ?  props.attributes.form_id : false;

            return [
                !!focus && el(
                    InspectorControls,
                    {key: 'inspector'},
                    el(
                        textControl,
                        {
                            label: 'Untappd Latest Badge',
                            value: props.attributes.form_id ? parseInt(props.attributes.form_id) : 0,
                            instanceId: 'untappd-mb-selector',
                            onChange: function (value) {
                                props.setAttributes({form_id: value});
                            },
                        }
                    ),
                    el(
                        'div',
                        {
                            'style': {
                                'padding': '8px 0 0 0',
                                'fontSize': '11px',
                                'fontStyle': 'italic',
                                'color': '#5A5A5A',
                                'marginTop': '-22px',
                                'marginBottom': '22px'
                            },
                        }
                    )
                ),
                el(
                    'div',
                    {
                        key: 'constant-contact-form',
                        className: props.className,
                    },
                    props.attributes.form_id ? 'Selected Form: ' : 'Choose a Constant Contact Form'
                )
            ];
        },
        save: function (props) {
            return el(
                'p',
                {},
                'Selected form ID: '
            );
        },
    });
})(
    window.wp.blocks,
    window.wp.i18n,
    window.wp.element
);
