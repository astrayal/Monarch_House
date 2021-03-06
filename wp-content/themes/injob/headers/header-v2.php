<?php
$header_sticky = Inwave_Helper::getPostOption( 'header_sticky', 'header_sticky' );
$header_sticky_mobile       = Inwave_Helper::getPostOption( 'header_sticky_mobile', 'header_sticky_mobile' );
if ( $header_sticky && function_exists( 'iwj_get_page_id' ) ) {
	$post = get_post();
	if ( $post && iwj_get_page_id( 'dashboard' ) == $post->ID ) {
		$header_sticky = false;
	}
}
$show_buy_service  = Inwave_Helper::getPostOption( 'show_buy_service', 'show_buy_service' );
$buy_service_url   = Inwave_Helper::getPostOption( 'buy_service_url', 'buy_service_url' );
$show_post_a_job   = Inwave_Helper::getPostOption( 'show_post_a_job', 'show_post_a_job' );
$show_search_form  = Inwave_Helper::getPostOption( 'show_search_form', 'show_search_form' );
$logo              = Inwave_Helper::getPostOption( 'logo', 'logo' );
$logo_sticky       = Inwave_Helper::getPostOption( 'logo_sticky', 'logo_sticky' );
$logo_mobile       = Inwave_Helper::getPostOption( 'logo_mobile', 'logo_mobile' );
$show_page_heading = Inwave_Helper::getPostOption( 'show_pageheading', 'show_page_heading' );
$disable_candidate_register = iwj_option( 'disable_candidate_register' );
$disable_employer_register  = iwj_option( 'disable_employer_register' );

if ( function_exists( 'WC' ) ) {
	$cartUrl   = wc_get_cart_url();
	$cartTotal = WC()->cart->cart_contents_count;
}

