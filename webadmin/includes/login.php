<script type="text/javascript" src="../js/ajax_object.js"></script>
<script type="text/javascript">

	/*function checkPassOne(username,password){

		str = "username="+username+"&password="+password;

		var myRequest = new ajaxObject('checkFFA.php');

	    myRequest.update(str,'POST');

		myRequest.callback = function(responseText, responseStatus, responseXML) {

			document.getElementById('response_div').style.display = "block";

			document.getElementById('response_div').innerHTML = responseText;

		}

	}

	

	function checkPassTwo(username,password,password2){

		str = "second_check=1&username="+username+"&password="+password+"&password2="+password2;

		var myRequest = new ajaxObject('checkFFA.php');

	    myRequest.update(str,'POST');

		myRequest.callback = function(responseText, responseStatus, responseXML) {

			document.getElementById('response_div2').style.display = "block";

			document.getElementById('response_div2').innerHTML = responseText;

		}

	}*/

</script>
<script language="JavaScript">
function check()
{				
	//alert(document.editFrm.name.value);
	if(document.loginFrm.username.value.search(/\S/)==-1)
	{
		alert("Please enter username.");
		document.loginFrm.username.focus();
		return false;
	}
	
	if(document.loginFrm.password.value.search(/\S/)==-1)
	{
		alert("Please enter password.");
		document.loginFrm.password.focus();
		return false;
	}
 	return true;   		
}

</script>
<table width="475" border="0" align="center" cellpadding="0" cellspacing="0" class="login_main">
  <!--<tr>
    <td align="center" valign="top"><img src="images/logo.png" alt=""   /></td>
  </tr>-->

  
  <tr>
    <td align="left" valign="top" class="welcome_bg"><h1>Welcome to <?=COMPANY_NAME?> </h1></td>
  </tr>
  <tr>
    <td style="height:5px;" align="left" valign="top">&nbsp;</td>
  </tr>
  
  <tr>
    <td align="left" valign="top" class="login_bg"><form name="loginFrm" method="post" action="valid_login.php" onSubmit="return check();">
      <table width="95%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="56" align="right" valign="middle"><table width="202" border="0" align="right" cellpadding="0" cellspacing="0">
            <tr>
              <td align="left" valign="top"><? if(isset($_SESSION[SUCCESS_MSG]) && $_SESSION[SUCCESS_MSG]!="") {?><p class="sucess"><?=$_SESSION[SUCCESS_MSG]; ?><? }?><? if(isset($_SESSION[ERROR_MSG]) && $_SESSION[ERROR_MSG]!="") {?><p class="alert"><?=$_SESSION[ERROR_MSG]; ?></p><? }
			  $_SESSION[SUCCESS_MSG]="";
			  $_SESSION[ERROR_MSG]="";
			  unset($_SESSION[SUCCESS_MSG]);
			  unset($_SESSION[ERROR_MSG]);
			  ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="right" valign="top"><table width="295" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="93" align="left" valign="top" class="login_text">User name</td>
              <td width="202" height="50" align="left" valign="top" class=""><input size="30" name="username" class="login_input" type="text" id="username" maxlength="20"></td>
            </tr>
            <tr>
              <td align="left" valign="top" class="login_text">Password</td>
              <td height="50" align="left" valign="top"><input name="password" class="login_input" onblur="checkPassOne(document.getElementById('username').value,this.value)" size="30" type="password" id="password" maxlength="20"></td>
            </tr>
            <tr>
              <td align="left" valign="top">&nbsp;</td>
              <td height="50" align="left" valign="top"><input type="submit" name="button" id="button" value="Login"  class="login_bttn"/></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
      </table>

    </form>
    </td>
  </tr>
</table>
