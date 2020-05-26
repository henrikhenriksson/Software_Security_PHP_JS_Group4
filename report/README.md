# Grupp 4 - Software Security (DT167G) Report

General introduction

## Threats

## XSS

### Mitigations

**Sanitize on output**

What good is sanitizing input that is stored in a database if an attacker
launches a successful SQL injection attack that changes the contents of the
database. As far as XSS threat goes, database contents are unsafe and should
always be escaped.

Every class that has methods that returns a string for HTML output escapes that
string so that it no longer contains characters that can be treated as HTML
code.

## CSRF

This threat is a special case of XSS, where an Attacker tricks the Victim to
send a request to the target server, unbeknownst to the Victim. Since it is the
Victim's application, usually a web browser, that sends the request, the request
will be accompanied by the Victims cookie for the target server. Effectively,
the Attacker is able to send a request to the target server on the behalf of the
Victim.

The classic scenario is that the Victim is logged in to his or her bank. To avoid
having to enter the login credentials for each page load the bank stores
information about the Victim in a session. The session is linked to the Victim
using a session ID as part of a cookie. In short, the cookie is now the
authentication token to access the bank as the Victim.

The attacker creates a website and lures the Victim to visit the site. When the
page loads the web browser sees the request for an image and tries to load the
image. But the link provided for the image is instead a request to the bank that
transfers a lot of money from the victims account to the attackers account.

### Mitigations

**Application logic**

**Response headers**