$header_class = array();
if ( ! is_page_template( 'page-templates/home-page.php' ) && ! is_singular( 'post' ) && $show_page_heading == 'no' ) {
	$header_class[] = ' no-page-heading';
}
if ( $header_sticky && $header_sticky != 'no' ) {
	$header_class[] = 'header-sticky';
} else {
	$header_class[] = 'no-header-sticky';
}
if ( $header_sticky_mobile) {
    $header_class[] = 'header-sticky-mobile';
} else {
    $header_class[] = 'no-header-sticky-mobile';
}
?>
<div class="header header-default header-style-v2 absolute <?php echo esc_attr( implode( ' ', $header_class ) ); ?> ">
	<div class="navbar navbar-default iw-header">
		<div class="navbar-default-inner">
			<h1 class="iw-logo float-left">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr( bloginfo( 'name' ) ); ?>">
					<img class="main-logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php esc_attr( bloginfo( 'name' ) ); ?>">
					<img class="sticky-logo" src="<?php echo esc_url( $logo_sticky ); ?>" alt="<?php esc_attr( bloginfo( 'name' ) ); ?>">
					<img class="logo-mobile" src="<?php echo esc_url( $logo_mobile ); ?>" alt="<?php esc_attr( bloginfo( 'name' ) ); ?>">
				</a>
			</h1>
			<div class="header-btn-action">
				<div class="btn-action-wrap">
                    <?php echo function_exists('iwj_get_languages_flag_html') ? iwj_get_languages_flag_html() : ''; ?>
                    <?php if ( class_exists( 'IWJ_Class' ) ) {
                        $disable_notification = iwj_option( 'disable_notification', array() );
                        $user                 = IWJ_User::get_user();

                        if ( ( $user && ( ( $user->is_candidate() && ! in_array( 'candidate', $disable_notification ) ) || ( $user->is_employer() && ! in_array( 'employer', $disable_notification ) ) ) ) || ( ! is_user_logged_in() && ! in_array( 'guest', $disable_notification ) ) ) {
                            ?>
                            <div class="notification">
                                <?php iwj_get_template_part( 'notification-menu', array() ); ?>
                            </div>
                        <?php
                        }
                        ?>
                    <?php } ?>
                    <span class="off-canvas-btn">
                            <i class="fa fa-bars"></i>
                    </span>
					<?php
					if ( ( is_user_logged_in() && current_user_can( 'create_iwj_jobs' ) )|| ( ! is_user_logged_in() && ! iwj_option( 'disable_employer_register' ) ) ) { ?>
						<div class="iwj-action-button float-right">
							<?php if ( $show_post_a_job && function_exists( 'iwj_get_page_permalink' ) ) { ?>
								<?php
								$new_job_url = esc_url( add_query_arg( array( 'iwj_tab' => 'new-job' ), iwj_get_page_permalink( 'dashboard' ) ) );
								?>
								<div class="iw-post-a-job">
									<a class="action-button" href="<?php echo (string) $new_job_url; ?>">
										<i class="ion-compose"></i>
										<span data-hover="<?php echo esc_html( __( 'Post a job', 'injob' ) ); ?>">
                                            <?php echo esc_html( __( 'Post a job', 'injob' ) ); ?>
                                        </span>
									</a>
								</div>
							<?php } ?>
						</div>
					<?php } elseif ( is_user_logged_in() && current_user_can( 'apply_job' ) ) { ?>
						<div class="iwj-action-button float-right">
							<?php if ( $show_post_a_job && function_exists( 'iwj_get_page_permalink' ) ) { ?>
								<?php
								$new_resume_url = esc_url( add_query_arg( array( 'iwj_tab' => 'profile' ), iwj_get_page_permalink( 'dashboard' ) ) );
								?>
								<div class="iw-post-a-job resume">
									<a class="action-button" href="<?php echo (string) $new_resume_url; ?>">
										<i class="ion-compose"></i>
										<span data-hover="<?php echo esc_html( __( 'Update a resume', 'injob' ) ); ?>">
                                            <?php echo esc_html( __( 'Update a resume', 'injob' ) ); ?>
                                        </span>
									</a>
								</div>
							<?php } ?>
						</div>
						<?php
					} ?>
					<?php if ( class_exists( 'IWJ_Class' ) ) {
						echo '<div class="iwj-author-desktop float-right">';
						$user                 = IWJ_User::get_user();
						if ( $user ) {
							$dashboard_url = iwj_get_page_permalink( 'dashboard' );
							$tab           = isset( $_GET['iwj_tab'] ) ? $_GET['iwj_tab'] : '';
							if ( ! $tab ) {
								$tab = 'profile';
							}
							?>

							<div class="author-login">
								<a href="<?php echo esc_url( $dashboard_url ); ?>">
									<div class="author-avatar"><?php echo get_avatar( $user->get_id(), 40 ); ?></div>
									<div class="author-name">
										<div class="hello theme-color"><?php echo __( 'Hello', 'injob' ); ?></div>
										<span><?php echo (string) $user->get_display_name(); ?></span>
									</div>
								</a>
								<div class="iwj-dashboard-menu">
									<?php iwj_get_template_part( 'dashboard-menu', array( 'tab' => $tab ) ) ?>
								</div>
							</div>
							<?php
						} else {
							$class                      = ! $disable_candidate_register || ! $disable_employer_register ? "" : "only-login";
                            echo '<span class="register-login ' . $class . '">';
                            $active_class = "active";
                            if ( ! $disable_candidate_register || ! $disable_employer_register ) {
                                $active_class = "";
                                echo '<a class="register active" href="' . esc_url( iwj_get_page_permalink( 'register' ) ) . '" onclick="return InwaveRegisterBtn();"><span>' . __( 'register', 'injob' ) . '</span><i class="fa fa-user-plus"></i></a>';
                            }
                            echo '<a class="login ' . $active_class . '" href="' . esc_url( iwj_get_page_permalink( 'login' ) ) . '" onclick="return InwaveLoginBtn();"><span>' . __( 'login', 'injob' ) . '</span><i class="fa fa-user"></i></a>';
                            echo '</span>';
						}

						echo '</div>';
						?>
					<?php } ?>
					<?php if ( class_exists( 'IWJ_Class' ) ) {
						echo '<div class="iwj-author-mobile float-right">';
						$user                 = IWJ_User::get_user();
						if ( $user ) {
							$dashboard_url = iwj_get_page_permalink( 'dashboard' );
							$tab           = isset( $_GET['iwj_tab'] ) ? $_GET['iwj_tab'] : '';
							if ( ! $tab ) {
								$tab = 'profile';
							}
							?>
							<div class="author-login">
								<a href="<?php echo esc_url( $dashboard_url ); ?>">
									<span class="action-button author-avatar"><?php echo get_avatar( $user->get_id(), 32 ); ?></span>
								</a>
							</div>
						<?php } else {
							echo '<span class="login-mobile">';
							echo '<a class="login action-button" href="' . esc_url( iwj_get_page_permalink( 'login' ) ) . '"><i class="fa fa-user"></i></a>';
							echo '</span>';
                            if ( ! $disable_candidate_register || ! $disable_employer_register ) {
                                echo '<span class="register-mobile">';
                                echo '<a class="login action-button" href="' . esc_url( iwj_get_page_permalink( 'register' ) ) . '"><i class="fa fa-user-plus"></i></a>';
                                echo '</span>';
                            }
							/*if ( ( is_user_logged_in() && current_user_can( 'create_iwj_jobs' ) ) || ( ! is_user_logged_in() && ! iwj_option( 'disable_employer_register' ) ) ) {
								if ( $show_post_a_job && function_exists( 'iwj_get_page_permalink' ) ) { */?><!--
									<?php
/*									$new_job_url = esc_url( add_query_arg( array( 'iwj_tab' => 'new-job' ), iwj_get_page_permalink( 'dashboard' ) ) );
									*/?>
									<span class="login-mobile iw-post-a-job">
										<a class="newjob action-button" href="<?php /*echo (string) $new_job_url; */?>">
											<i class="ion-compose"></i>
											<span data-hover="<?php /*echo esc_html( __( 'Post a job', 'injob' ) ); */?>">
                                            <?php /*echo esc_html( __( 'Post a job', 'injob' ) ); */?>
                                        </span>
										</a>
									</span>
								--><?php /*}
							}*/
						}
						echo '</div>';
					} ?>
				</div>
			</div>
			<div class="iw-menu-header-default float-right">
				<nav class="main-menu iw-menu-main nav-collapse">
					<?php get_template_part( 'blocks/menu' ); ?>
				</nav>
			</div>
		</div>
	</div>
</div>

<!--End Header-->