<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wemiura.com
 * @since      1.0.0
 *
 * @package    Wp_trova_prezzi_free
 * @subpackage Wp_trova_prezzi_free/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_trova_prezzi_free
 * @subpackage Wp_trova_prezzi_free/admin
 * @author     WeMiura <info@wemiura.com>
 */
class Wp_trova_prezzi_free_Admin {


	private $option_name = 'wp_trovaprezzi_';
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles($hook) {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_trova_prezzi_free_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_trova_prezzi_free_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		if( $hook !== 'settings_page_wp-trova-prezzi-free' ) {
			return;
		}

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-trova-prezzi-free-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'skins', plugin_dir_url( __FILE__ ) . 'skins/all.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery.pwstabs.min', plugin_dir_url( __FILE__ ) . 'css/jquery.pwstabs.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {
		if( $hook !== 'settings_page_wp-trova-prezzi-free' ) {
			return;
		}
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_trova_prezzi_free_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_trova_prezzi_free_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-trova-prezzi-free-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('icheck.min', plugin_dir_url( __FILE__ ) . 'js/icheck.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery.pwstabs.min.js', plugin_dir_url( __FILE__ ) . 'js/jquery.pwstabs.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery.tablesorter.min', plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('jquery.tablesorter.pager', plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.pager.js', array( 'jquery' ), $this->version, false );

	}

	public function create_wp_trovaprezzi_metabox(){

		add_meta_box(
			'wp_trovaprezzi_metabox',
			'TrovaPrezzi',
			 array( $this,'display_wp_trovaprezzi_metabox') ,
			'product',
			'normal',
			'default'
		);
	}

	public function display_wp_trovaprezzi_metabox( $post ) {

		$product_wp_trovaprezzi = 0;
		wp_nonce_field( 'wp_trovaprezzi_metabox', 'wp_trovaprezzi_metabox_nonce' );
		$product_wp_trovaprezzi  = get_post_meta( $post->ID, 'product_wp_trovaprezzi', true );
		$taxonomy_selected_for_category = get_option('wp_trovaprezzi_category_tp_settings');
		$taxonomy_excluded_for_category = get_post_meta($post->ID,'wp_trovaprezzi_exclude_tax_product_settings',true);

		echo '<div class="wrap">';
		echo '<label for="product_wp_trovaprezzi"><input class="text" type="checkbox" id="product_wp_trovaprezzi" name="product_wp_trovaprezzi" value="" ';
		echo 'disabled="disabled"';
		echo '/>';
		echo _e( 'Prodotto in Trova Prezzi', 'wp-trova-prezzi-free' ) . '</label> <br/>';
		echo '<h4>'.__('Escludi dalla creazione dell\' albero delle categorie di TrovaPrezzi','wp-trova-prezzi-free').'</h4>';

		$taxonomy_product = get_object_taxonomies('product', 'objects');

		foreach($taxonomy_product as $key => $single_taxonomy_product) {
			$class = 'show_wp_tp_exclude_cat';
			if($key != 'product_cat'){
			  $class = 'hide_wp_tp_exclude_cat hidden';
			}
			echo '<ul class="children '.$class.'">';
			if($single_taxonomy_product->name!='product_tag' && $single_taxonomy_product->name != 'product_shipping_class' && $single_taxonomy_product->name !='product_type' && $single_taxonomy_product->name!='brand_wp_tp') {
			$selected_cats = array();


			$args_exclude_tax = array(
				'descendants_and_self' => 0,
				'selected_cats' => array(),
				'popular_cats' => false,
				'walker' => new Walker_Taxonomy_Wp_Trovaprezzi_Exclude_Tax_Product,
				'taxonomy' => $key,
				'checked_ontop' => true
			);
			echo '<h5>'.$single_taxonomy_product->labels->name.'</h5>';
			wp_terms_checklist(0, $args_exclude_tax);
		}
		echo '</ul>';
		}

		echo '<h4>Brand TrovaPrezzi</h4>';

		echo '<select name="wp_trovaprezzi_brand" disabled="disabled">';
		echo '<option value="0">'.__('Seleziona un Brand','wp-trova-prezzi-free').'</option>';
		echo '</select>';
		echo '<br />';
		echo '<h4>'.__('FUNZIONALITÀ DISPONIBILE NELLA ','wp-trova-prezzi-free').'<a href="http://www.wemiura.com/wp-trovaprezzi/" target="_blank">'.__(' VERSIONE PREMIUM ','wp-trova-prezzi-free').'</a>'.'</h4>';
		echo '</div>';

	}

	function set_wp_trovaprezzi_wp_list_table(){
		?>
<script type="text/javascript">
	jQuery(document).ready( function(){
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		jQuery('.set_wp_trovaprezzi_wp_list_table').on('click', function(){
			var current_tp = jQuery(this);
			var data_id = current_tp.attr('data-id');
			var data = {
				action: 'set_wp_trovaprezzi_wp_list_table_ajax',
				data_id: data_id
			};
			jQuery.post(ajaxurl, data, function(response) {
				current_tp.html(response);
			});
		});
	});
</script>
<?php
	}

	function set_wp_trovaprezzi_wp_list_table_ajax_callback(){
		$active = 0;
		$data_id = $_POST['data_id'];
		$product_wp_trovaprezzi  = get_post_meta( $data_id, 'product_wp_trovaprezzi', true );
		if((isset($product_wp_trovaprezzi) && $product_wp_trovaprezzi == 0) || !isset($product_wp_trovaprezzi)){
			$active = 1;
		}

		if($active == 0){

			update_post_meta( $data_id, 'product_wp_trovaprezzi', 0 );
			echo '<img src="'.plugin_dir_url( __FILE__ ) . 'img/tp24_off.png'.'" />';
		}
		else{

			update_post_meta( $data_id, 'product_wp_trovaprezzi', 1 );
			echo '<img src="'.plugin_dir_url( __FILE__ ) . 'img/tp24_on.png'.'" />';
		}

		die();
	}

	public function add_filter_trovaprezzi()
	{
		global $typenow;
		$selected = '';
		if(isset($_GET['filter_wp_trovaprezzi']) && $_GET['filter_wp_trovaprezzi'] == 1){
			$selected = 'selected';
		}


		if( $typenow == 'product' )
		{
echo '<select name="filter_wp_trovaprezzi">
<option value="">Filtra per TrovaPrezzi</option>
<option value="1" '.$selected.'>Presenti su TrovaPrezzi</option>
</select>';

    }
}

	public function run_filter_trovaprezzi( $query )
	{
		global $typenow;
		global $pagenow;
if(isset($_GET['filter_wp_trovaprezzi'])){
		if( $pagenow == 'edit.php' && $typenow == 'product' && $_GET['filter_wp_trovaprezzi'] )
		{
			$query->query_vars[ 'meta_key' ] = 'product_wp_trovaprezzi';
			$query->query_vars[ 'meta_value' ] = (int)$_GET['filter_wp_trovaprezzi'];
		}
}
	}

	public function add_options_page() {


		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'TrovaPrezzi', 'wp-trova-prezzi-free' ),
			__( 'TrovaPrezzi', 'wp-trova-prezzi-free' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'wp_trovaprezzi_display_options_page' )
		);

	}

