/*******************************************************************************
 * Group 4 Project DT167G
 * File: main.js
 * Desc: main JavaScript file for Group 4 Project
 ******************************************************************************/

var xhr; // Variabel att lagra XMLHttpRequestobjektet
const CURRENT_PAGE = window.location.pathname;

/*******************************************************************************
 * Util functions
 ******************************************************************************/
function byId(id) {
  return document.getElementById(id);
}
/******************************************************************************/

/*******************************************************************************
 * Main function
 ******************************************************************************/
function main() {
  byId('loginButton').addEventListener('click', doLogin, false);
  byId('logoutButton').addEventListener('click', doLogout, false);

  const CURRENT_PAGE = window.location.pathname;

  // Add event listener to upload button, if the userpage is active.
  if (CURRENT_PAGE.includes('signupForm.php') && byId('sign_up_button')) {
    byId('sign_up_button').addEventListener('click', doSignup, false);
  }

  // Stöd för IE7+, Firefox, Chrome, Opera, Safari
  try {
    if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
      // code for IE6, IE5
      xhr = new ActiveXObject('Microsoft.XMLHTTP');
    } else {
      throw new Error('Cannot create XMLHttpRequest object');
    }
  } catch (e) {
    alert('"XMLHttpRequest failed!' + e.message);
  }
}
// Connect the main function to window load event
window.addEventListener('load', main, false);

/*******************************************************************************
 * Function doLogin
 ******************************************************************************/
function doLogin() {
  const UNAME = byId('uname').value;
  const PSW = byId('psw').value;
  const TOKEN = byId('token').value;
  const TS = byId('TS').value;

  if (UNAME !== '' && PSW !== '') {
    xhr.addEventListener('readystatechange', processLogin, false);
    let data = new FormData();
    data.append('uname', UNAME);
    data.append('psw', PSW);
    data.append('token', TOKEN);
    data.append('TS', TS);

    // Send formdata with URL to login.php
    //xhr.open("GET", `login.php?uname=${UNAME}&psw=${PSW}`, true);
    xhr.open('POST', `login.php`, true);
    xhr.send(data);
  }
}
/*******************************************************************************
 * Function doSignup
 ******************************************************************************/
function doSignup() {
  const userName = byId('userName').value;
  const password1 = byId('password1').value;
  const password2 = byId('password2').value;
  const token = byId('su_token').value;
  const timeStamp = byId('su_ts').value;

  if (userName != '' && password1 != '') {
    xhr.addEventListener('readystatechange', processSignup, false);
    let data = new FormData();
    data.append('user_name', userName);
    data.append('password1', password1);
    data.append('password2', password2);
    data.append('su_token', token);
    data.append('su_ts', timeStamp);
    xhr.open('POST', 'signup.php', true);
    xhr.send(data);
  } else {
    byId('signup_message').innerHTML =
      'Username and/or password can not be empty!';
  }
}

/*******************************************************************************
 * Function doLogout
 ******************************************************************************/
function doLogout() {
  xhr.addEventListener('readystatechange', processLogout, false);
  xhr.open('GET', 'logout.php', true);
  xhr.send(null);
}

/*******************************************************************************
 * Function processLogin
 ******************************************************************************/
function processLogin() {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    //First we must remove the registered event since we use the same xhr object for login and logout
    xhr.removeEventListener('readystatechange', processLogin, false);
    console.log('Login response:' + this.responseText);

    var myResponse = JSON.parse(this.responseText);

    // Get menu links from XHR response
    let links = myResponse['links'];
    let menu = '';
    for (let key in links) {
      menu += `<li><a href="${links[key]}">${key}</a></li>`;
    }

    // If successful login update menu and login form
    if (myResponse['isValidLogin']) {
      byId('menu-list').innerHTML = menu;
      byId('logout').style.display = 'block';
      byId('login').style.display = 'none';

      if (CURRENT_PAGE.includes('guestbook.php')) {
        byId('guestbookForm').style.display = 'block';
      }
    }

    // Show information about the login
    byId('loginMsg').innerHTML = myResponse['msg'];
  }
}
/*******************************************************************************
 * Function doLogin
 ******************************************************************************/
function processSignup() {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    xhr.removeEventListener('readystatechange', processSignup, false);
    console.log(this.responseText);
    let myResponse = JSON.parse(this.responseText);
    byId('signup_message').innerHTML = myResponse['msg'];
  }
}

/*******************************************************************************
 * Function processLogout
 ******************************************************************************/
function processLogout() {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    //First we most remove the registered event since we use the same xhr object for login and logout
    xhr.removeEventListener('readystatechange', processLogout, false);
    console.log('Logout: ' + this.responseText);
    var myResponse = JSON.parse(this.responseText);

    // Get menu links from XHR response
    let links = myResponse['links'];
    let menu = '';
    for (let key in links) {
      menu += `<li><a href="${links[key]}">${key}</a></li>`;
    }

    // Update menu and login form
    byId('menu-list').innerHTML = menu;
    byId('loginMsg').innerHTML = myResponse['msg'];
    byId('login').style.display = 'block';
    byId('logout').style.display = 'none';
    // If used has previously posted, hide guestbook-form
    if (CURRENT_PAGE.includes('guestbook.php') && myResponse['hasPosted']) {
      byId('guestbookForm').style.display = 'none';
    }

    // If current page is members.php, redirect to index.php
    if (
      CURRENT_PAGE.includes('members.php') ||
      CURRENT_PAGE.includes('admin.php')
    ) {
      location.replace('index.php');
    }
  }
}
