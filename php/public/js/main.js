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
  addListeners();

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
 * Function addListeners
 ******************************************************************************/
function addListeners() {
  // Add event listener to upload button, if the userpage is active.
  if (CURRENT_PAGE.includes("signupForm.php") && byId("sign_up_button")) {
    byId("sign_up_button").addEventListener("click", doSignup, false);
  }

  byId("loginButton").addEventListener("click", doLogin, false);
  byId("logoutButton").addEventListener("click", doLogout, false);
  addLikeButtonListeners();
  addDislikeButtonListeners();
  addRemoveButtonListeners();
}

/*******************************************************************************
 * Function addLikeButtonListeners
 ******************************************************************************/
function addLikeButtonListeners() {
  // Add onClickListeners for like buttons
  $(".like-btn").on("click", function () {
    let post_id = $(this).data("id");
    let token = byId("gb-token").value;
    let ts = byId("gb-ts").value;
    $clicked_btn = $(this);

    if ($clicked_btn.hasClass("far")) {
      action = "like";
    } else if ($clicked_btn.hasClass("fas")) {
      action = "unlike";
    }

    $.ajax({
      url: "rating.php",
      type: "post",
      data: {
        action: action,
        post_id: post_id,
        token: token,
        ts: ts,
      },
      dataType: "json",
      success: function (data) {
        if (action == "like") {
          $clicked_btn.removeClass("far");
          $clicked_btn.addClass("fas");
        } else if (action == "unlike") {
          $clicked_btn.removeClass("fas");
          $clicked_btn.addClass("far");
        }

        $clicked_btn.siblings("span.likes").text(data.likes);
        $clicked_btn.siblings("span.dislikes").text(data.dislikes);

        $clicked_btn
          .siblings("i.fas.fa-thumbs-down")
          .removeClass("fas")
          .addClass("far");
      },
    });
  });
}

/*******************************************************************************
 * Function addDislikeButtonListeners
 ******************************************************************************/
function addDislikeButtonListeners() {
  // Add onClickListeners for dislike buttons
  $(".dislike-btn").on("click", function () {
    let post_id = $(this).data("id");
    let token = byId("gb-token").value;
    let ts = byId("gb-ts").value;
    $clicked_btn = $(this);

    if ($clicked_btn.hasClass("far")) {
      action = "dislike";
    } else if ($clicked_btn.hasClass("fas")) {
      action = "undislike";
    }

    $.ajax({
      url: "rating.php",
      type: "post",
      data: {
        action: action,
        post_id: post_id,
        token: token,
        ts: ts,
      },
      dataType: "json",
      success: function (data) {
        if (action == "dislike") {
          $clicked_btn.removeClass("far");
          $clicked_btn.addClass("fas");
        } else if (action == "undislike") {
          $clicked_btn.removeClass("fas");
          $clicked_btn.addClass("far");
        }

        $clicked_btn.siblings("span.likes").text(data.likes);
        $clicked_btn.siblings("span.dislikes").text(data.dislikes);

        $clicked_btn
          .siblings("i.fas.fa-thumbs-up")
          .removeClass("fas")
          .addClass("far");
      },
    });
  });
}

/*******************************************************************************
 * Function addRemoveButtonListeners
 ******************************************************************************/
function addRemoveButtonListeners() {
  $(".delete-post").on("click", function () {
    let post_id = $(this).data("id");
    let token = byId("gb-token").value;
    let ts = byId("gb-ts").value;
    $clicked_btn = $(this);

    $.ajax({
      url: "delete-post.php",
      type: "post",
      data: {
        post_id: post_id,
        token: token,
        ts: ts,
      },
      dataType: "json",
      success: function (data) {
        if (data === "true") {
          location.reload();
        }
      },
    });
  });
}

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
    data.append("uname", UNAME);
    data.append("psw", PSW);
    data.append("token", TOKEN);
    data.append("TS", TS);

    // Send formdata with URL to login.php
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

    var myResponse = JSON.parse(this.responseText);

    // Get menu links from XHR response
    let links = myResponse["links"];
    let menu = "";
    for (let key in links) {
      menu += `<li><a href="${links[key]}">${key}</a></li>`;
    }

    // If successful login update menu and login form
    if (myResponse["isValidLogin"]) {
      byId("logout").style.display = "block";
      byId("login").style.display = "none";

      // If current page is index.php.
      if (byId("welcome-message")) {
        byId("welcome-message").style.display = "none";
        byId("gb-form").style.display = "block";
      }

      location.reload();
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
    var myResponse = JSON.parse(this.responseText);

    // Get menu links from XHR response
    let links = myResponse["links"];
    let menu = "";
    for (let key in links) {
      menu += `<li><a href="${links[key]}">${key}</a></li>`;
    }

    // Update menu and login form
    byId("loginMsg").innerHTML = myResponse["msg"];
    byId("login").style.display = "block";
    byId("logout").style.display = "none";

    // If current page is index.php.
    if (byId("welcome-message")) {
      byId("welcome-message").style.display = "block";
      byId("gb-form").style.display = "none";
    }

    location.reload();
  }
}

/*******************************************************************************
 * Function doSignup
 ******************************************************************************/
function doSignup() {
  const userName = byId("userName").value;
  const password1 = byId("password1").value;
  const password2 = byId("password2").value;
  const token = byId("su_token").value;
  const timeStamp = byId("su_ts").value;

  if (!userName || userName.trim() == "") {
    byId("signup_message").innerHTML = "Username can not be empty!";
    return;
  }

  if (!password1 || password1.trim() === "") {
    byId("signup_message").innerHTML =
      "Password can not be empty or contain only whitespace characters.";
    return;
  }

  // if username is not empty and password has any value (not null);
  xhr.addEventListener("readystatechange", processSignup, false);
  let data = new FormData();
  data.append("user_name", userName);
  data.append("password1", password1);
  data.append("password2", password2);
  data.append("su_token", token);
  data.append("su_ts", timeStamp);
  xhr.open("POST", "signup.php", true);
  xhr.send(data);
}

/*******************************************************************************
 * Function processSignup
 ******************************************************************************/
function processSignup() {
  if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
    xhr.removeEventListener("readystatechange", processSignup, false);
    console.log(this.responseText);
    let myResponse = JSON.parse(this.responseText);
    byId("signup_message").innerHTML = myResponse["msg"];
  }
}
