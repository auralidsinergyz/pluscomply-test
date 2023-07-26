<div class="wrap uo-install-automator">

	<div class="uo-install-automator__header">

		<h1>
			<?php esc_html_e( 'Take your site to the next level with Uncanny Automator', 'uncanny-learndash-groups' ); ?>
		</h1>
		
		<p>

			<?php esc_html_e( 'Finding Uncanny Groups useful for your LearnDash site?', 'uncanny-learndash-groups' ); ?></br>

			<?php esc_html_e( "You'll love", 'uncanny-learndash-groups' ); ?> 

			<strong>
				<?php esc_html_e( 'Uncanny Automator.', 'uncanny-learndash-groups' ); ?>
			</strong>

			<?php esc_html_e( 'Best of all,', 'uncanny-learndash-groups' ); ?> 

			<strong>
				<?php esc_html_e( "it's free!", 'uncanny-learndash-groups' ); ?>
			</strong>

		</p>

		<div class="uo-install-automator__box">

			<div class="uo-install-automator__logo">

				<img src="<?php echo esc_url( $this->get_image_url( 'uncanny-automator.svg' ) ); ?>" alt="Uncanny Automator logo" />

			</div>

			<div class="uo-install-automator__text">

				<h3><?php esc_html_e( 'Uncanny Automator', 'uncanny-learndash-groups' ); ?></h3>

				<span><?php esc_html_e( 'By the creators of Uncanny Toolkit', 'uncanny-learndash-groups' ); ?></span>

				<span class="uo-install-automator__rating">
					<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
					<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
					<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
					<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
					<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow half star" /> 4.7 out of 5 stars
				</span>

			</div>

			<div class="uo-install-automator__button">
					
				<?php echo $this->get_installer()->button( 'uncanny-automator', admin_url( 'admin.php?page=uncanny-automator-dashboard' ) ); ?>

			</div>

		</div>

	</div>

	<div class="uo-install-automator__body">

		<h2>
			<?php esc_html_e( 'How it works', 'uncanny-learndash-groups' ); ?>
		</h2>

		<p>
			<?php echo sprintf( '%1$s - <strong>%2$s</strong>', esc_html__( 'Use Uncanny Automator to connect LearnDash and Uncanny Groups to your favourite plugins and apps', 'uncanny-learndash-groups' ), esc_html__( 'without hiring a developer to write custom code.', 'uncanny-learndash-groups' ) ); ?>
		</p>

		<p>
			<?php esc_html_e( 'Here are a few examples:', 'uncanny-learndash-groups' ); ?>
		</p>
		
			<ul class="uo-install-automator__examples">
				<li>
					<?php esc_html_e( 'When students complete all courses in a group, send them a certificate by email.', 'uncanny-learndash-groups' ); ?> 
					<div class="uo-install-automator__requirements">
						<label><?php esc_html_e( 'Requires', 'uncanny-learndash-groups' ); ?></label>
						<ul>
							<li><span class="uo-install-automator__icon"><img src="<?php echo esc_url( $this->get_image_url( 'learndash-icon.svg' ) ); ?>" alt="Learndash icon" /></span> Learndash</li>
						</ul>
					</div>
				</li>
				<li>
					<?php esc_html_e( 'When users redeem a group enrollment key, add a tag in ActiveCampaign.', 'uncanny-learndash-groups' ); ?>
					<div class="uo-install-automator__requirements">
						<label><?php esc_html_e( 'Requires', 'uncanny-learndash-groups' ); ?></label>
						<ul>
							<li><span class="uo-install-automator__icon"><img src="<?php echo esc_url( $this->get_image_url( 'uncanny-owl-icon.svg' ) ); ?>" alt="Uncanny Groups" /></span> Uncanny Groups</li>
							<li><span class="uo-install-automator__icon"><img src="<?php echo esc_url( $this->get_image_url( 'active-campaign.svg' ) ); ?>" alt="ActiveCampaign icon" /></span> ActiveCampaign</li>
						</ul>
					</div>
				</li>
				<li>
					<?php esc_html_e( 'When a form is submitted with a group ID, add a number of seats to an Uncanny group.', 'uncanny-learndash-groups' ); ?>
					<div class="uo-install-automator__requirements">
						<label><?php esc_html_e( 'Requires', 'uncanny-learndash-groups' ); ?></label>
						<ul>
							<li><span class="uo-install-automator__icon"><img src="<?php echo esc_url( $this->get_image_url( 'gravity-forms-icon.svg' ) ); ?>" alt="Gravity Forms" /></span> Gravity Forms</li>
							<li><span class="uo-install-automator__icon"><img src="<?php echo esc_url( $this->get_image_url( 'uncanny-owl-icon.svg' ) ); ?>" alt="Uncanny Groups" /></span> Uncanny Groups</li>
						</ul>
					</div>
				</li>
				<li>
					<?php esc_html_e( 'When students in a specific group fail a quiz 3 times, notify their Group Leader.', 'uncanny-learndash-groups' ); ?> 
					<div class="uo-install-automator__requirements">
						<label><?php esc_html_e( 'Requires', 'uncanny-learndash-groups' ); ?></label>
						<ul>
						<li><span class="uo-install-automator__icon"><img src="<?php echo esc_url( $this->get_image_url( 'learndash-icon.svg' ) ); ?>" alt="Learndash icon" /></span> Learndash</li>
						</ul>
					</div>
				</li>
			</ul>
	
			<div class="uo-install-automator__recipes">
				<div class="header">
					<strong><?php esc_html_e( 'Uncanny Automator', 'uncanny-learndash-groups' ); ?></strong> <?php esc_html_e( 'supports', 'uncanny-learndash-groups' ); ?> <a href="https://automatorplugin.com/integrations/?utm_source=uncanny_learndash_groups&utm_medium=try_automator&utm_content=all_of_the_most_popular_plugins" target="_blank"> <?php esc_html_e( 'all of the most popular WordPress plugins', 'uncanny-learndash-groups' ); ?> <span class="external-link"><img src="<?php echo esc_url( $this->get_image_url( 'icon-link-blue.svg' ) ); ?>" alt="External link icon" /></span></a> <?php esc_html_e( "and we're adding new integrations all the time. The possibilities are limitless.", 'uncanny-learndash-groups' ); ?>
				</div>
				<div class="triggers">
					<div class="uo-recipe-simulator">
						<div class="uo-recipe-simulator__title"><?php esc_html_e( 'Choose any combination of triggers', 'uncanny-learndash-groups' ); ?></div>
						<div class="uo-recipe-simulator__box">
							<div class="uo-recipe-simulator__items">
							<ul>
								<li><?php esc_html_e( 'Users complete a lesson', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Users are added to a group', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Users fill out a form', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Users register for an event', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Users buy a product', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Users complete a course', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Users fail a quiz', 'uncanny-learndash-groups' ); ?></li>
							</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="actions">
					<div class="uo-recipe-simulator">
						<div class="uo-recipe-simulator__title"><?php esc_html_e( '...to initiate any combination of actions', 'uncanny-learndash-groups' ); ?></div>
						<div class="uo-recipe-simulator__box">
							<div class="uo-recipe-simulator__items">
							<ul>
								<li><?php esc_html_e( 'Add users to a group', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Send an email', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Mark a lesson complete', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Unlock a new course', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Reset course progress', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Trigger a Zapier webhook', 'uncanny-learndash-groups' ); ?></li>
								<li><?php esc_html_e( 'Add a tag in Infusionsoft', 'uncanny-learndash-groups' ); ?></li>
							</ul>
							</div>
						</div>
					</div>
				</div>

				<script>

					// Global JS variable to init the JS
					window.hasRecipeSimulator = true;

				</script>

				<div class="robot">
					<span class="robot"><img src="<?php echo esc_url( $this->get_image_url( 'automator-robot.svg' ) ); ?>" alt="External link icon" /></span>
				</div>

			</div>

			<p>
				<?php esc_html_e( 'Build better experiences for your users while saving money on custom development. And save', 'uncanny-learndash-groups' ); ?>
				<strong><?php esc_html_e( 'your', 'uncanny-learndash-groups' ); ?></strong>
				<?php esc_html_e( 'time by automating routine tasks — all with no code.', 'uncanny-learndash-groups' ); ?>
			</p>

			<div class="uo-install-automator__box">
				<div class="uo-install-automator__logo">
					<img src="<?php echo esc_url( $this->get_image_url( 'uncanny-automator.svg' ) ); ?>" alt="Uncanny Automator logo" />
				</div>
				<div class="uo-install-automator__text">
					<h3><?php esc_html_e( 'Uncanny Automator', 'uncanny-learndash-groups' ); ?></h3>
					<span><?php esc_html_e( 'By the creators of Uncanny Toolkit', 'uncanny-learndash-groups' ); ?></span>
					<span class="uo-install-automator__rating">
						<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
						<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
						<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
						<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow star" />
						<img src="<?php echo esc_url( $this->get_image_url( 'icon-star-yellow.svg' ) ); ?>" alt="Yellow half star" /> 4.7 out of 5 stars</span>
				</div>
				<div class="uo-install-automator__button">
					
					<?php echo $this->get_installer()->button( 'uncanny-automator', admin_url( 'admin.php?page=uncanny-automator-dashboard' ) ); ?>

				</div>

			</div>

	</div>

</div>
