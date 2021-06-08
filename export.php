<?php

function func_export_all_circular() {
    if(isset($_GET['export_all_circular'])) {
        $arg = array(
            'post_type' => 'circular',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
  
        global $post;
        $arr_post = get_posts($arg);
        if ($arr_post) {
  
            header('Content-type: text/csv');
            header('Content-Disposition: attachment; filename="wp-circular.csv"');
            header('Pragma: no-cache');
            header('Expires: 0');
  
            $file = fopen('php://output', 'w');
			
			

			
			
			
  
            fputcsv($file, array('Circular Number','Circular Date', 'Circular Division','Circular Title', 'Attachment'));
  
            foreach ($arr_post as $post) {
                setup_postdata($post);
				//-------------------------------------
						
				 $at_file = get_post_meta($post->ID, 'circular_attachment', true); 
				 $at_file_extra = get_post_meta($post->ID, 'circular_extra_attachment', true); 
				 
				if(!empty($at_file)){
					$file_new =$at_file;
				}else{
					$file_new =$at_file_extra;
				}
				 
				 $circular_date = get_post_meta($post->ID, 'circular_date', true); 
				 $circular_no = get_post_meta($post->ID, 'circular_number', true);
				 
				 if(!empty($file_new['url'])){
					$url = $file_new['url'];
				 }
                  
                // Get Catagory name of circular
				foreach (get_the_terms($post->ID, 'circular_category') as $cat) {
				   $category_name = $cat->name;
				}
				
				
				//Circular Number','Circular Date', 'Circular Division','Circular Title', 'Attachment			
                fputcsv(
					$file, array(
						$circular_no,
						$circular_date,
						$category_name,
						get_the_title(),
						$url
					)
				);
            }
  
            exit();
        }
    }
}
 
add_action( 'init', 'func_export_all_circular' );



function admin_post_list_add_export_button( $which ) {
    global $typenow;
  
    if ( 'circular' === $typenow && 'top' === $which ) {
        ?>
        <input type="submit" name="export_all_circular" class="button button-primary" value="<?php _e('Export All Circular'); ?>" />
        <?php
    }
}
 
add_action( 'manage_posts_extra_tablenav', 'admin_post_list_add_export_button', 20, 1 );