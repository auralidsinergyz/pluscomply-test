<?php

/**
 * Admin form: Categories widget.
 *
 * @link    https://plugins360.com
 * @since   1.0.0
 *
 * @package All_In_One_Video_Gallery
 */
?>

<div class="aiovg aiovg-widget-form aiovg-widget-form-categories aiovg-template-<?php echo esc_attr( $instance['template'] ); ?>">
	<div class="aiovg-widget-field aiovg-widget-field-title">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'all-in-one-video-gallery' ); ?></label> 
		<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat aiovg-widget-input-title" value="<?php echo esc_attr( $instance['title'] ); ?>">
	</div>	

	<div class="aiovg-widget-field aiovg-widget-field-template">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>"><?php esc_html_e( 'Select Template', 'all-in-one-video-gallery' ); ?></label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'template' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'template' ) ); ?>" class="widefat aiovg-widget-input-template"> 
			<?php
				$options = array(
					'grid'     => esc_html__( 'Grid', 'all-in-one-video-gallery' ),
					'list'     => esc_html__( 'List', 'all-in-one-video-gallery' ),
					'dropdown' => esc_html__( 'Dropdown', 'all-in-one-video-gallery' )		
				);
			
				foreach( $options as $key => $value ) {
					printf( '<option value="%s"%s>%s</option>', $key, selected( $key, $instance['template'], false ), $value );
				}
			?>
		</select>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-child_of">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'child_of' ) ); ?>"><?php esc_html_e( 'Select Parent', 'all-in-one-video-gallery' ); ?></label> 
		<?php
			wp_dropdown_categories( array(
				'show_option_none'  => '-- ' . esc_html__( 'Select Parent', 'all-in-one-video-gallery' ) . ' --',
				'option_none_value' => 0,
				'taxonomy'          => 'aiovg_categories',
				'name' 			    => $this->get_field_name( 'child_of' ),
				'class'             => 'widefat aiovg-widget-input-child_of',
				'orderby'           => 'name',
				'selected'          => (int) $instance['child_of'],
				'hierarchical'      => true,
				'depth'             => 10,
				'show_count'        => false,
				'hide_empty'        => false,
			) );
		?>
	</div>	

	<div class="aiovg-widget-field aiovg-widget-field-columns">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>"><?php esc_html_e( 'Columns', 'all-in-one-video-gallery' ); ?></label> 
		<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" class="widefat aiovg-widget-input-columns" value="<?php echo esc_attr( $instance['columns'] ); ?>" />
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-limit">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Limit (per page)', 'all-in-one-video-gallery' ); ?></label> 
		<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" class="widefat aiovg-widget-input-limit" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-orderby">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order By', 'all-in-one-video-gallery' ); ?></label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat aiovg-widget-input-orderby"> 
			<?php
				$options = array(
					'id'    => esc_html__( 'ID', 'all-in-one-video-gallery' ),
					'count' => esc_html__( 'Count', 'all-in-one-video-gallery' ),
					'name'  => esc_html__( 'Name', 'all-in-one-video-gallery' ),
					'slug'  => esc_html__( 'Slug', 'all-in-one-video-gallery' )	
				);
			
				foreach( $options as $key => $value ) {
					printf( '<option value="%s"%s>%s</option>', $key, selected( $key, $instance['orderby'], false ), $value );
				}
			?>
		</select>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-order">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'all-in-one-video-gallery' ); ?></label>
		<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat aiovg-widget-input-order"> 
			<?php
				$options = array(
					'asc'  => esc_html__( 'ASC', 'all-in-one-video-gallery' ),
					'desc' => esc_html__( 'DESC', 'all-in-one-video-gallery' )
				);
			
				foreach( $options as $key => $value ) {
					printf( '<option value="%s"%s>%s</option>', $key, selected( $key, $instance['order'], false ), $value );
				}
			?>
		</select>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-hierarchical">		
		<label for="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'hierarchical' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'hierarchical' ) ); ?>" class="aiovg-widget-input-hierarchical" value="1" <?php checked( 1, $instance['hierarchical'] ); ?> />
			<?php esc_html_e( 'Show Hierarchy', 'all-in-one-video-gallery' ); ?>
		</label>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-show_description">		
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_description' ) ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_description' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_description' ) ); ?>" class="aiovg-widget-input-show_description" value="1" <?php checked( 1, $instance['show_description'] ); ?> />
			<?php esc_html_e( 'Show Description', 'all-in-one-video-gallery' ); ?>
		</label>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-show_count">		
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" class="aiovg-widget-input-show_count" value="1" <?php checked( 1, $instance['show_count'] ); ?> />
			<?php esc_html_e( 'Show Videos Count', 'all-in-one-video-gallery' ); ?>
		</label>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-hide_empty">		
		<label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>" class="aiovg-widget-input-hide_empty" value="1" <?php checked( 1, $instance['hide_empty'] ); ?> />
			<?php esc_html_e( 'Hide Empty Categories', 'all-in-one-video-gallery' ); ?>
		</label>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-show_more">		
		<label for="<?php echo esc_attr( $this->get_field_id( 'show_more' ) ); ?>">
			<input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_more' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_more' ) ); ?>" class="aiovg-widget-input-show_more" value="1" <?php checked( 1, $instance['show_more'] ); ?> />
			<?php esc_html_e( 'Show More Button', 'all-in-one-video-gallery' ); ?>
		</label>
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-more_label">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'more_label' ) ); ?>"><?php esc_html_e( 'More Button Label', 'all-in-one-video-gallery' ); ?></label> 
		<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'more_label' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'more_label' ) ); ?>" class="widefat aiovg-widget-input-more_label" value="<?php echo esc_attr( $instance['more_label'] ); ?>">
	</div>

	<div class="aiovg-widget-field aiovg-widget-field-more_link">
		<label class="aiovg-widget-label" for="<?php echo esc_attr( $this->get_field_id( 'more_link' ) ); ?>"><?php esc_html_e( 'More Button Link', 'all-in-one-video-gallery' ); ?></label> 
		<input type="text" name="<?php echo esc_attr( $this->get_field_name( 'more_link' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'more_link' ) ); ?>" class="widefat aiovg-widget-input-more_link" value="<?php echo esc_attr( $instance['more_link'] ); ?>">
		<p class="description"><?php esc_html_e( 'Leave this field blank to use Ajax', 'all-in-one-video-gallery' ); ?></p>
	</div>
</div>
