<?php
/**
* Plugin Name: custom post type
* Plugin URI: https://www.yourwebsiteurl.com/
* Description: This is the very first plugin I ever created.
* Version: 1.0
* Author: Elroi
* Author URI: http://yourwebsiteurl.com/
**/

function erpwp_latest_cpt_init() {
if ( !function_exists( 'register_sidebar_widget' ))
return;

function erpwp_latest_cpt($args) {
global $post;
extract($args);

// These are our own options
$options = get_option( 'erpwp_latest_cpt' );
$title = $options['title']; // Widget title
$phead = $options['phead']; // Heading format
$ptype = $options['ptype']; // Post type
$pshow = $options['pshow']; // Number of Tweets

$beforetitle = '';
$aftertitle = '';


// Output
echo $before_widget;

if ($title) echo $beforetitle . $title . $aftertitle;

$pq = new WP_Query(array( 'post_type' => $ptype, 'showposts' => $pshow ));
if( $pq->have_posts() ) :
?>

<ul class="erp-cpt-post-widget">
<?php while($pq->have_posts()) : $pq->the_post(); ?>
    <li>
    <div class="erp-cpt-image"><?php the_post_thumbnail();?></div>
    <a class="erp-cpt-title" href="<?php the_permalink(); ?>" rel="bookmark"> <?php the_title(); ?> </a>	
    </li>
</ul>

<?php wp_reset_query();
endwhile; ?>

<?php endif; ?>


<style type="text/css">

.erp-cpt-post-widget{
	width:100%;
	}
.erp-cpt-image{
	width:20%;
	height:auto;
	float:left;
	margin-right:5%;
	overflow:hidden;
}
.erp-cpt-title{
	width:75%;
	height:auto;
	float: left;
	}
.erp-cpt-post-widget ul{}
.erp-cpt-post-widget li{
	float:left;
	width:100%;
	margin-top:10px;
	margin-bottom:10px;
	list-style:none;
	}
.erp-cpt-post-widget li a{
	width:75%;
	height:auto;
	float: left;
	
	}
</style>


<?php
echo $after_widget;
}


//settings 

function erpwp_latest_cpt_control() {
$options = get_option( 'erpwp_latest_cpt' );
if ( !is_array( $options ))
$options = array(
'title' => 'Latest Posts',
'phead' => 'h2',
'ptype' => 'post',
'pshow' => '5'
);

if ( $_POST['latest-cpt-submit'] ) {
$options['title'] = strip_tags( $_POST['latest-cpt-title'] );
$options['phead'] = $_POST['latest-cpt-phead'];
$options['ptype'] = $_POST['latest-cpt-ptype'];
$options['pshow'] = $_POST['latest-cpt-pshow'];
update_option( 'erpwp_latest_cpt', $options );
}

$title = $options['title'];
$phead = $options['phead'];
$ptype = $options['ptype'];
$pshow = $options['pshow'];

?>

<label for="latest-cpt-title"><?php echo __( 'Widget Title' ); ?>
<input id="latest-cpt-title" type="text" name="latest-cpt-title" size="30" value="<?php echo $title; ?>" />
</label>

<label for="latest-cpt-phead"><?php echo __( 'Widget Heading Format' ); ?></label>

<select name="latest-cpt-phead">
	<option selected="selected" value="h2">H2 - <h2></h2></option>
	<option selected="selected" value="h3">H3 - <h3></h3></option>
	<option selected="selected" value="h4">H4 - <h4></h4></option>
	<option selected="selected" value="strong">Bold - <strong></strong></option>
</select>
<select name="latest-cpt-ptype">
	<option value="">- <?php echo __( 'Select Post Type' ); ?> -</option>
</select>

<?php $args = array( 'public' => true );
$post_types = get_post_types( $args, 'names' );
foreach ($post_types as $post_type ) { ?>
	<select name="latest-cpt-ptype">
		<option selected="selected" value="<?php echo $post_type; ?>"><?php echo $post_type;?></option>
	</select>
	<?php } ?>

<label for="latest-cpt-pshow"><?php echo __( 'Number of posts to show' ); ?>
    <input id="latest-cpt-pshow" type="text" name="latest-cpt-pshow" size="2" value="<?php echo $pshow; ?>" />
</label>

<input id="latest-cpt-submit" type="hidden" name="latest-cpt-submit" value="1" />
<?php
}

wp_register_sidebar_widget( 'widget_latest_cpt', __('ERP Custom Posts'), 'erpwp_latest_cpt' );
wp_register_widget_control( 'widget_latest_cpt', __('ERP Custom Posts'), 'erpwp_latest_cpt_control', 300, 200 );

}
add_action( 'widgets_init', 'erpwp_latest_cpt_init' );

?>