<?php
/**
 * The Site Settings admin page.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Settings;

defined( 'ABSPATH' ) || exit;

/**
 * A plain Settings API page: WordPress handles the nonce, the capability check on save and the notices.
 */
final class SettingsPage {

	/**
	 * Settings group and page slug.
	 */
	private const SLUG = 'bpch-site-settings';

	/**
	 * Hook suffix of the page, used to load the script only there.
	 *
	 * @var string
	 */
	private $hook_suffix = '';

	/**
	 * Blank ZIP rows shown under the saved ones.
	 */
	private const BLANK_ROWS = 2;

	/**
	 * Hooks the page.
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Adds the top-level menu item. Editing business details is an admin job, hence manage_options.
	 */
	public function add_page(): void {
		$this->hook_suffix = (string) add_menu_page(
			__( 'Site Settings', 'blue-peak-climate-core' ),
			__( 'Site Settings', 'blue-peak-climate-core' ),
			'manage_options',
			self::SLUG,
			array( $this, 'render_page' ),
			'dashicons-store',
			59
		);
	}

	/**
	 * Registers the option, its sections and its fields.
	 */
	public function register_settings(): void {
		register_setting(
			self::SLUG,
			Settings::OPTION,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize' ),
				'default'           => Settings::defaults(),
			)
		);

		$this->add_section( 'business', __( 'Business details', 'blue-peak-climate-core' ), __( 'Shown in the header, footer and contact page.', 'blue-peak-climate-core' ) );
		$this->add_field( 'business', 'legal_name', __( 'Legal business name', 'blue-peak-climate-core' ), 'text' );
		$this->add_field( 'business', 'phone', __( 'Phone', 'blue-peak-climate-core' ), 'tel', __( 'Shown as typed. The click-to-call link is made from its digits.', 'blue-peak-climate-core' ) );
		$this->add_field( 'business', 'email', __( 'Email', 'blue-peak-climate-core' ), 'email', __( 'Public contact email. Quote requests whose ZIP code has no office rule below also go here.', 'blue-peak-climate-core' ) );

		$this->add_section( 'availability', __( 'Hours and service area', 'blue-peak-climate-core' ) );
		$this->add_field( 'availability', 'hours', __( 'Hours', 'blue-peak-climate-core' ), 'text', __( 'One short line, for example "Emergency line open 24/7".', 'blue-peak-climate-core' ) );
		$this->add_field( 'availability', 'service_area', __( 'Service area', 'blue-peak-climate-core' ), 'text', __( 'One short line, for example "Homes across Texas".', 'blue-peak-climate-core' ) );
		$this->add_field( 'availability', 'area_served', __( 'Area served (for search engines)', 'blue-peak-climate-core' ), 'text', __( 'The state, region or city name only, for example "Texas". It goes into the business and service markup that Google reads.', 'blue-peak-climate-core' ) );

		$this->add_section( 'emergency', __( 'Emergency banner', 'blue-peak-climate-core' ) );
		$this->add_field( 'emergency', 'emergency_banner', __( 'Show the emergency banner', 'blue-peak-climate-core' ), 'checkbox', __( 'Turn on during a heat wave or cold snap.', 'blue-peak-climate-core' ) );

		$this->add_section( 'demo', __( 'Demo notice', 'blue-peak-climate-core' ) );
		$this->add_field( 'demo', 'demo_notice', __( 'Footer notice', 'blue-peak-climate-core' ), 'text', __( 'One line shown at the bottom of every page, for a portfolio or staging copy. Leave blank on a real client site to hide it.', 'blue-peak-climate-core' ) );

		$this->add_section( 'social', __( 'Social links', 'blue-peak-climate-core' ), __( 'Full addresses starting with https://. Leave blank to hide a link.', 'blue-peak-climate-core' ) );
		$this->add_field( 'social', 'facebook', __( 'Facebook', 'blue-peak-climate-core' ), 'url' );
		$this->add_field( 'social', 'instagram', __( 'Instagram', 'blue-peak-climate-core' ), 'url' );
		$this->add_field( 'social', 'youtube', __( 'YouTube', 'blue-peak-climate-core' ), 'url' );
		$this->add_field( 'social', 'google_business', __( 'Google Business Profile', 'blue-peak-climate-core' ), 'url' );

