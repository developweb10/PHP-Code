<?php 
function download_page($path){
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$path);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	
	$retValue = curl_exec($ch);		

	curl_close($ch);
	return $retValue;
	
}
$path ='https://maps.googleapis.com/maps/api/place/textsearch/json?query=towing+companies+in+jasper&key=AIzaSyCLv-b_3t1ps0zVJuJ-GrPoL6TObnJKiuQ';
$out =  download_page($path);
$data = json_decode($out, true);
foreach($data["results"] as $result){
	echo "---------------------- <br>Name - ".$result["name"] . "<br>";
	$contacts = download_page("https://maps.googleapis.com/maps/api/place/details/json?reference=".$result["reference"]."&key=AIzaSyBwpbrKvoACqEhpsfOo6a-pVJBDWTiu0d4");
	$contacts_ = json_decode($contacts, true);
	echo "Contact Number".$contacts_["result"]["formatted_phone_number"]."<br>";
}
exit();
$oXML = new SimpleXMLElement($sXML);

foreach($oXML->entry as $oEntry){
	echo $oEntry->title . "\n";
}
exit();
function httpPost($url,$params)
{
  $postData = '';
   //create name value pairs seperated by &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   $postData = rtrim($postData, '&');
 
    $ch = curl_init();  
 
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      
 
 	curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);
 curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);  
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
 
}

$params = array(
   "query" => "restaurants in Sydney",
   "key" => "AIzaSyCLv-b_3t1ps0zVJuJ-GrPoL6TObnJKiuQ"
);
 
$sXML =  httpPost("https://maps.googleapis.com/maps/api/place/textsearch/xml?query=restaurants+in+Sydney&key=AIzaSyCLv-b_3t1ps0zVJuJ-GrPoL6TObnJKiuQ",$params);
$oXML = new SimpleXMLElement($sXML);

foreach($oXML->entry as $oEntry){
	echo $oEntry->title . "\n";
}
exit();
 // create curl resource 
        $ch = curl_init(); 
// set url 
        curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/place/textsearch/xml?query=restaurants+in+Sydney&key=AIzaSyCLv-b_3t1ps0zVJuJ-GrPoL6TObnJKiuQ"); 

        //return the transfer as a string 
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);      

echo "output -- ".$output;


/*echo 'https://www.google.co.in/search?q=towing+companies+in+jasper&npsic=0&rflfq=1&rlha=0&rllag=33839649,-87291809,2340&tbm=lcl&ved=0ahUKEwjA0On6rIzQAhVLuo8KHYRRAQUQjGoIMw&tbs=lf:1,lf_ui:2,lf_pqs:EAE';

	$html = file_get_contents('https://www.google.co.in/search?q=towing+companies+in+jasper&npsic=0&rflfq=1&rlha=0&rllag=33839649,-87291809,2340&tbm=lcl&ved=0ahUKEwjA0On6rIzQAhVLuo8KHYRRAQUQjGoIMw&tbs=lf:1,lf_ui:2,lf_pqs:EAE');
		
		echo $html."<br>";
		
		exit();
		*/

