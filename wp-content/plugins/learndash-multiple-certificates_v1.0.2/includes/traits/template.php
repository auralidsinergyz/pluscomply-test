<?php 
namespace LDMC\Traits;

if( ! trait_exists('\LDMC\Traits\Template') ) {
    trait Template
    {

		/**
		 * Helper function for PHP output buffering. same as learndash_ob_get_clean()
		 *
		 * @todo not sure what this is preventing with a while looping
		 *       counting to 10 and checking current buffer level
		 *
		 * @since 2.1.0
		 *
		 * @param int $level Optional. The level for output buffering. Default 0.
		 *
		 * @return string Buffered output.
		 */
		public function template_ob_get_clean( $level = 0 ) {
			$content = '';
			$i       = 1;
			while ( $i <= 10 && ob_get_level() > $level ) {
				$i++;
				$content = ob_get_clean();
			}
			return $content;
		}

		public function get_template_name( $template_location ){
			$template_name = '';
			$file_path_directories = explode( '.', $template_location );
			if ( is_array( $file_path_directories ) ) {
				$template_name = end( $file_path_directories );	
				$template_name = $template_name.'.php';
			}
			return $template_name;
		}

		public function get_template_path( $template_location = '' ) {
			$file_path_directories = explode( '.', $template_location );
			$file_path             = '';
			if ( is_array( $file_path_directories ) ) :
				foreach ( $file_path_directories as $file_path_directory ) :
					$file_path .= '/' . $file_path_directory;
				endforeach;
			else :
				$file_path .= '/' . $file_path_directories;
			endif;
			return $file_path;
        }

        public function get_template( $template_location = '', $template_data = array(), $echo = false, $return_file_path = false  ) {
			$template_name = $this->get_template_name( $template_location );
			$template_path = $this->get_template_path( $template_location );
			$template_path = LD_MC_DIR_PATH. 'templates'.$template_path . '.php';
            // $this->write_log('$template_path');
            // $this->write_log($template_path);
			/**
			 * Filters file path for the learndash template being called.
			 *
			 * @since 2.1.0
			 * @since 3.0.3 - Allow override of empty or other checks.
			 *
			 * @param string  $template_path         Template file path.
			 * @param string  $template_name         Template file name.
			 * @param array   $template_data         Template data.
			 * @param boolean $echo             Whether to echo the template output or not.
			 * @param boolean $return_file_path Whether to return file or path or not.
			 */
			$template = apply_filters( 'ld_mc_template', array(
				'template_name' => $template_name,
				'template_path' => $template_path,
				'template_data' => $template_data,
				'echo' => $echo,
				'return_file_path' => $return_file_path
			));
			extract( $template );

			$template_path = apply_filters( 'ld_mc_template_path', $template_path, $template_name, $template_data, $echo, $return_file_path );
			if ( ! $template_path ) {
				return false;
			}
			// Added check to ensure external hooks don't return empty or non-accessible filenames.
			if ( ( ! empty( $template_path ) ) && ( file_exists( $template_path ) ) && ( is_file( $template_path ) ) ) {
				if ( $return_file_path ) {
					return $template_path;
				}
				/**
				* Filters template data.
				*
				* The dynamic part of the hook refers to the template_name of the template.
				*
			 	* @param string  $template_data         Template file data.
			 	* @param string  $template_name         Template file name.
			 	* @param array   $template_path         Template file path.
			 	* @param boolean $echo             Whether to echo the template output or not.
			 	* @param boolean $return_file_path Whether to return file or path or not.
				*/
				
				$template_data = apply_filters( 'ld_mc_template_data', $template_data, $template_name, $template_path, $echo, $return_file_path );
				if ( ( ! empty( $template_data ) ) && ( is_array( $template_data ) ) ) {
					extract( $template_data );
				}
				$level = ob_get_level();
				ob_start(); 
				include $template_path;
				$contents = $this->template_ob_get_clean( $level );
				if ( ! $echo ) {
					return $contents;
				}
				echo $contents;
			}
		}

    }
}