<?php
$section[] = array(
    'title' => '',
    'desc' => __('Creates a section with unique background image and colors', 'redux-page-builder'),
    'fields' => array(
        array(
            'id' => 'custom_section',
            'type' => 'switch',
            'title' => __('Enable Customizing section', 'redux-page-builder'),
            'subtitle' => __('You can enable custom background color or Image for your Section here.', 'redux-page-builder'),
            'default' => 0,
        ),
        array(
            'id' => 'custom_choice',
            'type' => 'switch',
            'required' => array('custom_section' , '=' ,1),
            'title' => __('Enable Custom Background Color or Image', 'redux-page-builder'),
            'default' => 1,
            'on' => 'Custom BG Color',
            'off' => 'Custom BG Image',
        ),
        array(
            'id' => 'custom_bg',
            'type' => 'color',
            'title' => __('Custom Background Color', 'redux-page-builder'),
            'subtitle' => __('Select a custom background color for your Section here. Leave empty if you want to use the background color of the color scheme defined above', 'redux-page-builder'),
            'default' => '#FFFFFF',
            'required' => array('custom_choice' , '=' , '1'),
        ),
        array(
            'id' => 'src',
            "title" => __("Custom Background Image", 'redux-page-builder'),
            "subtitle" => __("Either upload a new, or choose an existing image from your media library. Leave empty if you want to use the background image of the color scheme defined above", 'redux-page-builder'),
            "type" => "media",
            "default" => '',
            'required' => array('custom_choice' , '=' , 0),
        ),
        array(
            'id' => 'position',
            'type' => 'select',
            'title' => __('Background Image Position', 'redux-page-builder'),
            'subtitle' => __('Select here if you want to open the linked page in a new window', 'redux-page-builder'),
            "default" => "top left",
            'required' => array('custom_choice' , '=' , 0),
            'options' => array(
                'top left' => __('Top Left', 'redux-page-builder'),
                'top center' => __('Top Center', 'redux-page-builder'),
                'top right' => __('Top Right', 'redux-page-builder'),
                'bottom left' => __('Bottom Left', 'redux-page-builder'),
                'bottom center' => __('Bottom Center', 'redux-page-builder'),
                'bottom right' => __('Bottom Right', 'redux-page-builder'),
                'center left' => __('Center Left', 'redux-page-builder'),
                'center center' => __('Center Center', 'redux-page-builder'),
                'center right' => __('Center Right', 'redux-page-builder'),
            )
        ),
        array(
            'id' => 'repeat',
            'type' => 'select',
            'title' => __('Background Repeat', 'redux-page-builder'),
            "default" => "no-repeat",
            'required' => array('custom_choice' , '=' , 0),
            'options' => array(
                'no-repeat' => __('No Repeat', 'redux-page-builder'),
                'repeat' => __('Repeat', 'redux-page-builder'),
                'repeat-x' => __('Tile Horizontally', 'redux-page-builder'),
                'repeat-y' => __('Tile Vertically', 'redux-page-builder'),
                'stretch' => __('Stretch to fit', 'redux-page-builder'),
            )
        ),
        array(
            'id' => 'attach',
            'type' => 'select',
            'title' => __('Background Attachment', 'redux-page-builder'),
            "default" => "scroll",
            'required' => array('custom_choice' , '=' , 0),
            'options' => array(
                'scroll' => __('Scroll', 'redux-page-builder'),
                'fixed' => __('Fixed', 'redux-page-builder'),
            )
        ),
        array(
            'id' => 'margin',
            'mode'=>'margin',
            'type' => 'spacing',
            'title' => __('Section Margin', 'redux-page-builder'),
            'subtitle' => __('Define the sections top and bottom margin', 'redux-page-builder'),
            'default' => array('top' => 20, 'bottom' => 20, 'left'=>0, 'right'=>0),
            'units' => 'px',
            'required' => array('custom_section' , '=' , '1'),
        ),
        array(
            'id' => 'padding',
            'mode'=>'padding',
            'type' => 'spacing',
            'title' => __('Section Padding', 'redux-page-builder'),
            'subtitle' => __('Define the sections top and bottom padding', 'redux-page-builder'),
            'default' => array('top' => 10, 'bottom' => 10, 'left'=>0, 'right'=>0),
            'units' => 'px',
            'required' => array('custom_section' , '=' , '1'),
        ),
        array(
            'id' => 'shadow',
            'type' => 'select',
            'title' => __('Section Top Shadow', 'redux-page-builder'),
            'subtitle' => __('Display a small styling shadow at the top of the section', 'redux-page-builder'),
            "default" => "no-shadow",
            'required' => array('custom_section' , '=' , '1'),
            'options' => array(
                'shadow' => __('Display shadow', 'redux-page-builder'),
                'no-shadow' => __('Do not display shadow', 'redux-page-builder'),
            )
        ),
    )
);
$args['opt_name'] = 'redux_page_builder';
?>
<form method="post" action="admin-ajax.php" id="section-settings-form" rel="<?php echo $_REQUEST['section_settings_id']; ?>" >
<?php 
    require_once 'redux.php';
    new ReduxFields($section, $args);
?>    
<p>
	<button type="submit" id="submit_module" class="button-primary btn5x">Save Settings</button>
	<input type="button" onclick="tb_remove()" class="button-secondary btn5x" value="Cancel" />
</p>
</form>