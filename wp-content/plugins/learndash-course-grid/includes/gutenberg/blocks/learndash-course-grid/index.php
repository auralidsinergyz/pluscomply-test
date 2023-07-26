<?php
namespace LearnDash\Course_Grid\Gutenberg\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
    exit();
}

use LearnDash;
use LearnDash\Course_Grid\Lib\LearnDash_Gutenberg_Block;
use LearnDash_Gutenberg_Block as Core_LearnDash_Gutenberg_Block;
use WP_Block;

/**
 * Course grid block trait
 */
trait LearnDash_Course_Grid_Block_Trait
{
    /**
     * Object constructor
     */
    public function __construct() {
        $this->shortcode_slug   = 'learndash_course_grid';
        $this->block_slug       = 'ld-course-grid';
        $this->block_attributes = array(
            'post_type' => array(
                'type' => 'string',
            ),
            'per_page' => array(
                'type' => 'integer',
            ),
            'orderby' => array(
                'type' => 'string',
            ),
            'order' => array(
                'type' => 'string',
            ),
            'taxonomies' => array(
                'type' => 'string',
            ),
            'enrollment_status' => array(
                'type' => 'string',
            ),
            'progress_status' => array(
                'type' => 'string',
            ),
            'thumbnail' => array(
                'type' => 'boolean',
            ),
            'thumbnail_size' => array(
                'type' => 'string',
            ),
            'ribbon' => array(
                'type' => 'boolean',
            ),
            'content' => array(
                'type' => 'boolean',
            ),
            'title' => array(
                'type' => 'boolean',
            ),
            'title_clickable' => array(
                'type' => 'boolean',
            ),
            'description' => array(
                'type' => 'boolean',
            ),
            'description_char_max' => array(
                'type' => 'integer',
            ),
            'post_meta' => array(
                'type' => 'boolean',
            ),
            'button' => array(
                'type' => 'boolean',
            ),
            'pagination' => array(
                'type' => 'string',
            ),
            'grid_height_equal' => array(
                'type' => 'boolean',
            ),
            'progress_bar' => array(
                'type' => 'boolean',
            ),
            'filter' => array(
                'type' => 'boolean',
            ),
            'skin' => array(
                'type' => 'string',
            ),
            'card' => array(
                'type' => 'string',
            ),
            'columns' => array(
                'type' => 'integer',
            ),
            'min_column_width' => array(
                'type' => 'string',
            ),
            'items_per_row' => array(
                'type' => 'integer',
            ),
            'font_family_title' => array(
                'type' => 'string',
            ),
            'font_family_description' => array(
                'type' => 'string',
            ),
            'font_size_title' => array(
                'type' => 'string',
            ),
            'font_size_description' => array(
                'type' => 'string',
            ),
            'font_color_title' => array(
                'type' => 'string',
            ),
            'font_color_description' => array(
                'type' => 'string',
            ),
            'background_color_title' => array(
                'type' => 'string',
            ),
            'background_color_description' => array(
                'type' => 'string',
            ),
            'background_color_ribbon' => array(
                'type' => 'string',
            ),
            'font_color_ribbon' => array(
                'type' => 'string',
            ),
            'background_color_icon' => array(
                'type' => 'string',
            ),
            'font_color_icon' => array(
                'type' => 'string',
            ),
            'background_color_button' => array(
                'type' => 'string',
            ),
            'font_color_button' => array(
                'type' => 'string',
            ),
            // Misc
            'id' => array(
                'type' => 'string',
            ),
            'className' => array(
                'type' => 'string',
            ),
            'preview_show' => array(
                'type' => 'boolean',
            ),
            'display_state' => array(
                'type' => 'object'
            ),
            // Filter
            'filter_search' => [
                'type' => 'boolean',
            ],
            'filter_taxonomies' => [
                'type' => 'array',
            ],
            'filter_price' => [
                'type' => 'boolean',
            ],
            'filter_price_min' => [
                'type' => 'string',
            ],
            'filter_price_max' => [
                'type' => 'string',
            ],
        );

        $this->self_closing = true;

        $this->init();
    }

