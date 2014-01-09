<ul id="modules">
    <?php
    global $mxwidgets;
    //print_r($mxwidgets);die();
    function list_widgets($mxwidgets) {
        foreach ($mxwidgets as $id => $widget) {
            if ($id) {
                if(  (isset($widget->icon_type) && $widget->icon_type == 'image')) {
                    $icon = ( !isset( $widget->icon ) ) ? '' : '<img class="image_icon_type" src="' . $widget->icon . '" /> ';
                } else {
                    $icon_class = ( !isset( $widget->icon_class ) ) ? ' icon-4x' : ' ' . $widget->icon_class;
                    $icon = ( !isset( $widget->icon ) ) ? '<span class="icon-stack"><i class="icon-circle icon-stack-base"></i><i class="icon-light icon-puzzle-piece' . $icon_class . '"></i></span> ' : '<span class="icon-stack"><i class="icon-circle icon-stack-base"></i><i class="icon-light icon-' . $widget->icon . $icon_class . '"></i></span> ';
                }
                ?>
                <li class="mxwdgt" id="<?php echo $id; ?>">
                    <?php echo $icon; ?>
                    <a href="#" class="insert" rel="<?php echo $id; ?>" title="<?php echo $widget->name; ?>">
                        <h3 style="margin: 0px" class="widget-title"><?php echo ucfirst($widget->name); ?></h3>

                        <em><small><?php echo $widget->widget_options['description'] ?></small></em>
                        <div style="clear: both;"></div>

                    </a>
                </li>
                <?php
            }
        }
    }
    //print_r(get_declared_classes());die();
    foreach (get_declared_classes() as $class) {
        if (is_subclass_of($class, 'Redux_Widget')) {
            $_redux_widgets[] = $class;
        }
    }

    foreach ($mxwidgets as $class => $widget) {
        if (in_array($class, $_redux_widgets))
            $redux_widgets[$class] = $widget;
        else
            $other_widgets[$class] = $widget;
    }
    echo '<h3>'.__( 'Redux Widgets' ,'redux-page-builder').'</h3>';
    list_widgets($redux_widgets);
    echo '<div class="clear clearfix"></div>';
    echo "<h3>".__( "Wordpress default widgets and plugin's widget" ,'redux-page-builder')."</h3>";
    list_widgets($other_widgets);
    
    ?>
</ul>
<div style="clear: both;"></div>