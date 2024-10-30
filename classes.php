<?php
class JPEGQuality_Image_Editor_GD extends WP_Image_Editor_GD {

	public function get_quality() {
		if($this->_quality) return $this->_quality;
		return parent::get_quality();
	}

    public function multi_resize( $sizes ) {
        $metadata  = array();
        $orig_size = $this->size;
 
        foreach ( $sizes as $size => $size_data ) {
            if ( ! isset( $size_data['width'] ) && ! isset( $size_data['height'] ) ) {
                continue;
            }

            // Additions here for quality control
            $this->_quality = apply_filters('jpegquality_' . $size, apply_filters('jpegquality_all', 82, $size));

            if ( ! isset( $size_data['width'] ) ) {
                $size_data['width'] = null;
            }
            if ( ! isset( $size_data['height'] ) ) {
                $size_data['height'] = null;
            }
 
            if ( ! isset( $size_data['crop'] ) ) {
                $size_data['crop'] = false;
            }
 
            $image     = $this->_resize( $size_data['width'], $size_data['height'], $size_data['crop'] );
            $duplicate = ( ( $orig_size['width'] == $size_data['width'] ) && ( $orig_size['height'] == $size_data['height'] ) );
 
            if ( ! is_wp_error( $image ) && ! $duplicate ) {
                $resized = $this->_save( $image );
 
                imagedestroy( $image );
 
                if ( ! is_wp_error( $resized ) && $resized ) {
                    unset( $resized['path'] );
                    $metadata[ $size ] = $resized;
                }
            }
 
            $this->size = $orig_size;
        }
 
        return $metadata;
    }

}

class JPEGQuality_Image_Editor_Imagick extends WP_Image_Editor_Imagick {

    public function multi_resize( $sizes ) {
        $metadata   = array();
        $orig_size  = $this->size;
        $orig_image = $this->image->getImage();
 
        foreach ( $sizes as $size => $size_data ) {
            if ( ! $this->image ) {
                $this->image = $orig_image->getImage();
            }
 
            if ( ! isset( $size_data['width'] ) && ! isset( $size_data['height'] ) ) {
                continue;
            }
 
            if ( ! isset( $size_data['width'] ) ) {
                $size_data['width'] = null;
            }
            if ( ! isset( $size_data['height'] ) ) {
                $size_data['height'] = null;
            }
 
            if ( ! isset( $size_data['crop'] ) ) {
                $size_data['crop'] = false;
            }

            // Additions here for quaity control
            $quality = apply_filters('jpegquality_' . $size, apply_filters('jpegquality_all', 82, $size));
            $this->set_quality($quality);
 
            $resize_result = $this->resize( $size_data['width'], $size_data['height'], $size_data['crop'] );
            $duplicate     = ( ( $orig_size['width'] == $size_data['width'] ) && ( $orig_size['height'] == $size_data['height'] ) );

            if ( ! is_wp_error( $resize_result ) && ! $duplicate ) {
                $resized = $this->_save( $this->image );
 
                $this->image->clear();
                $this->image->destroy();
                $this->image = null;
 
                if ( ! is_wp_error( $resized ) && $resized ) {
                    unset( $resized['path'] );
                    $metadata[ $size ] = $resized;
                }
            }
 
            $this->size = $orig_size;
        }
 
        $this->image = $orig_image;
 
        return $metadata;
    }
 
}