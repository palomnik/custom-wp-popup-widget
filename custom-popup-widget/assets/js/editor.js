const { registerBlockType } = wp.blocks;
const { InnerBlocks } = wp.blockEditor;
const { __ } = wp.i18n;

registerBlockType('custom-popup-widget/popup-content', {
    title: __('Popup Content', 'custom-popup-widget'),
    icon: 'welcome-widgets-menus',
    category: 'widgets',
    description: __('Add content to be displayed in the popup widget.', 'custom-popup-widget'),
    supports: {
        html: false,
        anchor: true
    },
    
    edit: function(props) {
        return (
            <div className="cpw-editor-content">
                <InnerBlocks />
            </div>
        );
    },

    save: function(props) {
        return (
            <div className="cpw-content">
                <InnerBlocks.Content />
            </div>
        );
    }
}); 