	public function wp_trovaprezzi_display_options_page()
	{
		?>

		<div class="wrap">
			<h2></h2>
			<div class="wp_trovaprezzi_logo_tp">
				<img width="200" src="<?php echo plugin_dir_url(__FILE__).'/img/logo_tp.png'; ?>" />
			</div>

		<?php
		if (!isset($_GET['wp_trovaprezzi_page'])) {
		?>
			<div class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active"
			   href="<?php echo admin_url() ?>options-general.php?page=wp-trova-prezzi-free&wp_trovaprezzi_page=settings"><span class="dashicons wp_tp_dashicons  dashicons-admin-generic"></span> <?php _e('Impostazioni','wp-trova-prezzi-free'); ?></a>
			<a class="nav-tab"
			   href="<?php echo admin_url() ?>options-general.php?page=wp-trova-prezzi-free&wp_trovaprezzi_page=products"><span class="dashicons wp_tp_dashicons  dashicons-visibility"></span> <?php _e('Prodotti su TrovaPrezzi','wp-trova-prezzi-free'); ?></a>
			</div>
		<?php
			include_once 'partials/wp-trova-prezzi-free-admin-display.php';
		}
		else{
		if ($_GET['wp_trovaprezzi_page'] == 'settings') {
			?>
			<div class="nav-tab-wrapper">
			<a class="nav-tab nav-tab-active"
			   href="<?php echo admin_url() ?>options-general.php?page=wp-trova-prezzi-free&wp_trovaprezzi_page=settings"><span class="dashicons wp_tp_dashicons  dashicons-admin-generic"></span> <?php _e('Impostazioni','wp-trova-prezzi-free'); ?></a>
			<a class="nav-tab"
			   href="<?php echo admin_url() ?>options-general.php?page=wp-trova-prezzi-free&wp_trovaprezzi_page=products"><span class="dashicons wp_tp_dashicons  dashicons-visibility"></span><?php _e('Prodotti su TrovaPrezzi','wp-trova-prezzi-free'); ?></a>
			</div>
			<?php
			include_once 'partials/wp-trova-prezzi-free-admin-display.php';
		} else if ($_GET['wp_trovaprezzi_page'] == 'products') {
			?>
			<div class="nav-tab-wrapper">
			<a class="nav-tab"
			   href="<?php echo admin_url() ?>options-general.php?page=wp-trova-prezzi-free&wp_trovaprezzi_page=settings"><span class="dashicons wp_tp_dashicons  dashicons-admin-generic"></span> <?php _e('Impostazioni','wp-trova-prezzi-free'); ?></a>
			<a class="nav-tab nav-tab-active"
			   href="<?php echo admin_url() ?>options-general.php?page=wp-trova-prezzi-free&wp_trovaprezzi_page=products"><span class="dashicons wp_tp_dashicons  dashicons-visibility"></span> <?php _e('Prodotti su TrovaPrezzi','wp-trova-prezzi-free'); ?></a>
		</div>
			<?php
			include_once 'partials/wp-trova-prezzi-free-admin-display-products.php';
		}
	}
			 ?>



		</div>
		<?php


	}




