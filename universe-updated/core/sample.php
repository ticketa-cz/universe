<?php

/**
*
* (c) king-theme.com 
*
*/

?>
<div class="style-1" id="theme-setup-section">
	<section class="wrap col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
		<div class="row" style="padding: 20px">
			<section class="content col-md-12">
				<?php 
					
					if( !empty( $_POST['importSampleData'] ) ){
				
				?>
				<img src="<?php echo UNIVERSE_THEME_URI; ?>/core/assets/images/king-gray.png" height="50" class="pull-right" />
				<div id="errorImportMsg" class="p" style="width:100%;"></div>
				<div id="importWorking">
					<h2 style="color: #30bfbf;">
						<?php esc_html_e('The importer is working', 'universe' ); ?>
					</h2>
					<p>
						<?php esc_html_e('Please do not navigate away while importing. if the import process was stopped midway, it is because your server installation maximum processing time is too low.', 'universe' ); ?>
						 <a href="#" onclick="document.getElementById('form-importing').submit();">Please click here to continue importing.</a>
						 <p class="alert alert-warning">
							<i>Important: Under ThemeForest's rules we only provide images for PREVIEW purpose only.</i>
						</p>
						<br />
						<form action="" method="post" id="form-importing">
							<input type="hidden" value="1" name="importSampleData" />
							<input type="hidden" value="<?php echo (isset( $_POST['tmpl'] )?$_POST['tmpl']:'universe_tmpl'); ?>" name="tmpl" id="import_tmpl" />
						</form>
					</p>
					<i>
						<?php esc_html_e('Status', 'universe' ); ?>: 
						<span id="import-status" style="font-size: 12px;color: maroon;">
							<?php esc_html_e('Preparing to connect to server', 'universe' ); ?>...
						</span>
					</i>
					<div class="progress" style="height:35px;">
				    	<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" id="importStatus" style="width: 0%;height:35px;line-height: 35px;">
					    	0% Complete
					    </div>
				    </div>
				    <center>
					    &copy; king-theme.com
				    </center>
				</div>
			    <script type="text/javascript">
			    	
			    	var docTitle = document.title;
			    	var el = document.getElementById('importStatus');
			    		
			    	function istaus( is ){
			    		
			    		var perc = parseInt( is*100 )+'%';
			    		el.style.width = perc;
			    		
			    		if( perc != '100%' ){
			    			el.innerHTML = perc+' Complete';
			    		}	
			    		else{
				    		el.innerHTML = 'Initializing...';	
			    		}
			    		document.title = el.innerHTML+'  - '+docTitle;
			    	}
			    	
			    	function tstatus( t ){
			    		document.getElementById('import-status').innerHTML = t;
			    	}
			    	
			    	function iserror( msg ){
				    	document.getElementById('errorImportMsg').innerHTML += '<div class="alert alert-danger">'+msg+'</div>';
				    	document.getElementById('errorImportMsg').style.display = 'inline-block';
			    	}
			    </script>
			    						
			<?php	
			
				get_template_part( 'core'.DS.'sample'.DS.'king.importer' );						
				
			?>		
				<script type="text/javascript">
					window.onbeforeunload = null;
					document.getElementById('importWorking').style.display = 'none';
				</script>
				
				<h2 style="color: #30bfbf;"><?php esc_html_e('Import has completed', 'universe' ); ?></h2>
				<div class="h4">
					<p><?php esc_html_e('We will redirect you to homepage after', 'universe' ); ?> <span id="countdown">10</span> seconds.  
						<?php esc_html_e('You can', 'universe' ); ?>  
						<a href="#" onclick="clearTimeout(countdownTimer)">
							<?php esc_html_e('Stop Now', 'universe' ); ?>
						</a>
						 <?php esc_html_e('or go to', 'universe' ); ?> 
						<a href="<?php echo admin_url('admin.php?page='.strtolower(UNIVERSE_THEME_NAME).'-panel'); ?>" onclick="clearTimeout(countdownTimer)">
							<?php esc_html_e('Theme Panel', 'universe' ); ?>
						</a>
					</p>
				</div>		
				<div class="p">
					<div class="updated settings-error below-h2">
						<p></p>
						<h3><?php esc_html_e('Import Successful', 'universe' ); ?></h3>
						<p><?php esc_html_e('All done. Have fun!', 'universe' ); ?></p>
						<p></p>
						<p></p>
					</div>
				</div>		
					
				<?php	
					
					}else{
					
				?>
				
				<form action="" method="post" onsubmit="doSubmit(this)">
					<img src="<?php echo UNIVERSE_THEME_URI; ?>/core/assets/images/king-gray.png" height="50" class="pull-right" />
					<h2 style="color: #30bfbf;"><?php esc_html_e('Welcome to', 'universe' ); ?> <?php echo UNIVERSE_THEME_NAME; ?> </h2>
					<div class="h4">
						<p><?php esc_html_e('Thank you for using the', 'universe' ); ?> <?php echo UNIVERSE_THEME_NAME; ?> Theme.</p>
					</div>	
					<div class="bs-callout bs-callout-info">
						<h4><?php esc_html_e('Sample Data', 'universe' ); ?></h4>			
						<div class="p">
							<p>
							<?php esc_html_e('Let our custom demo content importer do the heavy lifting. Painlessly import settings, layouts, menus, colors, fonts, content, slider and plugins. Then get customising', 'universe' ); ?>
							</p>
							<?php esc_html_e('Notice: Before import, Make sure your website data is empty (posts, pages, menus...etc...)', 'universe' ); ?> 
							<br />
							<?php esc_html_e('We suggest you use the plugin', 'universe' ); ?> <a href="<?php echo esc_url(UNIVERSE_SITE_URI); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-reset&from=<?php echo strtolower(UNIVERSE_THEME_NAME); ?>-theme&TB_iframe=true&width=800&height=550" class="thickbox" title="<?php esc_html_e('Install WordPress Reset', 'universe' ); ?>">"<?php esc_html_e('WordPress Reset', 'universe' ); ?>"</a> <?php esc_html_e('to reset your website before import', 'universe' ); ?>. <br />
							<i>( <?php esc_html_e('After install plugin', 'universe' ); ?> <a href="<?php echo esc_url(UNIVERSE_SITE_URI); ?>/wp-admin/plugin-install.php?tab=plugin-information&plugin=wordpress-reset&from=<?php echo strtolower(UNIVERSE_THEME_NAME); ?>-theme&TB_iframe=true&width=800&height=550" class="thickbox" title="Install WordPress Reset">"<?php esc_html_e('WordPress Reset', 'universe' ); ?>"</a> <?php esc_html_e('go to: Tool -> reset', 'universe' ); ?> )</i>
						</div>		
					</div>	
					
					<div class="p">
						<p>
							<label class="label-form-sel">
								<?php esc_html_e('We required using 4 plugins', 'universe' ); ?> ( King Composer, KC Pro, Master Slider & Contact Form 7  )
							</label>
							<br />
							<button id="submitbtn2" onclick="doSubmit2()" class="btn submit-btn">
								<?php esc_html_e('Install Plugins Only', 'universe' ); ?>
							</button>
							<input type="hidden" value="" name="pluginsOnly" id="pluginsOnly" />
							<br />
							<br />
							<i class="sub-label-form-sel">
								<?php esc_html_e('Plugins will be installed automatically during Import Sample Data. You also able to find the installation files in the directory', 'universe' ); ?>: wp-content/themes/<?php echo strtolower(UNIVERSE_THEME_NAME); ?>/core/sample/plugins
							</i>
							<p class="alert alert-warning">
								<i>Important: Under ThemeForest's rules we only provide images for PREVIEW purpose only.</i>
							</p>
						</p>
					</div>
										
					<div class="p">
						<p>
							
							<input type="submit" id="submitbtn" value="Import Demos" class="btn submit-btn" />

							<h3 id="imp-notice">
								<img src="<?php echo UNIVERSE_THEME_URI; ?>/core/assets/images/loading.gif" /> 
								<?php esc_html_e('Please do not navigate away while importing', 'universe' ); ?>
								<br />
								<span style="font-size: 10px;float: right;margin: 5px 7px 0 0;">
									<?php esc_html_e('It may take up to 10 minutes', 'universe' ); ?>
								</span>
							</h3>
							
							<input type="hidden" value="1" name="importSampleData" />
							<input type="hidden" value="universe_tmpl" name="tmpl" id="import_tmpl" />
						</p>
					</div>
				</form>		
				<?php } ?>
			</section><!-- /content -->
		</div><!-- /row -->

		<div class="row">
			<section class="col-md-12">
				<div class="footer">
					<?php echo UNIVERSE_THEME_NAME; ?> <?php esc_html_e('version', 'universe' ); ?> 
					<?php global $universe_options; echo UNIVERSE_THEME_VERSION; ?> &copy; by King-Theme
					|  <?php esc_html_e('Question?', 'universe' ); ?> 
					<a href="<?php echo esc_url( 'http://help.king-theme.com' ); ?>">help.king-theme.com</a>
					
					<a onclick="if(!confirm('<?php esc_html_e('Notice: If you do not install plugins and sample data, your site will not work fully functional. Click Ok if you want to dismiss.', 'universe' ); ?>')){return false;}else{clearTimeout(countdownTimer);return true;}" class="pull-right link btn btn-default" class="btn btn-default" href="<?php echo admin_url('admin.php?page='.strtolower(UNIVERSE_THEME_NAME).'-panel'); ?>">
						<?php esc_html_e('Dismiss', 'universe' ); ?> &nbsp; <i class="fa fa-sign-out"></i>
					</a>
				</div>
			</section><!-- /subscribe -->
		</div><!-- /row -->
	</section>
</div>		
<script type="text/javascript">


	function doSubmit( form ){
		var btn = document.getElementById('submitbtn');
		btn.className+=' disable';
		btn.disabled=true;
		btn.value='Importing.....';
		document.getElementById('imp-notice').style.display = 'block';
	}
	function doSubmit2(){
		jQuery('#pluginsOnly').val('ON');
		jQuery('#submitbtn').trigger('click');
	}
	var countdown = document.getElementById('countdown');
	var countdownTimer = null;
	if( countdown ){
		
		function count_down( second ){
			
			second--;
			countdown.innerHTML = second;
			if(second>0){
				countdownTimer = setTimeout('count_down('+second+')', 1000);
			}else{
				window.location = '<?php echo UNIVERSE_SITE_URI; ?>';
			}	
		}

		count_down( 10 );
		
	}
	
	
	
</script>  