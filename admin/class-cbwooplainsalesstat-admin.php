<?php
/**
 * Plugin Name.
 *
 * @package   CbwooplainsalesstatAdmin
 * @author    rothy <info@codeboxr.com>
 * @license   GPL-2.0+
 * @link      http://codeboxr.com
 * @copyright 2014 codeboxr
 */


class CbwooplainsalesstatAdmin {

	protected static $instance = null;
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 */
	private function __construct() {

		$plugin             = Cbwooplainsalesstat::get_instance();
		$this->plugin_slug  = $plugin->get_plugin_slug();



		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'cbwooplainsalesstat_enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'cbwooplainsalesstat_enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'cbwooplainsalesstat_add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'cbwooplainsalesstat_add_action_links' ) );


	}

	/**
	 * Return an instance of this class.
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 * @return    null    Return early if no settings page is registered.
	 */
	public function cbwooplainsalesstat_enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id || $screen->id == 'plain-sales-stat_page_cbwooplainsalesstatmonthly' ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/cbwooplainsalesstat_admin.css', __FILE__ ), array(), Cbwooplainsalesstat::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 * @return    null    Return early if no settings page is registered.
	 */
	public function cbwooplainsalesstat_enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();

		if ( $this->plugin_screen_hook_suffix == $screen->id || $screen->id == 'plain-sales-stat_page_cbwooplainsalesstatmonthly' ) {
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/cbwooplainsalesstat_admin.js', __FILE__ ), array( 'jquery' ), Cbwooplainsalesstat::VERSION );


        }

	}



	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 */
	public function cbwooplainsalesstat_add_plugin_admin_menu() {

		$this->plugin_screen_hook_suffix = add_submenu_page(
            'woocommerce',
			__( 'Plain Sales Stat', $this->plugin_slug ),
			__( 'Plain Sales Stat', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'cbwooplainsalesstat_display_plain_stat' ),plugins_url( 'assets/css/sale.png', __FILE__ )
		);

      /*  add_submenu_page(

            '',
            __( 'Monthly Stat', $this->plugin_slug ),
            __( 'Monthly Stat', $this->plugin_slug ),
            'manage_options',
            $this->plugin_slug.'monthly',
            array( $this, 'cbwooplainsalesstat_display_monthly_stat' )
        );*/



    }

    // Configure the "Previous" link in the calendar
    /**
     * @param $cur_year
     * @param $cur_month
     *
     * @return string
     */
    public static function cb_calender_prev_link($cur_year,$cur_month){

        $cdpage_link ='admin.php?page=cbwooplainsalesstat';
        $cdpage_link = $cdpage_link.'&' ;
        $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');
        $last_year = $cur_year - 1;

        if ($cur_month == 1)
        {
            $llink = __('Prev','cbwooplainsalesstat');
            return '<a class = "button" href="' . $cdpage_link . 'cbstatmonth=12&amp;cbstatyear='. $last_year .'">&laquo; '.$llink.'</a>';
        }
        else
        {
            $next_month = $cur_month - 1;
            //$month = $mod_rewrite_months[$next_month];
            $llink = __('Prev','cbwooplainsalesstat');
            return '<a class = "button" href="' . $cdpage_link . 'cbstatmonth='.$next_month.'&amp;cbstatyear=' . $cur_year . '">&laquo; '.$llink.'</a>';
        }
    }
    /*
     *
     */
   public static  function cb_calender_current_link($cur_year,$cur_month){

        $cdpage_link = 'admin.php?page=cbwooplainsalesstat';
        $cdpage_link = $cdpage_link.'&' ;
        return '<a class = "button cbwooplainsalesstat" href="' . $cdpage_link . 'cbstatmonth='.$cur_month.'&amp;cbstatyear=' . $cur_year . '">'.__('Current Month','cbwooplainsalesstat').'</a>';
    }

    /**
     * @param $cur_year
     * @param $cur_month
     *
     * @return string
     */
    public static  function cb_calender_next_link($cur_year,$cur_month){

        $cdpage_link =  'admin.php?page=cbwooplainsalesstat';
        $cdpage_link = $cdpage_link.'&' ;
        $mod_rewrite_months = array(1=>'jan','feb','mar','apr','may','jun','jul','aug','sept','oct','nov','dec');
        $next_year = $cur_year + 1;

        if ($cur_month == 12)
        {
            $rlink = __('Next','cbwooplainsalesstat');
            return '<a class = "button cbwooplainsalesstat" href="' . $cdpage_link . 'cbstatmonth=1&amp;cbstatyear=' . $next_year . '">'.$rlink.' &raquo;</a>';
        }
        else
        {
            $next_month = $cur_month + 1;
           // $month = $mod_rewrite_months[$next_month];
            $rlink = __('Next','cbwooplainsalesstat');
            return '<a class = "button cbwooplainsalesstat" href="' . $cdpage_link . 'cbstatmonth='.$next_month.'&amp;cbstatyear=' . $cur_year . '">'.$rlink.' &raquo;</a>';
        }
    }

    /**
     * @param string $link
     *
     * @return string
     */
    public static function get_cb_permalink($link = ''){
        $link .= (get_option('permalink_structure')) ? '?' : '&';
        return $link;
    }
    /**
     * Render the settings page for this plugin.
     *
     *
     */
    public function cbwooplainsalesstat_display_plain_stat() {
		echo '<div class="wrap">';
	        echo '<h2>'.__('Woocommerce Plain Sales Stat','cbwooplainsalesstat').'</h2>';

	        if(isset($_REQUEST['cbstatlasttwelve']) && $_REQUEST['cbstatlasttwelve'] != null ){
		        //every day stat for one month
	           self::cbwooplainsalesstat_display_monthly_stat();
	        }
	        else{

	            $cdpage_link =  'admin.php?page=cbwooplainsalesstat&cbstatlasttwelve=1';
	            $cdpage_link_stat =  'admin.php?page=cbwooplainsalesstat';
	            $stathtml = '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
	                             <a class="nav-tab nav-tab-active" href="'.$cdpage_link_stat.'">'.__('Daily Sales Log of Month','cbwooplainsalesstat').'</a>
	                             <a class="nav-tab " href="'.$cdpage_link.'">'.__('Last 12 Months Total Sales Log','cbwooplainsalesstat').'</a>

	             </h2>';
	            $cbxstat_getdate            = getdate();

		        //var_dump($cbxstat_getdate);

	            $cbxstat_orderyear          = $cbxstat_getdate["year"];
	            $cbxstat_ordermon           = $cbxstat_getdate["mon"];

	            if(isset($_REQUEST['cbstatyear']) && $_REQUEST['cbstatyear'] != null && isset($_REQUEST['cbstatmonth']) && $_REQUEST['cbstatmonth'] != null ){
	                $cbxstat_orderyear          = $_REQUEST['cbstatyear'];
	                $cbxstat_ordermon           = $_REQUEST['cbstatmonth'];
	            }
	            $stat_of_month              = self::cbwooplainstat_build_stat_permonth( $cbxstat_ordermon , $cbxstat_orderyear);

	            $stathtml .=  '<div class="cbwooplainsalesstat_button_wrapper">';

	            $stathtml .=   self::cb_calender_prev_link($cbxstat_orderyear , $cbxstat_ordermon).self::cb_calender_current_link($cbxstat_getdate["year"] , $cbxstat_getdate["mon"]).self::cb_calender_next_link($cbxstat_orderyear , $cbxstat_ordermon).' <div>';

	            $stathtml .=  '<div class="cbwooplainsalesstat_wrapper metabox-holder">'.$stat_of_month.'</div>';

	            echo  $stathtml;
	        }
	    echo '</wrap>';


    }

    /**
     * @param $cbxstat_ordermon
     * @param $cbxstat_orderyear
     *
     * @return string
     */
    public static function cbwooplainstat_build_stat_permonth($cbxstat_ordermon , $cbxstat_orderyear){

	    $current_time               = current_time('timestamp');
        $cbxstat_getdate            = getdate($current_time);
	    $currency_pos               = get_option( 'woocommerce_currency_pos' );
	    $price_format               = get_woocommerce_price_format();
	    $currency_symbol           = get_woocommerce_currency_symbol();

        $cbxstat_days_of_month      = array( 31,28,31,30,31,30,31,31,30,31,30,31);

        $cbxstat_month_names        = array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $cbxstat_head               = __('Daily Sales Log for Month ', 'cbwooplainsalessta') .$cbxstat_month_names[$cbxstat_ordermon-1] .' , ' . $cbxstat_orderyear;
	    $cbxstat                    = '<div class="postbox " id="dashboard_cbwooplainsalesstat">
										<h3 class="hndle ui-sortable-handle"><span>'.$cbxstat_head.'</span></h3>
										<div class="inside">
											<div class="activity-block">';
        //$cbxstat                    = '<h3 class="hndle ui-sortable-handle">'.$cbxstat_head.'</h3>';

        $cbxstat .=  '<table class="wp-list-table widefat fixed pages">';
        $cbxstat .=  '<thead>
                        <tr>
                            <th class="manage-column" scope="col" style="padding-left:10px;">Day</th>
                            <th class="manage-column" scope="col">'.__('No. Orders','cbwooplainsalesstat').'</th>
                            <th class="manage-column" scope="col">'.__('Total Items','cbwooplainsalesstat').'</th>
                            <th class="manage-column" scope="col">'.__('Order Amount','cbwooplainsalesstat').'('.get_woocommerce_currency().')'.'</th>
                        </tr>
                       </thead>
                       <tfoot>
                        <tr>
                            <th class="manage-column" scope="col" style="padding-left:10px;">Day</th>
                            <th class="manage-column" scope="col">'.__('No. Orders','cbwooplainsalesstat').'</th>
                            <th class="manage-column" scope="col">'.__('Total Items','cbwooplainsalesstat').'</th>
                            <th class="manage-column" scope="col">'.__('Order Amount','cbwooplainsalesstat').'('.get_woocommerce_currency().')'.'</th>
                        </tr>
                       </tfoot>
                       <tbody id="the-list">';

	    $days_of_this_month = $cbxstat_days_of_month[$cbxstat_ordermon-1];

	    $today  = false;
	    $countable_days = 0;
	    $total_orders   = 0;
	    $total_items    = 0;
	    $total_amount   = 0;
        for($i = $days_of_this_month ; $i >= 1 ; $i--){
	        if($i == $cbxstat_getdate['mday'] && $cbxstat_ordermon == $cbxstat_getdate['mon'] && $cbxstat_orderyear == $cbxstat_getdate['year']) {$today = true;}
	        else{
		        $today = false;
	        }

	        if($cbxstat_ordermon == $cbxstat_getdate['mon'] && $cbxstat_orderyear == $cbxstat_getdate['year'] && $i > $cbxstat_getdate['mday'] ) continue;

            $cbxstatorder               = self :: cbwooplainsalesstat_get_sale($i , $cbxstat_ordermon ,$cbxstat_orderyear);
            $cbxstat_newdate            = $cbxstat_orderyear.'/'.$cbxstat_ordermon.'/'.$i;
            $cbxstat_weekday            = date('l', strtotime($cbxstat_newdate)); // note: first arg to date() is lower-case L

	        //var_dump(strtotime($cbxstat_newdate));

            $cbxstat .=  '<tr class="'.(($i%2 == 0)? 'alternate':'').'"  '.(($today)? ' style="font-weight:bold;"': '').' >
                            <td scope="col">'.$i.' ( ' .$cbxstat_weekday.' ) ' .'</td>
                            <td scope="col">'.$cbxstatorder['order_number'].'</td>
                            <td scope="col">'.$cbxstatorder['order_item_number'].'</td>
                            <td scope="col">'.sprintf( $price_format, $currency_symbol, $cbxstatorder['order_amount'] ).' </td>
                          </tr>';
	        $countable_days++;
	        $total_orders = $total_orders+$cbxstatorder['order_number'];
	        $total_items  = $total_items+$cbxstatorder['order_item_number'];
	        $total_amount = $total_amount + $cbxstatorder['order_amount'];
        }
        $cbxstat .= '</tbody></table>';

	    $cbxstat .= '</div></div></div>';
	    $avg_price = $total_amount/$countable_days;
		//<span class="dashicons dashicons-media-spreadsheet"></span>
        //$cbxstatorder               = self :: cbwooplainsalesstat_get_sale('' , $cbxstat_ordermon ,$cbxstat_orderyear);
        $cbxstat_footer =  '<div class="postbox " id="dashboard_cbwooplainsalesstat">
								<h3 class="hndle ui-sortable-handle"><span>'.__('Month At a Glance', 'cbwooplainsalesstat').'</span></h3>
								<div class="inside">
									<div class="activity-block">
										<ul>
				                            <li> <span class="dashicons dashicons-dashboard"></span> '.__('Month To Date Sales : ' , 'cbwooplainsalesstat').get_woocommerce_currency().' '.sprintf( $price_format, $currency_symbol, $total_amount ).'</li>
				                            <li> <span class="dashicons dashicons-share-alt"></span> '.__('Total Orders : ' , 'cbwooplainsalesstat'). $total_orders.'</li>
				                            <li> <span class="dashicons dashicons-share-alt"></span> '.__('Total Items : ' , 'cbwooplainsalesstat'). $total_items.'</li>
				                            <li><span class="dashicons dashicons-chart-pie"></span> ' .__('Average Sales/Day: ' , 'cbwooplainsalesstat').get_woocommerce_currency().' '.sprintf( $price_format, $currency_symbol, number_format($avg_price, 2, '.', '') ).'</li>
				                            <li><span class="dashicons dashicons-chart-pie"></span> ' .__('Avg Orders Per Day : ' , 'cbwooplainsalesstat').get_woocommerce_currency().' '.number_format($total_orders/$countable_days, 2, '.', '').'</li>
				                            <li> <span class="dashicons dashicons-chart-bar"></span> '.__('Forecasted Sales : ' , 'cbwooplainsalesstat').get_woocommerce_currency().' '.sprintf( $price_format, $currency_symbol, number_format($avg_price*$days_of_this_month, 2, '.', '')).'</li>
				                        </ul>
				                    </div>
				                </div>
				           </div>';

        return  $cbxstat_footer.$cbxstat;
    }

    /**
     * Single month Summary
     *
     * cbwooplainsalesstat_display_monthly_stat
     */
    public static function cbwooplainsalesstat_display_monthly_stat() {

	    $current_time               = current_time('timestamp');
	    $cbxstat_getdate            = getdate($current_time);
	    $currency_pos               = get_option( 'woocommerce_currency_pos' );
	    $price_format               = get_woocommerce_price_format();
	    $currency_symbol           = get_woocommerce_currency_symbol();

        $cdpage_link        =  'admin.php?page=cbwooplainsalesstat&cbstatlasttwelve=1';
        $cdpage_link_stat   =  'admin.php?page=cbwooplainsalesstat';
                   $cbxstat = '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                                         <a class="nav-tab" href="'.$cdpage_link_stat.'">'.__('Daily Sales Log of Month','cbwooplainsalesstat').'</a>
                                         <a class="nav-tab nav-tab-active" href="'.$cdpage_link.'">'.__('Last 12 Months Total Sales Log','cbwooplainsalesstat').'</a>

                                </h2>';
        $cbxstat_month_names        = array ("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        $cbxstat_head               = __('Last 12 Months Sales Log', 'cbwooplainsalessta') ;
        $cbxstat                   .= '<h3>'.$cbxstat_head.'</h3>';

        $cbxstat .=  '<table class="wp-list-table widefat fixed pages">';
        $cbxstat .=  '<thead><tr>
                        <th class="manage-column" style="padding-left:10px; " scope="col">Month</th>
                        <th class="manage-column" scope="col">'.__('Year','cbwooplainsalesstat').'</th>
                        <th class="manage-column" scope="col">'.__('No. Orders','cbwooplainsalesstat').'</th>
                        <th class="manage-column" scope="col">'.__('Total Items','cbwooplainsalesstat').'</th>
                        <th class="manage-column" scope="col">'.__('Order Amount','cbwooplainsalesstat').'('.get_woocommerce_currency().')'.'</th>
                      </tr></thead>';
	    $cbxstat .=  '<tfoot><tr>
                        <th class="manage-column" style="padding-left:10px; ">Month</th>
                        <th class="manage-column">'.__('Year','cbwooplainsalesstat').'</th>
                        <th class="manage-column">'.__('No. Orders','cbwooplainsalesstat').'</th>
                        <th class="manage-column">'.__('Total Items','cbwooplainsalesstat').'</th>
                        <th class="manage-column">'.__('Order Amount','cbwooplainsalesstat').'('.get_woocommerce_currency().')'.'</th>
                      </tr></tfoot>';

        $cbxstatm                   = strftime('%m');
        $cbxstaty                   = strftime('%Y');

	    $cbxstatorder               = self :: cbwooplainsalesstat_get_sale('' , $cbxstatm ,$cbxstaty);

	    //var_dump($cbxstatorder);

        $cbxstat .=  '<tbody id="the-list">
                        <tr class="alternate">
	                        <td scope="col"><strong>'.$cbxstat_month_names[$cbxstatm-1].'</strong></td>
	                        <td scope="col"><strong>'.$cbxstaty.'</strong></td>
	                        <td scope="col"><strong>'.$cbxstatorder['order_number'].'</strong></td>
	                        <td scope="col"><strong>'.$cbxstatorder['order_item_number'].'</strong></td>
	                        <td scope="col"><strong>'.sprintf( $price_format, $currency_symbol, $cbxstatorder['order_amount'] ).'</strong></td>
                        </tr>';

        for($i=1; $i<12; $i++){
            $cbxstatm--;
            if($cbxstatm <= 0)
            {
                $cbxstaty--;
                $cbxstatm = 12;
            }

            $cbxstatorder               = self :: cbwooplainsalesstat_get_sale('' , $cbxstatm ,$cbxstaty);

            $cbxstat .=  '<tr class="'.(($i%2 == 0)? 'alternate':'').'">
                            <td scope="col">'.$cbxstat_month_names[$cbxstatm-1].'</td>
                            <td scope="col">'.$cbxstaty.'</td>
                            <td scope="col">'.$cbxstatorder['order_number'].'</td>
                            <td scope="col">'.$cbxstatorder['order_item_number'].'</td>
                            <td scope="col">'.sprintf( $price_format, $currency_symbol, $cbxstatorder['order_amount'] ).'</td>
                          </tr>';
        }

        $cbxstat .= '</tbody>';


	    $cbxstat .= '</table>';

        echo $cbxstat;

    }

    /**
     * @param string $cbday
     * @param string $cbmonth
     * @param string $cbyear
     *
     * @return array
     */
    public static function cbwooplainsalesstat_get_sale($cbday = '' , $cbmonth = '' , $cbyear = ''){

        if($cbyear == ''){
            $cbxstat_year = strftime('%Y');
        }
        else{
            $cbxstat_year = $cbyear;
        }
        if($cbmonth == ''){
            $cbxstat_month = strftime('%m');
        }
        else{
            $cbxstat_month = $cbmonth;
        }
        if($cbday == ''){
            $cbxstat_date_args = array(   'year'          => $cbxstat_year,
                                          'month'         => $cbxstat_month,
                                      );
        }
        else{
            $cbxstat_date_args = array(   'year'          => $cbxstat_year,
                                          'month'         => $cbxstat_month,
                                          'day'           => $cbday,
            );
        }
        $cbxstat_orders             = array();
        $cbxstat_order_number       = 0;
        $cbxstat_order_item_number  = (int)0;
        $cbxstat_order_total        = 0.0;

        $args = array(

            'post_type'             => 'shop_order',
            'posts_per_page'        => -1,
            'ignore_sticky_posts'   => 0 ,
            'post_status'           => 'wc-completed',
            'date_query'            => array(
                $cbxstat_date_args,
             ),
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ): while ( $query->have_posts() ) : $query->the_post();

            array_push( $cbxstat_orders , get_the_ID());
            $cbxstat_order_number++;
            $cbxstatorderobj            = new WC_Order(get_the_ID());
            $cbxstat_items              = (int)$cbxstatorderobj->get_item_count();
            $cbxstat_order_item_number  += $cbxstat_items;
            $cbxstat_order_amount        = $cbxstatorderobj->get_total();
            $cbxstat_order_total        += $cbxstat_order_amount;

        endwhile; endif;

        return array('orders'=> $cbxstat_orders , 'order_amount' =>$cbxstat_order_total , 'order_number'=>$cbxstat_order_number , 'order_item_number'=>$cbxstat_order_item_number);

    }


	/**
	 * Add settings action link to the plugins page.
	 */
	public function cbwooplainsalesstat_add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);
	}

}// end of class
