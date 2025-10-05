<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title><?php echo lang('main_title'); ?></title>
        <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet" type="text/css"> 
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/ghpages-materialize.css"  media="screen,projection"/>
        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
		<script src="<?php echo base_url();?>js/jquery-ui.js"></script>
        <style>
		body { text-align:center; margin:0px; font-size:1em; display: flex; min-height: 100vh; flex-direction: column;}
		main { flex: 1 0 auto;  }
		#header-container{ height:75px; margin:0 auto;}
		#header-container a{ color:#474747; font-size:0.9em; font-weight:400; text-decoration:none; font-family: 'Poppins', 'Helvetica', 'Arial', sans-serif;}
		#header-container a:hover{ color:#999;}
		#header-container a.btn{ color:#ffffff;}
		#header-container .row{ height:30px; margin:15px 0px; }
		#header-container .row a.active:after, #header-container .row a:hover:after{ content:""; display:inline-block; position:absolute; width:100%; height:0px; 
		                 top:100%;  left:0; margin-top:2px; transition:all 100ms linear; background:#ff3535; height:2px;}
		#header-container .row .order-menu{float:left; width:25%; display:none; margin-top:10px; text-align:left;}
		#header-container .row .order-logo{float:left; display:inline-block; width:25%; text-align:center;} 
		#header-container .row .order-logo img{width:120px; margin-top:0px;}
		#header-container .row .order-nav{float:left; display:inline-block; width:50%; margin-top:10px;}
		#header-container .row .order-misc{float:right; display:inline-block; width:25%; margin-top:10px; text-align:right;} 
		#header-container .row .order-nav ul, #header-container .row .order-misc ul{ display: inline-block; list-style: none; margin: 0; padding: 0;}
		#header-container .row .order-nav ul li{ margin-right: 1.5em; display: inline-block; position: relative;}
		#header-container .row .order-misc ul li{ margin-right:23px; display: inline-block; position: relative;}
		#header-container .row .order-misc ul li .cart-counter{ text-align: center; display: block; color: #fff; position: absolute; top: -2px; right: -23px; 
		                font-size: 80%; width: 19px; height: 19px; line-height: 21px; background: #000000; border-radius: 50%;}		
		#main-container{-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; font-family: 'Poppins', 'Helvetica', 'Arial', sans-serif; 
		                font-size:14px; margin: 0px auto 50px auto; padding:0px;}
		#main-container .overlay{background-color:rgba(0, 0, 0, 0.4); margin-top:65px; padding:0px 10px 15px 10px;}
		#main-container h4{margin:0px; padding:5px 30px 10px 30px; color:#ffffff; font-size:2em; font-weight:600; text-shadow: #000000 0px 0px 10px;}
		#main-container a{border-bottom:2px solid #fff; padding-bottom:6px; color:#ffffff; font-size:1em; text-decoration:none; font-weight:600;
		                  text-shadow: #000000 0px 0px 10px;}
		#main-container .col_left{float:left; display:inline-block; width:60%;}
		#main-container .col_left .block1{background:#eaeaea; margin-right:5%; height:248px; border:#eaeaea 1px solid;
		                                  background-image: url('<?php echo base_url(); ?>images/main/uws-ambient.jpg');
										  background-size: cover; background-position:top right;}										  
		#main-container .col_left .block2{float:left; display:inline-block; margin:30px 5% 0px 0px; width:45%; background:#eaeaea; height:238px; 
		                                  border:#eaeaea 1px solid;
		                                  background-image: url('<?php echo base_url(); ?>images/main/uws-on-trade.jpg');
										  background-size: cover; background-position:top right;}
		#main-container .col_left .block3{float:right; display:inline-block; margin:30px 5% 0px 0px; width:45%; background:#eaeaea; height:238px;
		                                  border:#eaeaea 1px solid;
		                                  background-image: url('<?php echo base_url(); ?>images/main/uws-vapes.jpg');
										  background-size: cover; background-position:top right;}
		#main-container .col_right{float:right; display:inline-block; width:40%; height:518px; background: linear-gradient(to right, #4a4a48, #494948);
		                                  border:#eaeaea 1px solid;
		                                  background-image: url('<?php echo base_url(); ?>images/main/uws-chilled.jpg');
										  background-size: cover; background-position:top right;}
		#main-container .col_right .overlay{margin-top:345px; }
		#main-container .col_right h4{padding-top:5px;}
		footer a{color:#616161;}
		footer a:hover{ color:#ff3535 !important;}
		.page-footer .material-icons {vertical-align:bottom;}
		.page-footer h5{font-size:1.5em;}
		#l_link{text-shadow:none !important; color:#333 !important; font-weight:normal !important; border-bottom:none !important;}
				
		input:-webkit-autofill { -webkit-box-shadow:0 0 0 50px white inset; -webkit-text-fill-color: #333; }		
		input:-webkit-autofill:focus { -webkit-box-shadow: /*your box-shadow*/,0 0 0 50px white inset; -webkit-text-fill-color: #333; }  
		
		#tabs { border: 0; padding-top:50px; font-family: 'Poppins', 'Helvetica', 'Arial', sans-serif !important; }
		#tabs li { position: relative; border: 0; padding-right: 75px !important; }
		#tabs li.active .tab__title span { color: #000000 !important; }
		#tabs li .tab__title span { margin-bottom: 0; font-weight: 700 !important; }
		#tabs li.active .tab__title span { color: #eeb013; }
		#tabs li.active .tab__title { border-bottom: #ff3535 solid 2px;	padding-bottom:5px;}
		#tabs li.active { opacity: 1; }
		#tabs li:last-child { padding-right:0px !important;}
		#tabs li:last-child:after {border:0px;}
		#tabs > .active, #tabs:hover { opacity: 1; }
		#tabs li { transition: 0.3s ease; -webkit-transition: 0.3s ease; -moz-transition: 0.3s ease; padding: 0px;	}
		#tabs > li { display: inline-block; opacity: .5; transition: 0.3s ease; -webkit-transition: 0.3s ease; -moz-transition: 0.3s ease;
		             -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; }
		#tabs li:after { content: ""; border-top: 1px solid #777777; width: 50px; display: inline-block; position: absolute; top: 50%; right: 0; }
		h3, .h3 { font-size: 1.78571429em; line-height: 1.5em;}
		
		form { max-width: 430px;  margin: 0 auto; text-align:left; }
		form .form-row label { line-height: 2; }
		form .form-row label, form .form-row label { display: block; }
		label { margin: 0; font-size: 0.95em; font-weight: 400; color:#666;}
		input[type=text]:focus + label, input[type=email]:focus + label, input[type=password]:focus + label { color: #e91e63; }
		input { height: 3.1875em; padding-left:1em !important;}
		input[type="text"], input[type=password]{ -webkit-appearance: none; background: #f7f7f9 !important; border: 1px solid #dddddd !important; width:96% !important;}
		input[type=text]:focus, input[type=email]:focus, input[type=password]:focus { border: 1px solid #ff3535 !important;  box-shadow: none;	
		-webkit-box-shadow:0px;}
		input[type=text]:focus + label { color: #000; }
		input[type=text]:focus { border-bottom: 1px solid #000; box-shadow: 0 1px 0 0 #000; }
		input[type=text]:focus { border-bottom: 1px solid #005eed !important;  box-shadow: 0 1px 0 0 #005eed !important; }
		
		input[type] + input[type], input[type] + .input-checkbox, input[type] + button, input[type] + .input-select { margin-top: 0.9375em; }
		input:not([class*='col-']), select:not([class*='col-']), .input-select:not([class*='col-']), textarea:not([class*='col-']), button[type="submit"]:not([class*='col-']) { width: 100%; }
        input[type="submit"] { padding: 1em !important; height: auto; outline: none; border: none; background: #ff3535; color: #fff;
		                       font-weight: 500; text-transform: uppercase; height: auto; }
		
		@media only screen and (max-width: 767px){  #header-container, #main-container {width: 90%;} 
                             		#header-container{ height:47px;}
		                            #header-container .row{ margin:5px 0px; }
									#header-container .row .order-logo{width:50%; text-align:center;}
		                            #header-container .row .order-logo img{width:100px; margin-top:0px;}
									#header-container .row .order-nav, #header-container .row .order-misc .device-behavior{display:none;}
									#header-container .row .order-menu{display:inline-block;}
		                            #main-container .col_left, #main-container .col_right{width:100%;}  
		                            #main-container .col_left .block1 {margin:0px 0px 5% 0px;;}
									#main-container .col_left .block2, #main-container .col_left .block3 {margin:0px 0px 5% 0px; width:100%;}
		}
		@media only screen and (min-width: 768px) and (max-width: 991px){    #header-container, #main-container {width: 80%;}   
                             		#header-container{ height:47px;}
		                            #header-container .row{ margin:5px 0px; }
									#header-container .row .order-logo{width:50%; text-align:center;}
		                            #header-container .row .order-logo img{width:100px; margin-top:0px;}
									#header-container .row .order-nav, #header-container .row .order-misc .device-behavior{display:none;} 
									#header-container .row .order-menu{display:inline-block;}}
        @media only screen and (min-width: 992px) and (max-width: 1199px){   #header-container, #main-container {width: 87%;}  }
		@media only screen and (min-width: 1200px) and (max-width:1550px){   #header-container, #main-container {width: 1140px;} }
		@media only screen and (min-width: 1551px){                          #header-container, #main-container {width: 1440px;} }
		</style>
        
	</head>
	<body>
        <header id="header-container">
			<div class="row">
				<div class="order-menu">
                     <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
                     <ul id="slide-out" class="sidenav">
                        <li>
                          <div class="user-view">
                          <div class="background red accent-2">&nbsp;</div>
                          <ul>
                          <li><a href="#!" style="color:#fff; padding-left:15px;"><i class="material-icons" style="color:#eee;">local_shipping</i>UWS Online Ordering</a></li>
                          </ul>
                          </div>
                        </li>

						<li>
							<a href="<?php echo base_url('home');?>" class="first-link <?= request()->uri->getSegment(1) == 'home' ? 'active' : '' ?>" target="_parent">
							<i class="material-icons">home</i>
							Home
							</a>
						</li>

                        <li><a href="<?php echo base_url();?>login"><i class="material-icons">exit_to_app</i>United Ambient</a></li>
                        <!--<li><a href="http://chill.uniteduk.co.uk/" target="_blank"><i class="material-icons">exit_to_app</i>Chilled</a></li>
                        <li><a href="http://www.vapesunited.com/" target="_blank"><i class="material-icons">exit_to_app</i>Vapes Untied</a></li>
                        <li><a href="#!"><i class="material-icons">exit_to_app</i>UWS On-Trade</a></li>-->
                        
                        <li><div class="divider"></div></li>
                        <li><a href="#!" class="sidenav-close"><i class="material-icons">close</i>Close</a></li>
                     </ul>
                </div>
				<div class="order-logo">
					<a href="<?php echo base_url();?>" class="ajax-link linked"><img alt="UWS ordering" src="<?php echo base_url();?>images/menubar/uws-logo.jpg"></a>
				</div>
                
				<div class="order-nav">
							<ul id="menu-main-menu">
                            <li class="menu-item"><a href="<?php echo base_url();?>login" class="first-link active">United Ambient</a></li>
                            <!--<li class="menu-item"><a href="http://chill.uniteduk.co.uk/" class="first-link" target="_blank">Chilled</a></li>
                            <li class="menu-item"><a href="http://www.vapesunited.com/" class="first-link" target="_blank">Vapes United</a></li>
                            <li class="menu-item"><a href="#!" class="first-link">UWS On-Trade</a></li>-->
                            </ul>							
                            <a href="#" data-layoutaction-link="search-box" title="Search" class="search-link"><i class="caviar-icon-search-menu"></i></a>
				</div>
				<div class="order-misc">
						<ul class="menu-action text-right">
                        <li class="device-behavior">&nbsp;</li>
                        </ul>
				</div>
                <br style="clear:both;">
			</div>
			<!--end of row-->
        </header>
        <main style="background:#f2f2f4;">
            <div id="main-container">
                <div class="tabs-container my__account-tab text-center" data-content-align="center" >
                    <ul id="tabs">
                        <li class="active">
                            <div class="tab__title">
                                <span class="h3">Login</span>
                            </div>
                            
                        </li>
                        </ul>
                        <?php echo form_open(base_url('login')) ?>    
						<input type="hidden" name="is_mobile" />
                        <div class="section red-text">
						<?php if (isset($validation) && $validation->getErrors()): ?>
								<div class="alert alert-danger">
									<ul>
										<?php foreach ($validation->getErrors() as $error): ?>
											<li><?= esc($error) ?></li>
										<?php endforeach ?>
									</ul>
								</div>
							<?php endif ?>
							<?php if (session()->getFlashdata('error')): ?>						
								<?= session()->getFlashdata('error') ?>							
							<?php endif; ?>
						</div>
                                <ul class="tabs-content"><li class="active"><div class="tab__content">
                                    <p class="form-row form-row-wide">
                                        <label for='email'>Enter your <?php echo lang('Main.login_username'); ?> <span class="red-text">*</span></label>
                            			<?php echo form_input(array('name'=>'username' , 'size'=>'20' , 'class'=>'cell1 validate' , 'id'=>'email')); ?>
                                    </p>
                                    <p class="form-row form-row-wide">
                                        <label for='password'>Enter your <?php echo lang('Main.login_password'); ?> <span class="red-text">*</span></label>
                           				<?php echo form_password(array('name'=>'password' , 'size'=>'20' , 'class'=>'cell1 validate', 'id'=>'password')); ?>
                                    </p>
                                    <p class="form-row">
                                        <input id="woocommerce-login-nonce" name="woocommerce-login-nonce" value="30972077ab" type="hidden">
                                        <input name="_wp_http_referer" value="/furniture/my-account/" type="hidden">							
                                        <input class="tex--uppercase" name="login" value="Login" type="submit">
                                    </p>
                                    <div >
                                            <center>OR<br /></center>
                                     </div>
                                    <p class="form-row">			
                                        <input class="tex--uppercase" name="guest" value="Guest Login" type="button" onclick="location.href='<?php echo base_url('login/guest_login');?>';">
                                    </p>
                                    <div >
                                            <?php echo lang('Main.login_footer_message');?>
                                     </div>
                            </div></li></ul>
                </div> 
                <div class="section"></div>
			<?php echo form_close(); ?>
<?php echo view("partial/footer"); ?>

<script>
	$(document).ready(function() {
		$("input[name='is_mobile']").val(window.visualViewport.width < 767 ? 1 : 0);
	})
</script>