{literal}
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
//���ۤ@�˭ninit�A���O�h�F�@�ӰѼơA�N�Ooauth�A�H�αq�H�e��response.session�令response.authResponse

 //����init���ʧ@�A��J�ۤv��app id
 FB.init({ 
    appId:'402367673161101', 
    cookie:true, 
    status:true, 
    xfbml:true,
    oauth : true // �h�F�o�ӰѼ�
 });
 
 //�U���O��@�@�ӵn�J��function
function fnLoginFb(){
    FB.login( function(response) {
			//���P�_�O�_�w�g�n�J�F�A�p�G�O�A�N����
			if (response.authResponse){  
				FB.api('/me', function(response) {
					alert(response.email);
				});
			//�U���O�S���n�J�ɤ~�|�����A�|�hsubscribe�@��event�A�N�O�h��ť�@��login event�]�]�N�O��login���\�H��A�|������T���^
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