echo "WELCOME";
$subject = '<div class="_Arj g"><a href="https://maps.google.co.in/maps?ion=1&amp;espv=2&amp;um=1&amp;ie=UTF-8&amp;fb=1&amp;gl=in&amp;sa=X&amp;sll=34.432748,-84.448306&amp;sspn=0.108743,0.1757983&amp;q=towing+companies+in+jasper&amp;ved=0ahUKEwjl__-cr47QAhUCkywKHYHKAh0QtgMIGA" tabindex="0" style="position:relative;height:200px;display:block"><img height="200" id="lu_map" src="/maps/vt/data=RfCSdfNZ0LFPrHSm0ublXdzhdrDFhtmHhN1u-gM,y9Oh_ijypOgGv6sxIWVnq1Y7duxY-Z8BzmqZafCGCxgb-ev3hR_2VamC__lXoy2Ng-M75Otthn8zoQbiPnoozgWMuJ8Dpk_cXG3z80a9H2paDCSNM3y05bRxsjy8rkFqv7QJI1ZBKt72b9Cp0BITrX7KJgN8rn73WJM0Naa24FxpH4K5Nz4EFz0COktbzsqC54gbXM1KxM6I7DTtJLmb2q6M-x-lSAv8mNHy0kTGaBklcoC25UEfObvORxlmryCc7dIra2Rdg8WhHGA7W6PAXnXnwxWn2HiM60th0u1JrR_htObgXIKAjM9sQw2XAN_NTBElKJD5pGew-tU_NIiFNxoYRjkJ7cGSMLqUBYS0XPrmEB8j2I3PMfPXdZbMr6HMEbK95JB_M_FYctI2uoa4Pkg4KNaUf7fyc_MxX_mYSEza9W0lRR6zOm2bpb7CVsL3Tp_jq_oKzBhQtgBePQOAgjSDW_jYJ2pcTCabVtLGg295GrMJWTrduXS9pqv7IIrVlyVdw-Oa_qawd4uicolPNvdlomxwufo0K48KznRKUTg8RAVk5FvWcKNeiKD5szN_iR6hT3_s1JEK-GaETWszRYKP01BAdNc6wcIuwAl2rKATX9J1C0be8GbY38pzwT1UGzI6zzvRbczRow4bvieZL0192_2Yhp1uNPy3jJh6zgre0WqStHPXRCaTKHDsDyLq_yICDxoL4DhHjMdRUSd2" width="547" title="Map of towing companies in jasper" alt="Map of towing companies in jasper" border="0"></a>
  <div class="_Fxi">
    <table class="_CXi">
      <td><a class="_axi" href="/search?espv=2&amp;ie=UTF-8&amp;q=A%26T+Towing+and+Service+Center+Jasper,+GA,+USA&amp;ludocid=2844066311714007287&amp;sa=X&amp;ved=0ahUKEwjl__-cr47QAhUCkywKHYHKAh0QvS4IGzAA">
          <div class="_uee rllt__wrap-on-expand" aria-level="3" role="heading">A&amp;T Towing and Service Center</div>
          <div><span class="_PXi">4.5</span>
            <g-review-stars>
              <div class="_WQf star">
                <div style="width:59px">&nbsp;</div>
              </div>
            </g-review-stars>
            (25) &middot; Towing Service</div>
          <div></div>
          <div><span>+1 706-253-8697</span></div>
          <div class="rllt__wrapped">Opens at 8:00 AM</div>
          </a></td>
        <td class="_F7n"><a href="/search?espv=2&amp;ie=UTF-8&amp;q=A%26T+Towing+and+Service+Center+Jasper,+GA,+USA&amp;ludocid=2844066311714007287&amp;sa=X&amp;ved=0ahUKEwjl__-cr47QAhUCkywKHYHKAh0Q_pABCCAwAA" class="_VWm">
          <div class="_hZm _lXm"></div>
          <div>More info</div>
          </a></td>
    </table>
  </div>
  <div class="_Fxi">
    <table class="_CXi">
      <td><a class="_axi" href="/search?espv=2&amp;ie=UTF-8&amp;q=R+%26+R+Towing+Jasper,+GA,+USA&amp;ludocid=6558090739642884727&amp;sa=X&amp;ved=0ahUKEwjl__-cr47QAhUCkywKHYHKAh0QvS4IIjAB">
          <div class="_uee rllt__wrap-on-expand" aria-level="3" role="heading">R &amp; R Towing</div>
          <div><span class="_PXi">4.2</span>
            <g-review-stars>
              <div class="_WQf star">
                <div style="width:52px">&nbsp;</div>
              </div>
            </g-review-stars>
            (6) &middot; Towing Service</div>
          <div></div>
          <div><span>195 Mountain Park Dr</span> &middot; +1 706-692-6831</div>
          <div class="rllt__wrapped">Open 24 hours</div>
          </a></td>
        <td class="_F7n"><a href="/search?espv=2&amp;ie=UTF-8&amp;q=R+%26+R+Towing+Jasper,+GA,+USA&amp;ludocid=6558090739642884727&amp;sa=X&amp;ved=0ahUKEwjl__-cr47QAhUCkywKHYHKAh0Q_pABCCgwAQ" class="_VWm">
          <div class="_hZm _lXm"></div>
          <div>More info</div>
          </a></td>
    </table>
  </div>
  <div><a class="_Tbj" href="/search?espv=2&amp;ie=UTF-8&amp;q=towing+companies+in+jasper&amp;npsic=0&amp;rlst=f&amp;rlha=0&amp;rllag=34432748,-84448305,3871&amp;sa=X&amp;ved=0ahUKEwjl__-cr47QAhUCkywKHYHKAh0QjGoIKQ">More places</a></div>
</div>
';
//$pattern = '/*_uee rllt__wrap-on-expand/';

//$value=preg_match_all('/<div class=\"lot\-price\-block\">(.*?)<\/div>/s',$file_contents,$estimates);
$value=preg_match_all('/<div class=\"_uee rllt__wrap\-on\-expand\" aria\-level=\"3\" role=\"heading\">(.*?)<\/div>/s',$subject,$estimates);
echo "<pre>";print_r($estimates);echo "</pre>";
echo "<pre>"; print_r($value); echo "</pre>";
//echo preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);
//print_r($matches);		
		
		?>