		add_settings_section(
			'routing',
			__( 'Quote form routing', 'blue-peak-climate-core' ),
			function () {
				echo '<p>' . esc_html__( 'Send quote requests to the nearest office. A request goes to the rule with the longest matching ZIP start (750 matches 75001). Other ZIP codes go to the main email.', 'blue-peak-climate-core' ) . '</p>';
			},
			self::SLUG
		);
		add_settings_field( 'offices', __( 'Offices by ZIP code', 'blue-peak-climate-core' ), array( $this, 'render_offices' ), self::SLUG, 'routing' );
	}

	/**
	 * Sanitize callback for the option. Passes the saved values so a bad phone or email keeps the old one.
	 *
	 * @param mixed $input Submitted form values.
	 * @return array<string, mixed>
	 */
	public function sanitize( $input ): array {
		$previous = get_option( Settings::OPTION, array() );

		return Settings::sanitize( $input, is_array( $previous ) ? $previous : array() );
	}

	/**
	 * Loads the small "Add row" script on this page only.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_assets( $hook_suffix ): void {
		if ( '' === $this->hook_suffix || $hook_suffix !== $this->hook_suffix ) {
			return;
		}

		wp_enqueue_script(
			'bpch-site-settings',
			BPCH_CORE_URL . 'assets/admin/site-settings.js',
			array(),
			BPCH_CORE_VERSION,
			true
		);
	}

	/**
	 * Prints the page.
	 */
	public function render_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::SLUG );
				do_settings_sections( self::SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Prints one simple field. $args come from add_field().
	 *
	 * @param array<string, string> $args key, type, description.
	 */
	public function render_field( array $args ): void {
		$key   = $args['key'];
		$id    = 'bpch-' . $key;
		$name  = Settings::OPTION . '[' . $key . ']';
		$value = Settings::get( $key );

		if ( 'checkbox' === $args['type'] ) {
			printf(
				'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s> %4$s</label>',
				esc_attr( $id ),
				esc_attr( $name ),
				checked( ! empty( $value ), true, false ),
				esc_html( $args['description'] )
			);
			return;
		}

		printf(
			'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text"%5$s>',
			esc_attr( $args['type'] ),
			esc_attr( $id ),
			esc_attr( $name ),
			esc_attr( is_string( $value ) ? $value : '' ),
			'' === $args['description'] ? '' : ' aria-describedby="' . esc_attr( $id ) . '-description"'
		);

		if ( '' !== $args['description'] ) {
			printf( '<p class="description" id="%s-description">%s</p>', esc_attr( $id ), esc_html( $args['description'] ) );
		}
	}

	/**
	 * Prints the ZIP → office table, with saved rows, a few blank ones, and a template the script copies.
	 */
	public function render_offices(): void {
		$offices = Settings::get( 'offices' );
		$offices = is_array( $offices ) ? array_values( $offices ) : array();
		$blank   = array_fill( 0, self::BLANK_ROWS, array() );
		$rows    = array_merge( $offices, $blank );
		?>
		<table class="widefat striped" style="max-width: 720px;">
			<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'ZIP starts with', 'blue-peak-climate-core' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Office name', 'blue-peak-climate-core' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Email', 'blue-peak-climate-core' ); ?></th>
				</tr>
			</thead>
			<tbody id="bpch-office-rows" data-next-index="<?php echo esc_attr( (string) count( $rows ) ); ?>">
				<?php
				foreach ( $rows as $index => $office ) {
					$this->render_office_row( (string) $index, $office );
				}
				?>
			</tbody>
		</table>
		<template id="bpch-office-template">
			<?php $this->render_office_row( '__INDEX__', array() ); ?>
		</template>
		<p>
			<button type="button" class="button" id="bpch-add-office"><?php esc_html_e( 'Add row', 'blue-peak-climate-core' ); ?></button>
		</p>
		<p class="description"><?php esc_html_e( 'To remove a rule, clear its ZIP start and save. Rows missing a ZIP start or an email are ignored.', 'blue-peak-climate-core' ); ?></p>
		<?php
	}

	/**
	 * Prints one table row of the offices table.
	 *
	 * @param string               $index  Row number, or the __INDEX__ placeholder in the template.
	 * @param array<string,string> $office Saved values, empty for a blank row.
	 */
	private function render_office_row( string $index, array $office ): void {
		$base = Settings::OPTION . '[offices][' . $index . ']';
		?>
		<tr>
			<td><input type="text" inputmode="numeric" maxlength="5" size="6" name="<?php echo esc_attr( $base . '[prefix]' ); ?>" value="<?php echo esc_attr( $office['prefix'] ?? '' ); ?>" aria-label="<?php esc_attr_e( 'ZIP starts with', 'blue-peak-climate-core' ); ?>"></td>
			<td><input type="text" class="regular-text" name="<?php echo esc_attr( $base . '[name]' ); ?>" value="<?php echo esc_attr( $office['name'] ?? '' ); ?>" aria-label="<?php esc_attr_e( 'Office name', 'blue-peak-climate-core' ); ?>"></td>
			<td><input type="email" class="regular-text" name="<?php echo esc_attr( $base . '[email]' ); ?>" value="<?php echo esc_attr( $office['email'] ?? '' ); ?>" aria-label="<?php esc_attr_e( 'Office email', 'blue-peak-climate-core' ); ?>"></td>
		</tr>
		<?php
	}

	/**
	 * Adds a settings section on this page.
	 *
	 * @param string $id          Section id.
	 * @param string $title       Heading.
	 * @param string $description Optional intro text.
	 */
	private function add_section( string $id, string $title, string $description = '' ): void {
		add_settings_section(
			$id,
			$title,
			'' === $description ? '__return_false' : function () use ( $description ) {
				echo '<p>' . esc_html( $description ) . '</p>';
			},
			self::SLUG
		);
	}

	/**
	 * Adds one simple field.
	 *
	 * @param string $section     Section id.
	 * @param string $key         Setting key.
	 * @param string $label       Field label.
	 * @param string $type        Input type: text, tel, email, url or checkbox.
	 * @param string $description Help text under the input.
	 */
	private function add_field( string $section, string $key, string $label, string $type, string $description = '' ): void {
		add_settings_field(
			$key,
			$label,
			array( $this, 'render_field' ),
			self::SLUG,
			$section,
			array(
				'label_for'   => 'bpch-' . $key,
				'key'         => $key,
				'type'        => $type,
				'description' => $description,
			)
		);
	}
}
