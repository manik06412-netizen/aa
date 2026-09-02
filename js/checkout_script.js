let userInfoForm = document.getElementById("userInfoForm");
let sign = document.getElementById("sign");
let inputname = document.getElementById("userfname");
let userlname = document.getElementById("userlname");
let userfullname = document.getElementById("userfullname");
let useremailname = document.getElementById("useremailname");
let userphotos = document.getElementById('photos');
let idname = document.getElementById("idname");
let usertoken = document.getElementById("usertoken");

// Function to show data from localStorage
function show_L_data() {
  if (localStorage.getItem("infos")) {
    let infosLparse = JSON.parse(localStorage.getItem("infos"));

    inputname.value = infosLparse.firstL;
    userlname.value = infosLparse.lastL;
    userfullname.value = infosLparse.fullnameL;
    useremailname.value = infosLparse.mailL;
    idname.value = infosLparse.id_numL;
    usertoken.value = infosLparse.emailTokenL;
    userphotos.value = infosLparse.photo_linkL;

    // Submit the form
    sendFormData();
  }
}

function sendFormData() {
  let formData = new FormData(userInfoForm);

  fetch('email_register.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text()) 
  .then(responseText => {
    if(responseText.trim() == 'Continue to Checkout') {
      alert('Login success');
      window.location.href='chackout.php';
      CommenSignOut();
    
    }else if(responseText.trim() == 'Inactive'){
      alert("Contact Admin.");
      window.location.reload();
      CommenSignOut();

    }else if(responseText.trim() == 'Registration_failed.'){
      window.location.reload();
      alert("Registration failed. Please try again later.");
      CommenSignOut();

    }else{
       alert("Continue to Checkout!");
      window.location.href='checkout.php';
     
    }
  })
  .catch(error => {
    window.location.reload();
    CommenSignOut();
    alert('An error occurred. Please try again later.');

    console.error('Error:', error);
  });
}


function handleCredentialResponse(response) {
  const responsePayload = decodeJwtResponse(response.credential);

  let infos = {
    fullnameL: responsePayload.name,
    photo_linkL: responsePayload.picture,
    firstL: responsePayload.given_name,
    lastL: responsePayload.family_name,
    mailL: responsePayload.email,
    id_numL: responsePayload.sub,
    genderL: responsePayload.id,
    phoneL: responsePayload.verifiedEmail,
    emailTokenL: response.credential
  }

  let infosL = JSON.stringify(infos);

  localStorage.setItem("infos", infosL);

  show_L_data();
}

function decodeJwtResponse(data) {
  let tokens = data.split(".");
  return JSON.parse(atob(tokens[1]));
}

function logoutAndClear() {
  localStorage.clear();
}

sign.addEventListener("click", logoutAndClear);

userInfoForm.addEventListener('submit', (e) => {
  e.preventDefault();
  userInfoForm.reset();
});

function CommenSignOut(){
  signOut();
  userInfoForm.reset();
}

function signOut() {
  var auth2 = gapi.auth2.getAuthInstance();
  auth2.signOut().then(function () {
    console.log('User signed out.');
  });
}
