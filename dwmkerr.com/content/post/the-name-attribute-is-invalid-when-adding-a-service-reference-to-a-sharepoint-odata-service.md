+++
author = "Dave Kerr"
categories = ["ODATA", "Sharepoint", "WCF Data Services"]
date = 2011-11-02T10:19:00Z
description = ""
draft = false
slug = "the-name-attribute-is-invalid-when-adding-a-service-reference-to-a-sharepoint-odata-service"
tags = ["ODATA", "Sharepoint", "WCF Data Services"]
title = "The \"Name attribute is invalid\" when adding a Service Reference to a Sharepoint OData Service"

+++


<p>Well this little issue took me a while to investigate, but the skinny is this:</p>
<p>If you are going to add a service reference to a Sharepoint OData service (e.g.&nbsp;http://sharepoint/_vti_bin/listdata.svc) then make sure your Sharepoint site name does NOT begin with a number - otherwise Visual Studio will fail to add the reference.</p>
<p>Quick and easy, but this took quite a while for me to find, hope it helps anyone in the same situation!</p>

