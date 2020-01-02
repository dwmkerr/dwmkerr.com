---
author: Dave Kerr
categories:
- Node.js
- CodeProject
- Slack
date: "2016-11-18T01:25:00Z"
description: ""
draft: false
slug: using-slack-for-server-development
tags:
- Node.js
- CodeProject
- Slack
title: Using Slack for Server Development
---


I recently found a surprisingly helpful approach for server-side development which uses Slack in a creative way.

## The Problem

The scenario can be roughly simplified to this:

![Planned Architecture](images/0-problem.png)

We are building a mobile app and application server. This will take data from a user, transform it and then pass it to the enterprise system processing.

The problem is that the enterprise system doesn't exist yet!

Now this is not too much of a challenge, the first thing we did was build a simple mock of the enterprise system in Node.js, so that we can at least talk to *something*:

![The Mock System](images/0-problem-2.png)

So now we have the question - is our application server transforming the data correctly?

Let's say that in our example we bring in three pieces of data from the UI - a first name, middle name and last name.

Our enterprise system, in this case we'll say it is a CRM system, only accepts a first name and last name. So in our app server, we are going to concatenate the middle name and last name.

Our testers want to make sure that the enterprise system will receive the right data - but at the moment it is only a mock, we cannot log in a check the middle and last names have been combined properly. What to do?

## Slack to the Rescue!

It's a trivial change to our mock server to send the received messages to Slack:

![Slack Diagram](images/1-slack.png)

Now our testers can input data in the mobile app and then watch a slack channel to see the data our application server will actually send to the enterprise system. They can verify the logic has been implemented correctly.

Here's how it might look - in the image below I am running my mock enterprise server, which has Swagger UI to show the mocked APIs and allow me to call them:

![Swagger UI](images/4-swagger.png)

The message is received on the server, sent to slack and we can check the result:

![Slack](images/5-slack.png)

Now this is obviously a trivial and contrived example, but Slack offers a lot of capabilities. Imagine you have a server which watermarks images, you could send an image file to render to the screen. There are a whole bunch of ways you can extend this use case.

In the early stages of a project, where they may be many mocked systems, being able to see what they are doing can be really useful.

## Setting it up

I've created a super-simple demo setup here:

[github.com/dwmkerr/slack-backend](https://github.com/dwmkerr/slack-backend)

Here's how you go about it.

Step 1: Set up a webhook on slack

![Menu](images/1-menu.png)

![Webhook](images/1-incoming-webhook.png)

![](images/3-hook.png)

Step 2: Use the HTTP APIs from Slack, or a client from your platform of choice to send the message:

```js

//  Create the Slack webhook based on our config.
const slack = new Slack();
slack.setWebhook("https://hooks.slack.com/services/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");

//  Every time we're about to handle a request, tell our friend Slack.
server.ext('onPreHandler', (request, reply) => {

  //  Never bother logging any requests to swagger UI.
  if(request.path.match(/\/swaggerui\//) ||
    request.path.match(/\/swagger.json/) ||
    request.path.match(/\/$/)) {
    return reply.continue();
  }

  //  Send the Slack message.
  slack.webhook({
    text: 
`Request *${request.id}*
\`\`\`${request.method} ${request.path}

${JSON.stringify(request.payload, null, 2)}
\`\`\``
  });

  reply.continue();
});
```

This approach was quick and easy to implement, hopefully others will find it useful!

