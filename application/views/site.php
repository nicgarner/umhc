<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
    <title><?= $title ?> - University of Manchester Hiking Club</title> 
    <link href="<?= base_url() ?>css/site.css" rel="stylesheet" type="text/css" />
	<!--[if IE]><link rel="stylesheet" type="text/css" href="ie.css" /><![endif]-->
    <meta name="Author" content="University of Manchester Hiking Club" /> 
    <meta name="Keywords" content="umhc, university of manchester, manchester,
                                   umsu, society, hiking, walking" /> 
    <meta name="Description" content="One of the most active societies in the 
                                      university with hiking trips and socials
                                      every week." /> 
    <link rel="shortcut icon" href="<?= base_url() ?>favicon.ico" type="image/x-icon"/>

	<? 
	
	require_once 'Mobile_Detect.php';
	$detect = new Mobile_Detect;
	
	if ((date("n") == 12 || (date("n") == 1 && date("j") <= 6)) && (!$detect->isMobile() && !$detect->isTablet()))
		$snow = 1;
	else
		$snow = 0;
	?>
    <script type="text/javascript"> 
	  	var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-27323959-1']);
			_gaq.push(['_trackPageview']);
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + 
								'.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();
	  </script>
</head>
<body<? if ($snow) echo " onload=\"init()\""?>>
<? if ($snow) echo "<div class=\"snow\" id=\"snow\"></div>
	<script type=\"text/javascript\" src=\"scripts/ThreeCanvas.js\"></script>
	<script type=\"text/javascript\" src=\"scripts/Snow.js\"></script>
	
		<script>

			var SCREEN_WIDTH = window.innerWidth;
			var SCREEN_HEIGHT = window.innerHeight;

			var container;

			var particle;

			var camera;
			var scene;
			var renderer;

			var mouseX = 0;
			var mouseY = 0;

			var windowHalfX = window.innerWidth / 2;
			var windowHalfY = window.innerHeight / 2;
			
			var particles = []; 
			var particleImage = new Image();//THREE.ImageUtils.loadTexture( \"scripts/ParticleSmoke.png\" );
			particleImage.src = 'scripts/ParticleSmoke.png'; 

		
		
			function init() {
			
				var SCREEN_WIDTH = window.innerWidth;
				var SCREEN_HEIGHT = window.innerHeight;

				// container = document.createElement('div');
				// document.getElementById('snow').appendChild(container);

				camera = new THREE.PerspectiveCamera( 75, SCREEN_WIDTH / SCREEN_HEIGHT, 1, 10000 );
				camera.position.z = 1000;

				scene = new THREE.Scene();
				scene.add(camera);
					
				renderer = new THREE.CanvasRenderer();
				renderer.setSize(SCREEN_WIDTH, SCREEN_HEIGHT);
				
				var material = new THREE.ParticleBasicMaterial( { map: new THREE.Texture(particleImage) } );
					
				for (var i = 0; i < 1000; i++) {

					particle = new Particle3D( material);
					particle.position.x = Math.random() * 2000 - 1000;
					particle.position.y = Math.random() * 2000 - 1000;
					particle.position.z = Math.random() * 2000 - 1000;
					particle.scale.x = particle.scale.y =  1;
					scene.add( particle );
					
					particles.push(particle); 
				}
				
				renderer.domElement.setAttribute(\"id\", \"snowCanvas\");
				// container.appendChild( renderer.domElement );
				document.getElementById('snow').appendChild( renderer.domElement );
	
				document.addEventListener( 'mousemove', onDocumentMouseMove, false );
				document.addEventListener( 'touchstart', onDocumentTouchStart, false );
				document.addEventListener( 'touchmove', onDocumentTouchMove, false );
				window.addEventListener( 'resize', resizeCanvas, false );
				window.addEventListener( 'orientationchange', resizeCanvas, false );
				
				setInterval( loop, 1000 / 60 );
				
			}
			
			function resizeCanvas () {
				element = document.getElementById('snowCanvas');
				element.parentNode.removeChild(element);
				init();
			}
			
			function onDocumentMouseMove( event ) {

				mouseX = event.clientX - windowHalfX;
				mouseY = event.clientY - windowHalfY;
			}

			function onDocumentTouchStart( event ) {

				if ( event.touches.length == 1 ) {

					event.preventDefault();

					mouseX = event.touches[ 0 ].pageX - windowHalfX;
					mouseY = event.touches[ 0 ].pageY - windowHalfY;
				}
			}

			function onDocumentTouchMove( event ) {

				if ( event.touches.length == 1 ) {

					event.preventDefault();

					mouseX = event.touches[ 0 ].pageX - windowHalfX;
					mouseY = event.touches[ 0 ].pageY - windowHalfY;
				}
			}

			//

			function loop() {

			for(var i = 0; i<particles.length; i++)
				{

					var particle = particles[i]; 
					particle.updatePhysics(); 
	
					with(particle.position)
					{
						if(y<-1000) y+=2000; 
						if(x>1000) x-=2000; 
						else if(x<-1000) x+=2000; 
						if(z>1000) z-=2000; 
						else if(z<-1000) z+=2000; 
					}				
				}
			
				camera.position.x += ( mouseX - camera.position.x ) * 0.05;
				camera.position.y += ( - mouseY - camera.position.y ) * 0.05;
				camera.lookAt(scene.position); 

				renderer.render( scene, camera );

				
			}

		</script>
";
?>
  <div id="UMHCContainer">
    <div id="UMHCHeader">
      <h1>University of Manchester Hiking Club</h1>
      
      <a href="/">
        <img src="<?=base_url()?>images/design/logo.gif" alt="Hiking Club logo"
             width="233" height="118" class="logo" />
      </a>
      <a href="/">
        <img src="<?= base_url() ?>images/design/sitename.gif" width="316" height="67" class="name" />
      </a>     
      <? if (isset($_SESSION['username'])): ?>
      <div class="admintools">
        <a href="/admin" title="Admin index" class="admin_button">
          <img src="<?= base_url() ?>images/design/icons/home.png" />
          <div class="link">Admin index</div>
        </a>
        <a href="/logout" title="Log out" class="admin_button">
          <img src="<?= base_url() ?>images/design/icons/logout.png" />
          <div class="link">Log out</div>
        </a>
      </div>
      <? endif ?>
	  <ul>
      <? foreach ($navigation_pages as $link): ?>
        <li<? if ($link['title'] == $title || $link['title'] == $parent) echo ' class="on"'; ?>>
          <a href="/<?= $link['slug'] ?>"><?= $link['title'] ?></a></li>
      <? endforeach ?>
      </ul>
      <div class="clear"></div>
    </div>

    <div id="UMHCMainBody">
    	<?
        if (isset($banner)) echo $banner;
        if (isset($announcements)) echo $announcements;
      ?>
    <div id="UMHCPage">
      <?= $contents ?>
      <div class="clear"></div>
		</div>
  </div>
  	<div id="UMHCFooter">
      <div class="UMHContentbox left"> 
        <p><strong>University of Manchester Hiking Club</strong>. Site maintained by <a href="mailto:webmaster@umhc.org.uk">Marco Smolla</a>.</p> 
        <p>Developed by <strong>Nic Garner</strong>, based on work by <a href="mailto:website@cjbanks.org.uk">		
           Chris Banks</a> and other committee members.</p>
        <p>&copy; University of Manchester Hiking Club 
           2007-<?= date("Y") ?></p>
      </div> 
      <div class="UMHContentbox right"> 
        <a href="http://www.facebook.com/group.php?gid=2222364440"> 
            <img class="icon" src="/images/design/icn_facebook.gif" /></a> 
          <a href="http://www.twitter.com/UoMHikingClub"> 
            <img class="icon" src="/images/design/icn_twitter.gif" /></a> 
          <a href="https://plus.google.com/116171653979727283653?prsrc=3"> 
              <img class="icon" src="/images/design/icn_google.gif" /></a> 
          <a href="http://www.manchesterstudentsunion.com/"> 
            <img class="icon" src="/images/design/icn_umsu.gif" /></a> 
      </div> 
    </div> 
  </div>

</body> 
</html> 
