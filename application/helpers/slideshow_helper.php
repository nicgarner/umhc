<?
function getImagesList($path) {
  $imageCounter = 0;
  if ($img_dir = @opendir($path))
  {
    while (false !== ($img_file = readdir($img_dir)))
		{
      // can add checks for other image file types here
      if (preg_match("/(\.gif|\.jpg)$/", $img_file))
	  	{
        $images[$imageCounter] = $img_file . "?award";
        $imageCounter++;
      }
    }
    closedir($img_dir);
    return $images;
  }
  return false;
}

// obsolete
function printBannerImage($path, $image, $id=NULL)
{
  $data = '<img id="frontimage'.$id.'" class="slideshow';
  if ($id > 0)
    $data .= ' hidden';
  $data .= '" src="/'.$path.$image.'" width="950" height="207" />';
  return $data;
}

function getRandomImage($path)
{
  if ($list = getImagesList($path))
  {
    mt_srand( (double)microtime() * 1000000 );
    $num = array_rand($list);
    $image = $list[$num];
		$data = printBannerImage($path, $image);
    return $data;
  }
}

function getSlideshow($path, $delay)
{
  // put the images for the slideshow in an array called list
  if ($list = getImagesList($path))
  {
    // put the images from the (php) array into a javascript array so the 
		// page can find them later
    $data = "<script type=\"text/javascript\"><!--\n";
    $data .= "var pictures=new Array(); \n";
    foreach ($list as $image)
      $data .= "  pictures.push(\"$image\");\n";
    $data .= " --></script>\n\n";
    
    // print the first image
    $data .= '<div class="slideshowContainer" id="slideshowContainer">';
    $data .= '<img id="frontimage" class="slideshow" src="/'.$path.$list[0].'"'.
         ' width="950" height="207" />';
    $data .= '</div>';
      
    $data .= "<script type=\"text/javascript\"><!--
        
    //change the opacity for different browsers
    function changeOpac(opacity, id) {
      var object = document.getElementById(id).style; 
      object.opacity = (opacity / 100);
      object.MozOpacity = (opacity / 100);
      object.KhtmlOpacity = (opacity / 100);
      object.filter = \"alpha(opacity=\" + opacity + \")\"
    }
    
    function blendimage(divid, imageid, imagefile, millisec) {
      var speed = Math.round(millisec / 100);
      var timer = 0;
      
      //set the current image as background
      document.getElementById(divid).style.backgroundImage = 
                         \"url(\" + document.getElementById(imageid).src + \")\";
      
      //make image transparent
      changeOpac(0, imageid);
      
      //make new image
      document.getElementById(imageid).src = imagefile;
    
      //fade in image
      for(i = 0; i <= 100; i++) {
        setTimeout(\"changeOpac(\"+i+\",'\"+imageid+\"')\",(timer*speed));
        timer++;
      }
    }
        
    function swapHomeImage(headlineon)
    {
      blendimage(\"slideshowContainer\",\"frontimage\",\"".$path.
                 "\"+pictures[headlineon],800);
      if (headlineon == pictures.length-1)
        var next = 0;
      else
        var next = headlineon+1;
      t = setTimeout(\"swapHomeImage(\"+next+\")\",".$delay.");
    }
    
    t = setTimeout(\"swapHomeImage(1)\",".$delay.");
    //--></script>";
    return $data;
  } 
  return false;
}

function slideshow()
{
  return '
    <script type="text/javascript"><!--
      var pictures=new Array(); 
      pictures.push("00.jpg");
      pictures.push("01.jpg");
      pictures.push("02.jpg");
      pictures.push("03.jpg");
      pictures.push("04.jpg");
      pictures.push("05.jpg");
      pictures.push("06.jpg");
      pictures.push("07.jpg");
      pictures.push("08.jpg");
      pictures.push("09.jpg");
      pictures.push("10.jpg");
      pictures.push("11.jpg");
      pictures.push("12.jpg");
      pictures.push("13.jpg");
      pictures.push("14.jpg");
      pictures.push("15.jpg");
      pictures.push("16.jpg");
      pictures.push("17.jpg");
      pictures.push("18.jpg");
    --></script> 
    <div class="slideshowContainer" id="slideshowContainer">
      <img id="frontimage" class="slideshow" src="'.base_url().'images/slideshow/00.jpg" 
           width="950" height="207" />
    </div>
    <script type="text/javascript"><!--
      //change the opacity for different browsers
      function changeOpac(opacity, id)
      {
        var object = document.getElementById(id).style; 
        object.opacity = (opacity / 100);
        object.MozOpacity = (opacity / 100);
        object.KhtmlOpacity = (opacity / 100);
        object.filter = "alpha(opacity=" + opacity + ")"
      }
      
      function blendimage(divid, imageid, imagefile, millisec)
      {
        var speed = Math.round(millisec / 100);
        var timer = 0;
        
        //set the current image as background
        document.getElementById(divid).style.backgroundImage = 
                           "url(" + document.getElementById(imageid).src + ")";
        
        //make image transparent
        changeOpac(0, imageid);
        
        //make new image
        document.getElementById(imageid).src = imagefile + "";
      
        //fade in image
        for(i = 0; i <= 100; i++) {
          setTimeout("changeOpac("+i+",\'"+imageid+"\')",(timer*speed));
          timer++;
        }
      }
      
      function swapHomeImage(headlineon)
      {
        blendimage("slideshowContainer","frontimage","'.base_url().'images/slideshow/"+pictures[headlineon]+"",800);
        if (headlineon == pictures.length-1)
          var next = 0;
        else
          var next = headlineon+1;
        
        t = setTimeout("swapHomeImage("+next+")",6000);
      }
      t = setTimeout("swapHomeImage(1)",6000);
    //--></script>';
}