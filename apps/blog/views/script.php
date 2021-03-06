<div id="<?php echo esc_attr($livefyre_element); ?>"></div>
<script type="text/javascript">
    var networkConfigBlog = {
        <?php echo isset( $strings ) ? 'strings: "' . esc_js( $strings ) . '",' : ''; ?>
        network: '<?php echo esc_js( $network->getName() ); ?>'
    };
    var convConfigBlog<?php echo esc_js( $articleId ); ?> = {
        siteId: <?php echo esc_js( $siteId ); ?>,
        articleId: <?php echo esc_js( $articleId ); ?>,
        el: '<?php echo esc_js( $livefyre_element ); ?>',
        collectionMeta: '<?php echo esc_js( $collectionMetaToken ); ?>',
        checksum: '<?php echo esc_js( $checksum ); ?>'
    };

    if(typeof(liveBlogConfig) !== 'undefined') {
        convConfigBlog<?php echo esc_js( $articleId ); ?> = Livefyre.LFAPPS.lfExtend(liveBlogConfig, convConfigBlog<?php echo esc_js( $articleId ); ?>);
    }

    Livefyre.require([<?php echo LFAPPS_Blog::get_package_reference(); ?>], function(ConvBlog) {
        load_livefyre_auth();
        new ConvBlog(networkConfigBlog, [convConfigBlog<?php echo esc_js( $articleId ); ?>], function(blogWidget) {
            if(typeof blogWidget !== "undefined") {
                var liveblogListeners = Livefyre.LFAPPS.getAppEventListeners('liveblog');
                if(liveblogListeners.length > 0) {
                    for(var i=0; i<liveblogListeners.length; i++) {
                        var liveblogListener = liveblogListeners[i];
                        blogWidget.on(liveblogListener.event_name, liveblogListener.callback);
                    }
                }
            }
        });
    });
</script>