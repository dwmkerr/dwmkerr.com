---
author: Dave Kerr
categories:
- ODATA
- Sharepoint
- WCF Data Services
date: "2011-11-02T10:28:00Z"
description: ""
draft: false
slug: data-service-exception-unauthorised-when-connecting-to-sharepoint-odata
tags:
- ODATA
- Sharepoint
- WCF Data Services
title: Data Service Exception "Unauthorised" when connecting to Sharepoint OData
---


<p>If you are struggling to fetch data from a Sharepoint OData service and getting an error as below:</p>
<pre> [DataServiceClientException: Unauthorized]
   System.Data.Services.Client.QueryResult.Execute() +436914
   System.Data.Services.Client.DataServiceRequest.Execute(DataServiceContext context, QueryComponents queryComponents) +133&nbsp;</pre>
<p>Then ensure you are setting the Credentials property of your Data Service Context, as below:</p>
<pre class="brush: c-sharp;">//  Create the data context.
SharepointDataContext dc = new SharepointDataContext(new Uri("http://dksp/_vti_bin/listdata.svc"));
        
//  Provide default credentials, without this authorisation will fail!
dc.Credentials = System.Net.CredentialCache.DefaultCredentials;

//  Etc...
var accounts = from a in dc.Accounts select a;</pre>
<p class="brush: c-sharp;">Just another issue you may come across when using Sharepoint OData services!</p>

