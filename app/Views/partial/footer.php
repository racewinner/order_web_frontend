			</div>
        </main>

		<?php  echo view("partial/cmslink_modal"); ?>

        <footer class="page-footer grey lighten-4" style="font-size:0.8em; padding-top:0px; border-top:#ddd 1px ridge;">
          <div class="container">
            <div class="row">
              <div class="col l6 s12">
                <h5 class="grey-text text-darken-4">UWS Online Ordering</h5>
                <p class="grey-text text-darken-3">Welcome to the United Wholesale (Scotland) Ltd. online ordering service. Fast and reliable online ordering for our valued customers.<br /><br /><!--<a href="https://www.epoints.com/united/join" rel="nofollow" target="_blank"><img src="images/epoints-50.png" width="50px" style="width:50px !important; height:19px !important"></a>--></p>
              </div>
              <div class="col l4 offset-l2 s12 fblock"> 
                 <div style="max-width:250px; margin:15px auto -5px auto;">
                    <a class="weatherwidget-io" href="https://forecast7.com/en/55d86n4d25/glasgow/" data-mode="Current" data-days="3" data-theme="pure" >Glasgow, UK</a>
					<script>
                    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='https://weatherwidget.io/js/widget.min.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','weatherwidget-io-js');
                    </script>
                </div>
              </div>
            </div>
           
          </div>
          <div class="footer-copyright grey darken-3 white-text">
            <div class="container left-align">&copy; 2020 Copyright UWS Online Ordering, All Rights Reserved.
            <a class="white-text right" href="http://www.uniteduk.co.uk" target="_blank">United Wholesale (Scotland) Ltd. Official Website</a>
            </div>
          </div>
        </footer> 
        <!--JavaScript at end of body for optimized loading-->
   	 	<script type="text/javascript" src="<?php echo base_url();?>js/materialize.js"></script>
		<script>	
             $(document).ready(function(){
				$('.sidenav').sidenav();
				$('.collapsible').collapsible();
				$('.newsfeed a').attr('target', '_blank');
				$('select, #per_page').formSelect();
				
				// if CPANEL
				$('.datepicker').datepicker();
				
				$("#t_1, #t_2, #t_3, #t_4, #t_5, #t_6, #t_7, #t_8").on( "click", function() {
					var n = $(this).attr("id").slice(-1);
					if(n==1){n='feature';}else if(n==2){n='slider';}else if(n==3){n='sponsors';}else if(n==4){n='banners';}else if(n==5){n='promotions';}else if(n==6){n='presell';}else if(n==7){n='settings';}else if(n==8){n='tracking';}
					window.location = '<?php echo base_url()."cpanel";?>#' + n;
				});
				
				$("#l_1, #l_2, #l_3, #l_4, #l_5, #l_6").on( "click", function() {
					var n = $(this).attr("id").slice(-1);
					$("#t_" + n).addClass("active");
				});

				var url = window.location.href;
				url = url.substr(url.indexOf("#") + 1);
				
				switch (url) { 
					case 'slider':     $("#l_2").trigger('click');	break;
					case 'sponsors':   $("#l_3").trigger('click');	break;
					case 'banners':    $("#l_4").trigger('click');	break;					
					case 'promotions': $("#l_5").trigger('click');	break;
					case 'presell':    $("#l_6").trigger('click');	break;
					case 'settings':   $("#l_7").trigger('click');	break;
					case 'tracking':   $("#l_8").trigger('click');	break;
					default:           $("#l_1").trigger('click');	break;
				}
				$('.tabs').tabs();
				
			 });
        </script>
          
		  <script>			     
        $(document).ready(function(){	
				$("#search0").autocomplete({minLength:2 ,
          select: function (event, ui) { 
            debugger
            this.val(); 
          },
					source: function( request, response ) {  
            debugger                     
						$.ajax({
							type : "POST" ,
							url: '<?php echo base_url("products/suggest2")?>' ,
							dataType: "json" ,
							data: {term:request.term} ,
							error : function(request, status, error) {
								 alert(error);
								},
							success: function(data) {
								//alert(data);
								response(data);
							}
						});	
					}
				});
				$(".my_account_dropdown").dropdown({ hover: true, alignment: 'right' });
				$(".dropdown-trigger:not(.select-dropdown, .my_account_dropdown)").dropdown({ hover: true });
				$(".dropdown-trigger.select-dropdown").dropdown({ hover: false });
			 });
			
			 /* this allows us to pass in HTML tags to autocomplete. Without this they get escaped */
			$[ "ui" ][ "autocomplete" ].prototype["_renderItem"] = function( ul, item) {
			return $( "<li></li>" ) 
			  .data( "item.autocomplete", item )
			  .append( item.label )
			  .appendTo( ul );
			};
			
        </script>
	</body>
</html>