    /**
     * Render Block
     *
     * This function is called per the register_block_type() function above. This function will output
     * the block rendered content.
     *
     * @param array         $attributes     Shortcode attrbutes.
     * @param string        $block_content
     * @param WP_Block|null $block          Block object.
     * @return void The output is echoed.
     */
    public function render_block( $attributes = array(), $block_content = '', WP_Block $block = null ) {
        $attributes = $this->preprocess_block_attributes( $attributes );

        $attributes = apply_filters( 'learndash_block_markers_shortcode_atts', $attributes, $this->shortcode_slug, $this->block_slug, '' );

        $shortcode_params_str = '';
        foreach ( $attributes as $key => $val ) {
            if ( is_null( $val ) ) {
                continue;
            }

            if ( is_array( $val ) ) {
                $val = implode( ',', $val );
            }

            if ( ! empty( $shortcode_params_str ) ) {
                $shortcode_params_str .= ' ';
            }
            $shortcode_params_str .= $key . '="' . esc_attr( $val ) . '"';
        }

        $shortcode_params_str = '[' . $this->shortcode_slug . ' ' . $shortcode_params_str . ']';

        $args = LearnDash\course_grid()->skins->parse_shortcode_tags( $shortcode_params_str );

        $style = LearnDash\course_grid()->skins->generate_custom_css( $args );

        ob_start();
        ?>
        <div class="learndash-course-grid-temp-css" style="display: none;">
            <?php echo html_entity_decode( $style ); ?>
        </div>
        <?php
        $script = ob_get_clean();

        $shortcode_out = $script;
        $shortcode_out .= do_shortcode( $shortcode_params_str );

        if ( ( empty( $shortcode_out ) ) ) {
            $shortcode_out = '[' . $this->shortcode_slug . '] placeholder output.';
        }

        return $this->render_block_wrap( $shortcode_out, true );
    }

    /**
     * Called from the LD function learndash_convert_block_markers_shortcode() when parsing the block content.
     *
     * @since 2.0
     *
     * @param array  $attributes The array of attributes parse from the block content.
     * @param string $shortcode_slug This will match the related LD shortcode ld_profile, ld_course_list, etc.
     * @param string $block_slug This is the block token being processed. Normally same as the shortcode but underscore replaced with dash.
     * @param string $content This is the orignal full content being parsed.
     *
     * @return array $attributes.
     */
    public function learndash_block_markers_shortcode_atts_filter( $attributes = array(), $shortcode_slug = '', $block_slug = '', $content = '' ) {
        if ( $shortcode_slug === $this->shortcode_slug ) {
            if ( isset( $attributes['preview_show'] ) ) {
                unset( $attributes['preview_show'] );
            }

            if ( isset( $attributes['className'] ) ) {
                $attributes['class_name'] = $attributes['className'];
                unset( $attributes['className'] );
            }

            if ( isset( $attributes['display_state'] ) ) {
                unset( $attributes['display_state'] );
            }

            if ( ! isset( $attributes['filter_taxonomies'] ) ) {
                $attributes['filter_taxonomies'] = '';
            }
            
            foreach ( $attributes as $key => $value ) {
                if ( is_array( $value ) ) {
                    $attributes[ $key ] = implode( ', ', $value );
                } elseif ( is_string( $value ) ) {
                    // Remove quotes to prevent the attributes from being stripped out.
                    $attributes[ $key ] = str_replace( [ '"', '\'' ] , '', $attributes[ $key ] );
                }
            }
        }

        return $attributes;
    }
}


if ( class_exists( 'Core_LearnDash_Gutenberg_Block' ) ) {
    class LearnDash_Course_Grid extends Core_LearnDash_Gutenberg_Block
    {
        use LearnDash_Course_Grid_Block_Trait;
    }
} else  {
    class LearnDash_Course_Grid extends LearnDash_Gutenberg_Block
    {
        use LearnDash_Course_Grid_Block_Trait;
    }
}
