<?php

/*
 * Most of your editing will be done in this section.
 *
 * Here you can override default values, uncomment args and change their values.
 *
 */
function register_builder_widgets() {
    $widgets['button'] = array(
        'title' => __('Button', 'redux-page-builder'),
        'icon' => 'hand-up',
        'desc' => __('Creates a colored button', 'redux-page-builder'),
        'fields' => array(
            array(
                'id' => 'label',
                'type' => 'text',
                'title' => __('Button Label', 'redux-page-builder'),
                'subtitle' => __('This is the text that appears on your button.', 'redux-page-builder'),
            ),
            array(
                'id' => 'link',
                "title" => __("Button Link?", 'redux-page-builder'),
                "subtitle" => __("Where should your button link to?", 'redux-page-builder'),
                "fetchTMPL" => true,
                "type" => "linkpicker",
                "default" => "",
                "options" => array(
                    'no-link' => __('No Link', 'redux-page-builder'),
                    'manually' => __('Set Manually', 'redux-page-builder'),
                    'single' => __('Single Entry', 'redux-page-builder'),
                    'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'link_target',
                'type' => 'select',
                'title' => __('Open Link in new Window?', 'redux-page-builder'),
                'subtitle' => __('Select here if you want to open the linked page in a new window', 'redux-page-builder'),
                "default" => "",
                'options' => array(
                    '' => __('No, open in same window', 'redux-page-builder'),
                    '_blank' => __('Yes, open in new window', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'color',
                'type' => 'select',
                'title' => __('Button Color', 'redux-page-builder'),
                'subtitle' => __('Choose a color for your button here', 'redux-page-builder'),
                "default" => "",
                'options' => array(
                    'btn-link' => __('Transparent', 'redux-page-builder'),
                    'btn-default' => __('Default (gray)', 'redux-page-builder'),
                    'btn-primary' => __('Main Color', 'redux-page-builder'),
                    'btn-info' => __('Light Blue', 'redux-page-builder'),
                    'btn-success' => __('Green', 'redux-page-builder'),
                    'btn-warning' => __('Orange', 'redux-page-builder'),
                    'btn-danger' => __('Red', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'size',
                'type' => 'select',
                'title' => __('Button Size', 'redux-page-builder'),
                'subtitle' => __('Choose the size of your button here', 'redux-page-builder'),
                "default" => "btn-m",
                'options' => array(
                    'btn-xs' => __('Extra small', 'redux-page-builder'),
                    'btn-sm' => __('Small', 'redux-page-builder'),
                    'btn-m' => __('Medium', 'redux-page-builder'),
                    'btn-lg' => __('Large', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'style',
                'type' => 'select',
                'title' => __('Button Style', 'redux-page-builder'),
                'subtitle' => __('Choose the style of your button here', 'redux-page-builder'),
                "default" => "rounded",
                'options' => array(
                    'rounded' => __('Rounded', 'redux-page-builder'),
                    'circle' => __('Circle', 'redux-page-builder'),
                    'bootstrap' => __('Bootstrap', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'position',
                'type' => 'select',
                'title' => __('Button Position', 'redux-page-builder'),
                'subtitle' => __('Choose the alignment of your button here', 'redux-page-builder'),
                "default" => "center",
                'options' => array(
                    'pull-left' => __('Align Left', 'redux-page-builder'),
                    'pull-center' => __('Align Center', 'redux-page-builder'),
                    'pull-right' => __('Align Right', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'icon_select',
                'type' => 'switch',
                'title' => __('Button Icon', 'redux-page-builder'),
                'sub_desc' => __('Should an icon be displayed at the left side of the button', 'redux-page-builder'),
                'on' => 'On',
                'off' => 'Off',
                "default" => 0,
            ),
            array(
                'id' => 'icon',
                'type' => 'select',
                'data' => 'elusive',
                'required' => array('icon_select', '=' , 1),
                'title' => __('Button Icon', 'redux-page-builder'),
                'subtitle' => __('Select an icon for your Button bellow', 'redux-page-builder'),
            ),
        )
    );

    $widgets['gallery'] = array(
        'title' => __('Gallery', 'redux-page-builder'),
        'desc' => __('Creates a custom gallery', 'redux-page-builder'),
        'icon' => 'picture',
        'fields' => array(
            array(
                'id' => 'widget_title',
                'type' => 'text',
                'title' => __('Widget Title', 'redux-page-builder'),
                'subtitle' => __('Leave empty if you do not want to display widget title', 'redux-page-builder'),
                'default' => '',
            ),
            array(
                'id' => 'ids',
                'type' => 'gallery',
                'title' => __('Add/Edit Gallery', 'redux-page-builder'),
                'subtitle' => __('Create a new Gallery by selecting existing or uploading new images', 'redux-page-builder'),
            ),
            // array(
            //     'id' => 'style',
            //     'type' => 'select',
            //     'title' => __('Gallery Style', 'redux-page-builder'),
            //     'subtitle' => __('Choose the layout of your Gallery', 'redux-page-builder'),
            //     "default" => "thumbnails",
            //     'options' => array(
            //         'thumbnails' => __('Small Thumbnails', 'redux-page-builder'),
            //         'big_thumb' => __('Big image with thumbnails bellow', 'redux-page-builder'),
            //     )
            // ),
            // array(
            //     'id' => 'preview_size',
            //     'type' => 'select',
            //     'title' => __('Gallery Big Preview Image Size', 'redux-page-builder'),
            //     'subtitle' => __('Choose image size for the Big Preview Image', 'redux-page-builder'),
            //     'default' => "portfolio",
            //     'required' => array('style', 'equals', 'big_thumb'),
            //     'options' => get_registered_image_sizes(array('logo')),
            // ),
            array(
                'id' => 'thumb_size',
                'type' => 'select',
                'title' => __('Gallery Preview Image Size', 'redux-page-builder'),
                'subtitle' => __('Choose image size for the small preview thumbnails', 'redux-page-builder'),
                "default" => "portfolio",
                'options' => get_registered_image_sizes(array('logo')),
            ),
            array(
                'id' => 'columns',
                "title" => __("Gallery Columns", 'redux-page-builder'),
                "subtitle" => __("Choose the column count of your Gallery", 'redux-page-builder'),
                "type" => "select",
                "default" => "5",
                "options" => number_array(1, 6, 1,array(5)),
            ),
            array(
                'id' => 'animation',
                "title" => __("Gallery Fade in Animation", 'redux-page-builder'),
                "subtitle" => __("Add a small animation to the gallery when the user first scrolls to the gallery position. This is only to add some 'spice' to the site and only works in modern browsers", 'redux-page-builder'),
                "type" => "select",
                "default" => "no-animation",
                "options" => array(
                    'no-animation' => __('No animation', 'redux-page-builder'),
                    'fade' => __('Fade In', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'imagelink',
                "title" => __("Use fancybox", 'redux-page-builder'),
                "subtitle" => __("Do you want to activate the fancybox", 'redux-page-builder'),
                "type" => "select",
                "default" => "5",
                "options" => array(
                    'fancybox' => __('Yes', 'redux-page-builder'),
                    '_self' => __('No, open the images in the browser window', 'redux-page-builder'),
                    '_blank' => __('No, open the images in a new browser window/tab', 'redux-page-builder'),
                    'none' => __('No, don\'t add a link to the images at al', 'redux-page-builder'),
                )
            ),
        )
    );

    $widgets['horizontal'] = array(
        'title' => __('Horizontal Ruler', 'redux-page-builder'),
        'desc' => __('Creates a delimiter to separate elements', 'redux-page-builder'),
        'icon' => 'minus',
        'fields' => array(
            array(
                'id' => 'class',
                'type' => 'select',
                'title' => __('Horizontal Ruler Styling', 'redux-page-builder'),
                'subtitle' => __('Here you can set the styling and size of the HR element', 'redux-page-builder'),
                "default" => "",
                'options' => array(
                    'default' => __('Default', 'redux-page-builder'),
                    'big' => __('Big Top and Bottom Margins, open in new window', 'redux-page-builder'),
                    'full' => __('Fullwidth Separator', 'redux-page-builder'),
                    'invisible' => __('Whitespace, open in new window', 'redux-page-builder'),
                    'short' => __('Short Separator', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'height',
                'type' => 'text',
                'title' => __('Height', 'redux-page-builder'),
                'required' => array('class','equals','invisible'),
                'subtitle' => __('How much whitespace do you need? Enter a pixel value', 'redux-page-builder'),
                "default" => "50",
            ),
            array(
                'id' => 'shadow',
                'type' => 'select',
                'title' => __('Section Top Shadow', 'redux-page-builder'),
                'subtitle' => __('Display a small styling shadow at the top of the section', 'redux-page-builder'),
                "default" => "no-shadow",
                'required' => array('class','equals','full'),
                'options' => array(
                    'shadow' => __('Display shadow', 'redux-page-builder'),
                    'no-shadow' => __('Do not display shadow', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'position',
                'type' => 'select',
                'title' => __('Position', 'redux-page-builder'),
                'subtitle' => __('Set the position of the short ruler', 'redux-page-builder'),
                "default" => "center",
                'required' => array('class','equals','short'),
                'options' => array(
                    'center' => __('Center', 'redux-page-builder'),
                    'left' => __('Left', 'redux-page-builder'),
                    'right' => __('Right', 'redux-page-builder'),
                )
            ),
        )
    );

    $widgets['iconbox'] = array(
        'title' => __('Icon box', 'redux-page-builder'),
        'desc' => __('Create a content block with icon to left or above', 'redux-page-builder'),
        'icon' => 'flag',
        'fields' => array(
            array(
                'id' => 'icon',
                'type' => 'select',
                'data' => 'elusive',
                'title' => __('Icon', 'redux-page-builder'),
                'sub_desc' => __('Select an Icon bellow', 'redux-page-builder'),
            ),
            array(
                'id' => 'position',
                'type' => 'select',
                'title' => __('Icon Position', 'redux-page-builder'),
                'subtitle' => __('Should the icon be positioned at the left or at the top?', 'redux-page-builder'),
                "default" => "left",
                'options' => array(
                    'left' => __('Left', 'redux-page-builder'),
                    'top' => __('Top', 'redux-page-builder'),
                    'left-big' => __('Left with big icon', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'title',
                "title" => __("Title", 'redux-page-builder'),
                "subtitle" => __("Add title here", 'redux-page-builder'),
                "type" => "text"
            ),
            array(
                'id' => 'link',
                "title" => __("Image Link?", 'redux-page-builder'),
                "subtitle" => __("Where should your image link to?", 'redux-page-builder'),
                "fetchTMPL" => true,
                "type" => "linkpicker",
                "default" => "",
                "options" => array(
                    'no-link' => __('No Link', 'redux-page-builder'),
                    'manually' => __('Set Manually', 'redux-page-builder'),
                    'single' => __('Single Entry', 'redux-page-builder'),
                    'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                )
            ),
            array(
                'id' => '_content',
                "title" => __("Content", 'redux-page-builder'),
                "subtitle" => __("Add some content", 'redux-page-builder'),
                'class' => 'redux-editor',
                "type" => "editor"
            ),
        )
    );

    $widgets['image'] = array(
        'title' => __('Image', 'redux-page-builder'),
        'desc' => __('Displays a simple image.', 'redux-page-builder'),
        'icon' => 'picture',
        'fields' => array(
            array(
                'id' => 'image',
                'type' => 'media',
                'title' => __('Choose image', 'redux-page-builder'),
                'subtitle' => __('Either upload a new, or choose an existing image from your media library', 'redux-page-builder'),
            ),
            array(
                'id' => 'size',
                'type' => 'select',
                'title' => __('Image Size', 'redux-page-builder'),
                'subtitle' => __('Choose image size.', 'redux-page-builder'),
                'default' => '',
                'options' => get_registered_image_sizes(),
            ),
            array(
                'id' => 'align',
                'type' => 'select',
                'title' => __('Image Alignment', 'redux-page-builder'),
                'subtitle' => __('Choose here, how to align your image', 'redux-page-builder'),
                "default" => "center",
                'options' => array(
                    'center' => __('Center', 'redux-page-builder'),
                    'pull-right' => __('Right', 'redux-page-builder'),
                    'pull-left' => __('Left', 'redux-page-builder'),
                    '' => __('No special alignment', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'style',
                'type' => 'select',
                'title' => __('Image style', 'redux-page-builder'),
                'subtitle' => __('Style your image', 'redux-page-builder'),
                "default" => "center",
                'options' => array(
                    '' => __('Default', 'redux-page-builder'),
                    'img-rounded' => __('Image rounded', 'redux-page-builder'),
                    'img-circle img-thumbnail' => __('Image circle', 'redux-page-builder'),
                    'img-thumbnail' => __('Image thumbnail', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'animation',
                "title" => __("Image Fade in Animation", 'redux-page-builder'),
                "subtitle" => __("Add a small animation to the image when the user first scrolls to the image position. This is only to add some 'spice' to the site and only works in modern browsers", 'redux-page-builder'),
                "type" => "select",
                "default" => "no-animation",
                "options" => array(
                    'no-animation' => __('No animation', 'redux-page-builder'),
                    'top-to-bottom' => __('Top to Bottom', 'redux-page-builder'),
                    'bottom-to-top' => __('Bottom to Top', 'redux-page-builder'),
                    'left-to-right' => __('Left to Right', 'redux-page-builder'),
                    'right-to-left' => __('Right to Left', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'link',
                "title" => __("Image Link?", 'redux-page-builder'),
                "subtitle" => __("Where should your image link to?", 'redux-page-builder'),
                "fetchTMPL" => true,
                "type" => "linkpicker",
                "default" => "",
                "options" => array(
                    'no-link' => __('No Link', 'redux-page-builder'),
                    'manually' => __('Set Manually', 'redux-page-builder'),
                    'single' => __('Single Entry', 'redux-page-builder'),
                    'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                )
            ),
        )
    );

    $widgets['layerslider'] = array(
        'title' => __('Advanced Layerslider', 'redux-page-builder'),
        'desc' => __('Display a Layerslider Slideshow', 'redux-page-builder'),
        'icon' => 'play-circle',
        'fields' => array(
            array(
                'id' => 'layerslider',
                'type' => 'select',
                'title' => __('Layerslider', 'redux-page-builder'),
                'subtitle' => __('Here you can choose which slideshow to show', 'redux-page-builder'),
                "default" => "",
                'data' => 'callback',
                'args' => array('find_layersliders'),
            ),
        )
    );

    $widgets['portfolio'] = array(
        'title' => __('Portfolio Grid', 'redux-page-builder'),
        'desc' => __('Creates a grid of portfolio excerpts', 'redux-page-builder'),
        'icon' => 'th',
        'fields' => array(
            array(
                'id' => 'categories',
                'type' => 'select',
                'multi' => true,
                'data' => 'categories',
                'args' => array('taxonomy' => 'portfolio-category'),
                'title' => __('Which categories should be used for the portfolio?', 'redux-page-builder'),
                'subtitle' => __('You can select multiple categories here. The Page will then show posts from only those categories.', 'redux-page-builder'),
                "default" => '',
            ),
            array(
                'id' => 'columns',
                'type' => 'select',
                'title' => __('Columns', 'redux-page-builder'),
                'subtitle' => __('How many columns should be displayed?', 'redux-page-builder'),
                "default" => '4',
                'options' => array(
                    '2' => __('2 Columns', 'redux-page-builder'),
                    '3' => __('3 Columns', 'redux-page-builder'),
                    '4' => __('4 Columns', 'redux-page-builder'),
                    '6' => __('6 Columns', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'items',
                'type' => 'select',
                'title' => __('Post Number', 'redux-page-builder'),
                'subtitle' => __('How many items should be displayed per page?', 'redux-page-builder'),
                "default" => '16',
                'options' => number_array(1, 100, 1, array('-1' => __('All', 'redux-page-builder')))
            ),
            array(
                'id' => 'style',
                'type' => 'select',
                'title' => __('Style', 'redux-page-builder'),
                'subtitle' => __('Chose the style of portfolio to display', 'redux-page-builder'),
                "default" => 'flip',
                'options' => array(
                    'invisible anim-scale' => __('Invisible (Hide Title and excerpt)', 'redux-page-builder'),
                    'static anim-scale' => __('Static', 'redux-page-builder'),
                    'overlay anim-scale' => __('Overlay', 'redux-page-builder'),
                    'flip anim-flip' => __('Flip', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'contents',
                'type' => 'select',
                'title' => __('Excerpt', 'redux-page-builder'),
                'subtitle' => __('Display Excerpt and Title bellow the preview image?', 'redux-page-builder'),
                "default" => 'excerpt',
                'options' => array(
                    'excerpt' => __('Title and Excerpt', 'redux-page-builder'),
                    'title' => __('Only Title', 'redux-page-builder'),
                    'only_excerpt' => __('Only excerpt', 'redux-page-builder'),
                    'no' => __('No Title and no excerpt', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'preview_mode',
                'type' => 'select',
                'title' => __('Preview Image Size', 'redux-page-builder'),
                'subtitle' => __('Set the image size of the preview images', 'redux-page-builder'),
                "default" => 'auto',
                'options' => array(
                    'auto' => __('Set the preview image size automatically based on column or layout width', 'redux-page-builder'),
                    'custom' => __('Choose the preview image size manually (select thumbnail size)', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'image_size',
                'type' => 'select',
                'title' => __('Select custom preview image size', 'redux-page-builder'),
                'subtitle' => __('Choose image size for Preview Image', 'redux-page-builder'),
                "required" => array('preview_mode', 'equals', 'custom'),
                "default" => 'portfolio',
                'options' => get_registered_image_sizes(array('logo', 'thumbnail', 'widget')),
            ),
            array(
                'id' => 'linking',
                'type' => 'select',
                'title' => __('Link Handling', 'redux-page-builder'),
                'subtitle' => __('When clicking on a portfolio item you can choose to open the link to the single entry or show a bigger version of the image in a lightbox overlay', 'redux-page-builder'),
                "default" => '',
                'options' => array(
                    '' => __('Open the entry on a new page', 'redux-page-builder'),
                    'fancybox' => __('Display the big image in a fancybox', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'sort',
                'type' => 'select',
                'title' => __('Sortable?', 'redux-page-builder'),
                'subtitle' => __('Should the sorting options based on categories be displayed?', 'redux-page-builder'),
                "default" => 'yes',
                'options' => array(
                    'yes' => __('Yes', 'redux-page-builder'),
                    'no' => __('No', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'paginate',
                'type' => 'select',
                'title' => __('Pagination', 'redux-page-builder'),
                'subtitle' => __('Should a pagination be displayed?', 'redux-page-builder'),
                "default" => 'yes',
                'options' => array(
                    'yes' => __('Yes', 'redux-page-builder'),
                    'no' => __('No', 'redux-page-builder'),
                )
            ),
        )
    );

    $widgets['testimonial'] = array(
        'title' => __('Testimonials', 'redux-page-builder'),
        'desc' => __('Creates a Testimonial Grid', 'redux-page-builder'),
        'icon' => 'comments',
        'fields' => array(
            array(
                'id' => 'slides',
                'type' => 'group',
                'title' => __('Add/Edit Testimonial', 'redux-page-builder'),
                'subtitle' => __('Here you can add, remove and edit your Testimonials.', 'redux-page-builder'),
                'groupname' => __('Testimonial', 'redux-page-builder'),
                'subfields' => array(
                    array(
                        'id' => 'src',
                        'type' => 'media',
                        'title' => __('Image', 'redux-page-builder'),
                        'subtitle' => __('Either upload a new, or choose an existing image from your media library', 'redux-page-builder'),
                    ),
                    array(
                        'id' => 'name',
                        'type' => 'text',
                        'title' => __('Name', 'redux-page-builder'),
                        'subtitle' => __('Enter the Name of the Person to quote', 'redux-page-builder'),
                    ),
                    array(
                        'id' => 'subtitle',
                        'type' => 'text',
                        'title' => __('Subtitle bellow name', 'redux-page-builder'),
                        'subtitle' => __('Can be used for a job description', 'redux-page-builder'),
                    ),
                    array(
                        'id' => '_content',
                        'type' => 'textarea',
                        'title' => __('Quote', 'redux-page-builder'),
                        'subtitle' => __('Enter the testimonial here', 'redux-page-builder'),
                    ),
                    array(
                        'id' => 'link',
                        'type' => 'text',
                        'title' => __('Website Link', 'redux-page-builder'),
                        'subtitle' => __('Link to the Persons website', 'redux-page-builder'),
                    ),
                    array(
                        'id' => 'linktext',
                        'type' => 'text',
                        'title' => __('Website Name', 'redux-page-builder'),
                        'subtitle' => __('Linktext for the above Link', 'redux-page-builder'),
                    ),
                ),
            ),
            array(
                'id' => 'columns',
                'type' => 'switch',
                'title' => __('Testimonial Style', 'redux-page-builder'),
                'sub_desc' => __('Here you can select how to display the testimonials. You can either create a testimonial slider or a testimonial grid with multiple columns
', 'redux-page-builder'),
                'on' => 'Grid',
                'off' => 'Slideshow',
                "default" => 0,
            ),
            array(
                "title" => __("Testimonial Grid Columns", 'redux-page-builder'),
                "subtitle" => __("How many columns do you want to display", 'redux-page-builder'),
                "id" => "grid",
                'required' => array('columns' , '=' , 1),
                "type" => "select",
                "default" => "2",
                "options" => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4'
                )
            ),
            array(
                "title" => __("Slideshow autorotation duration", 'redux-page-builder'),
                "subtitle" => __("Slideshow will rotate every X seconds", 'redux-page-builder'),
                "id" => "interval",
                'required' => array('columns', '=' , 0),
                "type" => "select",
                "default" => "5",
                "options" => array('false' => __('No , Don not autorotation', 'redux-page-builder'),'3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100')
                ),
        ),
    );

    $widgets['textblock'] = array(
        'title' => __('Text Block', 'redux-page-builder'),
        'desc' => __('Create a simple block text.', 'redux-page-builder'),
        'icon' => 'file-text',
        'fields' => array(
            array(
                'id' => 'widget_title',
                'type' => 'text',
                'title' => __('Widget Title', 'redux-page-builder'),
                'subtitle' => __('Leave empty if you do not want to display widget title', 'redux-page-builder'),
                'default' => '',
            ),
            array(
                'id' => '_content',
                'type' => 'editor',
                'class' => 'redux-editor',
                'title' => __('Content', 'redux-page-builder'),
                'subtitle' => __('Enter some content for this textblock', 'redux-page-builder'),
            ),
        )
    );

    $widgets['tabs'] = array(
        'title' => __('Tabs', 'redux-page-builder'),
        'desc' => __('Creates a tabbed content area', 'redux-page-builder'),
        'fields' => array(
            array(
                'id' => 'position',
                'type' => 'select',
                'title' => __('Tab Position', 'redux-page-builder'),
                'subtitle' => __('Where should the tabs be displayed', 'redux-page-builder'),
                'default' => 'top_tab',
                'options' => array(
                    'tabs-top' => 'Display tabs at the top',
                    'tabs-left' => 'Display Tabs on the left',
                    'tabs-right' => 'Display Tabs on the right',
                    //'tabs-below' => 'Display Tabs at the bottom',
                ),
            ),
            array(
                'id' => 'tabs',
                'type' => 'group',
                'title' => __('Add/Edit Tabs', 'redux-page-builder'),
                'subtitle' => __('Here you can add, remove and edit the Tabs you want to display.', 'redux-page-builder'),
                'groupname' => __('Tab', 'redux-page-builder'),
                'subfields' => array(
                    array(
                        'id' => 'tab_title',
                        'type' => 'text',
                        'title' => __('Tab Title', 'redux-page-builder'),
                        'subtitle' => __('Enter the tab title here (Better keep it short)', 'redux-page-builder'),
                        'default' => 'Tab Title',
                    ),
                    array(
                        'id' => 'icon_select',
                        'type' => 'switch',
                        'title' => __('Tab Icon', 'redux-page-builder'),
                        'subtitle' => __('Should an icon be displayed at the left side of the tab title?', 'redux-page-builder'),
                        'default' => 0,
                        'on' => __('Yes, display Icon', 'redux-page-builder'),
                        'off' => __('No Icon', 'redux-page-builder'),
                    ),
                    array(
                        'id' => 'icon',
                        'type' => 'select',
                        'title' => __('Tab Icon', 'redux-page-builder'),
                        'subtitle' => __('Select an icon for your tab title bellow', 'redux-page-builder'),
                        'data' => 'elusive',
                        'required' => array('icon_select' , '=' , 1),
                    ),
                    array(
                        'id' => '_content',
                        'type' => 'textarea',
                        'title' => __('Tab Content', 'redux-page-builder'),
                        'subtitle' => __('Enter some content here', 'redux-page-builder'),
                        'default' => __('Tab Content goes here', 'redux-page-builder'),
                    ),
                ),
            ),
            array(
                'id' => 'initial',
                'type' => 'text',
                'title' => __('Initial Open', 'redux-page-builder'),
                'subtitle' => __('Enter the Number of the Tab that should be open initially.', 'redux-page-builder'),
                'default' => '1',
            ),
        )
    );

    $widgets['accordion'] = array(
        'title' => __('Accordion', 'redux-page-builder'),
        'desc' => __('Creates toggles or accordions', 'redux-page-builder'),
        'icon' => 'align-justify',
        'fields' => array(
            array(
                'id' => 'accordions',
                'type' => 'group',
                'title' => __('Add/Edit Toggles', 'redux-page-builder'),
                'subtitle' => __('Here you can add, remove and edit the toggles you want to display.', 'redux-page-builder'),
                'groupname' => __('Accordion', 'redux-page-builder'),
                'subfields' => array(
                    array(
                        'id' => 'acc_title',
                        'type' => 'text',
                        'title' => __('Toggle Title', 'redux-page-builder'),
                        'subtitle' => __('Enter the toggle title here (Better keep it short)', 'redux-page-builder'),
                        'default' => 'Toggle Title',
                    ),
                    array(
                        'id' => '_content',
                        'type' => 'textarea',
                        'title' => __('Toggle Content', 'redux-page-builder'),
                        'subtitle' => __('Enter some content here', 'redux-page-builder'),
                        'default' => __('Toggle Content goes here', 'redux-page-builder'),
                    ),                    
                ),
            ),
            array(
                'id' => 'initial',
                'type' => 'text',
                'title' => __('Initial Open', 'redux-page-builder'),
                'subtitle' => __('Enter the Number of the Accordion Item that should be open initially. Set to Zero if all should be close on page load', 'redux-page-builder'),
                'default' => '1',
            ),
        )
    );

    $widgets['slide'] = array(
        'title' => __('Slide', 'redux-page-builder'),
        'desc' => __('Display a simple slideshow element', 'redux-page-builder'),
        'icon' => 'step-forward',
        'fields' => array(
            array(
                'id' => 'slides',
                'type' => 'group',
                'title' => __('Add Images', 'redux-page-builder'),
                'subtitle' => __('Here you can add new Images to the slideshow.', 'redux-page-builder'),
                'groupname' => __('Slide', 'redux-page-builder'),
                'subfields' => array(
                    array(
                        'id' => 'image',
                        'type' => 'media',
                        'title' => __('Choose another Image', 'redux-page-builder'),
                        'subtitle' => __('Either upload a new, or choose an existing image from your media library', 'redux-page-builder'),
                    ),
                    array(
                        'id' => 'caption_title',
                        'type' => 'text',
                        'title' => __('Caption Title', 'redux-page-builder'),
                        'subtitle' => __('Enter a caption title for the slide here', 'redux-page-builder'),
                        'default' => '',
                    ), 
                    array(
                        'id' => 'caption_text',
                        'type' => 'textarea',
                        'title' => __('Caption Text', 'redux-page-builder'),
                        'subtitle' => __('Enter some additional caption text', 'redux-page-builder'),
                        'default' => '',
                    ), 
                    array(
                        'id' => 'link',
                        "title" => __("Slide Link?", 'redux-page-builder'),
                        "subtitle" => __("Where should the Slide link to?", 'redux-page-builder'),
                        "fetchTMPL" => true,
                        "type" => "linkpicker",
                        "default" => "",
                        "options" => array(
                            'no-link' => __('No Link', 'redux-page-builder'),
                            'manually' => __('Set Manually', 'redux-page-builder'),
                            'single' => __('Single Entry', 'redux-page-builder'),
                            'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                        )
                    ),
                    array(
                        'id' => 'link_target',
                        'type' => 'select',
                        'title' => __('Open Link in new Window?', 'redux-page-builder'),
                        'subtitle' => __('Select here if you want to open the linked page in a new window', 'redux-page-builder'),
                        'default' => '',
                        'options' => array(
                            '' => __('No, open in same window', 'redux-page-builder'),
                            '_blank' => __('Yes, open in new window', 'redux-page-builder'),
                        ),
                    ),                  
                ),
            ),
            array(
                'id' => 'size_option',
                'type' => 'switch',
                'title' => __('image size', 'redux-page-builder'),
                'subtitle' => __('Choose image option size for slide', 'redux-page-builder'),
                'on' => __('Full width', 'redux-page-builder'),
                'off' => __('Custom width', 'redux-page-builder'),
                "default" => '0',
            ),
            array(
                'id' => 'size',
                'type' => 'select',
                'title' => __('Slideshow Image Size', 'redux-page-builder'),
                'subtitle' => __('Choose image size for your slideshow.', 'redux-page-builder'),
                //"required" => array('size_option', 'equals', '0'),
                'default' => 'featured',
                'options' => get_registered_image_sizes(array('thumbnail','logo','widget','slider_thumb')),
            ),
            array(
                'id' => 'interval',
                'type' => 'select',
                'title' => __('Slideshow autorotation duration', 'redux-page-builder'),
                'subtitle' => __('Images will be shown the selected amount of seconds.', 'redux-page-builder'),
                'default' => '5',
                'options' => array(
                    'false' => __('No , Don not autorotation', 'redux-page-builder'),'3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10','15'=>'15','20'=>'20','30'=>'30','40'=>'40','60'=>'60','100'=>'100'
                ),
            ),
        )
    );

    $widgets['progressbar'] = array(
        'title' => __('Progress Bars', 'redux-page-builder'),
        'desc' => __('Create some progress bars', 'redux-page-builder'),
        'icon' => 'tasks',
        'fields' => array(
            array(
                'id' => 'progressbar',
                'type' => 'group',
                'title' => __('Add/Edit Progress Bars', 'redux-page-builder'),
                'subtitle' => __('Here you can add, remove and edit the various progress bars.', 'redux-page-builder'),
                'groupname' => __('Progress Bar', 'redux-page-builder'),
                'subfields' => array(
                    array(
                        'id' => 'pb_title',
                        'type' => 'text',
                        'title' => __('Progress Bars Title', 'redux-page-builder'),
                        'subtitle' => __('Enter the Progress Bars title here', 'redux-page-builder'),
                        'default' => '',
                    ), 
                    array(
                        'id' => 'progress',
                        'type' => 'select',
                        'title' => __('Progress in %', 'redux-page-builder'),
                        'subtitle' => __('Select a number between 0 and 100', 'redux-page-builder'),
                        'default' => '100',
                        'options' => number_array(0,100,1),
                    ), 
                    array(
                        'id' => 'color',
                        'type' => 'select',
                        'title' => __('Bar Color', 'redux-page-builder'),
                        'subtitle' => __('Choose a color for your progress bar here', 'redux-page-builder'),
                        'default' => '',
                        'options' => array(
                            'progress-bar-success' => __('Green', 'redux-page-builder'),
                            'progress-bar-info' => __('Blue', 'redux-page-builder'),
                            'progress-bar-warning' => __('Orange', 'redux-page-builder'),
                            'progress-bar-danger' => __('Red', 'redux-page-builder'),
                        ),
                    ),
                    array(
                        'id' => 'striped',
                        'type' => 'switch',
                        'title' => __('Gradient', 'redux-page-builder'),
                        'subtitle' => __('Should active gradient color to be displayed in progress bar', 'redux-page-builder'),
                        'default' => 0,
                    ),
                    array(
                        'id' => 'animated',
                        'type' => 'switch',
                        'title' => __('Animated', 'redux-page-builder'),
                        'subtitle' => __('Should active animation in progress bar', 'redux-page-builder'),
                        'required' => array('striped' , '=' , 1),
                        'default' => 0,
                    ),
                    array(
                        'id' => 'icon_select',
                        'type' => 'switch',
                        'title' => __('Icon', 'redux-page-builder'),
                        'subtitle' => __('Should an icon be displayed at the left side of the progress bar', 'redux-page-builder'),
                        'default' => 0,
                        'on' => __('Yes, display Icon', 'redux-page-builder'),
                        'off' => __('No Icon', 'redux-page-builder'),
                    ),
                    array(
                        'id' => 'icon',
                        'type' => 'select',
                        'title' => __('List Item Icon', 'redux-page-builder'),
                        'subtitle' => __('Select an icon for your list item bellow', 'redux-page-builder'),
                        'data' => 'elusive',
                        'required' => array('icon_select' , '=' , 1),
                    ),                  
                ),
            ),
        )
    );

    $widgets['notification'] = array(
        'title' => __('Notification', 'redux-page-builder'),
        'desc' => __('Creates a notification box to inform visitors', 'redux-page-builder'),
        'icon' => 'lightbulb',
        'fields' => array(
            array(
                'id' => 'title',
                'type' => 'text',
                'title' => __('Title', 'redux-page-builder'),
                'subtitle' => __('This is the small title at the top of your Notification.', 'redux-page-builder'),
                'default' => 'Note',
            ),
            array(
                'id' => '_content',
                'type' => 'textarea',
                'title' => __('Message', 'redux-page-builder'),
                'subtitle' => __('This is the text that appears in your Notification.', 'redux-page-builder'),
                'default' => 'This is a notification of some sort.',
            ),
            array(
                'id' => 'button',
                'type' => 'switch',
                'title' => __('Use Button', 'redux-page-builder'),
                'subtitle' => __('Here you can active button to appear under the content', 'redux-page-builder'),
                'default' => 0,
            ),
            array(
                'id' => 'label',
                'type' => 'text',
                'title' => __('Button Label', 'redux-page-builder'),
                'subtitle' => __('This is the text that appears on your button.', 'redux-page-builder'),
                'required' => array('button','=','1'),
            ),
            array(
                'id' => 'link',
                "title" => __("Button Link?", 'redux-page-builder'),
                "subtitle" => __("Where should your button link to?", 'redux-page-builder'),
                "fetchTMPL" => true,
                "type" => "linkpicker",
                "default" => "",
                'required' => array('button','=','1'),
                "options" => array(
                    'no-link' => __('No Link', 'redux-page-builder'),
                    'manually' => __('Set Manually', 'redux-page-builder'),
                    'single' => __('Single Entry', 'redux-page-builder'),
                    'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'color',
                'type' => 'select',
                'title' => __('Message Colors', 'redux-page-builder'),
                'subtitle' => __('Choose the color for your Box here', 'redux-page-builder'),
                'default' => 'alert-success',
                'options' => array(
                    'success' => __('Green', 'redux-page-builder'),
                    'info' => __('Blue', 'redux-page-builder'),
                    'warning' => __('Orange', 'redux-page-builder'),
                    'danger' => __('Red', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'dismiss',
                'type' => 'switch',
                'title' => __('Dismiss notification', 'redux-page-builder'),
                'subtitle' => __('Should an dismiss icon be displayed at the right side of the notification', 'redux-page-builder'),
                'default' => 0,
                'on' => __('Yes, display dismiss icon', 'redux-page-builder'),
                'off' => __('No', 'redux-page-builder'),
            ),
            array(
                'id' => 'icon_select',
                'type' => 'switch',
                'title' => __('Icon', 'redux-page-builder'),
                'subtitle' => __('Should an icon be displayed at the left side of the notification', 'redux-page-builder'),
                'default' => 0,
                'on' => __('Yes, display Icon', 'redux-page-builder'),
                'off' => __('No Icon', 'redux-page-builder'),
            ),
            array(
                'id' => 'icon',
                'type' => 'select',
                'title' => __('List Item Icon', 'redux-page-builder'),
                'subtitle' => __('Select an icon for your list item bellow', 'redux-page-builder'),
                'data' => 'elusive',
                'required' => array('icon_select' , '=' , 1),
            ), 
        )
    );

    $widgets['video'] = array(
        'title' => __('Video', 'redux-page-builder'),
        'desc' => __('Display a video', 'redux-page-builder'),
        'icon' => 'youtube-play',
        'fields' => array(
            array(
                'id' => 'src',
                'type' => 'text',
                'title' => __('Video Link', 'redux-page-builder'),
                'subtitle' => __("Enter link to a video by URL",'redux-page-builder' )."<br/><br/>".
                                        __("A list of all supported Video Services can be found on",'redux-page-builder' ).
                                        " <a target='_blank' href='http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F'>WordPress.org</a><br/><br/>".
                                        __("Working examples, in case you want to use an external service:",'redux-page-builder' ). "<br/>".
                                        "<strong>http://vimeo.com/18439821</strong><br/>".
                                        "<strong>http://www.youtube.com/watch?v=G0k3kHtyoqc</strong><br/>",
                'default' => '',
            ),
            array(
                'id' => 'format',
                'type' => 'select',
                'title' => __('Video Format', 'redux-page-builder'),
                'subtitle' => __('Choose if you want to display a modern 16:9 or classic 4:3 Video, or use a custom ratio', 'redux-page-builder'),
                'default' => '16:9',
                'options' => array(
                    '16-9' => __('16:9', 'redux-page-builder'),
                    '4-3' => __('4:3', 'redux-page-builder'),
                    'custom' => __('Custom Ratio', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'width',
                'type' => 'text',
                'title' => __('Video width', 'redux-page-builder'),
                'subtitle' => __('Enter a value for the width', 'redux-page-builder'),
                'default' => '16',
                "required" => array('format','equals','custom')
            ),
            array(
                'id' => 'height',
                'type' => 'text',
                'title' => __('Video height', 'redux-page-builder'),
                'subtitle' => __('Enter a value for the height', 'redux-page-builder'),
                'default' => '9',
                "required" => array('format','equals','custom')
            ),
        )
    );

    $widgets['blog'] = array(
        'title' => __('Blog Posts', 'redux-page-builder'),
        'desc' => __('Displays Posts from your Blog', 'redux-page-builder'),
        'icon' => 'list-alt',
        'fields' => array(
            array(
                'id' => 'widget_title',
                'type' => 'text',
                'title' => __('Widget Title', 'redux-page-builder'),
                'subtitle' => __('Leave empty if you do not want to display widget title', 'redux-page-builder'),
                'default' => '',
            ),
            array(
                'id' => 'blog_type',
                'type' => 'select',
                'title' => __('Do you want to display blog posts?', 'redux-page-builder'),
                'subtitle' => __('Do you want to display blog posts or entries from a custom taxonomy?', 'redux-page-builder'),
                'default' => 'posts',
                'options' => array(
                    'posts' => __('Display blog posts', 'redux-page-builder'),
                    'taxonomy' => __('Display entries from a custom taxonomy', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'categories',
                'type' => 'select',
                'title' => __('Which categories should be used for the blog?', 'redux-page-builder'),
                'subtitle' => __('You can select multiple categories here. The Page will then show posts from only those categories.', 'redux-page-builder'),
                "required"  => array('blog_type', 'equals', 'posts'),
                'data' => 'categories',
                'multi' => true,
            ),
             array(
                'id' => 'link',
                'type' => 'linkpicker',
                "fetchTMPL" => true,
                'title' => __('Which Entries?', 'redux-page-builder'),
                'subtitle' => __('Select which entries should be displayed by selecting a taxonomy', 'redux-page-builder'),
                "required"  => array('blog_type', 'equals', 'taxonomy'),
                'data' => 'categories',
                'multiple' => 6,
                'default' => 'posts,1',
                'options' => array(
                    'taxonomy' => __('Display Entries from:', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'blog_style',
                'type' => 'select',
                'title' => __('Blog Style', 'redux-page-builder'),
                'subtitle' => __('Choose the default blog layout here.', 'redux-page-builder'),
                'default' => 'list-large',
                'options' => array(
                    'list-large' => __('List View with Large Thumbnail', 'redux-page-builder'),
                    'list-medium' => __('List View with Medium Thumbnail', 'redux-page-builder'),
                    'list-small' => __('List View with Small Thumbnail', 'redux-page-builder'),
                    'grid-medium' => __('Grid View with Medium Thumbnail', 'redux-page-builder'),
                    'grid-small' => __('Grid View with Small Thumbnail', 'redux-page-builder'),
                    'grid-mini' => __('Grid View with Mini Thumbnail', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'columns',
                'type' => 'select',
                'title' => __('Blog Grid Columns', 'redux-page-builder'),
                'subtitle' => __('How many columns do you want to display?', 'redux-page-builder'),
                'default' => '3',
                "required"  => array('blog_style', 'equals', 'blog-grid'),
                'options' => number_array(1,5,1),
            ),
            array(
                'id' => 'contents',
                'type' => 'select',
                'title' => __('Define Blog Grid layout', 'redux-page-builder'),
                'subtitle' => __('Do you want to display a read more link?', 'redux-page-builder'),
                'default' => 'excerpt',
                "required"  => array('blog_style', 'equals', 'blog-grid'),
                'options' => array(
                    'excerpt' => __('Title and Excerpt', 'redux-page-builder'),
                    'excerpt_read_more' => __('Title and Excerpt + Read More Link', 'redux-page-builder'),
                    'title' => __('Only Title', 'redux-page-builder'),
                    'title_read_more' => __('Only Title + Read More Link', 'redux-page-builder'),
                    'only_excerpt' => __('Only excerpt', 'redux-page-builder'),
                    'only_excerpt_read_more' => __('Only excerpt + Read More Link', 'redux-page-builder'),
                    'no' => __('No Title and no excerpt', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'content_length',
                'type' => 'select',
                'title' => __('Blog Content length', 'redux-page-builder'),
                'subtitle' => __('Should the full entry be displayed or just a small excerpt?', 'redux-page-builder'),
                'default' => 'content',
                "required"  => array('blog_style', '=', array('list-small','list-medium','list-large')),
                'options' => array(
                    'content' => __('Full Content', 'redux-page-builder'),
                    'excerpt' => __('Excerpt', 'redux-page-builder'),
                    'excerpt_read_more' => __('Excerpt With Read More Link', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'excerpt_length',
                'type' => 'text',
                'title' => __('Excerpt Content length', 'redux-page-builder'),
                'subtitle' => __('How many words should be displayed in excerpt?', 'redux-page-builder'),
                'default' => '55',
                "required"  => array('content_length', '=', array('excerpt','excerpt_read_more')),
            ),
            array(
                'id' => 'preview_mode',
                'type' => 'select',
                'title' => __('Preview Image Size', 'redux-page-builder'),
                'subtitle' => __('Set the image size of the preview images', 'redux-page-builder'),
                'default' => 'auto',
                'options' => array(
                    'auto' => __('Set the preview image size automatically based on column or layout width', 'redux-page-builder'),
                    'custom' => __('Choose the preview image size manually (select thumbnail size)', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'image_size',
                'type' => 'select',
                'title' => __('Select custom preview image size', 'redux-page-builder'),
                'subtitle' => __('Choose image size for Preview Image', 'redux-page-builder'),
                "required"  => array('preview_mode','equals','custom'),
                'default' => 'auto',
                'options' => get_registered_image_sizes(array('logo')),
            ),
            array(
                'id' => 'post_meta',
                'type' => 'switch',
                'title' => __('Display Post Meta', 'redux-page-builder'),
                'subtitle' => __('Should post meta be displayed?', 'redux-page-builder'),
                'default' => 1,
                'on' => __('Yes, display', 'redux-page-builder'),
                'off' => __('No, Do not display', 'redux-page-builder'),
            ),
            array(
                'id' => 'items',
                'type' => 'select',
                'title' => __('Post Number', 'redux-page-builder'),
                'subtitle' => __('How many items should be displayed per page?', 'redux-page-builder'),
                'default' => '3',
                'options' => number_array(1,100,1, array('All'=>'-1')),
            ),
            array(
                'id' => 'paginate',
                'type' => 'select',
                'title' => __('Pagination', 'redux-page-builder'),
                'subtitle' => __('Should a pagination be displayed?', 'redux-page-builder'),
                'default' => 'yes',
                'options' => array(
                    'yes' => __('Yes', 'redux-page-builder'),
                    'no' => __('No', 'redux-page-builder'),
                ),
            ),
        )
    );

    $widgets['promobox'] = array(
        'title' => __('Promo Box', 'redux-page-builder'),
        'desc' => __('Creates a notification box with call to action button', 'redux-page-builder'),
        'icon' => 'bullhorn',
        'fields' => array(
            array(
                'id' => 'title',
                'type' => 'text',
                'title' => __('Title', 'redux-page-builder'),
                'subtitle' => __('This is the small title at the top of your Notification.', 'redux-page-builder'),
                'default' => 'Note',
            ),
            array(
                'id' => '_content',
                'type' => 'textarea',
                'title' => __('Message', 'redux-page-builder'),
                'subtitle' => __('This is the text that appears in your Notification.', 'redux-page-builder'),
                'default' => 'This is a notification of some sort.',
            ),
            array(
                'id' => 'label',
                'type' => 'text',
                'title' => __('Button Label', 'redux-page-builder'),
                'subtitle' => __('This is the text that appears on your button.', 'redux-page-builder'),
                'default' => 'Click Me',
            ),
            array(
                'id' => 'link',
                "title" => __("Button Link?", 'redux-page-builder'),
                "subtitle" => __("Where should your button link to?", 'redux-page-builder'),
                "fetchTMPL" => true,
                "type" => "linkpicker",
                "default" => "",
                'required' => array('button','=','1'),
                "options" => array(
                    'no-link' => __('No Link', 'redux-page-builder'),
                    'manually' => __('Set Manually', 'redux-page-builder'),
                    'single' => __('Single Entry', 'redux-page-builder'),
                    'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'color',
                'type' => 'select',
                'title' => __('Message Colors', 'redux-page-builder'),
                'subtitle' => __('Choose the color for your Box here', 'redux-page-builder'),
                'default' => 'alert-success',
                'options' => array(
                    'success' => __('Green', 'redux-page-builder'),
                    'info' => __('Blue', 'redux-page-builder'),
                    'warning' => __('Orange', 'redux-page-builder'),
                    'danger' => __('Red', 'redux-page-builder'),
                ),
            ),
            array(
                'id' => 'dismiss',
                'type' => 'switch',
                'title' => __('Dismiss notification', 'redux-page-builder'),
                'subtitle' => __('Should an dismiss icon be displayed at the right side of the notification', 'redux-page-builder'),
                'default' => 0,
                'on' => __('Yes, display dismiss icon', 'redux-page-builder'),
                'off' => __('No', 'redux-page-builder'),
            ),
            array(
                'id' => 'icon_select',
                'type' => 'switch',
                'title' => __('Icon', 'redux-page-builder'),
                'subtitle' => __('Should an icon be displayed at the left side of the notification', 'redux-page-builder'),
                'default' => 0,
                'on' => __('Yes, display Icon', 'redux-page-builder'),
                'off' => __('No Icon', 'redux-page-builder'),
            ),
            array(
                'id' => 'icon',
                'type' => 'select',
                'title' => __('List Item Icon', 'redux-page-builder'),
                'subtitle' => __('Select an icon for your list item bellow', 'redux-page-builder'),
                'data' => 'elusive',
                'required' => array('icon_select' , '=' , 1),
            ), 
        )
    );

    $widgets['iconlist'] = array(
        'title' => __('Icon List', 'redux-page-builder'),
        'desc' => __('Creates a list with nice icons beside', 'redux-page-builder'),
        'icon' => 'list',
        'fields' => array(
            array(
                'id' => 'iconlist',
                'type' => 'group',
                'title' => __('Add/Edit List items', 'redux-page-builder'),
                'subtitle' => __('Here you can add, remove and edit the items of your item list.', 'redux-page-builder'),
                'groupname' => __('Icon List', 'redux-page-builder'),
                'subfields' => array(
                    array(
                        'id' => 'list_title',
                        'type' => 'text',
                        'title' => __('List Item Title', 'redux-page-builder'),
                        'subtitle' => __('Enter the list item title here (Better keep it short)', 'redux-page-builder'),
                        'default' => 'List Title',
                    ),
                    //link

                    array(
                        'id' => 'icon',
                        'type' => 'select',
                        'title' => __('List Item Icon', 'redux-page-builder'),
                        'subtitle' => __('Select an icon for your list item below', 'redux-page-builder'),
                        'data' => 'elusive',
                    ),
                    // array(
                    //     'id' => '_content',
                    //     'type' => 'textarea',
                    //     'title' => __('List Item Content', 'redux-page-builder'),
                    //     'subtitle' => __('Enter some content here', 'redux-page-builder'),
                    //     'default' => __('List Content goes here', 'redux-page-builder'),
                    // ),
                ),
            ),
        )
    );
    
    
    $widgets['pricetable'] = array(
        'title' => __('Price Table', 'redux-page-builder'),
        'desc' => __('Create price table', 'redux-page-builder'),
        'icon' => 'table',
        'fields' => array(
            array(
                'id' => 'title',
                'type' => 'text',
                'title' => __('Plan Name', 'redux-page-builder'),
                'subtitle' => __('This is the title at the top of your price table.', 'redux-page-builder'),
                'default' => '',
            ),
            array(
                'id' => 'price',
                'type' => 'text',
                'title' => __('Pricing', 'redux-page-builder'),
                'subtitle' => __('Enter the price', 'redux-page-builder'),
                'default' => '',
            ),
            array(
                'id' => 'duration',
                'type' => 'text',
                'title' => __('Duration', 'redux-page-builder'),
                'subtitle' => __('Enter the Duration of the plan', 'redux-page-builder'),
                'default' => '',
            ),
            array(
                'id' => 'features',
                'type' => 'group',
                'title' => __('Add/Edit List features', 'redux-page-builder'),
                'subtitle' => __('Here you can add, remove and edit the features of your plan.', 'redux-page-builder'),
                'groupname' => __('Feature', 'redux-page-builder'),
                'subfields' => array(
                    array(
                        'id' => 'feature_title',
                        'type' => 'text',
                        'title' => __('Plan Feature', 'redux-page-builder'),
                        'subtitle' => __('Here you can enter a list of your plan features', 'redux-page-builder'),
                        'default' => '',
                    ),
                    array(
                        'id' => 'icon',
                        'type' => 'select',
                        'title' => __('Plan Feature Icon', 'redux-page-builder'),
                        'subtitle' => __('Select an icon for your feature', 'redux-page-builder'),
                        'data' => 'elusive',
                    ),
                ),
            ),    
            array(
                'id' => 'label',
                'type' => 'text',
                'title' => __('Button Label', 'redux-page-builder'),
                'subtitle' => __('This is the text that appears on your button.', 'redux-page-builder'),
                'default' => 'Purchase Now',
            ),
            array(
                'id' => 'link',
                "title" => __("Button Link?", 'redux-page-builder'),
                "subtitle" => __("Where should your button link to?", 'redux-page-builder'),
                "fetchTMPL" => true,
                "type" => "linkpicker",
                "default" => "",
                "options" => array(
                    'no-link' => __('No Link', 'redux-page-builder'),
                    'manually' => __('Set Manually', 'redux-page-builder'),
                    'single' => __('Single Entry', 'redux-page-builder'),
                    'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'color',
                'type' => 'select',
                'title' => __('Price Table Color', 'redux-page-builder'),
                'subtitle' => __('Choose the color for your Price Table here', 'redux-page-builder'),
                'default' => 'success',
                'options' => array(
                    'default' => __('Default', 'redux-page-builder'),
                    'success' => __('Green', 'redux-page-builder'),
                    'primary' => __('Main Color', 'redux-page-builder'),
                    'danger' => __('Red', 'redux-page-builder'),
                ),
            ),
        ),
    );

    $widgets['team'] = array(
        'title' => __('Team Member', 'redux-page-builder'),
        'desc' => __('Displays a simple image.', 'redux-page-builder'),
        'icon' => 'picture',
        'fields' => array(
            array(
                'id' => 'image',
                'type' => 'media',
                'title' => __('Choose image', 'redux-page-builder'),
                'subtitle' => __('Either upload a new, or choose an existing image from your media library', 'redux-page-builder'),
            ),
            array(
                'id' => 'name',
                'type' => 'text',
                'title' => __('Name', 'redux-page-builder'),
                'subtitle' => __('Enter the Name of the Person', 'redux-page-builder'),
            ),
            array(
                'id' => 'subtitle',
                'type' => 'text',
                'title' => __('Subtitle bellow name', 'redux-page-builder'),
                'subtitle' => __('Can be used for a job description', 'redux-page-builder'),
            ),
            array(
                'id' => '_content',
                'type' => 'textarea',
                'title' => __('desc', 'redux-page-builder'),
                'subtitle' => __('Enter the description here', 'redux-page-builder'),
            ),
            array(
                'id' => 'label',
                'type' => 'text',
                'title' => __('Button Label', 'redux-page-builder'),
                'subtitle' => __('This is the text that appears on your button.', 'redux-page-builder'),
            ),
            array(
                'id' => 'link',
                "title" => __("Button Link?", 'redux-page-builder'),
                "subtitle" => __("Where should your button link to?", 'redux-page-builder'),
                "fetchTMPL" => true,
                "type" => "linkpicker",
                "default" => "",
                "options" => array(
                    'no-link' => __('No Link', 'redux-page-builder'),
                    'manually' => __('Set Manually', 'redux-page-builder'),
                    'single' => __('Single Entry', 'redux-page-builder'),
                    'taxonomy' => __('Taxonomy Overview Page', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'link_target',
                'type' => 'select',
                'title' => __('Open Link in new Window?', 'redux-page-builder'),
                'subtitle' => __('Select here if you want to open the linked page in a new window', 'redux-page-builder'),
                "default" => "",
                'options' => array(
                    '' => __('No, open in same window', 'redux-page-builder'),
                    '_blank' => __('Yes, open in new window', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'color',
                'type' => 'select',
                'title' => __('Button Color', 'redux-page-builder'),
                'subtitle' => __('Choose a color for your button here', 'redux-page-builder'),
                "default" => 'btn-primary',
                'options' => array(
                    'btn-link' => __('Transparent', 'redux-page-builder'),
                    'btn-default' => __('Default (gray)', 'redux-page-builder'),
                    'btn-primary' => __('Main Color', 'redux-page-builder'),
                    'btn-info' => __('Light Blue', 'redux-page-builder'),
                    'btn-success' => __('Green', 'redux-page-builder'),
                    'btn-warning' => __('Orange', 'redux-page-builder'),
                    'btn-danger' => __('Red', 'redux-page-builder'),
                )
            ),
            array(
                'id' => 'icon_select',
                'type' => 'switch',
                'title' => __('Button Icon', 'redux-page-builder'),
                'sub_desc' => __('Should an icon be displayed at the left side of the button', 'redux-page-builder'),
                'on' => 'On',
                'off' => 'Off',
                "default" => 0,
            ),
            array(
                'id' => 'icon',
                'type' => 'select',
                'data' => 'elusive',
                'required' => array('icon_select', '=' , 1),
                'title' => __('Button Icon', 'redux-page-builder'),
                'subtitle' => __('Select an icon for your Button bellow', 'redux-page-builder'),
            ),
        )
    );

    return $widgets;

}
add_filter('redux-widgets-options', 'register_builder_widgets');