	public function register_settings(){
		//general
		add_settings_section(
		$this->option_name .'general',
		__( '', 'wp-trova-prezzi-free' ),
		array( $this,$this->option_name .'general'),
		$this->plugin_name.'_general'
	);
		add_settings_field(
			$this->option_name . 'general_settings',
			__( 'Voglio impostare i prodotti per TrovaPrezzi:', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'general_settings_cb' ),
			$this->plugin_name.'_general',
			$this->option_name . 'general',
			array( 'label_for' => $this->option_name . 'general_settings' )
		);

		//sleect
		add_settings_section(
			$this->option_name .'select',
			__( 'Seleziona', 'wp-trova-prezzi-free' ),
			array( $this,$this->option_name .'select'),
			$this->plugin_name.'_select'
		);

		add_settings_field(
			$this->option_name . 'select_tax_settings',
			__( 'Inserisci nel feed i prodotti appartenenti alle seguenti tassonomie:', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'select_tax_settings_cb' ),
			$this->plugin_name.'_select',
			$this->option_name . 'select',
			array( 'label_for' => $this->option_name . 'select_tax_settings' )
		);
		add_settings_field(
			$this->option_name . 'select_status_settings',
			__( 'Voglio aggiungere i prodotti:', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'select_status_settings_cb' ),
			$this->plugin_name.'_select',
			$this->option_name . 'select',
			array( 'label_for' => $this->option_name . 'select_status_settings' )
		);

		//feed
		add_settings_section(
			$this->option_name .'feed',
			__( 'Impostazioni Feed', 'wp-trova-prezzi-free' ),
			array( $this,$this->option_name .'feed'),
			$this->plugin_name.'_feed'
		);
		add_settings_field(
			$this->option_name . 'category_tp_settings',
			__( 'Seleziona la Tassonomia per la creazione dell\'albero delle categorie di TrovaPrezzi', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'category_tp_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'category_tp_settings' )
		);
		add_settings_field(
			$this->option_name . 'category_tp_exclude_settings',
			__( 'Categoria da escludere a prescindere dall\' albero delle categorie', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'category_tp_exclude_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'category_tp_exclude_settings' )
		);
		add_settings_field(
			$this->option_name . 'brand_settings',
			__( 'Seleziona il valore Brand:', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'brand_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'brand_settings' )
		);
		add_settings_field(
			$this->option_name . 'code_settings',
			__( 'Quale valore associare al tag "Code":', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'code_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'code_settings' )
		);
		add_settings_field(
			$this->option_name . 'description_settings',
			__( 'Quale Description mostrare ?', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'description_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'description_settings' )
		);
		add_settings_field(
			$this->option_name . 'eancode_settings',
			__( 'Quale valore associare al tag "EanCode":', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'eancode_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'eancode_settings' )
		);
		add_settings_field(
			$this->option_name . 'part_number_settings',
			__( 'Quale valore associare al tag "PartNumber":', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'part_number_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'part_number_settings' )
		);
		add_settings_field(
			$this->option_name . 'shipping_cost_settings',
			__( 'Selezionare il costo di spedizione:', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'shipping_cost_settings_cb' ),
			$this->plugin_name.'_feed',
			$this->option_name . 'feed',
			array( 'label_for' => $this->option_name . 'shipping_cost_settings' )
		);


		//filter
		add_settings_section(
			$this->option_name .'filter',
			__( 'Impostazioni Filtri', 'wp-trova-prezzi-free' ),
			array( $this,$this->option_name .'filter'),
			$this->plugin_name.'_filter'
		);
		add_settings_field(
			$this->option_name . 'price_filter_settings',
			__( 'Filtra per prezzo', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'price_filter_settings_cb' ),
			$this->plugin_name.'_filter',
			$this->option_name . 'filter',
			array( 'label_for' => $this->option_name . 'price_filter_settings' )
		);
		add_settings_field(
			$this->option_name . 'stock_filter_settings',
			__( 'Filtra per Quantità', 'wp-trova-prezzi-free' ),
			array( $this, $this->option_name . 'stock_filter_settings_cb' ),
			$this->plugin_name.'_filter',
			$this->option_name . 'filter',
			array( 'label_for' => $this->option_name . 'stock_filter_settings' )
		);

		// xml and document
		add_settings_section(
			$this->option_name .'info',
			__( 'FEED GENERATO', 'wp-trova-prezzi-free' ),
			array( $this,$this->option_name .'info'),
			$this->plugin_name.'_xml_document'
		);

		register_setting( $this->plugin_name, $this->option_name . 'general_settings', array( $this, $this->option_name . 'sanitize_general_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'select_tax_settings', array( $this, $this->option_name . 'sanitize_select_tax_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'select_status_settings', array( $this, $this->option_name . 'sanitize_select_status_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'category_tp_settings', array( $this, $this->option_name . 'sanitize_category_tp_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'category_tp_exclude_settings', array( $this, $this->option_name . 'sanitize_category_tp_exclude_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'brand_settings', array( $this, $this->option_name . 'sanitize_brand_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'code_settings', array( $this, $this->option_name . 'sanitize_code_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'description_settings', array( $this, $this->option_name . 'sanitize_description_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'eancode_settings', array( $this, $this->option_name . 'sanitize_eancode_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'part_number_settings', array( $this, $this->option_name . 'sanitize_part_number_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'shipping_cost_settings', array( $this, $this->option_name . 'sanitize_shipping_cost_settings' ) );

		register_setting( $this->plugin_name, $this->option_name . 'custom_shipping_cost_settings', array( $this, $this->option_name . 'sanitize_custom_shipping_cost_settings' ) );

		register_setting( $this->plugin_name, $this->option_name . 'price_filter_settings', array( $this, $this->option_name . 'sanitize_price_filter_settings' ) );
		register_setting( $this->plugin_name, $this->option_name . 'stock_filter_settings', array( $this, $this->option_name . 'sanitize_stock_filter_settings' ) );




	}
	public function wp_trovaprezzi_general() {
		echo '<h2>'.__('Impostazioni Generali','wp-trova-prezzi-free').'</h2>';
		echo '<p>'.
		__('Da qui è possibile decidere se impostare i prodotti da esportare su TrovaPrezzi in maniera automatica o manuale.','wp-trova-prezzi-free').'<br />'.
		__('Nel caso in cui si scelga la gestione automatica, sarà possibile scegliere quali prodotti esportare','wp-trova-prezzi-free').'<br />'.
		__('su TrovaPrezzi tramite più possibilità di scelta: per categoria, tipologia, classi di spedizione, tags, attributi ed eventuali custom taxonomies.','wp-trova-prezzi-free').'<br />'.
		__('Se invece, si sceglie la gesione manuale, verranno esportati solo i prodotti selezionati manualmente','wp-trova-prezzi-free').'<br />'.
		__('dall\' ','wp-trova-prezzi-free').
		__('elenco prodotti ','wp-trova-prezzi-free').
		'<a href="http://www.wemiura.com/wp-trovaprezzi/" target="_blank">'.__('( versione premium )','wp-trova-prezzi-free').'</a>'.
		__(' cliccando sull\' apposito flag','wp-trova-prezzi-free').'<b><< TP >></b>'.
		__('o selezionando, all\' interno di ciascun prodotto, il check "Prodotto in TrovaPrezzi"','wp-trova-prezzi-free').'</p>';
	}
	public function wp_trovaprezzi_select() {
		echo '<p>'.
		__('Seleziona , sfogliando i vari tab, i prodotti che vuoi esportare su TrovaPrezzi.','wp-trova-prezzi-free').'<br />'.
		__('Puoi selezionarli per categorie, tag, tag prodotto, classi spedizione, attributi ed eventuali nuove tassonomie.','wp-trova-prezzi-free').' <br />'.
		__('Inoltre puoi selezionare i prodotti per Brands grazie a "Brand Trovaprezzi". Puoi impostare il Brand all\' interno di ogni prodotto','wp-trova-prezzi-free').'<br/>'.
		__('Puoi scegliere anche di aggiungere i prodotti In Saldo e/o In Vetrina','wp-trova-prezzi-free').'</p>';
	}
	public function wp_trovaprezzi_feed() {
		echo '<p>'.
		__( 'Impostazioni per la creazione del Feed. Seleziona i valori che vuoi mostrare.','wp-trova-prezzi-free').'<br />'.
		__('Il Feed ed il CSV saranno costituiti da paramentri a cui è necessario associare dei valori.','wp-trova-prezzi-free').'<br />'.
		__('Da questa sezione è possibile scegliere quale valore associare a ciascun parametro.', 'wp-trova-prezzi-free' ).'</p>';
	}
	public function wp_trovaprezzi_filter() {
		echo '<p>'.
		__( 'Da questa sezione sarà possibile, se lo si ritiene necessario, effettuare una raffinazione ed un filtro dei prodotti da esportare nel Feed ( o CSV ) di Trovaprezzi.', 'wp-trova-prezzi-free' ).'</p>';
	}
	public function wp_trovaprezzi_info() {
		echo '<div class="wp_trovaprezzi_download"><a href="#" class="wp_trovaprezzi_download_csv">'.
		__( 'Download CSV ', 'wp-trova-prezzi-free' ).'</a></div>';
		echo '<div class="wp_trovaprezzi_warning-csv">'.
		__('ATTENZIONE: se il download del CSV non dovesse andare a buon fine, assicurarsi di impostare il WP Memory Limit a 256 MB.','wp-trova-prezzi')
			.'<br /><a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">'.
			__('Maggiori Informazioni.','wp-trova-prezzi')
			.'</a></div>';
		echo '<div class="wp_trovaprezzi_download"><a onclick="return false;" href="#" class="wp_trovaprezzi_feed_csv" disabled="disabled">'.
		__( 'Feed XML ', 'wp-trova-prezzi-free' ).'</a><span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/">'.__( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ).'</a></span><div>

		<br /><b><u>'.
		__('IMPORTANTE:','wp-trova-prezzi-free').'</u></b> '.
		__('Ricordati che alla ','wp-trova-prezzi-free').'<b>'.
		__('prima','wp-trova-prezzi-free').'</b>'.
		__(' generazione del Feed XML sarà necessario ','wp-trova-prezzi-free').'<a target="_blank" href="'.get_admin_url().'options-permalink.php">'.
		__('rigenerare i Permalink.','wp-trova-prezzi-free').'</a></div></div>';
	}

	public function wp_trovaprezzi_general_settings_cb() {
		?>
		<fieldset>
			<label>
				<input type="radio" name="<?php echo $this->option_name . 'general_settings' ?>" id="<?php echo $this->option_name . 'general_settings' ?>" value="automatic" checked="checked">
				<span class="checkable"><?php _e( 'Gestisci Automaticamente', 'wp-trova-prezzi-free' ); ?></span>
			</label>
			<br>
			<label>
				<input type="radio" name="<?php echo $this->option_name . 'general_settings' ?>" value="manual" disabled="disabled">
				<span class="checkable"><?php _e( 'Gestisci Manualmente', 'wp-trova-prezzi-free' ); ?></span><span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
			</label>
		</fieldset>
	<?php

	}
	public function wp_trovaprezzi_select_tax_settings_cb() {
		$select_tax_settings = get_option( $this->option_name . 'select_tax_settings' );
		$taxonomy_product = get_object_taxonomies('product', 'objects');
		if ($select_tax_settings){
			$selected_cats = array($select_tax_settings);
		}else {
			$selected_cats = false;
		}

		$args = array(
			'descendants_and_self' => 0,
			'selected_cats' => $selected_cats,
			'popular_cats' => false,
			'walker' => new Walker_Taxonomy_Wp_Trovaprezzi_Checklist_Widget,
			'taxonomy' => 'product_cat',
			'checked_ontop' => false
		);

		echo '<div class="trovaprezzi_row trovaprezzi_row_feed_tax">';

		echo '<div data-pws-tab="' . __('Categorie Prodotto','wp-trova-prezzi-free') . '" data-pws-tab-name="' . __('Categorie Prodotto','wp-trova-prezzi-free') . '">';
		echo '<div class="trovaprezzi_col">';
		echo '<ul class="select_tax_settings">';
		echo '<h3>'.__('Categorie Prodotto','wp-trova-prezzi-free').'</h3>';
		wp_terms_checklist(0, $args);
		echo '</ul>';
		echo '</div>';
		echo '</div>'; // tab

		foreach($taxonomy_product as $key => $single_taxonomy_product) {
			if ($single_taxonomy_product->name != 'product_cat'){
				if (wp_count_terms($key) > 0) {
					echo '<div data-pws-tab="' . $single_taxonomy_product->labels->name . '" data-pws-tab-name="' . $single_taxonomy_product->labels->name . '">';
					echo '<div class="trovaprezzi_col">';
					echo '<ul class="select_tax_settings">';
					echo '<h3>' . $single_taxonomy_product->labels->name . '</h3>';
					echo '<span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/">' . __('Disponibile nella versione Premium', 'wp-trova-prezzi-free') . '</a></span>';
					echo '</ul>';
					echo '</div>';
					echo '</div>'; // tab
				}
		}
		}
		echo '</div>';
		?>

	<?php
echo '<h4>'.__('Nella ','wp-trova-prezzi-free').'<a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/">'.__('VERSIONE PREMIUM','wp-trova-prezzi-free').'</a>'.__(' è possibile anche selezionare più termini della stessa tassonomia ','wp-trova-prezzi-free').'</h4>';
	}
	public function wp_trovaprezzi_select_status_settings_cb() {
		?>
		<ul class="select_status_settings">
			<li class="product_status">
				<label>
					<input type="checkbox" name="" value="" disabled="disabled"> <span class="checkable"><?php _e( 'In Saldo', 'wp-trova-prezzi-free' ); ?></span>
					<span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
				</label>
			</li>
			<li class="product_status">
				<label class="selectit">
					<input  type="checkbox" name="" value="" disabled="disabled"> <span class="checkable"><?php _e( 'In Vetrina', 'wp-trova-prezzi-free' ); ?></span>
					<span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
				</label>
			</li>
		</ul>
	<?php

	}
	public function wp_trovaprezzi_category_tp_settings_cb()
	{
		$taxonomy_product = get_object_taxonomies('product', 'objects');

		?>
		<ul class="category_tp_settings">
			<li class="product_status">
				<label class="selectit">
					<input type="radio" name="" value="" checked="checked">
					<span class="checkable"><?php _e('Categorie Prodotto', 'wp-trova-prezzi-free'); ?></span>
				</label>
			</li>
			<?php foreach ($taxonomy_product as $key => $single_taxonomy_product) {
				if ($single_taxonomy_product->name != 'product_tag' && $single_taxonomy_product->name != 'product_shipping_class' && $single_taxonomy_product->name != 'product_type' && $single_taxonomy_product->name != 'product_cat') {
					?>

					<li class="product_status">
						<label class="selectit">
							<input type="radio" name="" value="" disabled="disabled">
							<span
								class="checkable"><?php _e($single_taxonomy_product->labels->name, 'wp-trova-prezzi-free'); ?></span>
							<span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
						</label>
					</li>
				<?php
				}
			} ?>
		</ul>

	<?php
	}


	public function wp_trovaprezzi_category_tp_exclude_settings_cb()
	{
			echo '<ul id="wp_tax_esclude_product_cat" class="wp_tax_esclude children ">';
				$args_exclude_tax = array(
					'descendants_and_self' => 0,
					'selected_cats' => array(),
					'popular_cats' => false,
					'walker' => new Walker_Taxonomy_Wp_Trovaprezzi_Exclude_Tax,
					'taxonomy' => 'product_cat',
					'checked_ontop' => true
				);
				echo '<h5>' .__('Categorie Prodotto','wp-trova-prezzi-free') . '</h5>';
				wp_terms_checklist(0, $args_exclude_tax);

			echo '</ul>';
			?>
		<span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
		<?php
	}

	public function wp_trovaprezzi_brand_settings_cb()
	{
		$brand_settings = get_option($this->option_name . 'brand_settings');
		$taxonomy_product = get_object_taxonomies('product', 'objects');

		?>
		<ul class="brand_settings">
			<?php _e('TASSONOMIE','wp-trova-prezzi-free'); ?>
		<?php foreach ($taxonomy_product as $key => $single_taxonomy_product) {
			if ($key != 'brand_wp_tp') {
				?>
				<li class="product_status">
					<label class="selectit">
						<input type="radio" name="<?php echo $this->option_name . 'brand_settings' ?>[choice]"
							   value="<?php echo $key; ?>" <?php checked($brand_settings['choice'], $key, 'selected'); ?>>
						<span class="checkable"><?php _e($single_taxonomy_product->labels->name, 'wp-trova-prezzi-free'); ?></span>
					</label>
				</li>
			<?php } else {
				?>

				<?php } } ?>
			<h5><?php _e('OPPURE','wp-trova-prezzi-free'); ?></h5>
			<li class="product_status">
				<label class="selectit">
					<input type="radio" name="<?php echo $this->option_name . 'brand_settings' ?>[choice]"
						   value="" disabled="disabled">
					<b><span class="checkable"><?php _e('Nessuna, utilizzo "Brand TrovaPrezzi" ( consigliata )', 'wp-trova-prezzi-free'); ?></span></b><span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
				</label>
			</li>
				<li class="product_status">
					<label class="selectit">
						<input type="radio" name="<?php echo $this->option_name . 'brand_settings' ?>[choice]"
							   value="custom_post_meta" <?php checked($brand_settings['choice'], 'custom_post_meta', 'selected'); ?>>
						<span class="checkable"><?php _e('Un custom post meta', 'wp-trova-prezzi-free'); ?></span>
					</label>
				</li>
				<ul class="custom_post_meta_value_settings <?php if($brand_settings['choice'] == 'custom_post_meta'){ echo 'visible'; } ?>">
				<li>
					Custom Post Meta: <input type="text" name="<?php echo $this->option_name . 'brand_settings' ?>[value]" value="<?php echo $brand_settings['value']; ?>" /> <span class="wp_tp_description"> ( es. my_custom_post_meta )</span>
				</li>
		</ul>
		</ul>
	<?php

	}
	public function wp_trovaprezzi_code_settings_cb() {

		$code_settings = get_option($this->option_name . 'code_settings');
		$attributes = wc_get_attribute_taxonomies();
		?>
		<ul class="code_settings">
			<li class="product_status">
				<label class="selectit">
					<input  type="radio" name="<?php echo $this->option_name . 'code_settings' ?>[choice]" value="_sku" <?php checked($code_settings['choice'],"_sku",'selected'); ?>> <b><span class="checkable"><?php _e( 'COD ( _sku )  ( consigliata )', 'wp-trova-prezzi-free' ); ?></span></b>
				</label>
			</li>
			<li class="product_status">
				<label class="selectit">
					<input  type="radio" name="<?php echo $this->option_name . 'code_settings' ?>[choice]" value="product_id" <?php checked($code_settings['choice'],"product_id",'selected'); ?>> <span class="checkable"><?php _e( 'ID Prodotto', 'wp-trova-prezzi-free' ); ?></span>
				</label>
			</li>
			<h5><?php _e('ATTRIBUTI','wp-trova-prezzi-free'); ?></h5>
			<?php
			foreach($attributes as $attributo){
				?>
				<li class="product_status">
					<label class="selectit">
						<input  type="radio" name="<?php echo $this->option_name . 'code_settings' ?>[choice]" value="<?php echo wc_attribute_taxonomy_name($attributo->attribute_name); ?>" <?php checked($code_settings['choice'],wc_attribute_taxonomy_name($attributo->attribute_name),'selected'); ?>>
						<span class="checkable"><?php _e( $attributo->attribute_label, 'wp-trova-prezzi-free' ); ?></span>
					</label>
				</li>
			<?php } ?>
			<?php _e('OPPURE','wp-trova-prezzi-free'); ?>
			<li class="product_status">
				<label class="selectit">
					<input type="radio" name="<?php echo $this->option_name . 'code_settings' ?>[choice]"
							 value="custom_post_meta" <?php checked($code_settings['choice'], 'custom_post_meta', 'selected'); ?>>
					<span class="checkable"><?php _e('Un custom post meta', 'wp-trova-prezzi-free'); ?></span>
				</label>
			</li>
			<ul class="code_custom_post_meta_value_settings <?php if($code_settings['choice'] == 'custom_post_meta'){ echo 'visible'; } ?>">
			<li>
				Custom Post Meta: <input type="text" name="<?php echo $this->option_name . 'code_settings' ?>[value]" value="<?php echo $code_settings['value']; ?>" /> <span class="wp_tp_description"> ( es. my_custom_post_meta )</span>
			</li>
		</ul>
		</ul>

	<?php

	}
	public function wp_trovaprezzi_description_settings_cb() {

		$description_settings = get_option($this->option_name . 'description_settings');
		?>
		<ul class="description_settings">
			<label class="selectit">
			<li class="product_status">

					<input  type="radio" name="<?php echo $this->option_name . 'description_settings' ?>" value="short" <?php checked($description_settings,"short",'selected'); ?>>
				<b><span class="checkable"><?php _e( 'Breve descrizione ( consigliata )', 'wp-trova-prezzi-free' ); ?></span></b>

			</li>
			</label>
			<label class="selectit">
			<li class="product_status">

					<input  type="radio" name="<?php echo $this->option_name . 'description_settings' ?>" value="long" <?php checked($description_settings,"long",'selected'); ?>>
				<span class="checkable"><?php _e( 'Descrizione Estesa ', 'wp-trova-prezzi-free' ); ?></span>

			</li>
				</label>
		</ul>

	<?php

	}
	public function wp_trovaprezzi_eancode_settings_cb() {

		$eancode_settings = get_option($this->option_name . 'eancode_settings');
		$attributes = wc_get_attribute_taxonomies();
		//echo '<pre>'; print_r($attributes); echo '</pre>';
		?>
		<ul class="eancode_settings">
			<label class="selectit">
			<li class="product_status">

					<input  type="radio" name="<?php echo $this->option_name . 'eancode_settings' ?>[choice]" value="_sku" <?php checked($eancode_settings['choice'],"_sku",'selected'); ?>> <b><span class="checkable"><?php _e( 'COD ( _sku )  ( consigliata )', 'wp-trova-prezzi-free' ); ?></span></b>
			</li>
				</label>
				<label class="selectit">
					<li class="product_status">
						<label class="selectit">
							<input  type="radio" name="<?php echo $this->option_name . 'eancode_settings' ?>[choice]" value="product_id" <?php checked($eancode_settings['choice'],"product_id",'selected'); ?>> <span class="checkable"><?php _e( 'ID Prodotto', 'wp-trova-prezzi-free' ); ?></span>
						</label>
					</li>
				</label>
				<h5><?php _e('ATTRIBUTI','wp-trova-prezzi-free'); ?></h5>
			<?php foreach($attributes as $attributo){ ?>
			<label class="selectit">
				<li class="product_status">
						<input  type="radio" name="<?php echo $this->option_name . 'eancode_settings' ?>[choice]" value="<?php echo wc_attribute_taxonomy_name($attributo->attribute_name); ?>" <?php checked($eancode_settings['choice'],wc_attribute_taxonomy_name($attributo->attribute_name),'selected'); ?>>
					<span class="checkable"><?php _e( $attributo->attribute_label, 'wp-trova-prezzi-free' ); ?></span>
				</li>
				</label>
			<?php } ?>
			<?php _e('OPPURE','wp-trova-prezzi-free'); ?>
			<li class="product_status">
				<label class="selectit">
					<input type="radio" name="<?php echo $this->option_name . 'eancode_settings' ?>[choice]"
							 value="custom_post_meta" <?php checked($eancode_settings['choice'], 'custom_post_meta', 'selected'); ?>>
					<span class="checkable"><?php _e('Un custom post meta', 'wp-trova-prezzi-free'); ?></span>
				</label>
			</li>
			<ul class="eancode_custom_post_meta_value_settings <?php if($eancode_settings['choice'] == 'custom_post_meta'){ echo 'visible'; } ?>">
			<li>
				Custom Post Meta: <input type="text" name="<?php echo $this->option_name . 'eancode_settings' ?>[value]" value="<?php echo $eancode_settings['value']; ?>" /> <span class="wp_tp_description"> ( es. my_custom_post_meta )</span>
			</li>
		</ul>
		<li class="product_status">
			<label class="selectit">
				<input type="radio" name="<?php echo $this->option_name . 'eancode_settings' ?>[choice]"
						 value="0" <?php checked($eancode_settings['choice'], '0', 'selected'); ?>>
				<span class="checkable"><?php _e('Non specificare EanCode.', 'wp-trova-prezzi-free'); ?></span>
			</label>
		</li>
		</ul>

	<?php

	}
	public function wp_trovaprezzi_part_number_settings_cb() {

		$part_number_settings = get_option($this->option_name . 'part_number_settings');
		$attributes = wc_get_attribute_taxonomies();
		?>
		<ul class="part_number_settings">
			<label class="selectit">
			<li class="product_status">
					<input  type="radio" name="<?php echo $this->option_name . 'part_number_settings' ?>[choice]" value="_sku" <?php checked($part_number_settings['choice'],"_sku",'selected'); ?>> <b><span class="checkable"><?php _e( 'COD ( _sku )  ( consigliata )', 'wp-trova-prezzi-free' ); ?></span></b>
			</li>
				</label>
			<label class="selectit">
			<li class="product_status">
				<input  type="radio" name="<?php echo $this->option_name . 'part_number_settings' ?>[choice]" value="product_id" <?php checked($part_number_settings['choice'],"product_id",'selected'); ?>> <span class="checkable"><?php _e( 'ID Prodotto', 'wp-trova-prezzi-free' ); ?></span>
			</li>
				</label>
				<h5><?php _e('ATTRIBUTI','wp-trova-prezzi-free'); ?></h5>
			<?php foreach($attributes as $attributo){ ?>
			<label class="selectit">
				<li class="product_status">
						<input  type="radio" name="<?php echo $this->option_name . 'part_number_settings' ?>[choice]" value="<?php echo wc_attribute_taxonomy_name($attributo->attribute_name); ?>" <?php checked($part_number_settings['choice'],wc_attribute_taxonomy_name($attributo->attribute_name),'selected'); ?>><span class="checkable"><?php _e( $attributo->attribute_label, 'wp-trova-prezzi-free' ); ?></span>
				</li>
				</label>
			<?php } ?>
			<?php _e('OPPURE','wp-trova-prezzi-free'); ?>
			<li class="product_status">
				<label class="selectit">
					<input type="radio" name="<?php echo $this->option_name . 'part_number_settings' ?>[choice]"
						   value="custom_post_meta" <?php checked($part_number_settings['choice'], 'custom_post_meta', 'selected'); ?>>
					<span class="checkable"><?php _e('Un custom post meta', 'wp-trova-prezzi-free'); ?></span>
				</label>
			</li>
			<ul class="part_number_custom_post_meta_value_settings <?php if($part_number_settings['choice'] == 'custom_post_meta'){ echo 'visible'; } ?>">
				<li>
					Custom Post Meta: <input type="text" name="<?php echo $this->option_name . 'part_number_settings' ?>[value]" value="<?php echo $part_number_settings['value']; ?>" /> <span class="wp_tp_description"> ( es. my_custom_post_meta )</span>
				</li>
			</ul>
			<li class="product_status">
				<label class="selectit">
					<input type="radio" name="<?php echo $this->option_name . 'part_number_settings' ?>[choice]"
							 value="0" <?php checked($part_number_settings['choice'], '0', 'selected'); ?>>
					<span class="checkable"><?php _e('Non specificare PartNumber.', 'wp-trova-prezzi-free'); ?></span>
				</label>
			</li>
		</ul>

	<?php

	}

	public function wp_trovaprezzi_shipping_cost_settings_cb() {
		global $product;
		$shipping_cost_settings = get_option($this->option_name . 'shipping_cost_settings');
		$custom_shipping_cost_settings = get_option($this->option_name . 'custom_shipping_cost_settings');
		$shipping_method_flat_rate = maybe_unserialize(get_option('woocommerce_flat_rate_settings'));
		$shipping_method_international_delivery = maybe_unserialize(get_option('woocommerce_international_delivery_settings'));
		$shipping_method_local_delivery = maybe_unserialize(get_option('woocommerce_local_delivery_settings'));
			?>
			<ul class="shipping_cost_settings">
			<?php if($shipping_method_flat_rate['enabled'] == 'yes'){ ?>
			<label class="selectit">
				<li class="shipping_method">
					<input  type="radio" name="<?php echo $this->option_name . 'shipping_cost_settings' ?>" value="flat_rate" <?php checked($shipping_cost_settings,'flat_rate','selected'); ?>><span class="checkable"><?php _e( 'Tariffa unica', 'wp-trova-prezzi-free' ); ?></span>
				</li>
			</label>
			<?php } ?>
		<?php if($shipping_method_international_delivery['enabled'] == 'yes'){ ?>
			<label class="selectit">
			<li class="shipping_method">
				<input  type="radio" name="<?php echo $this->option_name . 'shipping_cost_settings' ?>" value="international_delivery" <?php checked($shipping_cost_settings,'international_delivery','selected'); ?>><span class="checkable"><?php _e( 'Tariffa Unica Internazionale', 'wp-trova-prezzi-free' ); ?></span>
				</li>
			</label>
			<?php } ?>
		<?php if($shipping_method_local_delivery['enabled'] == 'yes'){ ?>
			<label class="selectit">
			<li class="shipping_method">
				<input  type="radio" name="<?php echo $this->option_name . 'shipping_cost_settings' ?>" value="local_delivery" <?php checked($shipping_cost_settings,'local_delivery','selected'); ?>><span class="checkable"><?php _e( 'Spedizione Nazionale', 'wp-trova-prezzi-free' ); ?></span>
				</li>
			</label>
			<?php } ?>
			<label class="selectit">
			<li class="shipping_method">
				<input  type="radio" name="<?php echo $this->option_name . 'shipping_cost_settings' ?>" value="0" <?php checked($shipping_cost_settings,'0','selected'); ?>><span class="checkable"><?php _e( 'Non specificare costi di Spedizione', 'wp-trova-prezzi-free' ); ?></span>
				</li>
			</label>
			<label class="selectit">
			<li class="shipping_method">
				<input  type="radio" name="<?php echo $this->option_name . 'shipping_cost_settings' ?>" value="" disabled="disabled"><span class="checkable"><?php _e( 'Voglio impostare manualmente i costi di spedizione da mostrare su TrovaPrezzi', 'wp-trova-prezzi-free' ); ?></span><span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
			</li>
			</label>
		</ul>
			<p><?php _e('Nel caso in cui siano presenti promozioni per spedizioni gratuite, verranno calcolati al momento della generazione del feed','wp-trova-prezzi-free'); ?></p>
</ul>

	<?php
	//	echo '<pre>'; print_r($shipping_methods); echo '</pre>';
	}

	public function wp_trovaprezzi_price_filter_settings_cb() {
		?>
		<ul class="price_filter_settings">
			<li class="product_status">
				<label class="selectit">
					<?php _e( 'Solo Prodotti con un prezzo maggiore di: ', 'wp-trova-prezzi-free' ); ?><input  type="text" name="" value="-1" disabled="disabled"><span class="wp_tp_description"><i><?php _e( '( -1 per disattivare il filtro )', 'wp-trova-prezzi-free' ); ?></i></span><br /><span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
				</label>
			</li>
			<li class="product_status">
				<label class="selectit">
					<?php _e( 'Solo Prodotti con un prezzo inferiore di: ', 'wp-trova-prezzi-free' ); ?><input  type="text" name="" value="-1" disabled="disabled"><span class="wp_tp_description"><i><?php _e('( -1 per disattivare il filtro )', 'wp-trova-prezzi-free' ); ?></i></span><br /><span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
				</label>
			</li>
		</ul>

	<?php

	}
	public function wp_trovaprezzi_stock_filter_settings_cb() {
		?>
		<ul class="stock_filter_settings">
			<li class="product_status">
				<label class="selectit">
					<?php _e( 'Solo Prodotti con una quantità maggiore di: ', 'wp-trova-prezzi-free' ); ?><input  type="text" name="" value="-1" disabled="disabled"><span class="wp_tp_description"><i><?php _e('( 0 per tutti i prodotti disponibili)', 'wp-trova-prezzi-free' ); ?></i> | <i><?php _e('( -1 per disattivare il filtro )', 'wp-trova-prezzi-free' ); ?></i></span><br /><span class="wp_trovaprezzi_premium_class"><a target="_blank" href="http://www.wemiura.com/wp-trovaprezzi/"><?php _e( 'Disponibile nella versione Premium', 'wp-trova-prezzi-free' ); ?></a></span>
				</label>
			</li>
		</ul>

	<?php

	}


	public function wp_trovaprezzi_sanitize_general_settings( $general_settings ) {
		if ( in_array( $general_settings, array( 'automatic', 'manual' ), true ) ) {
			return $general_settings;
		}
	}
	public function wp_trovaprezzi_sanitize_select_tax_settings( $select_tax_settings ) {
return $select_tax_settings;
	}
	public function wp_trovaprezzi_sanitize_select_status_settings( $select_status_settings ) {
			return $select_status_settings;
	}
	public function wp_trovaprezzi_sanitize_category_tp_settings( $category_tp_settings ) {
			return $category_tp_settings;
	}
	public function wp_trovaprezzi_sanitize_brand_settings( $brand_settings ) {
			return $brand_settings;
	}
	public function wp_trovaprezzi_sanitize_code_settings( $code_settings ) {
		return $code_settings;
	}
	public function wp_trovaprezzi_sanitize_category_tp_exclude_settings( $category_tp_exclude_settings ) {
		return $category_tp_exclude_settings;
	}
	public function wp_trovaprezzi_sanitize_description_settings( $description_settings ) {
		return $description_settings;
	}
	public function wp_trovaprezzi_sanitize_eancode_settings( $eancode_settings ) {
		return $eancode_settings;
	}
	public function wp_trovaprezzi_sanitize_part_number_settings( $part_number_settings ) {
		return $part_number_settings;
	}
	public function wp_trovaprezzi_sanitize_price_filter_settings( $price_filter_settings ) {

		return $price_filter_settings;

	}
	public function wp_trovaprezzi_sanitize_stock_filter_settings( $stock_filter_settings ) {

		return $stock_filter_settings;

	}
	public function wp_trovaprezzi_sanitize_shipping_cost_settings( $shipping_cost_settings ) {

		return $shipping_cost_settings;

	}
	public function wp_trovaprezzi_sanitize_custom_shipping_cost_settings( $custom_shipping_cost_settings ) {

		return $custom_shipping_cost_settings;

	}


	public function click_download_csv(){
		?>
<script type="text/javascript">
	jQuery(document).ready( function(){
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

		jQuery('.wp_trovaprezzi_download_csv').on('click', function(){
				var data = {
					action: 'wp_trovaprezzi_download_csv_ajax'
				};
				jQuery.post(ajaxurl, data, function(response) {
					location.href='<?php echo plugin_dir_url(__FILE__).'csv/'; ?>'+response;
				});
			return false;
		});
	});
</script>
<?php
	}



	public function wp_trovaprezzi_csv(){
		header('Content-type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="demo.csv"');
		header('Pragma: no-cache');
		header('Expires: 0');

		$file_name = 'csv_'.time().'.csv';
		$file = fopen(plugin_dir_path(__FILE__).'csv/'.$file_name, 'w');

		$array_header = array(
			'Name',
			'Brand',
			'Description',
			'Price',
			'Code',
			'Link',
			'Stock',
			'Categories',
			'Image',
			'ShippingCost',
			'PartNumber',
			'EanCode'
		);

		fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($file, $array_header);
		$data = array();

		$loop = wp_tp_create_query();

		if ($loop->have_posts()) {
			while ($loop->have_posts()) : $loop->the_post();
				$name = html_entity_decode(get_the_title());
				$price =create_price(get_the_ID());
				$code = create_code(get_the_ID());
				$link = get_the_permalink();
				$category_tree = html_entity_decode(create_category(get_the_ID()));
				if(($name != '') && ( $price!= '') && ($code != '') && ($link !='') && ($category_tree != '')) {
					array_push($data, array(
						html_entity_decode(get_the_title()),
						html_entity_decode(create_brand(get_the_ID())),
						wp_strip_all_tags(html_entity_decode(create_description(get_the_ID()))),
						create_price(get_the_ID()),
						create_code(get_the_ID()),
						get_permalink(),
						create_stock(get_the_ID()),
						html_entity_decode(create_category(get_the_ID())),
						create_image(get_the_ID()),
						create_shipping_cost(get_the_ID()),
						create_part_number(get_the_ID()),
						create_eancode(get_the_ID())
					));
				}

			endwhile;
		}

		foreach ($data as $row)
		{
		 fputcsv($file, $row);
		}
		fclose($file);

		echo  $file_name;

		die();
	}



public function  check_woocommerce_activation(){
	if(!is_plugin_active('woocommerce/woocommerce.php')){
		deactivate_plugins(plugin_basename( dirname(dirname(__FILE__ )).'/wp-trova-prezzi-free.php') );
	}
}

}




require_once( ABSPATH . '/wp-admin/includes/template.php' );

class Walker_Taxonomy_Wp_Trovaprezzi_Checklist_Widget extends Walker_Category_Checklist {

	private $name;
	private $id;

	function __construct( $name = '', $id = '' ) {
		$this->name = $name;
		$this->id = $id;
	}
	 function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	function start_el( &$output, $cat, $depth = 0, $args = array(), $id = 0 ) {
		extract( $args );
		if ( empty( $taxonomy ) ) $taxonomy = 'category';
		$class = in_array( $cat->term_id, $popular_cats ) ? ' class="popular-category"' : '';
		$id = $this->id . '-' . $cat->term_id;
		$checked = checked( in_array( $cat->term_id, $selected_cats ), true, false );
		$output .= "\n<li id='{$taxonomy}-{$cat->term_id}'$class>"
			. '<label class="selectit"><input value="'
			. $cat->term_id . '" type="radio" name="wp_trovaprezzi_select_tax_settings" id="in-'. $id . '"' . $checked
			. disabled( empty( $args['disabled'] ), false, false ) . ' /> '
			. esc_html( apply_filters( 'the_category', $cat->name ) )
			. '</label>';
	}
}
class Walker_Taxonomy_Wp_Trovaprezzi_Exclude_Tax extends Walker_Category_Checklist {

	private $name;
	private $id;

	function __construct( $name = '', $id = '' ) {
		$this->name = $name;
		$this->id = $id;
	}
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	function start_el( &$output, $cat, $depth = 0, $args = array(), $id = 0 ) {
		extract( $args );



		if ( empty( $taxonomy ) ) $taxonomy = 'category';
		$class = in_array( $cat->term_id, $popular_cats ) ? ' class="popular-category"' : '';
		$id = $this->id . '-' . $cat->term_id;
		$checked = checked( in_array( $cat->term_id, $selected_cats ), true, false );
		$output .= "\n<li id='{$taxonomy}-{$cat->term_id}'$class>"
			. '<label class="selectit"><input value="'
			. $cat->term_id . '" type="checkbox" disabled="disabled" name="" id="in-'. $id . '"' . $checked
			. disabled( empty( $args['disabled'] ), false, false ) . ' /> '
			. esc_html( apply_filters( 'the_category', $cat->name ) )
			. '</label>';
	}
}
class Walker_Taxonomy_Wp_Trovaprezzi_Exclude_Tax_Product extends Walker_Category_Checklist {




	private $name;
	private $id;

	function __construct( $name = '', $id = '' ) {
		$this->name = $name;
		$this->id = $id;
	}
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}
	function start_el( &$output, $cat, $depth = 0, $args = array(), $id = 0 ) {

		extract( $args );
		 $taxonomy_selected_for_category = get_option('wp_trovaprezzi_category_tp_settings');
		 if(get_option('wp_trovaprezzi_category_tp_exclude_settings') && array_key_exists($taxonomy_selected_for_category,get_option('wp_trovaprezzi_category_tp_exclude_settings'))){
		 $taxonomy_excluded_for_category = get_option('wp_trovaprezzi_category_tp_exclude_settings')[$taxonomy_selected_for_category];
		 }


		if ( empty( $taxonomy ) ) $taxonomy = 'category';
		$class = in_array( $cat->term_id, $popular_cats ) ? ' class="popular-category"' : '';
		$id = $this->id . '-' . $cat->term_id;
		$checked = checked( in_array( $cat->term_id, $selected_cats ), true, false );
		$output .= "\n<li id='{$taxonomy}-{$cat->term_id}'$class>"
			. '<label class="selectit"><input value="'
			. $cat->term_id . '" type="checkbox" disabled="disabled" name="" id="in-'. $id . '"' . $checked
			. disabled( empty( $args['disabled'] ), false, false ) . ' /> '
			. esc_html( apply_filters( 'the_category', $cat->name ) );
			if(get_option('wp_trovaprezzi_category_tp_exclude_settings') && array_key_exists($taxonomy_selected_for_category,get_option('wp_trovaprezzi_category_tp_exclude_settings'))){
			if(in_array($cat->term_id,$taxonomy_excluded_for_category)){
				$output.= '  ( <b style="font-size:10px;">Escluso a prescindere dalle <a target="_blank" href="'.admin_url().'options-general.php?page=wp-trova-prezzi-free">Impostazioni</a></b> )';
			}
		}
			$output .= '</label>';

	}
}
