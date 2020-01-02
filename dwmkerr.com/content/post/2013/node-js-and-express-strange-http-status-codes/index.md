---
author: Dave Kerr
categories:
- Express.js
- JsonClient
- Node.js
- Json
- Javascript
date: "2013-07-16T16:23:43Z"
description: ""
draft: false
slug: node-js-and-express-strange-http-status-codes
tags:
- Express.js
- JsonClient
- Node.js
- Json
- Javascript
title: Node.js and Express - Strange Http Status Codes
---


<h3>In a Nutshell</h3>
Sending a response in Express with a call like <em>res.send(status, body)</em> will send <em>body</em> as the status code if it is numeric - ignoring <em>status</em>. This is due to a fudge for backwards compatibility.
<h3>The Details</h3>
<span style="line-height: 1.714285714; font-size: 1rem;"><strong></strong>As part of a project I'm working on, I'm writing a service using </span><a style="line-height: 1.714285714; font-size: 1rem;" title="node.js" href="http://nodejs.org/" target="_blank">node.js</a><span style="line-height: 1.714285714; font-size: 1rem;"> and </span><a style="line-height: 1.714285714; font-size: 1rem;" title="Express" href="http://expressjs.com/" target="_blank">Express</a><span style="line-height: 1.714285714; font-size: 1rem;">. This service exposes some entities in a MongoDB database through a REST API. Typically I hit this API through client-side Javascript, but in some places I want to hit the same API from some C# code - and I don't want to have to create classes for everything. I've got a funky library for this which I'll be publishing soon, but it helped me find a problem.</span>

Testing the C# code showed me something that was a bit odd - GETs and POSTSs were working fine, but PUTs and DELETEs were showing an HTTP Status code of '1' (which isn't a valid code). Here's the what I was seeing:

<a href="http://www.dwmkerr.com/wp-content/uploads/2013/07/requests.png"><img src="images/requests.png" alt="requests" width="600" /></a>

Checking the node server showed the same thing - DELETEs were returning status 1.

<a href="http://www.dwmkerr.com/wp-content/uploads/2013/07/console.png"><img src="images/console.png" alt="console" width="600" /></a>

The server code is very lightweight so it's quick to see what's going on:

[code lang="js"]exports.deleteUser = function(request, response) {

	//	Get the id.
    var id = request.params.id;

    //	Log the user id.
    console.log('Deleting user: ' + id);

    //	Get the users collection, delete the object.
    db.collection(collectionName, function(err, collection) {
        collection.remove({'_id':new BSON.ObjectID(id)}, {safe:true}, function(err, result) {
            if (err) {
                console.log('Error deleting user: ' + err);
                response.send(400, {'error':'An error has occurred'});
            } else {
                console.log('' + result + ' document(s) deleted');
                response.send(result);
            }
        });
    });
}[/code]

The function is called successfully, so we hit 'response.send'. This looks like the problem - the result object is simply the number one, checking the <a title="Express API Documentation" href="http://expressjs.com/api.html" target="_blank">Express Api Documentation</a> for send shows some examples like this:
<pre><code>res.send(new Buffer('whoop'));
res.send({ some: 'json' });
res.send('some html');
res.send(404, 'Sorry, we cannot find that!');
res.send(500, { error: 'something blew up' });
res.send(200);</code></pre>
So just like the final example, we're sending the code 1, which is not valid. What surprised me was what happened when I changed the send call to the below:

[code lang="js"]response.send(200, result)[/code]

I was <em>still </em>getting the code 1 returned. It turns out that this is a kind of undocumented oddity of Express - if you pass a numeric code and <b>the second argument is also numeric</b> it sends the<b> second argument as the status</b>.

In response.js of Express we find:

[code lang="js"]res.send = function(body){
  var req = this.req;
  var head = 'HEAD' == req.method;
  var len;

  // allow status / body
  if (2 == arguments.length) {
    // res.send(body, status) backwards compat
    if ('number' != typeof body &amp;&amp; 'number' == typeof arguments[1]) {
      this.statusCode = arguments[1];
    } else {
      this.statusCode = body;
      body = arguments[1];
    }
  }[/code]

So it seems the Express used to support a call like res.send({body}, 200) - and checks for a numeric second argument for backwards compatibility.

The workaround - don't send numbers as any part of the response, unless it's most definitely the status code - if you want to return the number of documents deleted, format it as json first, otherwise Express will get confused and mess with your status codes.

