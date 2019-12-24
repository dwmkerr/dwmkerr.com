---
author: Dave Kerr
categories:
- Node.js
- Express.js
- CodeProject
- Auth0
- Security
- JWT
date: "2015-03-24T14:45:02Z"
description: ""
draft: false
slug: modifying-a-jwt-in-a-node-application
tags:
- Node.js
- Express.js
- CodeProject
- Auth0
- Security
- JWT
title: Manipulating JSON Web Tokens (JWTs)
---


I've been writing a couple of web services lately that use [Auth0](https://auth0.com/)  for identity management. It's a great platform that makes working with different identity providers a breeze.

One thing that I couldn't work out how to do at first was to quickly build a new JWT<sup><a href="#fn1" id="ref1">1</a></sup> from an existing token. I wanted to take my current token, add some more data to it and return it to the user. So here's a 'why' and 'how'.

## Why?

Why would you want to do this? A use case would be when you want to associate your a session with some data. For example, imagine a library gateway which offers access to a whole bunch of University libraries. First we authenticate. Then we ask for all of the libraries in the system. Then we ask for authorisation to use a specific library. We could put the library name in the token and pass it for every call onwards.

It might look like this:

#### 1. Authenticate

First, we authenticate, perhaps with a username and password.

```
POST libraries.com/api/authenticate
{"usename":"calculon","password":"dramatic...pause"}
```

Then we can return a JWT if all is well:

```
{"jwt":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJjYWxjdWxvbiJ9.VWkAafAMCxazY7uBlPTJoQwCBdUIy3T1d-C4TfxhAZQ"}
```

#### 2. Work with the Service 

We can put this JWT in an `Authorization` header and start asking for protected resources:

```
GET libraries.com/api/libraries
Authorization: Bearer eyJhb...AZQ
```

giving us:

```
[
  {"name": "Mars University Libary", "slug":"mul"},
  {"name": "Coney Island State Library", "slug":"cis"}
]
```

Two libraries we can choose from. Now I want to present this choice to a user, but once they've made their choice I don't want to change the libary again. I want to work with only one library in a session.

#### 3. Add Data to the Token

A nice thing we can do here is just create *another* authentication method, which attempts to see if we are authorised to use the given library:

```
POST libraries.com/api/libraries/mul/authorise
Authorization: Bearer eyJhb...AZQ
```
If the token is valid, we can check to see if the user is allowed to use this library. If so, we can return a *new* token, which is associated with a *specific* library:

```
HTTP/1.1 200 OK
{"jwt": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiJjYWxjdWxvbiIsImxpYnJhcnkiOiJtdWwifQ.NM2pqRMkIp65u9unZnGIoyxK6v2A18730lPwSMrK93Q"}
```

This is a new token. Paste it into [jwt.io](https://jwt.io), you'll see there's a library code in the payload.

#### 4. Work with the service

Now I can call APIs like:

```
GET libaries.com/api/books
```

And my server can check the library in my token. If I have one, I return books from the given library, otherwise I return a 401.

#### Is this useful?

This specific example might not appeal, but you may well find as you write more complex services you want to at times add data to your token.

The case above also shows how you can associate a session with a set of resources (in this case, a single library). This is useful if we know we'll only work with a subset of resources. I want to choose a library once and work with that only. If you need to work with multiple libraries, it wouldn't make sense.

## How?

If we are using Auth0, then we almost certainly have our token generated for us. The helper library [express-jwt](https://github.com/auth0/express-jwt) will certainly let us make sure the token is valid, and put the payload of data on the `request.user` object, but how can we create a new token *from the existing one*?

It turns out it's really pretty easy, as we would expect as we are using open standards. Here's the code:

```language-javascript
var jwt = require('jsonwebtoken');

function extendToken(secret, payload, extend) {

  //  Clone and extend the payload.
  var body = JSON.parse(JSON.stringify(payload));
  for (var prop in extend) {
    if (extend.hasOwnProperty(prop)) {
      body[prop] = extend[prop];
    }
  }

  //  Sign the new token with our secret.
  return jwt.sign(JSON.stringify(body), secret);

}
```

We have a function which takes a secret, the payload of an existing token, an object containing data to extend and that's it. Here's how you could use it:

```language-javascript
var expressJwt = require('express-jwt');
var mySecret = new Buffer('walkinonsunshine', 'base64');

//  Middleware for protecting routes...
var requireAuth = expressJwt({secret: mySecret});

app.post('/api/libraries/:lib/authorise', requireAuth, function(req, res, next) {

  // get the library, check the user has access...
  var lib = req.params.lib;
  checkLib(req.user.sub, lib, function(err, ok) {

    if(err) return next(err);
    if(!ok) return res.status(401).send("Access Denied."); 
    return res.status(200).send({jwt: extendToken(mySecret, req.user, {library: lib})});
    
  });
  
});
```

We've extended the original token with some new data, resigned it and passed it back to the user. Future requests will automatically have the `req.user.lib` field set (as the entire token payload is put by default on the `req.user` object with the express-js middleware.

Hopefully that'll be of some use if you ever need to extend the payload of a JWT token in a Node app.

-----------------

<sup id="fn1">1. Json Web Token, read more at [jwt.io](http://jwt.io/). <a href="#ref1">↩</a></sup>

