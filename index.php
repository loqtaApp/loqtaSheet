<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<html>
    <head>
      <!-- HTTPS required. HTTP will give a 403 forbidden response -->
<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>  
     <script>
  // initialize Account Kit with CSRF protection
  AccountKit_OnInteractive = function(){
    AccountKit.init(
      {
        appId:"1311763772269296", 
        state:"123456", 
        version:"v1.0",
        fbAppEventsEnabled:true
      }
    );
  };

  // login callback
  function loginCallback(response) {
      alert(response.status);
    if (response.status === "PARTIALLY_AUTHENTICATED") {
      var code = response.code;
      var csrf = response.state;
      // Send code to server to exchange for access token
    }
    else if (response.status === "NOT_AUTHENTICATED") {
      // handle authentication failure
    }
    else if (response.status === "BAD_PARAMS") {
      // handle bad parameters
    }
  }

  // phone form submission handler
  function smsLogin() {
    var countryCode = document.getElementById("country_code").value;
    var phoneNumber = document.getElementById("phone_number").value;
    AccountKit.login(
      'PHONE', 
      {countryCode: "+970", phoneNumber: phoneNumber}, // will use default values if not specified
      loginCallback
    );
  }


  // email form submission handler
  function emailLogin() {
    var emailAddress = document.getElementById("email").value;
    AccountKit.login(
      'EMAIL',
      {emailAddress: emailAddress},
      loginCallback
    );
  }
</script>   
    </head>
    <body>
        
        <input value="+970" id="country_code" />
<input placeholder="phone number" value="599536772" id="phone_number"/>
<button onclick="smsLogin();">Login via SMS</button>
<div>OR</div>
<input placeholder="email" id="email"/>
<button onclick="emailLogin();">Login via Email</button>
        
    </body>
    
</html>