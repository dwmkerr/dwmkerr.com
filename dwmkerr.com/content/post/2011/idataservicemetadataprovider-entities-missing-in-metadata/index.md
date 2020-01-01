---
author: Dave Kerr
categories:
- WCF Data Services
date: "2011-11-09T15:49:00Z"
description: ""
draft: false
slug: idataservicemetadataprovider-entities-missing-in-metadata
tags:
- WCF Data Services
title: IDataServiceMetadataProvider Entities Missing in $metadata
---


<p>If you are following through the example on creating custom data service providers as on this blog:</p>
<p><a href="http://blogs.msdn.com/b/alexj/archive/2010/01/08/creating-a-data-service-provider-part-3-metadata.aspx">http://blogs.msdn.com/b/alexj/archive/2010/01/08/creating-a-data-service-provider-part-3-metadata.aspx</a></p>
<p>And you notice that your entities are not showing up in the $metadata file, double check that you have added this:</p>
<pre class="brush: c-sharp;">public class service : MyNewDataService
    {
        // This method is called only once to initialize service-wide policies.
        public static void InitializeService(DataServiceConfiguration config)
        {
            config.SetEntitySetAccessRule("*", EntitySetRights.AllRead);
            config.DataServiceBehavior.MaxProtocolVersion = DataServiceProtocolVersion.V2;
        }
    }</pre>
<pre class="brush: c-sharp;">Just remember to set the entity set access rules for all entities - other they won't show up!</pre>

