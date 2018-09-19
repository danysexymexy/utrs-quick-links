<?php wp_enqueue_style('sld-css-style-14', OCOPD_TPL_URL . "/$template_code/style.css" ); ?>


<?php
	$customCss = sld_get_option( 'sld_custom_style' );

	if( trim($customCss) != "" ) :
?>
	<style>
		<?php echo trim($customCss); ?>
	</style>

<?php endif; ?>

<?php

// The Loop
if ( $list_query->have_posts() )
{

	//Getting Settings Values
	if($search=='true'){
		$searchSettings = 'on';
	}else{
		if($search=='false'){
			$searchSettings = 'off';
		}else{
			$searchSettings = sld_get_option( 'sld_enable_search' );
		}
	}
	$itemAddSettings = sld_get_option( 'sld_enable_add_new_item' );
	$itemAddLink = sld_get_option( 'sld_add_item_link' );
	$enableTopArea = sld_get_option( 'sld_enable_top_part' );
	$enableFiltering = sld_get_option( 'sld_enable_filtering' );

	//Check if border should be set
	$borderClass = "";

	if( $searchSettings == 'on' || $itemAddSettings == 'on' )
	{
		$borderClass = "sld-border-bottom";
	}

	//Hook - Before Search Template
	do_action( 'qcsld_before_search_tpl', $shortcodeAtts);

	//If the top area is not disabled (both serch and add item)
	if( $enableTopArea == 'on' && $top_area != 'off' ) :

		//Load Search Template
		require ( dirname(__FILE__) . "/search-template.php" );

	endif;
	if($enable_tag_filter=='true')
		sld_show_tag_filter($category);

	//Hook - Before Filter Template
	do_action( 'qcsld_before_filter_tpl', $shortcodeAtts);

	//Enable Filtering
	if( $enableFiltering == 'on' && $mode == 'all' && $enable_left_filter!='true') :

		//Load Search Template
		require ( dirname(__FILE__) . "/filter-template.php" );

	endif;

    if(sld_get_option('sld_enable_filtering_left')=='on' || $enable_left_filter=='true') {
	    $args = array(
		    'numberposts' => - 1,
		    'post_type'   => 'sld',
		    'orderby'     => $filterorderby,
			'order'       => $filterorder,
	    );

	    if ( $category != "" ) {
		    $taxArray = array(
			    array(
				    'taxonomy' => 'sld_cat',
				    'field'    => 'slug',
				    'terms'    => $category,
			    ),
		    );

		    $args = array_merge( $args, array( 'tax_query' => $taxArray ) );

	    }

	    $listItems = get_posts( $args );
	    ?>
        <style>
            .filter-area {

                position: relative;
            }

            .slick-prev::before, .slick-next::before {
                color: #489fdf;
            }

            .slick-prev, .slick-next {
                transform: translate(0px, -80%);
            }
        </style>
        <div class="filter-area-main sld_filter_mobile_view">
            <div class="filter-area" style="width: 100%;">

                <div class="filter-carousel">
                    <div class="item">
					<?php 
						$item_count_disp_all = '';
						foreach ($listItems as $item){
							if( $item_count == "on" ){
								$item_count_disp_all += count(get_post_meta( $item->ID, 'qcopd_list_item01' ));
							}
						}
					?>
					<a href="#" class="filter-btn" data-filter="all">
						<?php _e('Show All', 'qc-opd'); ?>
						<?php
							if($item_count == 'on'){
								echo '<span class="opd-item-count-fil">('.$item_count_disp_all.')</span>';
							}
						?>
					</a>
                    </div>

				    <?php foreach ( $listItems as $item ) :
					    $config = get_post_meta( $item->ID, 'qcopd_list_conf' );
					    $filter_background_color = '';
					    $filter_text_color = '';
					    if ( isset( $config[0]['filter_background_color'] ) and $config[0]['filter_background_color'] != '' ) {
						    $filter_background_color = $config[0]['filter_background_color'];
					    }
					    if ( isset( $config[0]['filter_text_color'] ) and $config[0]['filter_text_color'] != '' ) {
						    $filter_text_color = $config[0]['filter_text_color'];
					    }
					    ?>

					    <?php
					    $item_count_disp = "";

					    if ( $item_count == "on" ) {
						    $item_count_disp = count( get_post_meta( $item->ID, 'qcopd_list_item01' ) );
					    }
					    ?>

                        <div class="item">
                            <a href="#" class="filter-btn" data-filter="opd-list-id-<?php echo $item->ID; ?>"
                               style="background:<?php echo $filter_background_color ?>;color:<?php echo $filter_text_color ?>">
							    <?php echo $item->post_title; ?>
							    <?php
							    if ( $item_count == 'on' ) {
								    echo '<span class="opd-item-count-fil">(' . $item_count_disp . ')</span>';
							    }
							    ?>
                            </a>
                        </div>

				    <?php endforeach; ?>

                </div>

                <?php if($cattabid==''): ?>
                <script>
                    jQuery(document).ready(function ($) {

                        var fullwidth = window.innerWidth;
                        if (fullwidth < 479) {
                            $('.filter-carousel').slick({


                                infinite: false,
                                speed: 500,
                                slidesToShow: 1,


                            });
                        } else {
                            $('.filter-carousel').slick({

                                dots: false,
                                infinite: false,
                                speed: 500,
                                slidesToShow: 1,
                                centerMode: false,
                                variableWidth: true,
                                slidesToScroll: 3,

                            });
                        }

                    });
                </script>
				<?php endif; ?>

            </div>
        </div>
	    <?php
    }
	//If RTL is Enabled
	$rtlSettings = sld_get_option( 'sld_enable_rtl' );
	$rtlClass = "";

	if( $rtlSettings == 'on' )
	{
	   $rtlClass = "direction-rtl";
	}

	//Hook - Before Main List
	do_action( 'qcsld_before_main_list', $shortcodeAtts);

	//Directory Wrap or Container

	echo '<div class="qcopd-list-wrapper qc-full-wrapper">';
	?>

	<?php
	echo '<div id="opd-list-holder" class="qc-grid qcopd-list-hoder '.$rtlClass.'">';
	
	echo '<section class="qc-page-section qc-main-section"><div class="qc-sld-inner-row-11 qc-sld-wrapper" id="sld_slide_container"><div class="qc-sld-grid-11" style="width:100%">';
	
	global $wpdb;
	
	
	if(is_user_logged_in() and sld_get_option('sld_enable_bookmark')=='on'){
		$b_title = sld_get_option('sld_bookmark_title');
		
		$userid = get_current_user_id();
		
		$user_meta_data = get_user_meta($userid, 'sld_bookmark_user_meta');
		if(!empty($user_meta_data[0])){
		


?>
		<div id="bookmark_list" class="qc-feature-container qc-grid-item qc-sld-single-item-11 qcpd-list-column <?php echo $style;?>">
              	
				<h2><?php echo ($b_title!=''?$b_title:'Quick Links'); ?></h2>
				<ul id="sld_bookmark_ul">
<?php
			$lists = array();
			if(!empty($user_meta_data)){
				foreach($user_meta_data[0] as $postid=>$metaids){
					if(!empty($metaids)){
						foreach($metaids as $metaid){
							
							$results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = $postid AND meta_key = 'qcopd_list_item01'");
							if(!empty($results)){
								foreach ($results as $key => $value) {
									$unserialized = unserialize($value->meta_value);
									if (trim($unserialized['qcopd_timelaps']) == trim($metaid)) {
										$customdata = $unserialized;
										$customdata['postid'] = $postid;
										$lists[] = $customdata;
									}
								}
							}
						}
					}
				}
			}
			
			@usort($lists, "custom_sort_by_tpl_title");
			$b = 1;			
			foreach($lists as $list){
				$tooltip_content = '';

				if( $tooltip === 'true' ){
					$tooltip_content = ' data-tooltip="'.$list['qcopd_item_subtitle'].'" data-tooltip-stickto="top" data-tooltip-color="#000" data-tooltip-animate-function="scalein"';
				}
?>
                  
                  <li class="opt-column-0<?php echo $column; ?>" id="pd_bookmark_li_<?php echo $b; ?>" <?php echo $tooltip_content; ?>>
						<?php
							$item_url = $list['qcopd_item_link'];
							$masked_url = $list['qcopd_item_link'];

							if( $mask_url == 'on' ){
								$masked_url = 'http://' . qcsld_get_domain($list['qcopd_item_link']);
							}
							
						?>
                        <a <?php if( $mask_url == 'on') { echo 'onclick="document.location.href = \''.$item_url.'\'; return false;"'; } ?> <?php echo (isset($list['qcopd_item_nofollow']) && $list['qcopd_item_nofollow'] == 1) ? 'rel="nofollow"' : ''; ?> href="<?php echo $masked_url; ?>"
							<?php echo (isset($list['qcopd_item_newtab']) && $list['qcopd_item_newtab'] == 1) ? 'target="_blank"' : ''; ?> data-tag="<?php echo (isset($list['qcopd_tags'])?$list['qcopd_tags']:'' ); ?>" >
                        <div class="qc-sld-main">
							 <h4 class="sld-title"><?php echo $list['qcopd_item_title']; ?></h4>
                          <div class="qc-feature-media image">
                          	<?php
								$iconClass = (isset($list['qcopd_fa_icon']) && trim($list['qcopd_fa_icon']) != "") ? $list['qcopd_fa_icon'] : "";

								$showFavicon = (isset($list['qcopd_use_favicon']) && trim($list['qcopd_use_favicon']) != "") ? $list['qcopd_use_favicon'] : "";

								$faviconImgUrl = "";
								$faviconFetchable = false;
								$filteredUrl = "";

								$directImgLink = (isset($list['qcopd_item_img_link']) && trim($list['qcopd_item_img_link']) != "") ? $list['qcopd_item_img_link'] : "";

								if( $showFavicon == 1 )
								{
									$filteredUrl = qcsld_remove_http( $item_url );

									if( $item_url != '' )
									{

										$faviconImgUrl = 'https://www.google.com/s2/favicons?domain=' . $filteredUrl;
									}

									if( $directImgLink != '' )
									{

										$faviconImgUrl = trim($directImgLink);
									}

									$faviconFetchable = true;

									if( $item_url == '' && $directImgLink == '' ){
										$faviconFetchable = false;
									}
								}

							?>

							<!-- Image, If Present -->
							<?php if( ($list_img == "true") && isset($list['qcopd_item_img'])  && $list['qcopd_item_img'] != "" ) : ?>
								<?php 
									if (strpos($list['qcopd_item_img'], 'http') === FALSE){
								?>
								
									<?php
										$img = wp_get_attachment_image_src($list['qcopd_item_img'], 'full');
										
										
									?>
									<img src="<?php echo $img[0]; ?>" alt="<?php echo $list['qcopd_item_title']; ?>">
								
								<?php
									}else{
								?>
								
									<img src="<?php echo $list['qcopd_item_img']; ?>" alt="<?php echo $list['qcopd_item_title']; ?>">
								
								<?php
									}
								?>

							<?php elseif( $iconClass != "" ) : ?>
							
								
									<i class="fa <?php echo $iconClass; ?> sld_f_icon"></i>
								
							<?php elseif( $showFavicon == 1 && $faviconFetchable == true ) : ?>
								
									<img src="<?php echo $faviconImgUrl; ?>" alt="<?php echo $list['qcopd_item_title']; ?>">
								
							<?php else : ?>
								
									<img src="<?php echo QCOPD_IMG_URL; ?>/list-image-placeholder.png" alt="<?php echo $list['qcopd_item_title']; ?>">
								
							<?php endif; ?>
							
                            
                          </div>
                          <div class="qc-sld-content">
                           
                            
                            <p class="sub-title"><?php echo $list['qcopd_item_subtitle']; ?></p>
							
                          </div>
                          <div class="upvote-section">
								<?php 
								$bookmark = 0;
								if(isset($list['qcopd_is_bookmarked']) and $list['qcopd_is_bookmarked']!=''){
									$unv = explode(',',$list['qcopd_is_bookmarked']);
									if(in_array(get_current_user_id(),$unv) && get_current_user_id()!=0){
										$bookmark = 1;
									}
								}
								
								?>
								<?php if(sld_get_option('sld_enable_bookmark')=='on'): ?>
                                <span data-post-id="<?php echo $list['postid']; ?>" data-item-code="<?php echo trim($list['qcopd_timelaps']); ?>" data-is-bookmarked="<?php echo ($bookmark); ?>" class="bookmark-btn bookmark-on">
									
									<i class="fa <?php echo ($bookmark==1?'fa-star':'fa-star-o'); ?>" aria-hidden="true"></i>
								</span>
								<?php endif; ?>
								
								<span class="open-mpf-sld-more sld_load_more" data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo trim($list['qcopd_item_title']); ?>" data-item-link="<?php echo $list['qcopd_item_link']; ?>" style="cursor:pointer" data-mfp-src="#sldinfoc-<?php echo $list['postid'] ."-". $b; ?>">
									<i class="fa fa-info-circle"></i>
								</span>
								
								
                            </div> 
                          <div class="clear"></div>
                          </div>
						<?php if(isset($list['qcopd_new']) and $list['qcopd_new']==1):?>
						<!-- new icon section -->
						<div class="new-icon-section">
							<span>new</span>
						</div>
						<!-- /new icon section -->
						<?php endif; ?>
						
						
						<?php if(isset($list['qcopd_featured']) and $list['qcopd_featured']==1):?>
						<!-- featured section -->
						<div class="featured-section">
							<i class="fa fa-bolt"></i>
						</div>
						<!-- /featured section -->
						<?php endif; ?>
                      </a>
					  
						<div id="sldinfoc-<?php echo $list['postid'] ."-". $b; ?>" class="white-popup mfp-hide">
							<div class="sld_more_text">
								Loading...
							</div>
						</div>

                  </li>
				  
			<?php $b++; }; ?>

                  </ul>
        </div>
<?php		
		
		
		
		}
	}
	
	

	$outbound_conf = sld_get_option( 'sld_enable_click_tracking' );

	$listId = 1;
	global $wp;
	$current_url = home_url( $wp->request );
	while ( $list_query->have_posts() )
	{
		$list_query->the_post();

		if(sld_get_option('sld_new_expire_after')!=''){
			sld_new_expired(get_the_ID());
		}
		//$lists = get_post_meta( get_the_ID(), 'qcopd_list_item01' );
		$lists = array();
		$results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = ".get_the_ID()." AND meta_key = 'qcopd_list_item01' order by `meta_id` ASC");
		if(!empty($results)){
			foreach($results as $result){
				$unserialize = @unserialize($result->meta_value);
				if(!isset($unserialize['qcopd_unpublished']) or $unserialize['qcopd_unpublished']==0)
					$lists[] = $unserialize;
			}
		}
		$lists = sldmodifyupvotes(get_the_ID(), $lists);
		$conf = get_post_meta( get_the_ID(), 'qcopd_list_conf', true );

		$addvertise = get_post_meta( get_the_ID(), 'sld_add_block', true );

		$addvertiseContent = isset($addvertise['add_block_text']) ? $addvertise['add_block_text'] : '';


		if( $item_orderby == 'upvotes' )
		{
			usort($lists, "custom_sort_by_tpl_upvotes");
		}

		if( $item_orderby == 'title' )
		{
			usort($lists, "custom_sort_by_tpl_title");
		}

		if( $item_orderby == 'timestamp' )
		{
			usort($lists, "custom_sort_by_tpl_timestamp");
		}

		if( $item_orderby == 'random' )
		{
			shuffle( $lists );
		}
		if(sld_get_option('sld_featured_item_top')=='on'){
			$lists = sld_featured_at_top($lists);
		}
		//adding extra variable in config
		@$conf['item_title_font_size'] = $title_font_size;
		@$conf['item_subtitle_font_size'] = $subtitle_font_size;
		@$conf['item_title_line_height'] = $title_line_height;
		@$conf['item_subtitle_line_height'] = $subtitle_line_height;
		?>

		<style>

			#qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?> ul li .ilist-item-main {
					background-color: <?php echo $conf['list_bg_color']; ?>;
			}
			#qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?> ul li .ilist-item-main:hover {
					background-color: <?php echo $conf['list_bg_color_hov']; ?>;
			}
            #qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?> ul li .item-title-text {
                background: <?php echo $conf['list_border_color']; ?>;
            }

			#qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?> ul li .panel-title h3{
			  color: <?php echo $conf['list_txt_color']; ?>;
				<?php if($conf['item_title_font_size']!=''): ?>
				font-size:<?php echo $conf['item_title_font_size']; ?> !important;
				<?php endif; ?>

				<?php if($conf['item_title_line_height']!=''): ?>
				line-height:<?php echo $conf['item_title_line_height']; ?> !important;
				<?php endif; ?>
			}

			#qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?> ul li .panel-title h3:hover{
			  color: <?php echo $conf['list_txt_color_hov']; ?>;
			}

			#qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?> ul li .sld-hover-content p{
			  color: <?php echo $conf['list_subtxt_color']; ?>;
				<?php if($conf['item_subtitle_font_size']!=''): ?>
				font-size:<?php echo $conf['item_subtitle_font_size']; ?> !important;
				<?php endif; ?>

				<?php if($conf['item_subtitle_line_height']!=''): ?>
				line-height:<?php echo $conf['item_subtitle_line_height']; ?>!important;
				<?php endif; ?>
			}

			#qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?> ul li .sld-hover-content p:hover{
			  color: <?php echo $conf['list_subtxt_color_hov']; ?>;
			}




		</style>

		<?php if( $paginate_items === 'true' ) : ?>

			<script>
				jQuery(document).ready(function($){
					$("#jp-holder-<?php echo get_the_ID(); ?><?php echo (isset($cattabid)&&$cattabid!=''?'-'.$cattabid:''); ?>").jPages({
		    			containerID : "jp-list-<?php echo get_the_ID(); ?><?php echo (isset($cattabid)&&$cattabid!=''?'-'.$cattabid:''); ?>",
		    			perPage : <?php echo $per_page; ?>,
		  			});
					
					
					$(".sld_search_filter").keyup(function(){

						setTimeout(function(){
							$("#jp-holder-<?php echo get_the_ID(); ?><?php echo (isset($cattabid)&&$cattabid!=''?'-'.$cattabid:''); ?>").jPages({
								containerID : "jp-list-<?php echo get_the_ID(); ?><?php echo (isset($cattabid)&&$cattabid!=''?'-'.$cattabid:''); ?>",
								perPage : <?php echo $per_page; ?>,
							});
							$('.qc-grid').packery({
							  itemSelector: '.qc-grid-item',
							  gutter: 10
							});
						}, 900);

					})
					
					

				});
				
			</script>

		<?php endif; ?>

		<div id="qcopd-list-<?php echo $listId .'-'. get_the_ID(); ?>" class="qc-feature-container qc-grid-item qc-sld-single-item-11 qcpd-list-column <?php echo $style;?> <?php echo "opd-list-id-" . get_the_ID(); ?>">
              	
				<?php
					$item_count_disp = "";

					if( $item_count == "on" ){
						$item_count_disp = count(get_post_meta( get_the_ID(), 'qcopd_list_item01' ));
					}
				?>
				
				<?php if($mode!='one'): ?>
				<h2 <?php echo (isset($conf['list_title_color'])&&$conf['list_title_color']!=''?'style="color:'.$conf['list_title_color'].';"':''); ?>>
					<?php
						if(isset($conf['title_link']) && $conf['title_link']!=''):
							echo '<a href="'.$conf['title_link'].'" '.(isset($conf['title_link_new_tab'])&&$conf['title_link_new_tab']==1?'target="_blank"':'').' >';
						endif;
						?>
						<?php echo get_the_title(); ?>
						<?php
							if($item_count == 'on'){
								echo '<span class="opd-item-count">('.$item_count_disp.')</span>';
							}
						?>
						<?php 
						if(@$conf['title_link']!=''):
							echo '</a>';
						endif;
					?>
				</h2>
				<?php endif; ?>
				
				<ul id="jp-list-<?php echo get_the_ID(); ?><?php echo (isset($cattabid)&&$cattabid!=''?'-'.$cattabid:''); ?>">
                    <?php

						if( $item_orderby == 'upvotes' )
						{
    						usort($lists, "custom_sort_by_tpl_upvotes");
						}

						if( $item_orderby == 'title' )
						{
    						usort($lists, "custom_sort_by_tpl_title");
						}

						if( $item_orderby == 'timestamp' )
						{
							usort($lists, "custom_sort_by_tpl_timestamp");
						}

						if( $item_orderby == 'random' )
						{
							shuffle( $lists );
						}
						if(sld_get_option('sld_featured_item_top')=='on'){
							$lists = sld_featured_at_top($lists);
						}
						$count = 1;

						foreach( $lists as $list ) :
						$all_location[] = $list;
						
						$tooltip_content = '';

						if( $tooltip === 'true' ){
							$tooltip_content = ' data-tooltip="'.$list['qcopd_item_subtitle'].'" data-tooltip-stickto="top" data-tooltip-color="#000" data-tooltip-animate-function="scalein"';
						}

					?>
                  
                  <li class="opt-column-0<?php echo $column; ?>" id="item-<?php echo get_the_ID() ."-". $count; ?>" <?php echo $tooltip_content; ?> data-title="<?php echo $list['qcopd_item_title']; ?>" data-subtitle="<?php echo $list['qcopd_item_subtitle']; ?>" data-url="<?php echo $list['qcopd_item_link']; ?>">
						<?php
							$item_url = $list['qcopd_item_link'];
							$masked_url = $list['qcopd_item_link'];
							$popContent = '';
							if( $mask_url == 'on' ){
								$masked_url = 'http://' . qcpd_get_domain($list['qcpd_item_link']);
							}
							if($main_click=='popup'){
								$masked_url = '#';
								$popContent = 'class="open-mpf-sld-more2 sld_load_more2" data-post-id="'.get_the_ID().'" data-item-title="'.trim($list['qcopd_item_title']).'" data-item-link="'.$list['qcopd_item_link'].'" data-mfp-src="#sldinfo-'.get_the_ID() ."-". $count.'"';
							}
						?>
                        <a <?php if( $mask_url == 'on') { echo 'onclick="document.location.href = \''.$item_url.'\'; return false;"'; } ?> <?php echo (isset($list['qcpd_item_nofollow']) && $list['qcpd_item_nofollow'] == 1) ? 'rel="nofollow"' : ''; ?> href="<?php echo $masked_url; ?>"
							<?php echo (isset($list['qcopd_item_newtab']) && $list['qcopd_item_newtab'] == 1) ? 'target="_blank"' : ''; ?> data-tag="<?php echo (isset($list['qcopd_tags'])?$list['qcopd_tags']:'' ); ?>" <?php echo $popContent; ?> >
                        <div class="qc-sld-main">
							<h4 class="sld-title"><?php echo $list['qcopd_item_title']; ?></h4>
                          <div class="qc-feature-media image">
                          	<?php
								$iconClass = (isset($list['qcopd_fa_icon']) && trim($list['qcopd_fa_icon']) != "") ? $list['qcopd_fa_icon'] : "";

								$showFavicon = (isset($list['qcopd_use_favicon']) && trim($list['qcopd_use_favicon']) != "") ? $list['qcopd_use_favicon'] : "";

								$faviconImgUrl = "";
								$faviconFetchable = false;
								$filteredUrl = "";

								$directImgLink = (isset($list['qcopd_item_img_link']) && trim($list['qcopd_item_img_link']) != "") ? $list['qcopd_item_img_link'] : "";

								if( $showFavicon == 1 )
								{
									$filteredUrl = qcsld_remove_http( $item_url );

									if( $item_url != '' )
									{

										$faviconImgUrl = 'https://www.google.com/s2/favicons?domain=' . $filteredUrl;
									}

									if( $directImgLink != '' )
									{

										$faviconImgUrl = trim($directImgLink);
									}

									$faviconFetchable = true;

									if( $item_url == '' && $directImgLink == '' ){
										$faviconFetchable = false;
									}
								}

							?>

							<!-- Image, If Present -->
							<?php if( ($list_img == "true") && isset($list['qcopd_item_img'])  && $list['qcopd_item_img'] != "" ) : ?>
								<?php 
									if (strpos($list['qcopd_item_img'], 'http') === FALSE){
								?>
								
									<?php
										$img = wp_get_attachment_image_src($list['qcopd_item_img'], 'medium_large');
										
										
									?>
									<img src="<?php echo $img[0]; ?>" alt="<?php echo $list['qcopd_item_title']; ?>">
								
								<?php
									}else{
								?>
								
									<img src="<?php echo $list['qcopd_item_img']; ?>" alt="<?php echo $list['qcopd_item_title']; ?>">
								
								<?php
									}
								?>

							<?php elseif( $iconClass != "" ) : ?>
							
								
									<i class="fa <?php echo $iconClass; ?> sld_f_icon"></i>
								
							<?php elseif( $showFavicon == 1 && $faviconFetchable == true ) : ?>
								
									<img src="<?php echo $faviconImgUrl; ?>" alt="<?php echo $list['qcopd_item_title']; ?>">
								
							<?php else : ?>
								
									<img src="<?php echo QCOPD_IMG_URL; ?>/list-image-placeholder.png" alt="<?php echo $list['qcopd_item_title']; ?>">
								
							<?php endif; ?>
							
                            
                          </div>
                          <div class="qc-sld-content">

                            <p class="sub-title"><?php echo $list['qcopd_item_subtitle']; ?></p>
							 
                          </div>
						  <?php if(isset($list['qcopd_new']) and $list['qcopd_new']==1):?>
						<!-- new icon section -->
						<div class="new-icon-section">
							<span>new</span>
						</div>
						<!-- /new icon section -->
						<?php endif; ?>
						
						
						<?php if(isset($list['qcopd_featured']) and $list['qcopd_featured']==1):?>
						<!-- featured section -->
						<div class="featured-section">
							<i class="fa fa-bolt"></i>
						</div>
						<!-- /featured section -->
						<?php endif; ?>
                      </a>
						  <div class="upvote-section">
								<span class="open-mpf-sld-more sld_load_more" data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo trim($list['qcopd_item_title']); ?>" data-item-link="<?php echo $list['qcopd_item_link']; ?>" style="cursor:pointer" data-mfp-src="#sldinfo-<?php echo get_the_ID() ."-". $count; ?>">
								<?php if(isset($list['qcopd_description']) && $list['qcopd_description']!=''): ?>
									<i class="fa fa-info-circle"></i>
								<?php endif; ?>
								</span>
								<?php 
								$bookmark = 0;
								if(isset($list['qcopd_is_bookmarked']) and $list['qcopd_is_bookmarked']!=''){
									$unv = explode(',',$list['qcopd_is_bookmarked']);
									if(in_array(get_current_user_id(),$unv) && get_current_user_id()!=0){
										$bookmark = 1;
									}
								}
								
								?>
								<?php if(sld_get_option('sld_enable_bookmark')=='on' && $favorite!='disable'): ?>
                                <span data-post-id="<?php echo get_the_ID(); ?>" data-item-code="<?php echo trim($list['qcopd_timelaps']); ?>" data-is-bookmarked="<?php echo ($bookmark); ?>" class="bookmark-btn bookmark-on">
									
									<i class="fa <?php echo ($bookmark==1?'fa-star':'fa-star-o'); ?>" aria-hidden="true"></i>
								</span>
								<?php endif; ?>
								
								<?php if( $upvote == 'on' ) : ?>
                                <div class="favourite">
                                    <span data-post-id="<?php echo get_the_ID(); ?>" data-item-title="<?php echo trim($list['qcopd_item_title']); ?>" data-item-link="<?php echo $list['qcopd_item_link']; ?>" class="sld-upvote-btn upvote-on upvote-btn">
									<i class="fa <?php echo $sld_thumbs_up; ?>"></i>
									</span>
									<span class="upvote-count">
										<?php
										  if( isset($list['qcopd_upvote_count']) && (int)$list['qcopd_upvote_count'] > 0 ){
											echo (int)$list['qcopd_upvote_count'];
										  }
										?>
									</span>
                                </div>
								<?php endif; ?>
                            </div>
                          
                          <div class="clear"></div>
                          </div>
						
					  

				
							<div id="sldinfo-<?php echo get_the_ID() ."-". $count; ?>" class="white-popup mfp-hide">
								<div class="sld_more_text">
									Loading...
								</div>
							</div>
                  </li>
				  
				  <?php $count++; endforeach; ?>

                  </ul>
        </div>


		
		<?php

		$listId++;
	}
?>

		</div>

          <div class="clear"></div>
		
		</div>
		</section>

<?php
	echo '<div class="sld-clearfix"></div>
			</div>
		<div class="sld-clearfix"></div>
	</div>';

	//Hook - After Main List
	do_action( 'qcsld_after_main_list', $shortcodeAtts);

}

?>

<script>
var login_url_sld = '<?php echo sld_get_option('sld_bookmark_user_login_url'); ?>';
var template = '<?php echo $style; ?>';
var bookmark = {
	<?php 
	if ( is_user_logged_in() ) {
	?>
	is_user_logged_in:true,
	<?php
	} else {
	?>
	is_user_logged_in:false,
	<?php
	}
	?>
	userid: <?php echo get_current_user_id(); ?>

};
	jQuery(document).ready(function($){

		$( '.filter-btn[data-filter="all"]' ).on( "click", function() {
	  		//Masonary Grid
		    $('.qc-grid').packery({
		      itemSelector: '.qc-grid-item',
		      gutter: 10
		    });
		});

		$( '.filter-btn[data-filter="all"]' ).trigger( "click" );

	});
</script>
