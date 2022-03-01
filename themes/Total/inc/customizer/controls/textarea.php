<?php
namespace TotalTheme\Customizer\Controls;
use \WP_Customize_Control;

/**
 * Customizer Textarea Control
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3.1
 */
class Textarea extends WP_Customize_Control {

	/**
	 * The control type
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'wpex-textarea';

	/**
	 * How many rows for the textarea
	 *
	 * @access public
	 * @var string
	 */
	public $rows = '10';

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @access public
	 */
	public function to_json() {
		parent::to_json();

		$this->json['rows'] = $this->rows;
	}

	/**
	 * Renders a JS template for the content of the site icon control.
	 *
	 * @since 5.3.1
	 */
	public function content_template() {

		?>

		<# if ( data.label ) { #>
			<span class="customize-control-title">{{ data.label }}</span>
		<# } #>

		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>

		<textarea rows="{{ data.rows ? data.rows : 10 }}" <?php $this->link(); ?> style="width:100%;"></textarea>

		<?php

	}

}