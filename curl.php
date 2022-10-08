<?php 
namespace aw2\curl;

\aw2_library::add_service('curl.api.get','CURL GET for API',['func'=>'api_get','namespace'=>__NAMESPACE__]);
function api_get($atts=null,$content=null,$shortcode=null){
	if (\aw2_library::pre_actions('all', $atts, $content, $shortcode) == false) {
        return;
    }
    
    extract(\aw2_library::shortcode_atts(array(
    'data' => null,
    'url' => null,
    'proxy' => null,
    'header' => null
    ), $atts));
	
	$live_debug_event=array();
	$live_debug_event['flow']='curl';
	$live_debug_event['action']='curl.called';
	$live_debug_event['stream']='curl.api.get';
	
	
    if(is_null($url)) { 
			$temp_debug=$live_debug_event;
			$temp_debug['error']='yes';
			$temp_debug['error_type']='curl url is missing';
		\aw2\live_debug\publish_event(['event'=>$temp_debug,'bgcolor'=>'#F0EBE3']);
		return;
	}
	
	$return_value = curl_call('get', $url, $data, $header, $proxy);
	
    $return_value = \aw2_library::post_actions('all', $return_value, $atts);
    return $return_value;
    
}


\aw2_library::add_service('curl.api.post','CURL GET for API',['func'=>'api_post','namespace'=>__NAMESPACE__]);
function api_post($atts=null,$content=null,$shortcode=null){
	if (\aw2_library::pre_actions('all', $atts, $content, $shortcode) == false) {
        return;
    }
    
    extract(\aw2_library::shortcode_atts(array(
    'data' => null,
    'url' => null,
    'proxy' => null,
    'header' => null
    ), $atts));
	
	$live_debug_event=array();
	$live_debug_event['flow']='curl';
	$live_debug_event['action']='curl.called';
	$live_debug_event['stream']='curl.api.get';
	
	
    if(is_null($url)) { 
			$temp_debug=$live_debug_event;
			$temp_debug['error']='yes';
			$temp_debug['error_type']='curl url is missing';
		\aw2\live_debug\publish_event(['event'=>$temp_debug,'bgcolor'=>'#F0EBE3']);
		return;
	}
	
	$return_value = curl_call('POST', $url, $data, $header, $proxy);
	
    $return_value = \aw2_library::post_actions('all', $return_value, $atts);
    return $return_value;
    
}


\aw2_library::add_service('curl.page.get','CURL GET for API',['func'=>'page_get','namespace'=>__NAMESPACE__]);
function page_get($atts=null,$content=null,$shortcode=null){
	if (\aw2_library::pre_actions('all', $atts, $content, $shortcode) == false) {
        return;
    }
    
    extract(\aw2_library::shortcode_atts(array(
    'data' => null,
    'url' => null,
    'proxy' => null,
    'header' => null
    ), $atts));
	
	$live_debug_event=array();
	$live_debug_event['flow']='curl';
	$live_debug_event['action']='curl.called';
	$live_debug_event['stream']='curl.api.get';
	
	
    if(is_null($url)) { 
			$temp_debug=$live_debug_event;
			$temp_debug['error']='yes';
			$temp_debug['error_type']='curl url is missing';
		\aw2\live_debug\publish_event(['event'=>$temp_debug,'bgcolor'=>'#F0EBE3']);
		return;
	}
	
	$return_value = curl_call('POST', $url, $data, $header, $proxy);
	
    $return_value = \aw2_library::post_actions('all', $return_value, $atts);
    return $return_value;
    
}

function curl_call($method, $url, $data=null, $headers=null, $proxy=null){
	$live_debug_event=array();
	$live_debug_event['flow']='curl';
	$live_debug_event['action']='curl_call';
	$live_debug_event['stream']='curl_call';
	
    $curl = curl_init();
    switch ($method){
	  case "POST":
		 curl_setopt($curl, CURLOPT_POST, 1);
		 if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		 break;
	  case "PUT":
		 curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		 if ($data)
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
		 break;
	  default:
		 if ($data)
			$url = sprintf("%s?%s", $url, http_build_query($data));
    }
   
     
	// OPTIONS:
	curl_setopt($curl, CURLOPT_URL, $url);
	if(!is_null($headers)){
		$head= array();
		foreach($headers['header'] as $item){
			$head[]=$item['key'].": ".$item['val'];
		}
	   curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
	}
	
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   curl_setopt($curl, CURLOPT_TIMEOUT, 60);
   
   //proxy
   if(is_array($proxy)){
		//Set the proxy IP.
		curl_setopt($ch, CURLOPT_PROXY, $proxy['host']);

		//Set the port.
		curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']);
		if(isset($proxy['username'])){
			//Specify the username and password.
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['username'].":".$proxy['password']);
		}
   }
    /**
    $result_headers=[];
    // this function is called by curl for each header received
	curl_setopt($ch, CURLOPT_HEADERFUNCTION,
	  function($curl, $header) use (&$result_headers)
	  {
		$len = strlen($header);
		$header = explode(':', $header, 2);
		if (count($header) < 2) // ignore invalid headers
		  return $len;

		$result_headers[strtolower(trim($header[0]))][] = trim($header[1]);
		
		return $len;
	  }
	);
     **/ 
   // EXECUTE:
   $result = curl_exec($curl);
   if(!$result){
		$temp_debug=$live_debug_event;
		$temp_debug['error']='yes';
		$temp_debug['error_type']='curl url is missing';
		\aw2\live_debug\publish_event(['event'=>$temp_debug,'bgcolor'=>'#F0EBE3']);
		return;
   }
   curl_close($curl);
   
   /**$temp_arr=array(
	"header"=>$result_headers,
	"body"=>$result
   );
   **/
   return $result;
}
