/*******************************************************************************
 * Laboration 4, Kurs: DT161G
 * File: main.js
 * Desc: main JavaScript file for Laboration 4
 *
 * Fredrik Helgesson
 * frhe0300
 * frhe0300@student.miun.se
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
  byId("loginButton").addEventListener("click", doLogin, false);
  byId("logoutButton").addEventListener("click", doLogout, false);

  // Stöd för IE7+, Firefox, Chrome, Opera, Safari
  try {
    if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xhr = new XMLHttpRequest();
    } else if (window.ActiveXObject) {
      // code for IE6, IE5
      xhr = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
      throw new Error("Cannot create XMLHttpRequest object");
    }
  } catch (e) {
    alert('"XMLHttpRequest failed!' + e.message);
  }
}
// Connect the main function to window load event
window.addEventListener("load", main, false);

/*******************************************************************************
 * Function doLogin
 ******************************************************************************/
function doLogin() {
  const UNAME = byId("uname").value;
  const PSW = byId("psw").value;
  const TOKEN = byId("token").value;
  const TS = byId("TS").value;

  if (UNAME !== "" && PSW !== "") {
    xhr.addEventListener("readystatechange", processLogin, false);
    let data = new FormData();
    data.append('uname', UNAME);
    data.append('psw', PSW);
    data.append("token", TOKEN);
    data.append("TS", TS);


    // Send formdata with URL to login.php
    //xhr.open("GET", `login.php?uname=${UNAME}&psw=${PSW}`, true);
    xhr.open("POST", `login.php`, true);
    xhr.send(data);
  }
}

/*******************************************************************************
 * Function doLogout
 ******************************************************************************/
function doLogout() {
  xhr.addEventListener("readystatechange", processLogout, false);
  xhr.open("GET", "logout.php", true);
  xhr.send(null);
}

/*******************************************************************************
 * Function processLogin
 ******************************************************************************/
function processLogin() {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    //First we must remove the registered event since we use the same xhr object for login and logout
    xhr.removeEventListener("readystatechange", processLogin, false);
    console.log("Login response:" + this.responseText);

    var myResponse = JSON.parse(this.responseText);

    // Get menu links from XHR response
    let links = myResponse["links"];
    let menu = "";
    for (let key in links) {
      menu += `<li><a href="${links[key]}">${key}</a></li>`;
    }

    // If successful login update menu and login form
    if (myResponse["isValidLogin"]) {
      byId("menu-list").innerHTML = menu;
      byId("logout").style.display = "block";
      byId("login").style.display = "none";

      if (CURRENT_PAGE.includes("guestbook.php")) {
        byId("guestbookForm").style.display = "block";
      }
    }

    // Show information about the login
    byId("loginMsg").innerHTML = myResponse["msg"];
  }
}

/*******************************************************************************
 * Function processLogout
 ******************************************************************************/
function processLogout() {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    //First we most remove the registered event since we use the same xhr object for login and logout
    xhr.removeEventListener("readystatechange", processLogout, false);
    console.log("Logout: " + this.responseText);
    var myResponse = JSON.parse(this.responseText);

    // Get menu links from XHR response
    let links = myResponse["links"];
    let menu = "";
    for (let key in links) {
      menu += `<li><a href="${links[key]}">${key}</a></li>`;
    }

    // Update menu and login form
    byId("menu-list").innerHTML = menu;
    byId("loginMsg").innerHTML = myResponse["msg"];
    byId("login").style.display = "block";
    byId("logout").style.display = "none";
    // If used has previously posted, hide guestbook-form
    if (CURRENT_PAGE.includes("guestbook.php") && myResponse["hasPosted"]) {
      byId("guestbookForm").style.display = "none";
    }

    // If current page is members.php, redirect to index.php
    if (
      CURRENT_PAGE.includes("members.php") ||
      CURRENT_PAGE.includes("admin.php")
    ) {
      location.replace("index.php");
    }
  }
}
