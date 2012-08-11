{literal}
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
//接著一樣要init，但是多了一個參數，就是oauth，以及從以前的response.session改成response.authResponse

 //先做init的動作，輸入自己的app id
 FB.init({ 
    appId:'402367673161101', 
    cookie:true, 
    status:true, 
    xfbml:true,
    oauth : true // 多了這個參數
 });
 
 //下面是實作一個登入的function
function fnLoginFb(){
    FB.login( function(response) {
			//先判斷是否已經登入了，如果是，就直接
			if (response.authResponse){  
				FB.api('/me', function(response) {
					alert(response.email);
				});
			//下面是沒有登入時才會做的，會去subscribe一個event，就是去監聽一個login event（也就是說login成功以後，會接收到訊息）
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

