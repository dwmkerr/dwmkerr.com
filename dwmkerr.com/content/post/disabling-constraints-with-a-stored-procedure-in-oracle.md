---
author: Dave Kerr
categories:
- Oracle
- SQL
date: "2012-02-24T06:20:00Z"
description: ""
draft: false
slug: disabling-constraints-with-a-stored-procedure-in-oracle
tags:
- Oracle
- SQL
title: Disabling Constraints with a Stored Procedure in Oracle
---


<p>Sometimes you need to disable constraints on a Oracle Database. Why might this be? Well image the situation that you are exporting data into an intermediate schema, you only want to import data from a certain date range and due to this you have only a subset of the records. You need this subset for analysis but you don't care about referential integrity - in fact if it is on then constraints will be violated. How can we do this?</p>
<p>Here's a stored procedure that disables constraints for tables owned by 'UserName1' or 'UserName2':</p>
<pre>CREATE OR REPLACE PROCEDURE extraction.sp_PrepExtractionDatabase&nbsp;</pre>
<pre>AUTHID CURRENT_USER</pre>
<pre>IS&nbsp;</pre>
<pre>&nbsp; &nbsp; v_Statement VARCHAR(5000);</pre>
<pre>BEGIN &nbsp;</pre>
<pre>&nbsp; &nbsp; FOR const in (CURSOR c_Constraints IS</pre>
<pre>&nbsp; &nbsp; &nbsp; SELECT constraint_name, table_name, owner</pre>
<pre>&nbsp; &nbsp; &nbsp; FROM ALL_CONSTRAINTS</pre>
<pre>&nbsp; &nbsp; &nbsp; WHERE owner IN ('UserName1', 'UserName2')) LOOP</pre>
<pre>&nbsp; &nbsp; &nbsp; v_Statement := 'ALTER TABLE ' || const.owner <br />|| '.' || const.table_name || ' DISABLE CONSTRAINT '<br /> || const.constraint_name;</pre>
<pre>&nbsp; &nbsp; &nbsp; EXECUTE IMMEDIATE v_Statement;</pre>
<pre>&nbsp; &nbsp; END LOOP;</pre>
<pre>END;</pre>
<pre>/</pre>
<p>What's the key thing here? 'AUTHID CURRENT_USER'. Without this, running the query itself will work fine, but the stored procedure will find NOTHING in the ALL_CONSTRAINTS view. Run in the context of the current user and then the stored procedure will work fine.</p>

