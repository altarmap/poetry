{literal}
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>

 FB.init({ 
    appId:'402367673161101', 
    cookie:true, 
    status:true, 
    xfbml:true,
    oauth : true
 });
 
function fnLoginFb(){
    FB.login( function(response) {
			if (response.authResponse){  
				FB.api('/me', function(response) {
					alert(response.email);
				});
			}else{
				FB.Event.subscribe('auth.login', function(response) {
					if (response.authResponse) {
						FB.api('/me', function(response) {
							alert(response.email);
						});          
					}
				}); 
			}

    }, {scope:'email'});

} 
</script>
{/literal}

<div>
	<div>
    	login <fb:login-button>Login with Facebook</fb:login-button> <img src="http://101.haleluya.com.tw/image/image_gallery?img_id=5112628&t=1343629858283" />
    </div>

</div>

