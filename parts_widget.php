<?php
function get_parts_file()
{
    $array = array(
        // filename => title
        'fb' => 'フェイスブック',
        'twitter' => 'ツイッター'
    );
    return $array;
}

class Parts_Widget extends WP_Widget
{
    const CHECK_FILE_EXISTS = false;

    function __construct()
    {
        parent::__construct('parts', "パーツ");
    }

    private function check_file_exists($file)
    {
        if (!isset($file))
        {
            return false;
        }

        if (!self::CHECK_FILE_EXISTS)
        {
            return true;
        }

        if (locate_template($file.'.php'))
        {
            return true;
        }

        return false;
    }

    function widget($args, $instance)
    {
        get_template_part($instance['file'],$args['id']);
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array)$new_instance, array('file' => ''));

        if ($this->check_file_exists($new_instance['file']))
        {
            $instance['file'] = $new_instance['file'];
        }

        return $instance;
    }

    function form($instance)
    {
        $instance = wp_parse_args((array)$instance, array('file' => ''));
        $filenames = (array)get_parts_file();
        ?>
        <select name="<?php echo $this->get_field_name('file'); ?>"
                id="<?php echo $this->get_field_id('file'); ?>" class="widefat">
            <?php foreach ($filenames as $filename => $filetitle) :?>
                <?php if ($this->check_file_exists($filename)) :?>
                    <option value="<?php echo esc_attr($filename);?>" <?php selected($instance['file'], $filename); ?>><?php echo esc_html($filetitle);?></option>
                <?php endif; ?>
            <?php endforeach;?>
        </select>
    <?php
    }
}

function my_register_widgets()
{
    register_widget('Parts_Widget');
}

add_action('widgets_init', 'my_register_widgets');