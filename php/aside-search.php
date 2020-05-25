<!-- /*******************************************************************************
* Project Group 4 DT167G
* File: config.class.php
******************************************************************************/ -->

<h2>SEARCH POSTS</h2>
<form id="search-form" action="search.php" method="GET">
    <input id="search-user" type="radio" name="search-type" value="username" checked>
    <label for="search-user">Username</label>

    <input id="search-keyword" type="radio" name="search-type" value="keyword">
    <label for="search-keyword">Keyword</label>

    <input type="text" name="search-field">
    <button type="submit">Search</button>

</form>